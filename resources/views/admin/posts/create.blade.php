@extends('bloggy::layouts.admin.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Add a new {{ $postType->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button class="btn btn-secondary btn-sm ml-1" onclick="$('#saveAsDraft').click();">Save as Draft</button>
        <button class="btn btn-success btn-sm ml-1" onclick="$('#publish').click();">Publish</button>
    </div>
</div>

<form action="{{ route('admin.postType.posts.store', $postType) }}" method="post">
    @csrf
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" name="title" id="title" placeholder="Title">
    </div>

    <div class="form-group">
        <label for="body">Content</label>
        <textarea class="form-control" name="body" id="body" placeholder="Post Content" rows="10"></textarea>
    </div>

    <div class="form-group">
        <label for="allow_comments">
            <input type="checkbox" name="allow_comments" id="allow_comments"> Allow Comments
        </label>
    </div>

    <input id="saveAsDraft" type="submit" name="saveAsDraft" hidden>
    <input id="publish" type="submit" name="publish" hidden>
</form>

<div class="d-flex justify-content-end flex-wrap flex-md-nowrap align-items-center pt-2 mt-3 border-top">
    <div class="btn-toolbar mt-2 mt-md-0">
        <button class="btn btn-secondary btn-sm ml-1" onclick="$('#saveAsDraft').click();">Save as Draft</button>
        <button class="btn btn-success btn-sm ml-1" onclick="$('#publish').click();">Publish</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#body').summernote();
});
</script>
@endsection