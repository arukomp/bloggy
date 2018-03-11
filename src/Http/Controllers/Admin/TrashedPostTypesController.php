<?php

namespace Arukomp\Bloggy\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Arukomp\Bloggy\Http\Controllers\Controller;
use Arukomp\Bloggy\Models\PostType;

class TrashedPostTypesController extends Controller
{
    public function index()
    {
        $postTypes = PostType::onlyTrashed()->get();
        $activePostTypesCount = PostType::count();

        return view('admin.postTypes.index', compact('postTypes', 'activePostTypesCount'));
    }
}
