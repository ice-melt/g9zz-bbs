<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/6/6
 * Time: 下午5:13
 */

namespace App\Services\Console;


use App\Repositories\Contracts\InviteCodeRepositoryInterface;
use App\Services\BaseService;
use Vinkla\Hashids\Facades\Hashids;

class InviteCodeService extends BaseService
{
    protected $inviteCodeRepository;
    public function __construct(InviteCodeRepositoryInterface $inviteCodeRepository)
    {
        $this->inviteCodeRepository = $inviteCodeRepository;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function paginate($request)
    {
        $status = $request->get('status');
        $query = $this->inviteCodeRepository->models();
        if (!empty($status)) {
            $query = $query->where('status',$status);
        }

        $orderId = $request->get('orderId');
        $orderId = $orderId == 'desc' || $orderId == 'asc' ? $orderId : '';
        if (!empty($orderId)) {
            $query = $query->orderBy('id',$orderId);
        }
        return $query->paginate(per_page());
    }

    /**
     * @return mixed
     */
    public function store()
    {
        $authHid = "p983GK32LY";//TODO::修改成登录这个人的ID
        $maxNum = config('g9zz.invite_code.max_num');
        $hasNum = $this->inviteCodeRepository->getNumByAuthHid($authHid);//return int
        if ($hasNum >= $maxNum) {
            $this->setCode(config('validation.invite_code')['max.num']);
            return $this->response();
        }
        $id = Hashids::connection('user')->decode($authHid);
        $hash = [$id[0],time()];
        $create = [
            'inviter_hid' => $authHid,
            'code' =>   Hashids::connection('code')->encode($hash),
            'status' => 'created',
            'obsoleted_at' => null
        ];
        $this->log('service.request to '.__METHOD__,['create' => $create]);
        return $this->inviteCodeRepository->create($create);
    }

    /**
     * @return mixed
     */
    public function getOwnCode()
    {
        $authHid = 'p983GK32LY';//TODO::修改为登录者HID
        return $this->inviteCodeRepository->getAllCodeByInviterHid($authHid);
    }

}