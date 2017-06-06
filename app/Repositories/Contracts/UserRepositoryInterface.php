<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/6/5
 * Time: 下午5:24
 */

namespace App\Repositories\Contracts;


interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * 通过邮箱 获取user
     * @param $email
     * @return mixed
     */
    public function findUserByEmail($email);

    /**
     * 通过githubId 获取user
     * @param $githubId
     * @return mixed
     */
    public function findUserByGithubId($githubId);

    /**
     * 重新分配角色
     * @param $role
     * @param $id
     * @return mixed
     */
    public function syncRelationship($role,$id);

}