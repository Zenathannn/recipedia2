<div class="min-h-screen pt-24 pb-16 bg-stone-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-10">
            <h1 class="font-display text-4xl md:text-5xl font-bold text-stone-900 mb-3">
                Edit Resep <span class="gradient-text">Baru</span>
            </h1>
            <p class="text-stone-500 text-lg">
                Bagikan kreasi masakanmu kepada jutaan orang
            </p>
        </div>

        {{-- Progress Steps --}}
        <div class="bg-white rounded-2xl p-6 shadow-md border border-stone-100 mb-8">
            <div class="flex items-center justify-between mb-4">
                @foreach([
                ['num' => 1, 'label' => 'Info Dasar'],
                ['num' => 2, 'label' => 'Bahan'],
                ['num' => 3, 'label' => 'Langkah'],
                ['num' => 4, 'label' => 'Foto'],
                ] as $step)
                <div class="flex-1 flex items-center">
                    <button wire:click="goToStep({{ $step['num'] }})"
                        class="flex flex-col items-center gap-2 w-full group">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                                        font-bold text-sm transition-all duration-300
                                        {{ $currentStep >= $step['num']
                                            ? 'bg-primary-600 text-white shadow-lg'
                                            : 'bg-stone-200 text-stone-500' }}">
                            @if($currentStep > $step['num'])
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            @else
                            {{ $step['num'] }}
                            @endif
                        </div>
                        <span class="text-xs font-semibold
                                         {{ $currentStep === $step['num']
                                             ? 'text-primary-600'
                                             : 'text-stone-500' }}">
                            {{ $step['label'] }}
                        </span>
                    </button>
                    @if(!$loop->last)
                    <div class="flex-1 h-0.5 mx-2
                                        {{ $currentStep > $step['num']
                                            ? 'bg-primary-600'
                                            : 'bg-stone-200' }}">
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Form Container --}}
        <div class="bg-white rounded-3xl p-8 shadow-md border border-stone-100">

            {{-- ════════════════════════════════════════════
                 STEP 1: BASIC INFO
            ════════════════════════════════════════════ --}}
            @if($currentStep === 1)
            <div class="space-y-6">
                <h2 class="font-display text-2xl font-bold text-stone-900 mb-6">
                    📋 Informasi Dasar Resep
                </h2>

                {{-- Title --}}
                <div>
                    <label class="block text-sm font-semibold text-stone-700 mb-2">
                        Judul Resep <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="title"
                        placeholder="Contoh: Rendang Daging Sapi Bumbu Kacang"
                        class="w-full px-4 py-3 rounded-xl border border-stone-200
                                      focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                      transition-all duration-200" />
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-semibold text-stone-700 mb-2">
                        Deskripsi Resep <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="description" rows="4"
                        placeholder="Ceritakan tentang resep ini, apa yang membuatnya istimewa..."
                        class="w-full px-4 py-3 rounded-xl border border-stone-200
                                         focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                         resize-none transition-all duration-200"></textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label class="block text-sm font-semibold text-stone-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                        @foreach($categories as $category)
                        <button type="button"
                            wire:click="$set('category_id', {{ $category->id }})"
                            class="flex flex-col items-center gap-2 p-4 rounded-2xl
                                               border-2 transition-all duration-200
                                               {{ $category_id === $category->id
                                                   ? 'border-primary-600 bg-primary-50'
                                                   : 'border-stone-200 hover:border-stone-300' }}">
                            <span class="text-3xl">{{ $category->icon }}</span>
                            <span class="text-xs font-semibold text-center
                                                 {{ $category_id === $category->id
                                                     ? 'text-primary-700'
                                                     : 'text-stone-700' }}">
                                {{ $category->name }}
                            </span>
                        </button>
                        @endforeach
                    </div>
                    @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Timing & Servings --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-stone-700 mb-2">
                            ⏱️ Waktu Persiapan (menit)
                        </label>
                        <input type="number" wire:model="prep_time" min="1" max="999"
                            class="w-full px-4 py-3 rounded-xl border border-stone-200
                                          focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                          transition-all duration-200" />
                        @error('prep_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-stone-700 mb-2">
                            🔥 Waktu Memasak (menit)
                        </label>
                        <input type="number" wire:model="cook_time" min="1" max="999"
                            class="w-full px-4 py-3 rounded-xl border border-stone-200
                                          focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                          transition-all duration-200" />
                        @error('cook_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-stone-700 mb-2">
                            🍽️ Porsi
                        </label>
                        <input type="number" wire:model="servings" min="1" max="50"
                            class="w-full px-4 py-3 rounded-xl border border-stone-200
                                          focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                          transition-all duration-200" />
                        @error('servings') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Difficulty --}}
                <div>
                    <label class="block text-sm font-semibold text-stone-700 mb-2">
                        Tingkat Kesulitan <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-4">
                        @foreach([
                        ['value' => 'mudah', 'label' => 'Mudah', 'icon' => '✅', 'color' => 'green'],
                        ['value' => 'sedang', 'label' => 'Sedang', 'icon' => '⚠️', 'color' => 'yellow'],
                        ['value' => 'sulit', 'label' => 'Sulit', 'icon' => '🔥', 'color' => 'red'],
                        ] as $diff)
                        <button type="button"
                            wire:click="$set('difficulty', '{{ $diff['value'] }}')"
                            class="flex items-center justify-center gap-2 p-4 rounded-xl
                                               border-2 font-semibold text-sm transition-all duration-200
                                               {{ $difficulty === $diff['value']
                                                   ? 'border-'.$diff['color'].'-500 bg-'.$diff['color'].'-50 text-'.$diff['color'].'-700'
                                                   : 'border-stone-200 text-stone-600 hover:border-stone-300' }}">
                            <span class="text-xl">{{ $diff['icon'] }}</span>
                            {{ $diff['label'] }}
                        </button>
                        @endforeach
                    </div>
                    @error('difficulty') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Thumbnail --}}
                <div>
                    <label class="block text-sm font-semibold text-stone-700 mb-2">
                        📸 Foto Utama Resep <span class="text-red-500">*</span>
                    </label>
                    <div class="border-2 border-dashed border-stone-300 rounded-2xl p-8 text-center
                                    hover:border-primary-500 transition-colors duration-200">
                        @if($thumbnail)
                        <div class="relative inline-block">
                            <img src="{{ $thumbnail->temporaryUrl() }}"
                                class="max-w-full h-64 object-cover rounded-xl shadow-lg" />
                            <button type="button" wire:click="$set('thumbnail', null)"
                                class="absolute -top-2 -right-2 w-8 h-8 rounded-full
                                                   bg-red-500 text-white flex items-center justify-center
                                                   hover:bg-red-600 transition-colors duration-200">
                                ×
                            </button>
                        </div>
                        @else
                        <label class="cursor-pointer">
                            <div class="mb-3">
                                <svg class="w-12 h-12 mx-auto text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-stone-600 font-medium mb-1">
                                Klik untuk upload foto
                            </p>
                            <p class="text-stone-400 text-xs">
                                JPG, PNG maksimal 5MB
                            </p>
                            <input type="file" wire:model="thumbnail" accept="image/*" class="hidden" />
                        </label>
                        @endif
                    </div>
                    @error('thumbnail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Tags --}}
                <div>
                    <label class="block text-sm font-semibold text-stone-700 mb-2">
                        🏷️ Tag (Pilih maksimal 5)
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($allTags as $tag)
                        <button type="button"
                            wire:click="
                                            @if(in_array($tag->id, $selectedTags))
                                                $set('selectedTags', {{ json_encode(array_values(array_diff($selectedTags, [$tag->id]))) }})
                                            @elseif(count($selectedTags) < 5)
                                                $set('selectedTags', {{ json_encode(array_merge($selectedTags, [$tag->id])) }})
                                            @endif
                                        "
                            class="badge transition-all duration-200
                                               {{ in_array($tag->id, $selectedTags)
                                                   ? 'bg-primary-600 text-white ring-2 ring-primary-300'
                                                   : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                            {{ $tag->name }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- ════════════════════════════════════════════
                 STEP 2: INGREDIENTS
            ════════════════════════════════════════════ --}}
            @if($currentStep === 2)
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="font-display text-2xl font-bold text-stone-900">
                        🥘 Bahan-Bahan
                    </h2>
                    <button type="button" wire:click="addIngredient"
                        class="px-4 py-2 rounded-xl bg-primary-600 text-white
                                       font-semibold text-sm hover:bg-primary-700
                                       transition-colors duration-200">
                        + Tambah Bahan
                    </button>
                </div>

                <div class="space-y-4">
                    @foreach($ingredients as $index => $ingredient)
                    <div class="bg-stone-50 rounded-2xl p-5 border border-stone-200">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary-100
                                                flex items-center justify-center font-bold text-primary-700 text-sm">
                                {{ $index + 1 }}
                            </div>

                            <div class="flex-1 grid grid-cols-1 md:grid-cols-12 gap-3">
                                {{-- Name --}}
                                <div class="md:col-span-5">
                                    <input type="text"
                                        wire:model="ingredients.{{ $index }}.name"
                                        placeholder="Nama bahan (contoh: Bawang merah)"
                                        class="w-full px-3 py-2 rounded-lg border border-stone-300
                                                          focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                                          text-sm transition-all duration-200" />
                                    @error("ingredients.{$index}.name")
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Amount --}}
                                <div class="md:col-span-2">
                                    <input type="text"
                                        wire:model="ingredients.{{ $index }}.amount"
                                        placeholder="Jumlah"
                                        class="w-full px-3 py-2 rounded-lg border border-stone-300
                                                          focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                                          text-sm transition-all duration-200" />
                                </div>

                                {{-- Unit --}}
                                <div class="md:col-span-2">
                                    <select wire:model="ingredients.{{ $index }}.unit"
                                        class="w-full px-3 py-2 rounded-lg border border-stone-300
                                                           focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                                           text-sm transition-all duration-200">
                                        <option value="">Satuan</option>
                                        @foreach(['gram', 'kg', 'ml', 'liter', 'sdm', 'sdt', 'buah', 'siung', 'lembar', 'batang', 'bungkus', 'sachet'] as $unit)
                                        <option value="{{ $unit }}">{{ $unit }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Notes --}}
                                <div class="md:col-span-3">
                                    <input type="text"
                                        wire:model="ingredients.{{ $index }}.notes"
                                        placeholder="Catatan (opsional)"
                                        class="w-full px-3 py-2 rounded-lg border border-stone-300
                                                          focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                                          text-sm transition-all duration-200" />
                                </div>
                            </div>

                            @if(count($ingredients) > 1)
                            <button type="button"
                                wire:click="removeIngredient({{ $index }})"
                                class="flex-shrink-0 w-8 h-8 rounded-lg bg-red-100 text-red-600
                                                       hover:bg-red-200 transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                @error('ingredients')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            @endif

            {{-- ════════════════════════════════════════════
                 STEP 3: STEPS
            ════════════════════════════════════════════ --}}
            @if($currentStep === 3)
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="font-display text-2xl font-bold text-stone-900">
                        👨‍🍳 Langkah-Langkah Memasak
                    </h2>
                    <button type="button" wire:click="addStep"
                        class="px-4 py-2 rounded-xl bg-primary-600 text-white
                                       font-semibold text-sm hover:bg-primary-700
                                       transition-colors duration-200">
                        + Tambah Langkah
                    </button>
                </div>

                <div class="space-y-6">
                    @foreach($steps as $index => $step)
                    <div class="bg-stone-50 rounded-2xl p-6 border border-stone-200">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full
                                                bg-gradient-to-br from-primary-500 to-primary-600
                                                flex items-center justify-center font-bold text-white shadow-lg">
                                {{ $index + 1 }}
                            </div>

                            <div class="flex-1 space-y-4">
                                {{-- Instruction --}}
                                <div>
                                    <textarea wire:model="steps.{{ $index }}.instruction"
                                        rows="3"
                                        placeholder="Tulis instruksi untuk langkah ini..."
                                        class="w-full px-4 py-3 rounded-xl border border-stone-300
                                                             focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                                             resize-none transition-all duration-200"></textarea>
                                    @error("steps.{$index}.instruction")
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex flex-col sm:flex-row gap-4">
                                    {{-- Duration --}}
                                    <div class="flex-1">
                                        <label class="block text-xs font-semibold text-stone-600 mb-1">
                                            ⏱️ Durasi (menit, opsional)
                                        </label>
                                        <input type="number"
                                            wire:model="steps.{{ $index }}.duration"
                                            placeholder="0"
                                            min="1"
                                            class="w-full px-3 py-2 rounded-lg border border-stone-300
                                                              focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                                              text-sm transition-all duration-200" />
                                    </div>

                                    {{-- Image --}}
                                    <div class="flex-1">
                                        <label class="block text-xs font-semibold text-stone-600 mb-1">
                                            📷 Foto Langkah (opsional)
                                        </label>
                                        @if(isset($steps[$index]['image']) && $steps[$index]['image'])
                                        <div class="relative inline-block">
                                            <img src="{{ $steps[$index]['image']->temporaryUrl() }}"
                                                class="h-20 w-20 object-cover rounded-lg" />
                                            <button type="button"
                                                wire:click="$set('steps.{{ $index }}.image', null)"
                                                class="absolute -top-1 -right-1 w-5 h-5 rounded-full
                                                                       bg-red-500 text-white text-xs flex items-center justify-center">
                                                ×
                                            </button>
                                        </div>
                                        @else
                                        <label class="block cursor-pointer">
                                            <div class="border-2 border-dashed border-stone-300 rounded-lg
                                                                    p-3 text-center hover:border-primary-500
                                                                    transition-colors duration-200">
                                                <p class="text-xs text-stone-500">Upload foto</p>
                                            </div>
                                            <input type="file"
                                                wire:model="steps.{{ $index }}.image"
                                                accept="image/*"
                                                class="hidden" />
                                        </label>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if(count($steps) > 1)
                            <button type="button"
                                wire:click="removeStep({{ $index }})"
                                class="flex-shrink-0 w-8 h-8 rounded-lg bg-red-100 text-red-600
                                                       hover:bg-red-200 transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                @error('steps')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            @endif

            {{-- ════════════════════════════════════════════
                 STEP 4: ADDITIONAL IMAGES
            ════════════════════════════════════════════ --}}
            @if($currentStep === 4)
            <div class="space-y-6">
                <h2 class="font-display text-2xl font-bold text-stone-900">
                    📸 Foto Tambahan (Opsional)
                </h2>
                <p class="text-stone-500">
                    Upload foto tambahan untuk melengkapi resep kamu
                </p>

                <div class="border-2 border-dashed border-stone-300 rounded-2xl p-8 text-center
                                hover:border-primary-500 transition-colors duration-200">
                    <label class="cursor-pointer">
                        <svg class="w-12 h-12 mx-auto text-stone-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-stone-600 font-medium mb-1">
                            Klik untuk upload foto tambahan
                        </p>
                        <p class="text-stone-400 text-xs">
                            JPG, PNG maksimal 3MB per foto
                        </p>
                        <input type="file"
                            wire:model="additionalImages"
                            accept="image/*"
                            multiple
                            class="hidden" />
                    </label>
                </div>

                @if(!empty($additionalImages))
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($additionalImages as $index => $image)
                    <div class="relative group">
                        <img src="{{ $image->temporaryUrl() }}"
                            class="w-full aspect-square object-cover rounded-xl shadow-md" />
                        <button type="button"
                            wire:click="removeAdditionalImage({{ $index }})"
                            class="absolute top-2 right-2 w-8 h-8 rounded-full
                                                   bg-red-500 text-white flex items-center justify-center
                                                   opacity-0 group-hover:opacity-100
                                                   hover:bg-red-600 transition-all duration-200">
                            ×
                        </button>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif

            {{-- Navigation Buttons --}}
            <div class="flex items-center justify-between pt-8 mt-8 border-t border-stone-200">
                @if($currentStep > 1)
                <button type="button" wire:click="previousStep"
                    class="flex items-center gap-2 px-6 py-3 rounded-xl
                                   bg-stone-100 text-stone-700 font-semibold text-sm
                                   hover:bg-stone-200 transition-colors duration-200">
                    ← Kembali
                </button>
                @else
                <div></div>
                @endif

                @if($currentStep < $totalSteps)
                    <button type="button" wire:click="nextStep"
                    class="flex items-center gap-2 px-6 py-3 rounded-xl
                                   bg-primary-600 text-white font-semibold text-sm
                                   hover:bg-primary-700 transition-colors duration-200">
                    Lanjutkan →
                    </button>
                    @else
                    <button type="button" wire:click="update"
                        wire:loading.attr="disabled"
                        class="flex items-center gap-2 px-8 py-3 rounded-xl
                                   bg-gradient-to-r from-primary-500 to-primary-600
                                   hover:from-primary-600 hover:to-primary-700
                                   text-white font-bold text-sm shadow-lg hover:shadow-xl
                                   disabled:opacity-50 disabled:cursor-not-allowed
                                   transition-all duration-200">
                        <span wire:loading.remove>💾 Update Resep</span>
                        <span wire:loading>Mengupload...</span>
                    </button>
                    @endif
            </div>
        </div>

        {{-- Loading Overlay --}}
        <div wire:loading wire:target="submit"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50
                    flex items-center justify-center">
            <div class="bg-white rounded-2xl px-8 py-6 shadow-2xl flex flex-col items-center gap-3">
                <div class="w-12 h-12 border-4 border-primary-600 border-t-transparent
                            rounded-full animate-spin"></div>
                <p class="font-semibold text-stone-700">Mengupload resep...</p>
                <p class="text-xs text-stone-500">Harap tunggu sebentar</p>
            </div>
        </div>
    </div>
</div>
