@props(['type','name','placeholder', 'value'])

<input {{ $attributes->merge(['class' => 'form-control border-0 border-bottom rounded-0 text-light shadow-none w-100 bg-transparent',
    'type' => $type,
    'name' =>$name,
    'style'=>'padding-left: 30px',
    'value' => $value??'',
    'placeholder'=> $placeholder
    ])}}>
