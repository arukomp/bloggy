@extends('bloggy::layouts.admin.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="addNewDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Add New ...
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="addNewDropdown">
                    @foreach ($postTypes as $postType)
                    <a class="dropdown-item" href="{{ route('admin.postType.posts.create', $postType) }}">{{ $postType->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection