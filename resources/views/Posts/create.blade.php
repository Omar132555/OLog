@extends('layouts.app')
@section('content')
    <div class="container d-flex flex-wrap justify-content-center p-5">
        <form url="{{ route('posts.store') }}" class="w-50" id="form" enctype="multipart/form-data">
            @csrf
            <div class="mt-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" name="title" placeholder="Post title">
                <x-form-error name="title"></x-form-error>
            </div>
            <div class="mt-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" name="description" placeholder="Post Description" rows="3"></textarea>
                <x-form-error name="description"></x-form-error>
            </div>
            <div class="mt-3 position-relative">
                <label for="category" class="form-label">Category</label>
                <input type="text" name="category" id="Search" class="form-control" url="{{ route('category.search') }}">
                <input type="hidden" name="categoryId" id="categoryId">
                <div id="results" class="position-absolute z-3 w-100">
                </div>
                <x-form-error name="category"></x-form-error>
                <x-form-error name="categoryId"></x-form-error>
            </div>
            <div class="mt-3">
                <div class="mt-3">
                    <label for="image" class="form-label">Upload Image</label>
                    <input type="file" name="image" class="form-control" id="imageInput">
                    <x-form-error name="image"></x-form-error>
                </div>
                <x-form-button class="px-4">Create</x-form-button>
            </div>
        </form>
    </div>
@endsection
