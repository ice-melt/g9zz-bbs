<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/8/18
 * Time: 下午4:06
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RoleUser
 *
 * @property int $user_id
 * @property int $role_id
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RoleUser whereRoleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RoleUser whereUserId($value)
 * @mixin \Eloquent
 */
class RoleUser extends Model
{
    protected $table = 'role_user';
    protected $fillable = [
        'role_id',
        'user_id',
    ];
}