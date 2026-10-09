<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full inline-flex items-center justify-center px-6 py-3.5 bg-hfc-primary border border-transparent rounded-xl font-semibold text-sm text-white tracking-wide hover:bg-hfc-hover active:bg-hfc-dark focus:outline-none focus:ring-2 focus:ring-hfc-primary focus:ring-offset-2 shadow-lg shadow-hfc-primary/25 transition duration-200 cursor-pointer']) }}>
    {{ $slot }}
</button>
