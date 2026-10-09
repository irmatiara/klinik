<x-guest-layout>
    <!-- Card Header -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-hfc-dark tracking-tight">Pendaftaran Akun Staf</h2>
        <p class="text-sm text-gray-500 mt-1">Buat akun staf baru untuk mengakses sistem klinik</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nama Lengkap Staf" class="font-medium text-hfc-dark text-xs uppercase tracking-wider mb-1" />
            <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="dr. Budi Santoso, Sp.OG" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" class="font-medium text-hfc-dark text-xs uppercase tracking-wider mb-1" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@klinik.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Role Selection -->
        <div>
            <x-input-label for="role" value="Peran / Role Staf" class="font-medium text-hfc-dark text-xs uppercase tracking-wider mb-1" />
            <select id="role" name="role" class="block w-full border-gray-200 focus:border-hfc-primary focus:ring-hfc-primary rounded-xl shadow-sm px-4 py-3 text-hfc-dark text-sm transition">
                <option value="dokter" {{ old('role') == 'dokter' ? 'selected' : '' }}>🩺 Dokter</option>
                <option value="perawat" {{ old('role') == 'perawat' ? 'selected' : '' }}>💉 Perawat / Triage</option>
                <option value="resepsionis" {{ old('role') == 'resepsionis' ? 'selected' : '' }}>📋 Resepsionis / Pendaftaran</option>
                <option value="apoteker" {{ old('role') == 'apoteker' ? 'selected' : '' }}>💊 Apoteker / Farmasi</option>
                <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>💳 Kasir / Billing</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>⚙️ Administrator</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Kata Sandi" class="font-medium text-hfc-dark text-xs uppercase tracking-wider mb-1" />
            <x-text-input id="password" class="block w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" class="font-medium text-hfc-dark text-xs uppercase tracking-wider mb-1" />
            <x-text-input id="password_confirmation" class="block w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="Ulangi kata sandi" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-2">
            <x-primary-button>
                Daftar Akun Staf
            </x-primary-button>
        </div>

        <!-- Login Redirect -->
        <div class="text-center pt-2">
            <span class="text-xs text-gray-500">Sudah memiliki akun?</span>
            <a href="{{ route('login') }}" class="text-xs font-semibold text-hfc-primary hover:text-hfc-hover ms-1 transition">Masuk di Sini</a>
        </div>
    </form>
</x-guest-layout>
