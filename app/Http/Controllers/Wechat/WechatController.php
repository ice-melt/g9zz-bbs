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
use EasyWeChat\Message\Text;

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
            switch ($message->MsgType) {
                case 'event':
                    return $this->event($message);
                    break;
                case 'text':
                    return $this->text($message);
                    break;
                case 'image':
                    return '暂不处理图片消息';
                    break;
                case 'voice':
                    return '暂不处理语音消息';
                    break;
                case 'video':
                    return '暂不处理视频消息';
                    break;
                case 'location':
                    return '暂不处理坐标消息';
                    break;
                case 'link':
                    return '暂不处理链接消息';
                    break;
                // ... 其它消息
                default:
                    return '暂不处理其它消息';
                    break;
            }
        });

        $this->log('return response.');

        return $server->serve();
    }

    /**
     * @param $message
     * @return string
     */
    public function event($message)
    {
        switch ($message->Event) {
            case 'subscribe':
                return "欢迎关注";
                break;
            case "unsubscribe":
                return "卧槽";
                break;
            case "CLICK":
                return "点击个啥";
                break;
            case 'VIEW':
                return "卧槽";
                break;
            default:
                break;
        }
    }

    /**
     * @param $message
     * @return Text
     */
    public function text($message)
    {
        $content = $message->Content;

        if($content == '叶落') {
            $text = new Text(['content' => $message->FromUserName]);
        } else {
            $text = new Text(['content' => '叶落山城秋2']);
        }
        return $text;
    }

    /**
     * 创建微信菜单
     */
    public function createMenu()
    {
        $menuConfig = config('my-wechat.wechat_menu');
        $result = $this->app->menu->add($menuConfig);
        dd($result);
    }

}