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

#[Title('Edit Resep')]
class EditRecipe extends Component
{
    use WithFileUploads;

    public Recipe $recipe;
    public int $currentStep = 1;
    public int $totalSteps = 4;

    // Step 1
    public string $title = '';
    public string $description = '';
    public ?int $category_id = null;
    public int $prep_time = 15;
    public int $cook_time = 30;
    public int $servings = 4;
    public string $difficulty = 'sedang';
    public $thumbnail;
    public ?string $existingThumbnail = null;
    public array $selectedTags = [];

    // Step 2
    public array $ingredients = [];

    // Step 3
    public array $steps = [];

    // Step 4
    public array $existingImages = [];
    public array $additionalImages = [];

    public function mount(int $id): void
    {
        $this->recipe = Recipe::with(['tags', 'ingredients', 'steps', 'images'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        // Load data
        $this->title              = $this->recipe->title;
        $this->description        = $this->recipe->description;
        $this->category_id        = $this->recipe->category_id;
        $this->prep_time          = $this->recipe->prep_time;
        $this->cook_time          = $this->recipe->cook_time;
        $this->servings           = $this->recipe->servings;
        $this->difficulty         = $this->recipe->difficulty;
        $this->existingThumbnail  = $this->recipe->thumbnail;
        $this->selectedTags       = $this->recipe->tags->pluck('id')->toArray();

        // Load ingredients
        $this->ingredients = $this->recipe->ingredients->map(function ($ingredient) {
            return [
                'id'     => $ingredient->id,
                'name'   => $ingredient->name,
                'amount' => $ingredient->amount ?? '',
                'unit'   => $ingredient->unit ?? '',
                'notes'  => $ingredient->notes ?? '',
            ];
        })->toArray();

        if (empty($this->ingredients)) {
            $this->ingredients = [['id' => null, 'name' => '', 'amount' => '', 'unit' => '', 'notes' => '']];
        }

        // Load steps
        $this->steps = $this->recipe->steps->map(function ($step) {
            return [
                'id'          => $step->id,
                'instruction' => $step->instruction,
                'duration'    => $step->duration,
                'image'       => null,
                'existing_image' => $step->image,
            ];
        })->toArray();

        if (empty($this->steps)) {
            $this->steps = [['id' => null, 'instruction' => '', 'duration' => null, 'image' => null, 'existing_image' => null]];
        }

        // Load existing images
        $this->existingImages = $this->recipe->images->map(function ($image) {
            return [
                'id'   => $image->id,
                'path' => $image->image_path,
            ];
        })->toArray();
    }

    protected function rulesForStep(int $step): array
    {
        return match ($step) {
            1 => [
                'title'       => 'required|min:5|max:200|unique:recipes,title,' . $this->recipe->id,
                'description' => 'required|min:20|max:1000',
                'category_id' => 'required|exists:categories,id',
                'prep_time'   => 'required|integer|min:1|max:999',
                'cook_time'   => 'required|integer|min:1|max:999',
                'servings'    => 'required|integer|min:1|max:50',
                'difficulty'  => 'required|in:mudah,sedang,sulit',
                'thumbnail'   => 'nullable|image|max:5120',
            ],
            2 => [
                'ingredients'         => 'required|array|min:1',
                'ingredients.*.name'  => 'required|string|max:100',
            ],
            3 => [
                'steps'               => 'required|array|min:1',
                'steps.*.instruction' => 'required|string|min:10|max:1000',
            ],
            4 => [
                'additionalImages.*' => 'nullable|image|max:3072',
            ],
            default => [],
        };
    }

    public function addIngredient(): void
    {
        $this->ingredients[] = ['id' => null, 'name' => '', 'amount' => '', 'unit' => '', 'notes' => ''];
    }

    public function removeIngredient(int $index): void
    {
        unset($this->ingredients[$index]);
        $this->ingredients = array_values($this->ingredients);
    }

    public function addStep(): void
    {
        $this->steps[] = ['id' => null, 'instruction' => '', 'duration' => null, 'image' => null, 'existing_image' => null];
    }

    public function removeStep(int $index): void
    {
        unset($this->steps[$index]);
        $this->steps = array_values($this->steps);
    }

    public function removeExistingImage(int $index): void
    {
        $image = $this->existingImages[$index] ?? null;
        if ($image) {
            RecipeImage::find($image['id'])?->delete();
            Storage::disk('public')->delete($image['path']);
            unset($this->existingImages[$index]);
            $this->existingImages = array_values($this->existingImages);
        }
    }

    public function removeAdditionalImage(int $index): void
    {
        unset($this->additionalImages[$index]);
        $this->additionalImages = array_values($this->additionalImages);
    }

    public function nextStep(): void
    {
        $this->validate($this->rulesForStep($this->currentStep));
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

    public function update()
    {
        foreach (range(1, $this->totalSteps) as $step) {
            $this->validate($this->rulesForStep($step));
        }

        DB::beginTransaction();
        try {
            // Update thumbnail
            if ($this->thumbnail) {
                if ($this->existingThumbnail) {
                    Storage::disk('public')->delete($this->existingThumbnail);
                }
                $thumbnailPath = $this->thumbnail->store('thumbnails', 'public');
            } else {
                $thumbnailPath = $this->existingThumbnail;
            }

            // Update recipe
            $this->recipe->update([
                'category_id' => $this->category_id,
                'title'       => $this->title,
                'slug'        => Str::slug($this->title) . '-' . Str::random(6),
                'description' => $this->description,
                'thumbnail'   => $thumbnailPath,
                'prep_time'   => $this->prep_time,
                'cook_time'   => $this->cook_time,
                'servings'    => $this->servings,
                'difficulty'  => $this->difficulty,
                'status'      => 'pending', // Re-submit untuk approval
            ]);

            // Sync tags
            $this->recipe->tags()->sync($this->selectedTags);

            // Update ingredients
            $this->recipe->ingredients()->delete();
            foreach ($this->ingredients as $index => $ingredient) {
                if (!empty($ingredient['name'])) {
                    Ingredient::create([
                        'recipe_id' => $this->recipe->id,
                        'name'      => $ingredient['name'],
                        'amount'    => $ingredient['amount'] ?? null,
                        'unit'      => $ingredient['unit'] ?? null,
                        'notes'     => $ingredient['notes'] ?? null,
                        'order'     => $index + 1,
                    ]);
                }
            }

            // Update steps
            $this->recipe->steps()->delete();
            foreach ($this->steps as $index => $step) {
                $stepImagePath = $step['existing_image'] ?? null;
                if (isset($step['image']) && $step['image']) {
                    if ($stepImagePath) {
                        Storage::disk('public')->delete($stepImagePath);
                    }
                    $stepImagePath = $step['image']->store('step-images', 'public');
                }

                Step::create([
                    'recipe_id'   => $this->recipe->id,
                    'step_number' => $index + 1,
                    'instruction' => $step['instruction'],
                    'duration'    => $step['duration'] ?? null,
                    'image'       => $stepImagePath,
                ]);
            }

            // Add new images
            if (!empty($this->additionalImages)) {
                $currentOrder = count($this->existingImages);
                foreach ($this->additionalImages as $index => $image) {
                    if ($image) {
                        $imagePath = $image->store('recipe-images', 'public');
                        RecipeImage::create([
                            'recipe_id'  => $this->recipe->id,
                            'image_path' => $imagePath,
                            'order'      => $currentOrder + $index + 1,
                        ]);
                    }
                }
            }

            DB::commit();

            $this->dispatch('toast', [
                'type'    => 'success',
                'title'   => 'Resep berhasil diperbarui! ✅',
                'message' => 'Menunggu persetujuan ulang dari admin.',
            ]);

            return redirect()->route('my-recipes');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('toast', [
                'type'    => 'error',
                'title'   => 'Gagal memperbarui resep',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        $categories = Category::active()->get();
        $allTags = Tag::orderBy('name')->get();

        return view('livewire.user.edit-recipe', compact('categories', 'allTags'));
    }
}
