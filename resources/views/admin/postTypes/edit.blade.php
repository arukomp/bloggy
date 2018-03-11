@extends('bloggy::layouts.admin.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Post Type: <span class="text-muted font-weight-light">{{ str_limit($postType->name, 45) }}</span></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        @if ($postType->trashed())
        <form class="button-form" action="{{ route('admin.postTypes.restore', $postType) }}" method="post">
            @csrf @method('put')
            <button type="submit" class="btn btn-sm btn-success mr-3">
                <i data-feather="chevrons-left"></i>
                Restore
            </button>
        </form>
        @else
        <form class="button-form" action="{{ route('admin.postTypes.destroy', $postType) }}" method="post">
            @csrf @method('delete')
            <button class="btn btn-sm btn-danger mr-3" type="submit">
                <i data-feather="trash-2"></i>
                Trash It
            </button>
        </form>
        @endif

        <button class="btn btn-primary btn-sm ml-1" onclick="$('#save').click();">
            <i data-feather="save"></i>
            Save
        </button>
    </div>
</div>

<form action="{{ route('admin.postTypes.update', $postType) }}" method="post">
    @csrf
    @method('put')
    <div class="form-group">
        <label for="title">Name</label>
        <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ $postType->name }}">
    </div>

    <div class="form-group">
        <label for="plural">Plural form</label>
        <input type="text" class="form-control" name="plural" id="plural" placeholder="Plural Form" value="{{ $postType->plural }}">
    </div>

    <div class="form-group">
        <label for="slug">Slug</label>
        <input type="text" class="form-control" name="slug" id="slug" placeholder="URL slug" value="{{ $postType->slug }}">
    </div>

    <div class="form-group">
        <label for="body">Description</label>
        <textarea class="form-control" name="description" id="description" placeholder="Description" rows="3">{{ $postType->description }}</textarea>
    </div>

    <input id="save" type="submit" name="save" hidden>
</form>

<div class="d-flex justify-content-end flex-wrap flex-md-nowrap align-items-center pt-2 mt-3 border-top">
    <div class="btn-toolbar mt-2 mt-md-0">
        @if ($postType->trashed())
        <form class="button-form" action="{{ route('admin.postTypes.restore', $postType) }}" method="post">
            @csrf @method('put')
            <button type="submit" class="btn btn-sm btn-success mr-3">
                <i data-feather="chevrons-left"></i>
                Restore
            </button>
        </form>
        @else
        <form class="button-form" action="{{ route('admin.postTypes.destroy', $postType) }}" method="post">
            @csrf @method('delete')
            <button class="btn btn-sm btn-danger mr-3" type="submit">
                <i data-feather="trash-2"></i>
                Trash It
            </button>
        </form>
        @endif

        <button class="btn btn-primary btn-sm ml-1" onclick="$('#save').click();">
            <i data-feather="save"></i>
            Save
        </button>
    </div>
</div>
@endsection