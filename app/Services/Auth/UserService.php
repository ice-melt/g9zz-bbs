<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/6/5
 * Time: 下午5:13
 */

namespace App\Services\Auth;

use App\Exceptions\TryException;
use App\Jobs\SendMail;
use App\Repositories\Contracts\GithubUserRepositoryInterface;
use App\Repositories\Contracts\InviteCodeRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\BaseService;
use Vinkla\Hashids\Facades\Hashids;

class UserService extends BaseService
{

    protected $inviteCodeRepository;
    protected $githubUserRepository;
    protected $userRepository;
    protected $isInvite;

    public function __construct(UserRepositoryInterface $userRepository,
                                InviteCodeRepositoryInterface $inviteCodeRepository,
                                GithubUserRepositoryInterface $githubUserRepository
    )
    {
        $this->isInvite = config('g9zz.invite_code.is_invite');
        $this->inviteCodeRepository = $inviteCodeRepository;
        $this->githubUserRepository = $githubUserRepository;
        $this->userRepository = $userRepository;

    }


    /**
     * @param $create
     * @param $other
     * @return mixed
     */
    public function loginCreate($create,$other)
    {
        $this->log('service.request to '.__METHOD__,['create' => $create]);

        try {
            \DB::beginTransaction();
            $user = $this->userRepository->create($create);
            $user->hid = Hashids::connection('user')->encode($user->id);
            $user->save();
            if ($this->isInvite) {
                $inviteCode = $this->inviteCodeRepository->getInviteCodeByCode($other['invite_code']);
                if (empty($inviteCode)) {
                    $this->setCode(config('validation.validation.register')['inviteCode.exists']);
                    return $this->response();
                }
                $inviteCodeUpdate = [
                    'status' => 'used',
                    'invitee_id' => $user->id,
                    'obsoleted_at' => date('Y-m-d H:i:s',time()),
                ];
                $this->log('service.request to '.__METHOD__,['invite_code_update' => $inviteCodeUpdate]);
                $this->inviteCodeRepository->update($inviteCodeUpdate,$inviteCode->id);
            }

            $this->verifyEmail($create['email'],$create['name'],$user->id);

            \DB::commit();
        } catch (\Exception $e) {
            $this->log('"service.error" to listener "' . __METHOD__ . '".', ['message' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
            \DB::rollBack();
            throw new TryException(json_encode($e->getMessage()),(int)$e->getCode());
        }
        return $user;
    }

    /**
     * @param $email
     * @param $name
     * @param $id
     * @return bool
     */
    public function verifyEmail($email,$name,$id)
    {
        $message = [$id,time(),3];
        $param = Hashids::connection('code')->encode($message);
        dispatch(new SendMail('verify_account',
//            is_local() ? 'g9zz@g9zz.com' : $email,//TODO::修改正式的
                'g9zz@g9zz.com',
            "邮箱激活",
            $name,
            'http://www.g9zz-bbs.dev/verify?token='.$param));
        return true;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserById($id)
    {
        return $this->userRepository->getUserById($id);
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function updateVerify($userId)
    {
        return $this->userRepository->update(['verified' => 'true'],$userId);
    }

    /**
     * @param $email
     * @return mixed
     */
    public function findUserByEmail($email)
    {
        return $this->userRepository->findUserByEmail($email);
    }

    /**
     * @param $requestPwd
     * @param $sqlPwd
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPwd($requestPwd,$sqlPwd)
    {
        if (!password_verify($requestPwd,$sqlPwd)) {
            $this->setCode(config('validation.login')['login.error']);
            return $this->response();
        }
    }

    /**
     * @param $githubId
     * @return mixed
     */
    public function checkIsGithub($githubId)
    {
        return  $this->githubUserRepository->getGithub($githubId);
    }

    /**
     * @param $user
     * @return mixed
     */
    public function storeGithub($user)
    {
        $data = $user->user;
//        dd($data,$user);
        $create = [
            'github_name' => $data['login'],
            'github_id' => $data['id'],
            'nickname' => $user->nickname,
            'display_name' => $data['name'],
            'email' => $data['email'],
            'avatar' => $data['avatar_url'],
            'gravatar_id' => $data['gravatar_id'],
            'url' => $data['url'],
            'html_url' => $data['html_url'],
            'type' => $data['type'],
            'site_admin' => $data['site_admin'],
            'company' => $data['company'],
            'blog' => $data['blog'],
            'location' => $data['location'],
            'hireable' => $data['hireable'],
            'bio' => $data['bio'],
            'public_repos' => $data['public_repos'],
            'public_gists' => $data['public_gists'],
            'followers' => $data['followers'],
            'github_created_at' => $data['created_at'],
            'github_updated_at' => $data['updated_at'],
        ];
        try {
            \DB::beginTransaction();
            $this->log('service.request to '.__METHOD__,['github_create' => $create]);
            $result = $this->githubUserRepository->create($create);

            $userCreate = [
                'email' => $create['email'],
                'github_id' => $result->id,
                'name' => empty($create['display_name']) ? $create['github_name'] : $create['display_name'],
                'avatar' => $create['avatar'],
                'register_source' => 'github',
                'verified' => 'true'
            ];
            $this->log('service.request to '.__METHOD__,['user_create' => $userCreate]);
            $userResult = $this->userRepository->create($userCreate);
            $update['hid'] = Hashids::connection('user')->encode($userResult->id);
            $this->log('service.request to '.__METHOD__,['user_update' => $update]);
            $this->userRepository->update($update,$userResult->id);
            \DB::commit();
        } catch (\Exception $e) {
            $this->log('"service.error" to listener "' . __METHOD__ . '".', ['message' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
            \DB::rollBack();
            throw new TryException(json_encode($e->getMessage()),(int)$e->getCode());
        }

        return $this->userRepository->find($userResult->id);
    }

    /**
     * @param $githubId
     * @return mixed
     */
    public function findUserByGithubId($githubId)
    {
        return $this->userRepository->findUserByGithubId($githubId);
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function findUserByToken($userId)
    {
        return $this->userRepository->first($userId);
    }
}