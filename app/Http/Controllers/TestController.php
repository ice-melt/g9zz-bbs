<?php

namespace App\Http\Controllers;


use App\Jobs\SendMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class TestController extends Controller
{
    //
    public function index()
    {



        $dd = strtotime('Tue May 10 11:37:42 +0800 2011');
        $aa = date('Y-m-d H:i:s',$dd);
dd($dd,$aa);


        $user = User::find(1);
//        foreach ($user->unreadNotifications as $notification) {
//            dd($notification->markAsRead());
//        }
//        Notification::send()
        $bb = $user->notifications()->orderBy('created_at','desc')->paginate();
//        $aa = $user->unreadNotifications()->paginate();
        dd($bb->toArray());
////        $user->unreadNotifications->markAsRead();
//        dd($user->readNotifications()->get()->toArray());
//        foreach ($user->notifications() as $notification) {
//            echo $notification->type;
//        }
////        dd($user->unreadNotifications->toArray());
////        $invoice =   "吼一吼";
//        $invoice = new \stdClass();
//        $invoice->id = 12;
//        $invoice->message = "测试信息";
//        $user->notify(new InvoicePaid($invoice));
    }

    public function store(Request $request)
    {
        $str = $request->get('content');

        $preg='/(?<=@)[^\s]+\s?/';
        $res = preg_match_all($preg, $str, $match);
        $arr = [];
        $pregs = [];
        foreach ($match[0] as $key =>  $value) {
            $pregs[$key] = '/'.$value.'/';
            $arr[$key] = "<a href='".env('APP_URL')."/user/". urlencode($value)."'>".$value."</a>";
        }

        $res2 = preg_replace($pregs,$arr,$str);
        dd($res2,$str);


        dd($res);
    }
}
