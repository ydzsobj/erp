<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class SsoMiddleware
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
        $userInfo = Session::get('user_login');
        if($userInfo){
            // 获取 Cookie 中的 token
            $singleToken = $request->cookie('SINGLE_TOKEN');
            if($singleToken){
                // 从 Redis 获取 time
                $redisTime = Redis::get("STRING_SINGLE_TOKEN_" . $userInfo->id);
                // 重新获取加密参数加密
                $ip = $request->getClientIp();
                $secret = md5($ip . $userInfo->id . $redisTime);
                if ($singleToken != $secret) {
                    // 记录此次异常登录记录
                    //\DB::table('data_login_exception')->insert(['guid' => $userInfo->guid, 'ip' => $ip, 'addtime' => time()]);
                    // 清除 session 数据
                    Session::forget('user_login');
                    return redirect('/admins/login')->with(['Msg' => '您的帐号在另一个地点登录..']);
                }
                return $next($request);

            }else{
                return redirect('/admins/login');
            }
        }else{
            return redirect('/admins/login');
        }



    }
}
