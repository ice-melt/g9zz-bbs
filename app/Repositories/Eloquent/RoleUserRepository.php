<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/8/18
 * Time: 下午4:05
 */

namespace App\Repositories\Eloquent;


use App\Models\RoleUser;
use App\Repositories\Contracts\RoleUserRepositoryInterface;

class RoleUserRepository extends BaseRepository implements RoleUserRepositoryInterface
{
    public function model()
    {
        return RoleUser::class;
    }
}