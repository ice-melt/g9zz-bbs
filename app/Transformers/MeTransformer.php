<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/8/23
 * Time: 下午3:09
 */

namespace App\Transformers;


use App\Models\User;

class MeTransformer extends BaseTransformer
{
    public function transform(User $user)
    {
        $return = [
            'hid' => $user->hid,
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'avatar' => $user->avatar,
            'verified' => $user->verified,
        ];

        if ($user->role) {
            $permissions = [];
            foreach ($user->role as $value) {
                $return['role'][] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'displayName' => $value->display_name,
                    'description' => $value->description
                ];
                if ($value->permission) {
                    foreach ($value->permission as $item) {
                        $permissions[$item->id] = [
                            'id' => $item->id,
                            'name' => $item->name,
                            'displayName' => $item->display_name,
                        ];
                    }
                }
            }
        }

        return $return;

    }
}