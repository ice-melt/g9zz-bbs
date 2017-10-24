<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/10/24
 * Time: 下午4:44
 */

namespace App\Transformers;


use App\Models\Posts;

class PopPostTransformer extends BaseTransformer
{
    public function transform(Posts $posts)
    {
        $return = [
            'hid' => $posts->hid,
            'title' => $posts->title,
        ];

        return $return;
    }
}