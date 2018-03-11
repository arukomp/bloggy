@extends('bloggy::layouts.admin.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Post Types</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-3">
            @if (isset($trashedPostTypesCount))
            <a class="btn btn-sm btn-outline-primary active" href="#">
                All Post Types <span class="badge badge-primary">{{ $postTypes->count() }}</span>
            </a>
            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.trashedPostTypes.index') }}">
                Trashed <span class="badge badge-primary">{{ $trashedPostTypesCount }}</span>
            </a>
            @elseif (isset($activePostTypesCount))
            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.postTypes.index') }}">
                All Post Types <span class="badge badge-primary">{{ $activePostTypesCount }}</span>
            </a>
            <a class="btn btn-sm btn-outline-primary active" href="#">
                Trashed <span class="badge badge-primary">{{ $postTypes->count() }}</span>
            </a>
            @endif
        </div>
        <div class="btn-group mr-2">
            <a href="{{ route('admin.postTypes.create') }}" class="btn btn-success btn-sm">
                <i data-feather="plus"></i>
                Add new Post Type
            </a>
        </div>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th width="100%">Description</th>
            <th>Date</th>
            <th class="text-right">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($postTypes as $postType)
        <tr>
            <td scope="row"><a href="{{ route('admin.postTypes.edit', $postType) }}">{{ $postType->name }}</a></td>
            <td>{{ $postType->description }}</td>
            <td class="text-nowrap">{{ $postType->created_at }}</td>
            <td class="text-nowrap text-right">
                <a href="{{ route('admin.postTypes.edit', $postType) }}" class="btn btn-sm btn-secondary">
                    <i data-feather="edit"></i>
                    Edit
                </a>
                @if (isset($trashedPostTypesCount))
                <form class="button-form" action="{{ route('admin.postTypes.destroy', $postType) }}" method="post">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i data-feather="trash-2"></i>     
                        Trash It
                    </button>
                </form>
                @elseif (isset($activePostTypesCount))
                <a href="{{ route('admin.postTypes.restore', $postType) }}" class="btn btn-sm btn-danger">
                    <i data-feather="chevrons-left"></i>     
                    Restore
                </a>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td scope="row" colspan="4">
                <p class="lead text-center">There are no post types</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection