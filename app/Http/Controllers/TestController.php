<?php

namespace App\Http\Controllers;




use App\Jobs\SendMail;
use App\Mail\OrderShipped;

class TestController extends Controller
{
    //
    public function index()
    {
//        \Mail::raw('他妈的,好像有人在DDO你网站啊,要不要看看？', function ($m) {
//            $m->to('g9zz@g9zz.com', '叶落山城')->subject('有坏淫，撸翻它');
//        });
//        \Mail::to('g9zz@g9zz.com')->send(new OrderShipped());
        $this->dispatch(new SendMail('verify_account',
            'g9zz@g9zz.com',
            '这是一个测试邮件',
            '叶落',
            'http://www.g9zz-bbs.dev'));
    }
}
