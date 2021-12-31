<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\News;
use App\Models\Topic;
use App\Models\Tag;
use App\Models\NewsTag;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $status = $request->input('status');
        $topic = $request->input('topic');
        $offset = ($page - 1) * $limit;
        $news;
        if (Redis::get('news:'.$page.$status.$limit)) {
            $news = json_decode(Redis::get('news'), true);
        } else {
            $news = News::offset($offset)
            ->limit($limit)
            ->orderByDesc("created_at")
            ->get()
            ->append(["topic_name", "tags"])
            ->filter(function ($data) use ($topic) {
                return $data->topic_name == $topic;
            });

            if ($status) {
                $news = $news->where("status", $status);
            }
            
            Redis::set('news:'.$page.$status.$limit, json_encode($news));
        }
       
        return response()->json([
            'status' => 'success',
            'data' => $news,
        ], 200);
    }

    public function show($slug)
    {
        $news;
        if (Redis::get('news:'.$slug)) {
            $news = json_decode(Redis::get('news:'.$slug), true);
        } else {
            $news = News::where('slug', $slug)->append(["topic_name", "tags"])->first();
            Redis::set('news:'.$slug, json_encode($news));
        }
        return response()->json([
            'status' => 'success',
            'data' => $news,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'status' => 'required',
            'topic_id' => 'required',
            'tags' => 'required|array',
        ]);

        if($request->hash_file('image_thumb')) {
            $thumbnail = $request->file("image_thumb");
            $thumbnail_name = time() . "_" . $thumbnail->getClientOriginalName();
            $folderUpload = "thumbnails";
            $thumbnail->move($folderUpload, $thumbnail_name);            
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Image thumbnail is required',
                'data' => null,
            ], 400);
        }

        if (count($request->tags) == 0) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Tags is required',
                'data' => null,
            ], 400);
        }

        $news = News::create([
            'title' => $request->input('title'),
            'slug' => str_slug($request->input('title')),
            'content' => $request->input('content'),
            'image_thumb' => $thumbnail_name,
            'status' => $request->input('status'),
            'topic_id' => $request->input('topic_id'),
        ]);

        $news->tags()->sync($request->tags);

        return response()->json([
            'status' => 'success',
            'data' => $news,
        ], 201);
    }

    public function update(Request $request, $slug)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'status' => 'required',
            'topic_id' => 'required',
            'tags' => 'required|array',
        ]);

        if($request->hash_file('image_thumb')) {
            $thumbnail = $request->file("image_thumb");
            $thumbnail_name = time() . "_" . $thumbnail->getClientOriginalName();
            $folderUpload = "thumbnails";
            $thumbnail->move($folderUpload, $thumbnail_name);            
        }else {
            $thumbnail_name = '';
        }

        $news = News::where('slug', $slug)->first();
        $news->title = $request->input('title');
        $news->slug = str_slug($request->input('title'));
        $news->content = $request->input('content');
        $news->image_thumb = $thumbnail_name == '' ? $news->image_thumb : $thumbnail_name;
        $news->status = $request->input('status');
        $news->topic_id = $request->input('topic_id');
        $news->save();

        $news->tags()->sync($request->tags);

        return response()->json([
            'status' => 'success',
            'data' => $news,
        ], 200);
    }

    public function destroy($slug)
    {
        $news = News::where('slug', $slug)->first();
        NewsTag::where('news_id', $news->id)->delete();
        $news->delete();

        return response()->json([
            'status' => 'success',
            'data' => $news,
        ], 200);
    }
}
