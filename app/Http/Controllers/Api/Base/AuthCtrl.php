<?php
/**
 * Created by PhpStorm.
 * User: xiaod
 * Date: 2017/9/2
 * Time: 11:55
 */

namespace App\Http\Controllers\Api\Base;


use App\Http\Controllers\Api\V1\BaseCtrl;
use GrahamCampbell\Throttle\Facades\Throttle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Gregwar\Captcha\CaptchaBuilder;


class AuthCtrl extends BaseCtrl
{
    public function authenticate(Request $request)
    {
        $throttle = Throttle::hit($request, 10, 5);
        $requestCount = $throttle->count();
        if (!$throttle->check()) {
            return $this->response->array(['code' => -1,'count' => $requestCount,'message' => '您在五分钟内多次登陆,请五分钟后重试']);
        }
        if ($requestCount >= 3) {
            $code = $request->get('captcha');
            $session_code = Session::get('captcha');
            if ($session_code != $code) {
                return $this->response->array(['code' => -1, 'count' => $requestCount,'message' => '验证码错误']);
            }
        }
        $credentials = $request->only('username', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->response->array(['code' => -1, 'count' => $requestCount,'message' => '用户名密码错误']);
            }
        } catch (JWTException $e) {
            return $this->response->array(['code' => -1, 'count' => $requestCount,'message' => '服务器内部错误']);
        }
        $throttle->clear();
        return $this->response->array(['code' => 0, 'token' => $token]);
    }
    
    public function validatePic()
    {
        $builder = new CaptchaBuilder;
        //可以设置图片宽高及字体
        $builder->build($width = 200, $height = 80, $font = null);
        //获取验证码的内容
        $phrase = $builder->getPhrase();

        //把内容存入session
        $result = Session::put('captcha', $phrase);
        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }


}