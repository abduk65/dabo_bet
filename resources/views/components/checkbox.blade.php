@props(['id', 'name', 'value'])

<input id="{{ $id }}" type="checkbox" name="{{ $name }}" value="{{ $value }}"
    {{ $attributes->merge(['class' => 'focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded']) }}>
