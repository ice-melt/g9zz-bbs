<?php

namespace App\Http\Controllers\Console;

use App\Services\Console\NotifyService;
use App\Services\Console\UserService;
use App\Transformers\PostListTransformer;
use App\Transformers\ReplyTransformer;
use App\Transformers\UserTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class UserController extends Controller
{
    protected $userService;
    protected $notifyService;
    public function __construct(UserService $userService,
                                NotifyService $notifyService)
    {
        $this->userService = $userService;
        $this->notifyService = $notifyService;
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

    /**
     * @param $hid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($hid)
    {
        $result = $this->userService->hidFind($hid);
        $resource = new Item($result,new UserTransformer());
        $this->setData($resource);
        return $this->response();
    }

    /**
     * @param $hid
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($hid)
    {
        $result = $this->userService->deleteHid($hid);
        if ($result) return $this->response();
    }

    /**
     * 封禁;  注意:此处是可以封禁自己的哦....
     * @param $hid
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function closure($hid,Request $request)
    {
        $status = $request->only('status');
        $this->log('controller.request to '.__METHOD__,['request' => $status]);
        $result = $this->userService->closureHid($hid,$status['status']);
        $resource = new Item($result,new UserTransformer());
        $this->setData($resource);
        return $this->response();
    }

    /**
     * @param $hid
     * @return \Illuminate\Http\JsonResponse
     */
    public function doVerify($hid)
    {
        $result = $this->userService->doVerify($hid);
        $resource = new Item($result,new UserTransformer());
        $this->setData($resource);
        return $this->response();
    }
}
