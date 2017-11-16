<?php
/**
 * Created by PhpStorm.
 * User: zhu
 * Email: ylsc633@gmail.com
 * Date: 2017/11/16
 * Time: 下午10:44
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
        $server = $this->app->server;
        $server->setMessageHandler(function($message){
            return "叶落山城秋";
        });

        $this->log('return response.');

        return $server->serve();
    }
}