@props(['style' => '', 'class' => ''])

<button
    {{ $attributes->merge([
        'class' => 'btn btn-primary button-pop rounded-3 p-2 mt-4 ' . $class,
        'style' => $style,
        'type' => 'submit',
    ]) }}>
    {{ $slot }}
</button>
