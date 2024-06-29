@props(['type' => 'button', 'class' => ''])

<button type="submit"
    {{ $attributes->merge(['type' => $type, 'class' => 'inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ' . $class]) }}>
    {{ $slot }}
</button>
