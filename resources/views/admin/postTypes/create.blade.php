@extends('bloggy::layouts.admin.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Add a new Post Type</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button class="btn btn-primary btn-sm ml-1" onclick="$('#save').click();">
            <i data-feather="save"></i>
            Save
        </button>
    </div>
</div>

<form action="{{ route('admin.postTypes.store') }}" method="post">
    @csrf
    <div class="form-group">
        <label for="title">Name</label>
        <input type="text" class="form-control" name="name" id="name" placeholder="Name">
    </div>

    <div class="form-group">
        <label for="plural">Plural form</label>
        <input type="text" class="form-control" name="plural" id="plural" placeholder="Plural Form">
    </div>

    <div class="form-group">
        <label for="slug">Slug</label>
        <input type="text" class="form-control" name="slug" id="slug" placeholder="URL slug">
    </div>

    <div class="form-group">
        <label for="body">Description</label>
        <textarea class="form-control" name="description" id="description" placeholder="Description" rows="3"></textarea>
    </div>

    <input id="save" type="submit" name="save" hidden>
</form>

<div class="d-flex justify-content-end flex-wrap flex-md-nowrap align-items-center pt-2 mt-3 border-top">
    <div class="btn-toolbar mt-2 mt-md-0">
        <button class="btn btn-primary btn-sm ml-1" onclick="$('#save').click();">
            <i data-feather="save"></i>
            Save
        </button>
    </div>
</div>
@endsection