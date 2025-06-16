<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['category', 'ward.district.province'])->latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.posts.create', [
            'categories' => Category::all(),
            'features' => Feature::all(),
            'wards' => Ward::all(),
            'provinces' => Province::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|unique:posts',
            'description' => 'required',
            'price' => 'required|integer',
            'area' => 'required|numeric',
            'address' => 'required|string',
            'ward_id' => 'required|exists:wards,id',
            'category_id' => 'nullable|exists:categories,id',
            'features' => 'nullable|array',
            'status' => 'nullable'
        ]);

        $post = Post::create($validated + [
            'user_id' => auth()->id,
        ]);

        $post->features()->sync($request->features ?? []);

        return redirect()->route('admin.posts.index')->with('success', 'Tạo bài đăng thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'category', 'ward.district.province', 'features']);
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('admin.posts.edit', [
            'post' => $post->load('features'),
            'categories' => Category::all(),
            'features' => Feature::all(),
            'wards' => Ward::all(),
            'provinces' => Province::all(),
            'selectedProvince' => optional($post->ward->district->province)->id,
            'selectedDistrict' => optional($post->ward->district)->id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|unique:posts,code,' . $post->id,
            'description' => 'required',
            'price' => 'required|integer',
            'area' => 'required|numeric',
            'address' => 'required|string',
            'ward_id' => 'required|exists:wards,id',
            'category_id' => 'nullable|exists:categories,id',
            'features' => 'nullable|array',
            'status' => 'nullable'
        ]);

        $post->update($validated);

        $post->features()->sync($request->features ?? []);

        return redirect()->route('admin.posts.index')->with('success', 'Cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Đã xoá bài đăng');
    }
}
