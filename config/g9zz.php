<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/4/28
 * Time: 下午7:54
 */

return [
    'node' => [
        'max_level' => 3,
        'show_num' => 6,//最多显示节点个数
        'most_num' => 15,//用的最多的个数
    ],
    'append' => [
        'max_count' => 3
    ],
    'invite_code' => [
        'max_num' => 5,
        'is_invite' => env('IS_INVITE',false),
    ],
    'token' => [
        'valid_time' => 12 * 60 * 60,  //秒数
        'over_time' => 36 * 60 * 60, //最多超出时间
        'login_way'  => ['github','weibo'],//'weixin','qq','weibo',''
    ],
    'verify' => [
        'valid_time' => 24 * 60 * 60
    ],
    'oauth' => [
        'login' => [
            1 => 'github_id',
            2 => 'weibo_id',
            3 => 'weixin_id',
            4 => 'qq_id',
            5 => 'xcx_id',
            6 => 'douban_id',
        ],
        'auth' => [
            'github' => 1,
            'weibo' => 2,
            'weixin' => 3,
            'qq' => 4,
            'xcx' => 5,
            'douban' => 6,
        ],

    ],
    'official' => [
        'name' => env('OFFICIAL_NAME','G9ZZ'),
        'hid' => '',
        'avatar' => env('OFFICIAL_AVATAR','images/g9zz.jpeg'),
    ],
    //G9ZZ前台
    'g9zz_index' => [
        'domain' => env('G9ZZ_INDEX_DOMAIN','https://www.g9zz.com'),
        'login_redirect' => env('G9ZZ_LOGIN_REDIRECT','login'),
        'auth_redirect' => env('G9ZZ_AUTH_REDIRECT','my'),
    ],
    'user' => [
        'avatar' => ['jpg','png','jpeg']
    ]
];