<?php

declare(strict_types=1);

namespace app\api\middleware;

class Check204
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        $origin = $request->header('Origin', '');

        //OPTIONS请求返回204请求
        if ($request->method(true) === 'OPTIONS') {
           exit('ok');
        }

        $response->header([
            'Access-Control-Allow-Origin'      => $origin,
            'Access-Control-Allow-Methods'     => 'GET,POST,PUT,DELETE',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Headers'     => '*',
        ]);

        // 检查状态码
        // if ($response->getStatusCode() == 204) {
        //     // 终止后续操作
        //     throw new \think\exception\HttpResponseException($response);
        // }
        
        // 继续执行后续的中间件
        return $response;

    }
}
