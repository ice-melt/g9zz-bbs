<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/8/23
 * Time: 下午3:04
 */

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Console\UserService;
use App\Transformers\MeTransformer;
use Illuminate\Http\Request;
use League\Fractal\Resource\Item;

class MeController extends Controller
{
    protected $userRepository;
    protected $userService;
    protected $request;

    public function __construct(UserRepositoryInterface $userRepository,
                                UserService $userService,
                                Request $request)
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
        $this->request = $request;
    }

    public function index()
    {
        $hid = $this->request->get('g9zz_user_hid');
        $resource = new Item($this->userRepository->hidFind($hid),new MeTransformer());
        $this->setData($resource);
        return $this->response();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAvatar(Request $request)
    {
        $url = $this->userService->uploadAvatar($request);
        $data = new \stdClass();
        if ($url->getUrl()) {
            $this->userService->updateAvatar($url->getUrl());
            $data->url = $url->getUrl();
            $this->setData($data);
            $this->setCode(200);
            return $this->response();
        } else {
            $this->setMessage(config('validation.user')['upload_avatar.failed']);
            return $this->response();
        }

    }

}