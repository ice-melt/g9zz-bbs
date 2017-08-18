<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/6/6
 * Time: 下午5:47
 */

namespace App\Services\Console;


use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Http\Request;

class UserService extends BaseService
{

    protected $userRepository;
    protected $roleRepository;
    protected $request;
    public function __construct(UserRepositoryInterface $userRepository,
                                RoleRepositoryInterface $roleRepository,
                                Request $request)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function paginate()
    {
        return $this->userRepository->paginate(per_page());
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * 给用户分配权限
     * @param $userId
     * @param $roleId
     * @return mixed
     */
    public function attachRole($userId,$roleId)
    {
        $this->roleRepository->find($roleId);
        $this->userRepository->find($userId);
        return $this->userRepository->syncRelationship($roleId,$userId);
    }

    /**
     * @param $userHid
     */
    public function getPostByUser($userHid)
    {
        return $this->userRepository->getPostByUser($userHid)->paginate(per_page());
    }

    /**
     * @param $userHid
     * @return mixed
     */
    public function getReplyByUser($userHid)
    {
        return $this->userRepository->getReplyByUser($userHid)->paginate(per_page());
    }

    /**
     * @param $hid
     * @return mixed
     */
    public function hidFind($hid)
    {
        return $this->userRepository->hidFind($hid);
    }

    /**
     * @param $hid
     * @return mixed
     */
    public function deleteHid($hid)
    {
        return $this->userRepository->hidDelete($hid);
    }

    /**
     * @param $hid
     * @param $status
     * @return mixed
     */
    public function closureHid($hid,$status)
    {
        $status = $status == 'closure' ? 'closure' : 'activated';
        $this->log('service.request to '.__METHOD__,['request' => $status]);

        //当前登录用户HID
        $authId = $this->request->get('g9zz_user_id');
        $this->log('service.request to '.__METHOD__,['auth_id' => $authId]);
        $levels = $this->userRepository->getRoleLevelsByUserId($authId);
        if (empty($levels)) {
            $this->setCode(config('validation.default')['some.error']);
            return $this->response();
        }
        //登录用户的最大权限(值最小)
        $userMaxLevel = $levels[array_search(min($levels),$levels)];
        $this->log('service.request to '.__METHOD__,['user_max_level' => $userMaxLevel]);

        //被处理用户
        $closureId = $this->userRepository->hidFind($hid)->id;
        $closureLevels = $this->userRepository->getRoleLevelsByUserId($closureId);
        $this->log('service.request to '.__METHOD__,['closure_user_levels' => $closureLevels]);

        if (!empty($closureLevels)) {
            //被处理用户最大权限(最小值)
            $closureMaxLevel = $closureLevels[array_search(min($closureLevels),$closureLevels)];
            $this->log('service.request to '.__METHOD__,['closure_user_max_level' => $closureMaxLevel]);

            // 权限里level越小 权限越大
            if ($userMaxLevel >= $closureMaxLevel) {
                $this->setCode(config('validation.permission')['permission.forbidden']);
                return $this->response();
            }
        }

        $result = $this->userRepository->hidFind($hid);
        $result->status = $status;
        $result->save();
        return $result;
    }

    /**
     * 手动修改验证状态
     * @param $hid
     * @return mixed
     */
    public function doVerify($hid)
    {
        $result = $this->userRepository->hidFind($hid);
        $result->verified = true;
        $result->save();
        return $result;
    }

}