<?php
declare (strict_types = 1);

namespace app\middleware;

class UserCheck
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
        //检查是否已登录		
		if(!session('?user')) {
            $res = res_result(NULL, 1, "请先登录后台");
            return json($res);	
		}
        
        return $next($request);
    }
}
