@props(['url' => '','class' => '' ,'id' => '', 'user_id'=>''])

<button
    {{ $attributes->merge([
        'class' => 'followBtn'. $class,
        'id' => 'followBtn',
        'url' => route('follow'),
        'data-id' => $id,
        'user_id' => auth()->user()->id,
        'style' => $user_id == $id ? 'display:none' : ''
    ]) }}>
    {{ auth()->user()->following()->where('following_id', $id)->exists() ? 'Following' : 'Follow' }}
</button>
