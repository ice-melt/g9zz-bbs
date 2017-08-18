<?php

namespace App\Http\Middleware;

use App\Traits\G9zzLog;
use Closure;
use Vinkla\Hashids\Facades\Hashids;

class IdDecode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $actionName = $request->route()->getName();
        \Log::info('middleware.request to '.__METHOD__,['action_name' => $actionName]);
        if ($actionName == 'console.user.attach.role') {
            $userId = $request->route()->parameter('userId');
            \Log::info('middleware.request to '.__METHOD__,['user_id' => $userId]);
            if ($userId !== (int)$userId ) {
                $userId = Hashids::connection('user')->decode($userId);
                \Log::info('middleware.request to '.__METHOD__,['Hashids_user_id' => $userId]);
                $request->route()->setParameter('userId',$userId[0]);
            }
        }
        return $next($request);
    }
}
