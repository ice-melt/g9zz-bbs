<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/8/22
 * Time: 下午2:10
 */

namespace App\Http\Requests\Auth;


use App\Http\Requests\BaseRequest;

class GetLoginRequest extends BaseRequest
{
    public $key = 'login';

    public function rules()
    {
        $rule = [
            'auth' => 'required'
        ];
        return $rule;
    }
}