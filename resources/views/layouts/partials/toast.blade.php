<div
    x-data="{
        toasts: [],
        add(toast) {
            const id = Date.now() + Math.random()
            this.toasts.push({ id, ...toast })
            setTimeout(() => this.remove(id), 4000)
        },
        remove(id) {
            this.toasts = this.toasts.filter((toast) => toast.id !== id)
        },
    }"
    x-on:toast.window="add($event.detail)"
    class="fixed bottom-6 right-6 z-[100] flex flex-col gap-3 pointer-events-none"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-8 scale-95"
            x-transition:enter-end="opacity-100 translate-x-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0 scale-100"
            x-transition:leave-end="opacity-0 translate-x-8 scale-95"
            :class="{
                'bg-green-50 border-green-200 text-green-800': toast.type === 'success',
                'bg-red-50 border-red-200 text-red-800': toast.type === 'error',
                'bg-blue-50 border-blue-200 text-blue-800': toast.type === 'info',
                'bg-yellow-50 border-yellow-200 text-yellow-800': toast.type === 'warning',
            }"
            class="pointer-events-auto flex items-start gap-3 px-4 py-3 rounded-2xl border shadow-xl shadow-stone-200/50 max-w-sm min-w-[280px]"
        >
            <span class="mt-0.5">
                <svg x-show="toast.type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <svg x-show="toast.type === 'error'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <svg x-show="toast.type === 'warning'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-7.5 13A1 1 0 003.65 18h16.7a1 1 0 00.86-1.5l-7.5-13a1 1 0 00-1.72 0z" />
                </svg>
                <svg x-show="toast.type === 'info'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 110-20 10 10 0 010 20z" />
                </svg>
            </span>

            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold" x-text="toast.title"></p>
                <p class="text-xs opacity-80 mt-0.5" x-show="toast.message" x-text="toast.message"></p>
            </div>

            <button
                type="button"
                @click="remove(toast.id)"
                class="text-current opacity-50 hover:opacity-100 transition-opacity duration-150 flex-shrink-0"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>
</div>

@if (session('success'))
<script>
    window.addEventListener('load', () => {
        window.dispatchEvent(new CustomEvent('toast', {
            detail: {
                type: 'success',
                title: @js(session('success')),
                message: @js(session('success_message')),
            },
        }));
    });
</script>
@endif

@if (session('error'))
<script>
    window.addEventListener('load', () => {
        window.dispatchEvent(new CustomEvent('toast', {
            detail: {
                type: 'error',
                title: @js(session('error')),
                message: @js(session('error_message')),
            },
        }));
    });
</script>
@endif
