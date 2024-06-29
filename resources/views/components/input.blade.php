@props(['id', 'type' => 'text', 'name', 'value' => null])

<input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" value="{{ $value }}"
    {{ $attributes->merge(['class' => 'shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md']) }}>
