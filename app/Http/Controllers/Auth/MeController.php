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
use App\Transformers\MeTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Fractal\Resource\Item;

class MeController extends Controller
{
    protected $userRepository;
    protected $request;

    public function __construct(UserRepositoryInterface $userRepository,Request $request)
    {
        $this->userRepository = $userRepository;
        $this->request = $request;
    }

    public function index()
    {
        $hid = $this->request->get('g9zz_user_hid');
        $resource = new Item($this->userRepository->hidFind($hid),new MeTransformer());
        $this->setData($resource);
        return $this->response();
    }

    public function uploadAvatar(Request $request)
    {
        if ($request->hasFile('file') && $request->file('file')->isValid()) {

            $userHid = $request->get('g9zz_user_hid');

            $newFileName =  time().$userHid.md5($request->file('file')->getClientOriginalName() . time())
                . '.'
                . $request->file('file')->getClientOriginalExtension();
            $put = Storage::disk('qiniu')->put($newFileName, \File::get($request->file('file')->path()));
            $url = Storage::disk('qiniu')->getDriver()->downloadUrl($newFileName);
            $data = new \stdClass();
            if ($put && $url->getUrl()) {
                $data->url = $url->getUrl();
                $this->setData($data);
                return $this->response();
            } else {
                $this->setMessage(config(''));
            }
        }

    }

}