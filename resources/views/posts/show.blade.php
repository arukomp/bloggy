@extends('bloggy::layouts.app')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $post->title }}
                @auth
                    <div class="float-right">
                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary btn-sm">Edit Post</a>
                    </div>
                @endauth
            </h4>
            <p>
                <small>by {{ $post->author->name }}</small>
                @auth
                    <span class="float-right">
                        Status:
                        <strong>
                            @if ($post->trashed())
                                <span class="text-danger">Trashed</span>
                            @elseif ($post->active)
                                <span class="text-success">Published</span>
                            @else
                                <span class="text-warning">Draft (Not Published)</span>
                            @endif
                        </strong>
                    </span>
                @endauth
            </p>
            <p class="card-text">{!! $post->body !!}</p>
        </div>
    </div>
</div>

@endsection