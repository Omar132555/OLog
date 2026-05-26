@props(['src','alt'])

<div class="card mb-3">
    <img src="{{ $src }}" alt="{{ $alt }}" class="card-img-top" style="width: 10">
  <div class="card-body">
    <h5 class="card-title">Card title</h5>
    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
    <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small></p>
  </div>
</div>