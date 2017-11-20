<?php

namespace App\Http\Middleware;

use App\Traits\G9zzLog;
use App\Traits\Respond;
use Closure;

class CaptchaMiddleware
{
    use G9zzLog,Respond;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $uuid = trim($request->get('uuid'));
        $this->log('middleware.request to '.__METHOD__,['uuid' => $uuid]);

        if (empty($uuid)) {
            $code = config('validation.captcha')['uuid.required'];
            $this->setCode($code);
            return $this->response();
        }

        return $next($request);
    }
}
