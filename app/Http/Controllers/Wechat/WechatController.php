<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/11/15
 * Time: 下午6:52
 */

namespace App\Http\Controllers\Wechat;


use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;

class WechatController extends Controller
{

    protected $app;

    public function __construct()
    {
        $this->app = new Application(config('wechat'));
    }

    public function serve()
    {
        $this->app->server->setMessageHandler(function($message){
            return "欢迎关注 ";
        });

        $this->log('return response.');

        return $this->app->server->serve();

    }
}