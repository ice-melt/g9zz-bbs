<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/8/16
 * Time: 下午6:02
 */

namespace App\Transformers;


use App\Models\Roles;

class RolePermissionListTransformer extends BaseTransformer
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
        if ($roles->permission) {
            foreach ($roles->permission as $key => $value) {
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