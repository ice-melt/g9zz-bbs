<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/8/9
 * Time: 下午10:01
 */

namespace App\Transformers;


use App\Models\Roles;

class ShowRoleTransformer extends BaseTransformer
{
    public function transform(Roles $roles)
    {
        $return = [
            'id' => $roles->id,
            'name' => $roles->name,
            'level' => $roles->level,
            'displayName' => $roles->display_name,
            'description' => $roles->description,
            'createdAt' => rfc_3339($roles->created_at)
        ];

        if ($roles->user) {
            foreach ($roles->user as $item) {
                $return['user'][] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'avatar' => $item->avatar
                ];
            }
        }

        if ($roles->permission) {
            foreach ($roles->permission as $value) {
                $return['permission'][] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'displayName' => $value->display_name
                ];
            }
        }
        return $return;
    }

}