{{-- Toast Notification System --}}
<div x-data="{
        toasts: [],
        add(toast) {
            const id = Date.now()
            this.toasts.push({ id, ...toast })
            setTimeout(() => this.remove(id), 4000)
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id)
        }
     }"
    x-on:toast.window="add($event.detail)"
    class="fixed bottom-6 right-6 z-[100] flex flex-col gap-3 pointer-events-none">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-8 scale-95"
            x-transition:enter-end="opacity-100 translate-x-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0 scale-100"
            x-transition:leave-end="opacity-0 translate-x-8 scale-95"
            :class="{
                'bg-green-50 border-green-200 text-green-800': toast.type === 'success',
                'bg-red-50 border-red-200 text-red-800':     toast.type === 'error',
                'bg-blue-50 border-blue-200 text-blue-800':  toast.type === 'info',
                'bg-yellow-50 border-yellow-200 text-yellow-800': toast.type === 'warning',
             }"
            class="pointer-events-auto flex items-start gap-3 px-4 py-3
                    rounded-2xl border shadow-xl shadow-stone-200/50
                    max-w-sm min-w-[280px]">

            {{-- Icon --}}
            <span class="text-xl mt-0.5" x-text="{
                success: '✅',
                error:   '❌',
                info:    'ℹ️',
                warning: '⚠️',
            }[toast.type] || 'ℹ️'"></span>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold" x-text="toast.title"></p>
                <p class="text-xs opacity-80 mt-0.5" x-text="toast.message" x-show="toast.message"></p>
            </div>

            {{-- Close --}}
            <button @click="remove(toast.id)"
                class="text-current opacity-50 hover:opacity-100
                           transition-opacity duration-150 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>
</div>

{{-- Session Flash Messages --}}
@if(session('success'))
<script>
    window.dispatchEvent(new CustomEvent('toast', {
        detail: {
            type: 'success',
            title: '{{ session('
            success ') }}'
        }
    }))
</script>
@endif
@if(session('error'))
<script>
    window.dispatchEvent(new CustomEvent('toast', {
        detail: {
            type: 'error',
            title: '{{ session('
            error ') }}'
        }
    }))
</script>
@endif