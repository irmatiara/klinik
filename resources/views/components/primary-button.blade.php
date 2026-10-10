<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-hfc-primary border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-hfc-hover active:bg-hfc-dark focus:outline-none focus:ring-2 focus:ring-hfc-primary focus:ring-offset-2 shadow-sm shadow-hfc-primary/20 transition duration-200 cursor-pointer shrink-0']) }}>
    {{ $slot }}
</button>
