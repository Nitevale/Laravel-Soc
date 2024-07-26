<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    //
    public function showCreateForm() {
        return view('create-post');
    }

    public function storeData(Request $request) {
        $incomingfields = $request->validate([
            'title'=>'required',
            'body'=>'required'
        ]);

        $incomingfields['title'] = strip_tags($incomingfields['title']);
        $incomingfields['body'] = strip_tags($incomingfields['body']);
        $incomingfields['user_id'] = auth()->id();

        $newPost = Post::create($incomingfields);

        return redirect("/post/{$newPost->id}")->with('success', 'Posted!');
    }

    public function viewSinglePost(Post $post) {
        $post['body'] = strip_tags(Str::markdown($post->body), '<p><ul><li><br><h3><em><strong><hr>');
        return view('single-post', ['post' => $post]);
    }

    public function delete(Post $post) {
        $post->delete();

        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post deleted');
    }

    public function showEditForm(Post $post) {
        return view('edit-form', ['post'=>$post]);
    }

    public function actuallyUpdate(Post $post, Request $request) {
        $incomingfields = $request->validate([
            'title'=>'required',
            'body'=>'required'
        ]);

        $incomingfields['title'] = strip_tags($incomingfields['title']);
        $incomingfields['body'] = strip_tags($incomingfields['body']);

        $post->update($incomingfields);

        return back()->with('success', 'Post Updated!');
    }
}
