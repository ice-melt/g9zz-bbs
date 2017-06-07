<?php

namespace App\Http\Controllers;


use App\Jobs\SendMail;

class TestController extends Controller
{
    //
    public function index()
    {
        $this->dispatch(new SendMail('verify_account',
            'g9zz@g9zz.com',
            date('Y-m-d H:i:s',time()),
            '叶落',
            'http://www.g9zz-bbs.dev'));
    }
}
