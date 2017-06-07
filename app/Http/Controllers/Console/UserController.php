<?php

namespace App\Http\Controllers\Console;

use App\Services\Console\UserService;
use App\Transformers\PostListTransformer;
use App\Transformers\ReplyTransformer;
use App\Transformers\UserTransformer;
use App\Http\Controllers\Controller;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $page = $this->userService->paginate();
        $resource = new Collection($page,new UserTransformer());
        $resource->setPaginator(new IlluminatePaginatorAdapter($page));
        $this->setData($resource);
        return $this->response();
    }

    /**
     * @param $userId
     * @param $roleId
     * @return \Illuminate\Http\JsonResponse
     */
    public function attachRole($userId,$roleId)
    {
        $this->userService->attachRole($userId,$roleId);
        $resource = new Item($this->userService->find($userId),new UserTransformer());
        $this->setData($resource);
        return $this->response();
    }


    /**
     * @param $userHid
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostByUser($userHid)
    {
        $result = $this->userService->getPostByUser($userHid);
        $resource = new Collection($result,new PostListTransformer());
        $resource->setPaginator(new IlluminatePaginatorAdapter($result));
        $this->setData($resource);
        return $this->response();
    }

    /**
     * @param $userHid
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReplyByUser($userHid)
    {
        $result = $this->userService->getReplyByUser($userHid);
        $resource = new Collection($result,new ReplyTransformer());
        $resource->setPaginator(new IlluminatePaginatorAdapter($result));
        $this->setData($resource);
        return $this->response();
    }
}
