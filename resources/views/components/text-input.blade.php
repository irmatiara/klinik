@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 focus:border-hfc-primary focus:ring-hfc-primary rounded-xl shadow-sm px-4 py-3 text-hfc-dark text-sm transition placeholder:text-gray-400']) }}>
