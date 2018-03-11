@extends('bloggy::layouts.admin.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
    <h1 class="h2">Edit {{ $postType->name }}: <span class="text-muted font-weight-light">{{ str_limit($post->title, 45) }}</span></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ $post->url }}" class="btn btn-primary btn-sm mr-3">
            <i data-feather="eye"></i>
            Preview
        </a>
        @if ($post->trashed())
            <form class="button-form" action="{{ route('admin.posts.restore', $post) }}" method="post">
                @csrf @method('put')
                <button type="submit" class="btn btn-sm btn-success mr-3">
                    <i data-feather="chevrons-left"></i>
                    Restore
                </button>
            </form>
        @else
            <form class="button-form" action="{{ route('admin.posts.destroy', $post) }}" method="post">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger mr-3" type="submit">
                    <i data-feather="trash-2"></i>
                    Trash It
                </button>
            </form>
        @endif

        @if ($post->active)
            <button class="btn btn-sm btn-success ml-1" onclick="$('#publish').click();">
                <i data-feather="save"></i>
                Save
            </button>
        @else
            <button class="btn btn-sm btn-secondary ml-1" onclick="$('#saveAsDraft').click();">Save as Draft</button>
            @if (!$post->trashed())
                <button class="btn btn-sm btn-success ml-1" onclick="$('#publish').click();">Publish</button>
            @endif
        @endif
    </div>
</div>

<div class="d-flex justify-content-start flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <span class="mr-3">Last Updated: <strong>{{ $post->updated_at->diffForHumans() }}</strong></span>
    <span class="mr-3">Revision: <strong>{{ $post->revision }}</strong></span>
    <span class="mr-3">
        Status:
        @if ($post->trashed())
            <strong>Trashed</strong>
        @elseif ($post->active)
            <strong>Published</strong>
            <a href="{{ route('admin.posts.unpublish', $post) }}" class="btn btn-sm btn-outline-danger pt-0 pb-0">Unpublish</a>
        @else
            <strong>Draft</strong>
            <a href="{{ route('admin.posts.publish', $post) }}" class="btn btn-sm btn-outline-success pt-0 pb-0">Publish</a>
        @endif
    </span>
    
</div>

<form action="{{ route('admin.posts.update', $post) }}" method="post">
    <input type="hidden" name="_method" value="put"> @csrf
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="{{ $post->title }}">
    </div>

    <div class="form-group">
        <p>URL slug: <strong>{{ $post->slug }}</strong></p>
        @if ($postsSharingSlug->isNotEmpty())
        <p class="text-danger">This slug is shared with these active {{ strtolower($postType->plural) }}:</p>
        <ul class="list-unstyled ml-3 mb-1">
            @foreach ($postsSharingSlug as $sharedPost)
            <li><a href="{{ route('admin.posts.edit', $sharedPost) }}" class="btn btn-sm btn-outline-danger pt-0 pb-0">{{ $sharedPost->title }}</a></li>
            @endforeach
        </ul>
        <p class="text-danger">
            Some or all of these {{ strtolower($postType->plural) }} above might not be accessible by the public. <br />
            Please change the URL slug:
        </p>
        @endif
        <input type="text" name="slug" class="form-control" value="{{ $post->slug }}">
    </div>

    <div class="form-group">
        <label for="body">Content</label>
        <textarea class="form-control" name="body" id="body" placeholder="{{ $postType->name }} Content" rows="10">{{ $post->body }}</textarea>
    </div>

    <div class="form-group">
        <label for="allow_comments">
            <input type="checkbox" name="allow_comments" id="allow_comments" @if($post->allow_comments) checked @endif> Allow Comments
        </label>
    </div>

    <input id="saveAsDraft" type="submit" name="saveAsDraft" hidden>
    <input id="publish" type="submit" name="publish" hidden>
</form>

<div class="d-flex justify-content-end flex-wrap flex-md-nowrap align-items-center pt-2 mt-3 border-top">
    <div class="btn-toolbar mt-2 mt-md-0">
        <a href="{{ $post->url }}" class="btn btn-primary btn-sm mr-3">
            <i data-feather="eye"></i>
            Preview
        </a>
        @if ($post->trashed())
        <form class="button-form" action="{{ route('admin.posts.restore', $post) }}" method="post">
            @csrf @method('put')
            <button type="submit" class="btn btn-sm btn-success mr-3">
                <i data-feather="chevrons-left"></i>
                Restore
            </button>
        </form>
        @else
        <form class="button-form" action="{{ route('admin.posts.destroy', $post) }}" method="post">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger mr-3" type="submit">
                <i data-feather="trash-2"></i>
                Trash It
            </button>
        </form>
        @endif 
        
        @if ($post->active)
        <button class="btn btn-sm btn-success ml-1" onclick="$('#publish').click();">
            <i data-feather="save"></i>
            Save
        </button>
        @else
        <button class="btn btn-sm btn-secondary ml-1" onclick="$('#saveAsDraft').click();">Save as Draft</button>
        @if (!$post->trashed())
        <button class="btn btn-sm btn-success ml-1" onclick="$('#publish').click();">Publish</button>
        @endif @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function(){
    $('#body').summernote();
});
</script>
@endsection