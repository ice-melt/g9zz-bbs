<?php

namespace App\Http\Middleware;

use App\Traits\Respond;
use Closure;
use Vinkla\Hashids\Facades\Hashids;

class IdDecode
{
    use  Respond;
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
            $userHid = $request->route()->parameter('userHid');
            \Log::info('middleware.request to '.__METHOD__,['user_id' => $userHid]);
            if ($userHid !== (int)$userHid ) {
                $userId = Hashids::connection('user')->decode($userHid);
                if (empty($userId)) {
                    $this->setCode(config('validation.default')['data.null']);
                    return $this->response();
                }
                \Log::info('middleware.request to '.__METHOD__,['Hashids_user_id' => $userId]);
                $request->route()->setParameter('userHid',$userId[0]);
            }
        }
        return $next($request);
    }
}
