@props(['post' => null, 'width'=>'45', 'height'=>'45'])

@if ($post->user->image)
    <div class="avatar-circle">
        <img src="{{ asset('storage/' . $post->user->image) }}" class="rounded-circle"
            width="{{ $width }}" height="{{ $height }}" alt="">
    </div>
@else
    <div class="profile-circle" style="width:{{ $width }}px !important; height:{{ $height }}px !important">
        {{ strtoupper(substr($post->user->name, 0, 2)) }}
    </div>
@endif
