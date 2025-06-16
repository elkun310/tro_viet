<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            'status' => 'nullable',
            'images' => 'array',
            'features' => 'array',
            'features.*' => 'exists:features,id',
        ]);

        DB::beginTransaction();

        try {

            $post = Post::create($validated + [
                'user_id' => auth()->id(),
            ]);

            if ($request->has('features')) {
                $post->features()->sync($request->features);
            } else {
                $post->features()->sync([]);
            }

            if ($request->filled('images')) {
                foreach ($request->images as $imagePath) {
                    $post->images()->create(['url' => $imagePath]);
                }
            }

            DB::commit();

            return redirect()->route('admin.posts.index')->with('success', 'Tạo bài đăng thành công');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Lỗi: '.$e->getMessage()])->withInput();
        }
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
            'post' => $post->load(['features', 'images']),
            'categories' => Category::all(),
            'features' => Feature::all(),
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
            'code' => 'required|unique:posts,code,'.$post->id,
            'description' => 'required',
            'price' => 'required|integer',
            'area' => 'required|numeric',
            'address' => 'required|string',
            'ward_id' => 'required|exists:wards,id',
            'category_id' => 'nullable|exists:categories,id',
            'features' => 'array',
            'features.*' => 'exists:features,id',
        ]);

        DB::beginTransaction();

        try {
            $post->update($validated);
            if ($request->has('features')) {
                $post->features()->sync($request->features);
            } else {
                $post->features()->sync([]);
            }

            if ($request->filled('new_images')) {
                foreach ($request->new_images as $path) {
                    $post->images()->create(['url' => $path]);
                }
            }

            DB::commit();

            return redirect()->route('admin.posts.index')->with('success', 'Cập nhật bài đăng thành công');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e->getMessage());

            return back()->withErrors(['error' => 'Lỗi: '.$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Đã xoá bài đăng');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $file = $request->file('file');
        $path = $file->store('posts', 'public');

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => asset('storage/'.$path), // Đường dẫn public để frontend preview luôn
        ]);
    }

    public function deleteImage(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:post_images,id',
        ]);

        $image = PostImage::findOrFail($request->id);

        // Optionally: xóa file khỏi storage
        Storage::disk('public')->delete($image->url);

        $image->forceDelete();

        return response()->json(['success' => true]);
    }
}
