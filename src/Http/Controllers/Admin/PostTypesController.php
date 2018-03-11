<?php

namespace Arukomp\Bloggy\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Arukomp\Bloggy\Http\Controllers\Controller;
use Arukomp\Bloggy\Models\PostType;
use Arukomp\Bloggy\Http\Requests\PostTypeCreateRequest;
use Arukomp\Bloggy\Http\Requests\PostTypeUpdateRequest;

class PostTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $postTypes = PostType::all();

        $trashedPostTypesCount = PostType::onlyTrashed()->count();

        return view('bloggy::admin.postTypes.index', compact('postTypes', 'trashedPostTypesCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bloggy::admin.postTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostTypeCreateRequest $request)
    {
        $postType = PostType::create($request->all());

        return redirect()->route('admin.postTypes.index')
            ->with('success', 'Post Type ' . $postType->name . ' has been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $postType = PostType::withTrashed()->find($id);

        if (is_null($postType)) {
            return redirect()->route('admin.postTypes.index')
                ->with('error', 'The requested Post Type could not be found');
        }

        return view('bloggy::admin.postTypes.edit', compact('postType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostTypeUpdateRequest $request, $id)
    {
        $postType = PostType::find($id);

        if (is_null($postType)) {
            return redirect()->route('admin.postTypes.index')
                ->with('erorr', 'The requested Post Type could not be found');
        }

        $postType->update($request->all());

        return redirect()->route('admin.postTypes.edit', $postType)
            ->with('success', 'Post Type ' . $postType->name . ' has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $postType = PostType::find($id);

        if (is_null($postType)) {
            return redirect()->route('admin.postTypes.index')
                ->with('error', 'The requested Post Type could not be found');
        }

        $postType->delete();

        return redirect()->back()->with('success', 'Post Type ' . $postType->name . ' has been deleted');
    }

    /**
     * Restore a specified resource
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $postType = PostType::withTrashed()->find($id);

        if (is_null($postType)) {
            return redirect()->route('admin.trashedPostTypes.index')
                ->with('error', 'The requested Post Type count not be found');
        }

        $postType->restore();

        return redirect()->back()->with('success', 'Post Type ' . $postType->name . ' has been restored');
    }
}
