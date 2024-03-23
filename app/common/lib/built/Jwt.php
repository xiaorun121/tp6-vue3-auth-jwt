<?php
namespace app\common\lib\built;

use thans\jwt\exception\TokenInvalidException;
use thans\jwt\facade\JWTAuth;

class Jwt{

    //刷新token
    public function refreshToken(){
        try {

            $result = JWTAuth::refresh();
            return json([
                'token' => $result,
                'token_expires_time' => env('JWT_REFRESH_TTL') + 7000
            ]);

        } catch (\Throwable $th) {
            
            return json(['error_info'=>'请重新登录' . $th->getMessage(), 'error'=>'user_error']);

        }
    }

    /**
     * 生成token
     * @param $data
     * @return array
     */
    public function createToken(array $data){
        // 生成token
        try {
            $token = JWTAuth::builder($data);
            return json(['access_token'=>$token,'ttl'=>env('JWT_TTL')]);
        } catch (\Throwable $th) {
            return json(['error'=>'token创建失败' . $th->getMessage()]);
        }
    }

    /**
     * 验证token是否有效
     * @return array
     */
    public function checkToken(){
        try {
            $result = JWTAuth::auth();
            return json(['data'=>$result, 'msg'=>'验证成功']);
        } catch (\Throwable $th) {
            return json(['error_info'=>'请先登录', 'error'=>'user_error']);
        } catch (TokenInvalidException $exception){
            return json(['error_info'=>'无效的Token', 'error'=>'jwt_expire']);
        }

       
    }
}