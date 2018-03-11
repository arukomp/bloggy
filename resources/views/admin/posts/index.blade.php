@extends('bloggy::layouts.admin.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">
        @if (isset($trashedPostsCount))
            All {{ $postType->plural }}
        @elseif (isset($activePostsCount))
            Deleted {{ $postType->plural }}
        @endif
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-3">
            @if (isset($trashedPostsCount))
            <a class="btn btn-sm btn-outline-primary active" href="#">
                All {{ $postType->plural }} <span class="badge badge-primary">{{ $posts->count() }}</span>
            </a>
            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.postType.trashedPosts.index', $postType) }}">
                Trashed <span class="badge badge-primary">{{ $trashedPostsCount }}</span>
            </a>
            @elseif (isset($activePostsCount))
            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.postType.posts.index', $postType) }}">
                All {{ $postType->plural }} <span class="badge badge-primary">{{ $activePostsCount }}</span>
            </a>
            <a class="btn btn-sm btn-outline-primary active" href="#">
                Trashed <span class="badge badge-primary">{{ $posts->count() }}</span>
            </a>
            @endif
        </div>
        <div class="btn-group mr-2">
            <a href="{{ route('admin.postType.posts.create', $postType) }}" class="btn btn-success btn-sm">
                <i data-feather="plus"></i>
                Add New {{ $postType->name }}
            </a>
        </div>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th width="100%">Title</th>
            <th>Author</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($posts as $post)
        <tr>
            <td scope="row"><a href="{{ route('admin.posts.edit', $post) }}">{{ $post->title }}</a></td>
            <td class="text-nowrap">{{ $post->author->name }}</td>
            <td class="text-nowrap">{{ $post->created_at }}</td>
            <td class="text-nowrap">
                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-secondary">
                    <i data-feather="edit"></i>
                    Edit
                </a>
                <form class="button-form" action="{{ route('admin.posts.destroy', $post) }}" method="post">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i data-feather="trash-2"></i>     
                        Trash It
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td scope="row" colspan="4">
                <p class="lead text-center">There are no posts...</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection