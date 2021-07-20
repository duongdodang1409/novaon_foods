<?php
/**
 * Project: Caller_Core
 * Package: Novaon\User
 * Author: Nhi
 * Create time: 11:15 5/27/21
 * Copyright (c) 2021 NOVAON.
 **/


namespace Novaon\User\Controllers\Api;

use App\ApiCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Novaon\User\Models\Post;
use Novaon\User\Request\Tenant\Post\ViewPostRequest;
use Novaon\User\Request\Tenant\Post\StorePostRequest;
use Novaon\User\Request\Tenant\Post\UpdatePostRequest;
use Novaon\User\Request\Tenant\Post\DeletePostRequest;

/**
 * @group Post Management
 *
 * APIs for managing Post
 */
class PostController extends Controller
{
    /**
     * List Post
     * @queryParam limit int Limit Posts of page. Ex: 20
     * @queryParam page int Current Page
     * @response{
     *  "success": true,
     *  "code": 200,
     *  "locale": "en",
     *  "message": "OK",
     *  "data": {
     *  "current_page": 1,
     *  "data": [
     *      {
     *      "id": 8,
     *      "title": "Trương Gia Toàn",
     *      "content": "<p>&ldquo;OnSales CRM ch&uacute;ng t&ocirc;i c&oacute; thể nhận biết được kh&aacute;chh&agrave;ng của m&igrave;nh qua mỗi cuộc gọi tới để ch&agrave;o hỏi 1 c&aacute;ch th&acirc;n thiện nhất.&rdquo;<\/p>",
     *      "slug": "04-dieu-can-tranh-neu-doanh-nghiep-muon-tang-truong-doanh-thu",
     *      "tags": null,
     *      "image": "/storage/files/customer-feedback-1.png",
     *      "status": 1,
     *      "created_at": "2021-06-07T08:28:56.000000Z",
     *      "updated_at": "2021-06-08T03:04:03.000000Z"
     *      }
     *  ],
     *  "first_page_url": "http://localhost:8000/api/v1/posts?page=1",
     *  "from": 1,
     *  "last_page": 1,
     *  "last_page_url": "http://localhost:8000/api/v1/posts?page=1",
     *  "next_page_url": null,
     *  "path": "http://localhost:8000/api/v1/posts",
     *  "per_page": 15,
     *  "prev_page_url": null,
     *  "to": 1,
     *  "total": 1
     *  }
     * }
     * @return \Symfony\Component\HttpFoundation\Response
     */



    public function index(ViewPostRequest $request)
    {
        $keyWord = $request->keySearch;
        $posts = Post::query();
        if($keyWord){
            $posts = $posts->where('title', 'like', '%' . $keyWord . '%');
        }

        $apiLimit = config('common.api_limit');
        if ($request->limit) {
            $apiLimit = $request->limit >= config('common.api_limit_max') ? config('common.api_limit_max') : $request->limit;
        }

        $posts = $posts->paginate($apiLimit);

        return ResponseBuilder::success($posts, ApiCode::SUCCESS);
    }

    /**
     * Get Post info
     * @urlParam id int required Post ID. Ex: 1
     * @response {
     *  "success": true,
     *  "code": 200,
     *  "locale": "en",
     *  "message": "OK",
     *  "data": {
     *      "item": {
     *          "id": 8,
     *          "title": "Trương Gia Toàn",
     *          "content": "<p>&ldquo;OnSales CRM ch&uacute;ng t&ocirc;i c&oacute; thể nhận biết được kh&aacute;chh&agrave;ng của m&igrave;nh qua mỗi cuộc gọi tới để ch&agrave;o hỏi 1 c&aacute;ch th&acirc;n thiện nhất.&rdquo;<\/p>",
     *          "slug": "04-dieu-can-tranh-neu-doanh-nghiep-muon-tang-truong-doanh-thu",
     *          "brief": "Những điều cần tránh nếu muốn tăng trưởng doanh thu của doanh nghiệp là....",
     *          "tags": null,
     *          "image": "/storage/files/customer-feedback-1.png",
     *          "status": 1,
     *          "created_at": "2021-06-07T08:28:56.000000Z",
     *          "updated_at": "2021-06-08T03:04:03.000000Z"
     *      }
     *  }
     * }
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\Response
     */


    public function show(Post $post, ViewPostRequest $request)
    {
        return ResponseBuilder::success($post, ApiCode::SUCCESS);
    }

    /**
     * Create new Post
     * @bodyParam title string required Title of Post
     * @bodyParam content string required Content of Post
     * @bodyParam slug string required Slug of Post
     * @bodyParam tags string Tags of Post
     * @bodyParam image string Image avatar of Post
     * @response {
        "success": true,
        "code": 200,
        "locale": "en",
        "message": "OK",
        "data": {
            "item": {
                "id": 8,
     *          "title": "Trương Gia Toàn",
     *          "content": "<p>&ldquo;OnSales CRM ch&uacute;ng t&ocirc;i c&oacute; thể nhận biết được kh&aacute;chh&agrave;ng của m&igrave;nh qua mỗi cuộc gọi tới để ch&agrave;o hỏi 1 c&aacute;ch th&acirc;n thiện nhất.&rdquo;<\/p>",
     *          "slug": "04-dieu-can-tranh-neu-doanh-nghiep-muon-tang-truong-doanh-thu",
     *          "brief": "Những điều cần tránh nếu muốn tăng trưởng doanh thu của doanh nghiệp là....",
     *          "tags": null,
     *          "image": "/storage/files/customer-feedback-1.png",
     *          "status": 1,
     *          "created_at": "2021-06-07T08:28:56.000000Z",
     *          "updated_at": "2021-06-08T03:04:03.000000Z"
            }
        }
    }
     * @param StorePostRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function store(StorePostRequest $request)
    {
        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'slug' => $request->slug,
            'image' => $request->image,
        ];
        $post = Post::create($data);
        return ResponseBuilder::success($post, ApiCode::SUCCESS);
    }

    /**
     * Update Post
     * @urlParam id int required Post ID. Ex: 1
     * @bodyParam title string required Title of Post
     * @bodyParam content string required Content of Post
     * @bodyParam slug string required Slug of Post
     * @bodyParam tags string Tags of Post
     * @bodyParam image string Image avatar of Post
     * @response {
        "success": true,
        "code": 200,
        "locale": "en",
        "message": "OK",
        "data": {
            "item": {
                "id": 8,
     *          "title": "Trương Gia Toàn",
     *          "content": "<p>&ldquo;OnSales CRM ch&uacute;ng t&ocirc;i c&oacute; thể nhận biết được kh&aacute;chh&agrave;ng của m&igrave;nh qua mỗi cuộc gọi tới để ch&agrave;o hỏi 1 c&aacute;ch th&acirc;n thiện nhất.&rdquo;<\/p>",
     *          "slug": "04-dieu-can-tranh-neu-doanh-nghiep-muon-tang-truong-doanh-thu",
     *          "brief": "Những điều cần tránh nếu muốn tăng trưởng doanh thu của doanh nghiệp là....",
     *          "tags": null,
     *          "image": "/storage/files/customer-feedback-1.png",
     *          "status": 1,
     *          "created_at": "2021-06-07T08:28:56.000000Z",
     *          "updated_at": "2021-06-08T03:04:03.000000Z"
            }
        }
    }
     * @param UpdatePostRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->title = $request->title ? $request->title : $post->title;
        $post->content = $request->content ? $request->content : $post->content;
        $post->slug = $request->slug ? $request->slug : $post->slug;
        $post->brief = $request->brief ? $request->brief : $post->brief;
        $post->image = $request->image ? $request->image : $post->image;
        $post->status = $request->status;
        $post->update();
        return ResponseBuilder::success($post, ApiCode::SUCCESS);
    }

    /**
     * Delete Post
     * @urlParam id int required Post ID. Ex: 1
     * @response {
        "success": true,
        "code": 200,
        "locale": "en",
        "message": "OK",
        "data": { }
    }
     * @return \Symfony\Component\HttpFoundation\Response
     */


    public function destroy(Post $post,DeletePostRequest $request)
    {

        $post->delete();
//        return ResponseBuilder::success();
    }
}
