<?php
/**
 * webtoken验证类
 * Created by PhpStorm.
 * User: mali@mali
 * Date: 2020/8/7
 * Time: 12:19 上午
 */

namespace app\base\controller;


class Jwt
{
    public function createJwt()
    {
        $time = time();
        $token = (new Builder())->issuedBy('http://maicaii.com') // 发行者
        ->permittedFor('http://maicaii.com') // 观众
        ->identifiedBy('4f1g23a12aa', true) // id (jti claim),
        ->issuedAt($time) //  发行时间(iat claim)
        ->canOnlyBeUsedAfter($time + 60) // 可使用时间 (nbf claim)
        ->expiresAt($time + 3600) // 过期时间(exp claim)
        ->withClaim('usernam', 'asdwaw') // 配置一个新的字段
        ->withClaim('password', 'sadwa') // 配置一个新的字段
        ->getToken(); // 生成令牌
        return $token;
    }

    public function verifyJwt($jwt = '')
    {
        $key = md5('zq8876!@!');
        try {
            $jwtAuth = json_encode(JWTUtil::decode($jwt, $key, array('HS256')));
            $authInfo = json_decode($jwtAuth, true);

            $msg = [];
            if (!empty($authInfo['user_id'])) {
                $msg = [
                    'status' => 1001,
                    'msg' => 'Token验证通过'
                ];
            } else {
                $msg = [
                    'status' => 1002,
                    'msg' => 'Token验证不通过,用户不存在'
                ];
            }
            return $msg;
        }  catch (\Firebase\JWT\ExpiredException $e) {
            echo json_encode([
                'status' => 1003,
                'msg' => 'Token过期'
            ]);
            exit;
        } catch (\Exception $e) {
            echo json_encode([
                'status' => 1002,
                'msg' => 'Token无效'
            ]);
            exit;
        }
    }
}