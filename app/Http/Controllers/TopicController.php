<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use Illuminate\Support\Facades\Redis;

class TopicController extends Controller
{
    public function index()
    {
        $topics;
        if (Redis::get('topics')) {
            $topics = json_decode(Redis::get('topics'), true);
        } else {
            $topics = Topic::all();
            Redis::set('topics', json_encode($topics));
        }
        return response()->json([
            'status' => 'success',
            'data' => $topics,
        ], 200);
    }

    public function show($id)
    {
        $topic = Topic::find($id);
        return response()->json([
            'status' => 'success',
            'data' => $topic,
        ], 200);
    }

    public function articles($id)
    {
        if (Redis::get('topic:'.$id)) {
            $articles = json_decode(Redis::get('topic:'.$id), true);
        } else {
            $articles = Topic::find($id)->articles;
            Redis::set('topic:'.$id, json_encode($topic));
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $articles,
        ], 200);
    }

    public function store(Request $request)
    {
        $topic = Topic::create($request->all());
        return response()->json([
            'status' => 'success',
            'data' => $topic,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $topic = Topic::find($id);
        $topic->update($request->all());
        return response()->json([
            'status' => 'success',
            'data' => $topic,
        ], 200);
    }

    public function delete($id)
    {
        $topic = Topic::find($id);
        $topic->delete();
        return response()->json([
            'status' => 'success',
            'data' => $topic,
        ], 200);
    }


}
