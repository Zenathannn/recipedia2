<?php

namespace App\Livewire\User;

use App\Models\Recipe;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Ingredient;
use App\Models\Step;
use App\Models\RecipeImage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

#[Title('Buat Resep Baru')]
class SubmitRecipe extends Component
{
    use WithFileUploads;

    // Multi-step
    public int $currentStep = 1;
    public int $totalSteps = 4;

    // Step 1: Basic Info
    public string $title = '';
    public string $description = '';
    public ?int $category_id = null;
    public int $prep_time = 15;
    public int $cook_time = 30;
    public int $servings = 4;
    public string $difficulty = 'sedang';
    public $thumbnail;
    public array $selectedTags = [];

    // Step 2: Ingredients
    public array $ingredients = [
        ['name' => '', 'amount' => '', 'unit' => '', 'notes' => '']
    ];

    // Step 3: Steps
    public array $steps = [
        ['instruction' => '', 'duration' => null, 'image' => null]
    ];

    // Step 4: Additional Images
    public array $additionalImages = [];

    protected array $units = [
        'gram',
        'kg',
        'ml',
        'liter',
        'sdm',
        'sdt',
        'buah',
        'siung',
        'lembar',
        'batang',
        'bungkus',
        'sachet'
    ];

    // ── Validation Rules per Step ────────────────────
    protected function rulesForStep(int $step): array
    {
        return match ($step) {
            1 => [
                'title'       => 'required|min:5|max:200|unique:recipes,title',
                'description' => 'required|min:20|max:1000',
                'category_id' => 'required|exists:categories,id',
                'prep_time'   => 'required|integer|min:1|max:999',
                'cook_time'   => 'required|integer|min:1|max:999',
                'servings'    => 'required|integer|min:1|max:50',
                'difficulty'  => 'required|in:mudah,sedang,sulit',
                'thumbnail'   => 'required|image|max:5120', // 5MB
                'selectedTags' => 'array|max:5',
            ],
            2 => [
                'ingredients'         => 'required|array|min:1',
                'ingredients.*.name'  => 'required|string|max:100',
                'ingredients.*.amount' => 'nullable|string|max:20',
                'ingredients.*.unit'  => 'nullable|string|max:20',
                'ingredients.*.notes' => 'nullable|string|max:200',
            ],
            3 => [
                'steps'               => 'required|array|min:1',
                'steps.*.instruction' => 'required|string|min:10|max:1000',
                'steps.*.duration'    => 'nullable|integer|min:1|max:999',
                'steps.*.image'       => 'nullable|image|max:3072', // 3MB
            ],
            4 => [
                'additionalImages.*' => 'nullable|image|max:3072',
            ],
            default => [],
        };
    }

    protected function messagesForStep(int $step): array
    {
        return match ($step) {
            1 => [
                'title.required'       => 'Judul resep wajib diisi',
                'title.unique'         => 'Judul resep sudah digunakan',
                'description.required' => 'Deskripsi wajib diisi',
                'description.min'      => 'Deskripsi minimal 20 karakter',
                'category_id.required' => 'Pilih kategori',
                'thumbnail.required'   => 'Upload foto resep',
                'thumbnail.image'      => 'File harus berupa gambar',
                'thumbnail.max'        => 'Ukuran foto maksimal 5MB',
            ],
            2 => [
                'ingredients.required'      => 'Minimal 1 bahan harus diisi',
                'ingredients.*.name.required' => 'Nama bahan wajib diisi',
            ],
            3 => [
                'steps.required'               => 'Minimal 1 langkah harus diisi',
                'steps.*.instruction.required' => 'Instruksi langkah wajib diisi',
                'steps.*.instruction.min'      => 'Instruksi minimal 10 karakter',
            ],
            default => [],
        };
    }

    // ── Add/Remove Items ─────────────────────────────
    public function addIngredient(): void
    {
        $this->ingredients[] = ['name' => '', 'amount' => '', 'unit' => '', 'notes' => ''];
    }

    public function removeIngredient(int $index): void
    {
        unset($this->ingredients[$index]);
        $this->ingredients = array_values($this->ingredients);
    }

    public function addStep(): void
    {
        $this->steps[] = ['instruction' => '', 'duration' => null, 'image' => null];
    }

    public function removeStep(int $index): void
    {
        unset($this->steps[$index]);
        $this->steps = array_values($this->steps);
    }

    public function removeAdditionalImage(int $index): void
    {
        unset($this->additionalImages[$index]);
        $this->additionalImages = array_values($this->additionalImages);
    }

    // ── Navigation ───────────────────────────────────
    public function nextStep(): void
    {
        $this->validate(
            $this->rulesForStep($this->currentStep),
            $this->messagesForStep($this->currentStep)
        );

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep(int $step): void
    {
        if ($step > 0 && $step <= $this->totalSteps) {
            $this->currentStep = $step;
        }
    }

    public function selectCategory(int $categoryId): void
    {
        $this->category_id = $categoryId;
    }

    public function toggleTag(int $tagId): void
    {
        if (in_array($tagId, $this->selectedTags, true)) {
            $this->selectedTags = array_values(array_filter(
                $this->selectedTags,
                fn(int $id): bool => $id !== $tagId
            ));
            return;
        }

        if (count($this->selectedTags) >= 5) {
            return;
        }

        $this->selectedTags[] = $tagId;
    }

    // ── Submit ───────────────────────────────────────
    public function submit()
    {
        // Validate all steps and jump to the first invalid step.
        $stepLabels = [
            1 => 'Info Dasar',
            2 => 'Bahan',
            3 => 'Langkah',
            4 => 'Foto',
        ];

        foreach (range(1, $this->totalSteps) as $step) {
            try {
                $this->validate(
                    $this->rulesForStep($step),
                    $this->messagesForStep($step)
                );
            } catch (ValidationException $e) {
                $this->currentStep = $step;

                $this->dispatch(
                    'toast',
                    type: 'error',
                    title: 'Data belum lengkap',
                    message: 'Lengkapi bagian ' . ($stepLabels[$step] ?? 'form') . ' terlebih dahulu.'
                );

                throw $e;
            }
        }

        DB::beginTransaction();
        try {
            // Upload thumbnail
            $thumbnailPath = $this->thumbnail->store('thumbnails', 'public');

            // Create recipe
            $recipe = Recipe::create([
                'user_id'     => auth()->id(),
                'category_id' => $this->category_id,
                'title'       => $this->title,
                'slug'        => Str::slug($this->title) . '-' . Str::random(6),
                'description' => $this->description,
                'thumbnail'   => $thumbnailPath,
                'prep_time'   => $this->prep_time,
                'cook_time'   => $this->cook_time,
                'servings'    => $this->servings,
                'difficulty'  => $this->difficulty,
                'status'      => 'pending', // Menunggu approval admin
            ]);

            // Attach tags
            if (!empty($this->selectedTags)) {
                $recipe->tags()->attach($this->selectedTags);
            }

            // Save ingredients
            foreach ($this->ingredients as $index => $ingredient) {
                if (!empty($ingredient['name'])) {
                    Ingredient::create([
                        'recipe_id' => $recipe->id,
                        'name'      => $ingredient['name'],
                        'amount'    => $ingredient['amount'] ?? null,
                        'unit'      => $ingredient['unit'] ?? null,
                        'notes'     => $ingredient['notes'] ?? null,
                        'order'     => $index + 1,
                    ]);
                }
            }

            // Save steps
            foreach ($this->steps as $index => $step) {
                $stepImagePath = null;
                if (isset($step['image']) && $step['image']) {
                    $stepImagePath = $step['image']->store('step-images', 'public');
                }

                Step::create([
                    'recipe_id'   => $recipe->id,
                    'step_number' => $index + 1,
                    'instruction' => $step['instruction'],
                    'duration'    => $step['duration'] ?? null,
                    'image'       => $stepImagePath,
                ]);
            }

            // Save additional images
            if (!empty($this->additionalImages)) {
                foreach ($this->additionalImages as $index => $image) {
                    if ($image) {
                        $imagePath = $image->store('recipe-images', 'public');
                        RecipeImage::create([
                            'recipe_id'  => $recipe->id,
                            'image_path' => $imagePath,
                            'order'      => $index + 1,
                        ]);
                    }
                }
            }

            DB::commit();

            $this->dispatch(
                'toast',
                type: 'success',
                title: 'Resep berhasil dibuat!',
                message: 'Menunggu persetujuan admin.'
            );

            return redirect()->route('my-recipes');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch(
                'toast',
                type: 'error',
                title: 'Gagal membuat resep',
                message: $e->getMessage()
            );
        }
    }

    // ── Render ───────────────────────────────────────
    public function render()
    {
        $categories = Category::active()->get();
        if ($categories->isEmpty()) {
            $categories = Category::query()->orderBy('order')->orderBy('name')->get();
        }

        $allTags = Tag::orderBy('name')->get();

        return view('livewire.user.submit-recipe', compact('categories', 'allTags'));
    }
}

