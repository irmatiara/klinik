<!-- Sidebar Overlay for Mobile -->
<div x-show="sidebarOpen"
    @click="sidebarOpen = false"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"></div>

<!-- Sidebar Container -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:sticky lg:top-0 h-screen flex flex-col justify-between shrink-0">

    <div class="flex flex-col flex-1 min-h-0">
        <!-- Sidebar Brand / Logo -->
        <div class="h-16 flex items-center justify-center px-4 border-b border-gray-100 shrink-0">
            <a href="{{ route('dashboard') }}" class="block w-full">
                <x-application-logo />
            </a>
        </div>

        <!-- Navigation Menu Links -->
        <nav class="px-4 py-6 space-y-1.5 flex-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-hfc-primary text-white shadow-md shadow-hfc-primary/20' : 'text-gray-600 hover:bg-hfc-light/60 hover:text-hfc-primary' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('pasien.index') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('pasien.*') ? 'bg-hfc-primary text-white shadow-md shadow-hfc-primary/20' : 'text-gray-600 hover:bg-hfc-light/60 hover:text-hfc-primary' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Pasien</span>
            </a>

            <a href="{{ route('kunjungan.index') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('kunjungan.*') ? 'bg-hfc-primary text-white shadow-md shadow-hfc-primary/20' : 'text-gray-600 hover:bg-hfc-light/60 hover:text-hfc-primary' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Antrian</span>
            </a>

            <a href="{{ route('pemeriksaan-awal.index') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('pemeriksaan-awal.*') ? 'bg-hfc-primary text-white shadow-md shadow-hfc-primary/20' : 'text-gray-600 hover:bg-hfc-light/60 hover:text-hfc-primary' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span>Pemeriksaan Awal</span>
            </a>

            <a href="{{ route('rekam-medis.index') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('rekam-medis.*') ? 'bg-hfc-primary text-white shadow-md shadow-hfc-primary/20' : 'text-gray-600 hover:bg-hfc-light/60 hover:text-hfc-primary' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Rekam Medis</span>
            </a>

            <a href="{{ route('obat.index') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('obat.*') ? 'bg-hfc-primary text-white shadow-md shadow-hfc-primary/20' : 'text-gray-600 hover:bg-hfc-light/60 hover:text-hfc-primary' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.12a2 2 0 00-1.87 1.05A6 6 0 004 19.5v.5h16v-.5a6 6 0 00-.572-3.072zM12 11a4 4 0 100-8 4 4 0 000 8z" />
                </svg>
                <span>Obat</span>
            </a>

            <a href="{{ route('tagihan.index') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('tagihan.*') ? 'bg-hfc-primary text-white shadow-md shadow-hfc-primary/20' : 'text-gray-600 hover:bg-hfc-light/60 hover:text-hfc-primary' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>Tagihan</span>
            </a>

            <a href="{{ route('resep-obat.index') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('resep-obat.*') ? 'bg-hfc-primary text-white shadow-md shadow-hfc-primary/20' : 'text-gray-600 hover:bg-hfc-light/60 hover:text-hfc-primary' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.12a2 2 0 00-1.87 1.05A6 6 0 004 19.5v.5h16v-.5a6 6 0 00-.572-3.072z" />
                </svg>
                <span>Resep Obat</span>
            </a>
        </nav>
    </div>

    <!-- Sidebar Footer / User Profile Dropdown -->
    <div class="p-4 border-t border-gray-100 shrink-0">
        <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
            <div @click="open = ! open">
                <button type="button" class="w-full flex items-center justify-between p-2.5 rounded-xl bg-gray-50 hover:bg-gray-100 transition text-left focus:outline-none">
                    <div class="truncate">
                        <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-500 shrink-0 ms-2 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <div x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute z-50 bottom-full mb-2 w-full rounded-xl shadow-lg bg-white border border-gray-100 py-1"
                style="display: none;"
                @click="open = false">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </div>
        </div>
    </div>
</aside>