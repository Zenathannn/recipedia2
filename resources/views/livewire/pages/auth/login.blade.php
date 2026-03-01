<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();
        Session::flash('success', 'Login berhasil.');
        Session::flash('success_message', 'Selamat datang kembali di Recipedia.');

        $this->redirectIntended(default: route('dashboard', absolute: false));
    }
}; ?>

<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        {{-- Card Container --}}
        <div class="bg-white rounded-2xl shadow-xl border border-stone-100 overflow-hidden">

            {{-- Header Background --}}
            <div class="bg-gradient-to-r from-orange-500 via-red-500 to-rose-500 h-2"></div>

            <div class="p-8">
                {{-- Title Section --}}
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-orange-100 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-stone-800">Selamat Datang Kembali</h2>
                    <p class="mt-2 text-stone-500">Masuk untuk melanjutkan eksplorasi resep favorit Anda</p>
                </div>

                @if (session('status'))
                <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    <x-auth-session-status :status="session('status')" />
                </div>
                @endif

                <form wire:submit="login" class="space-y-5">
                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-stone-700 mb-1">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input wire:model="form.email" id="email" type="email" name="email" required autofocus autocomplete="username" class="block w-full pl-10 pr-3 py-3 border border-stone-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 placeholder-stone-400 text-stone-800" placeholder="email@contoh.com">
                        </div>
                        @error('form.email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-stone-700 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input wire:model="form.password" id="password" type="password" name="password" required autocomplete="current-password" class="block w-full pl-10 pr-3 py-3 border border-stone-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 placeholder-stone-400 text-stone-800" placeholder="Masukkan password Anda">
                        </div>
                        @error('form.password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember" class="inline-flex items-center">
                            <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-stone-300 text-orange-600 shadow-sm focus:ring-orange-500" name="remember">
                            <span class="ml-2 text-sm text-stone-600">Ingat saya</span>
                        </label>
                        @if (Route::has('password.request'))
                        <a class="text-sm text-orange-600 hover:text-orange-700 transition-colors duration-200" href="{{ route('password.request') }}" wire:navigate>Lupa password?</a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <div class="pt-2">
                        <button type="submit" wire:loading.attr="disabled" wire:target="login" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-orange-500 via-red-500 to-rose-500 hover:from-orange-600 hover:via-red-600 hover:to-rose-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200 transform hover:scale-[1.02] disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none">
                            <span wire:loading.remove wire:target="login" class="inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Masuk Sekarang
                            </span>
                            <span wire:loading wire:target="login" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </form>

                {{-- Register Link --}}
                <div class="mt-6 text-center">
                    <p class="text-sm text-stone-500">
                        Belum punya akun?
                        <a href="{{ route('register') }}" wire:navigate class="font-medium text-orange-600 hover:text-orange-700 transition-colors duration-200">
                            Daftar sekarang
                        </a>
                    </p>
                </div>
            </div>
        </div>

        {{-- Footer Note --}}
        <p class="mt-4 text-center text-xs text-stone-400">
            Akses akun Anda untuk menyimpan resep, favorit, dan aktivitas memasak
        </p>
    </div>
</div>
