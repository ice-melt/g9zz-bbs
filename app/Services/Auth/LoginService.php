<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/6/20
 * Time: 下午11:39
 */

namespace App\Services\Auth;


use App\Exceptions\CodeException;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\XcxUserRepositoryInterface;
use App\Services\BaseService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class LoginService extends BaseService
{
    protected $client;
    protected $userService;
    protected $userRepository;
    protected $xcxUserRepository;

    public function __construct(Client $client,
                                UserService $userService,
                                UserRepositoryInterface $userRepository,
                                XcxUserRepositoryInterface $xcxUserRepository)
    {
        $this->client = $client;
        $this->userService = $userService;
        $this->userRepository = $userRepository;
        $this->xcxUserRepository = $xcxUserRepository;
    }

    public function getXCXUserInfo($code)
    {
        $miniProgramAppid = env('XCX_APPID');
        $miniProgramSECRET = env('XCX_SECRET');
        $url = 'https://api.weixin.qq.com/sns/jscode2session';
        $response = $this->client->request('GET',$url,['query' => ['appid' => $miniProgramAppid,
            'secret'=>$miniProgramSECRET,
            'js_code' => $code,
            'grant_type' => 'authorization_code']]);

        $body = $response->getBody();
        return (string) $body;
    }

    /**
     * @param $openid
     * @return mixed
     */
    public function getXcxByOpenId($openid)
    {
        return $this->xcxUserRepository->getXcxByOpenId($openid);
    }

    /**
     * @param $input
     * @return mixed
     */
    public function createXcx($input)
    {
        $result = $this->xcxUserRepository->create($input);
        $time = time();
        $param = [$result->id,$time,5];
        return Hashids::connection('user')->encode($param);
    }

    /**
     * @param $xcxId
     * @return mixed
     */
    public function getUserByXcxId($xcxId)
    {
        return $this->userRepository->getUserByXcxId($xcxId);
    }

    /**
     * 如果token失效了,但还在时间范围内,可以更新成一个新的
     * @param Request $request
     * @return mixed
     */
    public function updateToken($request)
    {
        $auth = $request->header('x-auth-token');
        $this->log('service.request to '.__METHOD__,['x-auth-token' => $auth]);
        if (empty($auth)) {
            throw new CodeException(config('validation.token')['token.isNull']);
        }

        $overTime = config('g9zz.token')['over_time'];
        $this->log('service.request to '.__METHOD__,['token_over_time' => $overTime]);
        $token = Hashids::connection('console_token')->decode($auth);
        $this->log('service.request to '.__METHOD__,['token' => $token]);
        if (empty($token) || !is_array($token) || count($token) < 2) {
            $code = config('validation.token')['token.invalid'];
            throw new CodeException($code);
        }

        $id = $token[0];
        $user = $this->userService->findUserByToken($id);
        if (empty($user)) {
            $code = config('validation.token')['token.invalid'];
            throw new CodeException($code);
        }

        $now = time();
        $beginTime = $token[1];
        $limitTime = config('g9zz.token.over_time');
        if ($now - $beginTime > $limitTime) {
            $code = config('validation.token')['token.invalid'];
            throw new CodeException($code);
        }

        return $user;
    }
}