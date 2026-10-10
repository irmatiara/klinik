<x-guest-layout>
    <!-- Card Header -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-hfc-dark tracking-tight">Selamat Datang Kembali</h2>
        <p class="text-sm text-gray-500 mt-1">Silakan masuk menggunakan akun staf klinik Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" class="font-medium text-hfc-dark text-xs uppercase tracking-wider mb-1" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@klinik.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <x-input-label for="password" value="Kata Sandi" class="font-medium text-hfc-dark text-xs uppercase tracking-wider" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-hfc-primary hover:text-hfc-hover font-semibold transition" href="{{ route('password.request') }}">
                        Lupa Password?
                    </a>
                @endif
            </div>

            <div x-data="{ showPassword: false }" class="relative">
                <x-text-input id="password" class="block w-full pe-10"
                                ::type="showPassword ? 'text' : 'password'"
                                type="password"
                                name="password"
                                required autocomplete="current-password"
                                placeholder="••••••••" />
                <button type="button" 
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 end-0 flex items-center pe-3 text-gray-400 hover:text-hfc-primary focus:outline-none transition">
                    <span x-show="!showPassword" class="material-symbols-outlined text-xl">visibility</span>
                    <span x-show="showPassword" class="material-symbols-outlined text-xl" x-cloak>visibility_off</span>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded-md border-gray-300 text-hfc-primary shadow-sm focus:ring-hfc-primary w-4 h-4" name="remember">
                <span class="ms-2 text-xs font-medium text-gray-600">Ingat Saya</span>
            </label>
        </div>

        <div class="pt-2">
            <x-primary-button>
                Masuk Sekarang
            </x-primary-button>
        </div>

        <!-- Register Redirect -->
        <div class="text-center pt-2">
            <span class="text-xs text-gray-500">Belum memiliki akun staf?</span>
            <a href="{{ route('register') }}" class="text-xs font-semibold text-hfc-primary hover:text-hfc-hover ms-1 transition">Daftar Akun Baru</a>
        </div>
    </form>
</x-guest-layout>
