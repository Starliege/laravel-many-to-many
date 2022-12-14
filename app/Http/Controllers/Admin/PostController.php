<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Post;
use App\Category;
use App\Mail\SendPostCreatedMail;
use App\Tag;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        $tags = Tag::all();
        return view('admin.posts.index', compact('posts', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|image'
        ]);

        $form_data = $request->all();

        $img_path =  Storage::disk('public')->put('cover', $form_data['image']);
        $form_data['cover'] = $img_path;

        $slugTmp = Str::slug($form_data['title']);

        $count = 1;
        while (Post::where('slug', $slugTmp)->first()) {
            $slugTmp = Str::slug($form_data['title']) . "-" . $count;
            $count++;
        }

        $form_data['slug'] = $slugTmp;

        $post = new Post();
        $post->fill($form_data);
        $post->save();
        $post->tags()->sync($form_data['tags']);

        Mail::to($request->user())->send(new SendPostCreatedMail($post));

        return redirect()->route('admin.posts.index', $post);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|mimes:jpeg,bmp,png,git,svg'
        ]);

        $form_data = $request->all();

        if (array_key_exists('image', $form_data)) {
            if ($post->cover) {
                Storage::delete($post->cover);
            }
            $img_path = Storage::put('cover', $form_data['image']);
            $form_data['cover'] = $img_path;
        }

        if ($post->title == $form_data['title']) {
            $slug = $post->slug;
        } else {
            $slug = Str::slug($form_data['title']);
            $count = 1;
            while (Post::where('slug', $slug)
                ->where('slug', "!=", $post->id)
                ->first()
            ) {
                $slug = Str::slug($form_data['title']) . "-" . $count;
                $count++;
            }
        }

        $form_data['slug'] = $slug;

        $post->update($form_data);
        $post->tags()->sync($form_data['tags']);

        return redirect()->route('admin.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index');

    }

    // Cancella immagine copertina
    public function deleteCover(Post $post) {

        if ($post->cover) {
            Storage::delete($post->cover);
        }

        $post->cover = null;
        $post->save();

        return redirect()->route('admin.posts.edit', [ 'post' => $post->id])->with('status', 'Copertina cancellata');

    }
}
