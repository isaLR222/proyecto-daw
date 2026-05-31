@props(['disabled' => false])

<input @disabled($disabled){{ $attributes->merge(['class' => 'bg-gray-800 text-white border-gray-500 focus:border-colorInputs focus:ring-colorInputs rounded-md shadow-sm',])}}>
