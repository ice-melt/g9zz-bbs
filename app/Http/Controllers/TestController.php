<?php

namespace App\Http\Controllers;


use App\Jobs\SendMail;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class TestController extends Controller
{
    //
    public function index()
    {





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
}
