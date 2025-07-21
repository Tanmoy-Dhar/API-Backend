<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $posts = Post::latest()->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Posts retrieved successfully',
                'data' => $posts
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $postData = [
                'title' => $request->title,
                'description' => $request->description,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $timestamp = now()->format('YmdHis');
                $extension = $image->getClientOriginalExtension();
                $filename = $timestamp . '.' . $extension;

                // Move file to public/upload directory
                $image->move(public_path('upload'), $filename);
                $postData['image'] = $filename;
            }

            $post = Post::create($postData);

            return response()->json([
                'status' => 'success',
                'message' => 'Post created successfully',
                'data' => $post
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $post = Post::find($id);

            if (!$post) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Post not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Post retrieved successfully',
                'data' => $post
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Debug logging
            Log::info('Update request received', [
                'id' => $id,
                'all_data' => $request->all(),
                'files' => $request->allFiles(),
                'method' => $request->method(),
                'headers' => $request->headers->all()
            ]);
            
            $post = Post::find($id);

            if (!$post) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Post not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed', ['errors' => $validator->errors()->toArray()]);
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = [];

            if ($request->has('title')) {
                $updateData['title'] = $request->title;
            }

            if ($request->has('description')) {
                $updateData['description'] = $request->description;
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($post->image && file_exists(public_path($post->image))) {
                    unlink(public_path($post->image));
                }

                $image = $request->file('image');
                $timestamp = now()->format('YmdHis');
                $extension = $image->getClientOriginalExtension();
                $filename = $timestamp . '.' . $extension;

                // Move file to public/upload directory
                $image->move(public_path('upload'), $filename);
                $updateData['image'] =  $filename;
            }

            $post->update($updateData);

            return response()->json([
                'status' => 'success',
                'message' => 'Post updated successfully',
                'data' => $post->fresh()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $post = Post::find($id);

            if (!$post) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Post not found'
                ], 404);
            }

            // Delete associated image if exists
            if ($post->image && file_exists(public_path('upload/' . $post->image))) {
                unlink(public_path('upload/' . $post->image));
            }

            $post->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Post deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete post',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
