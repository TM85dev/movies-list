<?php

namespace App\Http\Controllers;

use App\Models\UserPost;
use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = '';
        if(auth()->check()) {
            $user = auth()->user();
            if(UserPost::where('user_id', $user->id)->where('post_id', $id)->get()->first()) {
                $user_post = UserPost::where('user_id', $user->id)->where('post_id', $id)->get()->first();
                $user['post'] = $user_post;
            }
        }
        return view('post', ['id' => $id, 'user' => $user]);
    }

    public function addToUser($id) {
        if(auth()->check()) {
            if(!UserPost::where('user_id', auth()->user()->id)->where('post_id', $id)->get()->first()) {
                $user_post = UserPost::create([
                    'user_id' => auth()->user()->id,
                    'post_id' => $id
                ]);
                $user = auth()->user();
                $user['post'] = $user_post;
                return response()->json($user);
            }
            return response()->json(['message' => 'already exist']);
        }
        return response()->json(['message' => 'you are not log in']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if($post) {
            if($request->visits) {
                $post->update([
                    'visits' => $request->visits
                ]);
            } else if($request->like) {
                $user_post = UserPost::where('user_id', auth()->user()->id)->where('post_id', $post->id)->get()->first();
                $post->update([
                    'like' => $request->like
                ]);
                $user_post->update([
                    'liked' => true
                ]);
            }
            return response()->json($post);
        }
        return response()->json(['message' => 'do not exist']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
