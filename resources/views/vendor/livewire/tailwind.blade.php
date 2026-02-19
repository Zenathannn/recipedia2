@php
if (! isset($scrollTo)) {
$scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
? <<<JS
    (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '' ;
    @endphp

    <div>
    @if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            <span>
                @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-stone-400 bg-stone-100 border border-stone-200 cursor-default leading-5 rounded-lg">
                    Previous
                </span>
                @else
                <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-stone-700 bg-white border border-stone-200 leading-5 rounded-lg hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-primary-500 active:bg-stone-100 transition ease-in-out duration-150">
                    Previous
                </button>
                @endif
            </span>

            <span>
                @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-stone-700 bg-white border border-stone-200 leading-5 rounded-lg hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-primary-500 active:bg-stone-100 transition ease-in-out duration-150">
                    Next
                </button>
                @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-stone-400 bg-stone-100 border border-stone-200 cursor-default leading-5 rounded-lg">
                    Next
                </span>
                @endif
            </span>
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-stone-600 leading-5">
                    Menampilkan
                    <span class="font-semibold">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-semibold">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-semibold">{{ $paginator->total() }}</span>
                    hasil
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-xl shadow-sm">
                    <span>
                        @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Previous">
                            <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-stone-400 bg-stone-100 border border-stone-200 cursor-default rounded-l-xl leading-5" aria-hidden="true">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                        @else
                        <button wire:click="previousPage" rel="prev" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-stone-600 bg-white border border-stone-200 rounded-l-xl leading-5 hover:bg-primary-50 hover:text-primary-700 hover:border-primary-300 focus:z-10 focus:outline-none focus:ring-2 focus:ring-primary-500 active:bg-primary-100 transition ease-in-out duration-150" aria-label="Previous">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        @endif
                    </span>

                    @foreach ($elements as $element)
                    @if (is_string($element))
                    <span aria-disabled="true">
                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-stone-600 bg-white border border-stone-200 cursor-default leading-5">{{ $element }}</span>
                    </span>
                    @endif

                    @if (is_array($element))
                    @foreach ($element as $page => $url)
                    <span wire:key="paginator-page{{ $page }}">
                        @if ($page == $paginator->currentPage())
                        <span aria-current="page">
                            <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-bold text-white bg-primary-600 border border-primary-600 cursor-default leading-5">{{ $page }}</span>
                        </span>
                        @else
                        <button wire:click="gotoPage({{ $page }})" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-stone-700 bg-white border border-stone-200 leading-5 hover:bg-primary-50 hover:text-primary-700 hover:border-primary-300 focus:z-10 focus:outline-none focus:ring-2 focus:ring-primary-500 active:bg-primary-100 transition ease-in-out duration-150" aria-label="Go to page {{ $page }}">
                            {{ $page }}
                        </button>
                        @endif
                    </span>
                    @endforeach
                    @endif
                    @endforeach

                    <span>
                        @if ($paginator->hasMorePages())
                        <button wire:click="nextPage" rel="next" class="relative inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-stone-600 bg-white border border-stone-200 rounded-r-xl leading-5 hover:bg-primary-50 hover:text-primary-700 hover:border-primary-300 focus:z-10 focus:outline-none focus:ring-2 focus:ring-primary-500 active:bg-primary-100 transition ease-in-out duration-150" aria-label="Next">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        @else
                        <span aria-disabled="true" aria-label="Next">
                            <span class="relative inline-flex items-center px-3 py-2 -ml-px text-sm font-medium text-stone-400 bg-stone-100 border border-stone-200 cursor-default rounded-r-xl leading-5" aria-hidden="true">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                        @endif
                    </span>
                </span>
            </div>
        </div>
    </nav>
    @endif
    </div>