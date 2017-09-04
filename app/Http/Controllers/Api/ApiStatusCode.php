<?php
/**
 * 所有接口http状态码以及通用错误提示code码定义
 */
namespace App\Http\Controllers\Api;

class ApiStatusCode {
    //http协议层数据
    const HTTP_200 = '200';     //200，ok
    const HTTP_201 = '201';     //201，创建成功
    const HTTP_202 = '202';     //202， Accepted，服务器已接受请求，但尚未处理
    const HTTP_204 = '204';     //204, NO CONTENT, 服务器成功处理，但不需要返回内容
    const HTTP_304 = '304';     //304, NOT MODIFIED, GET的数据没有更改，客户端不需要处理
    const HTTP_400 = '400';     //400, Bad Request, 请求错误
    const HTTP_404 = '404';     //404, 不存在
    const HTTP_405 = '405';     //405, Method Not Allowed
    const HTTP_500 = '500';     //500, Internal Server Error,

    //通用api错误码定义
    const SUCCESS             = 0;//无错误

    const ERR_URI_NOT_FOUND   = -1; //资源不存在
    const ERR_PARAM           = -2; //一般参数校验错误
    const ERR_CHECK_LOGIN     = -3; //需要登录或者用户校验失败
    const ERR_NEED_CAPTCHA    = -4; //需要验证码
    const ERR_REQUEST_METHOD  = -5;

    //瓜子C端-2000 到 - 2500
    const ERR_TOKEN_EXPIRE = -2001; //token过期
    const ERR_CITY_LOCATION_FAIL = -2002; //城市定位失败
    const ERR_APP_SECRET_CHECK_FAILED = -2003; //加密校验失败
    const ERR_SEND_CODE_FAILED = -2004; //验证码发送失败
    const ERR_NEED_LOGIN = -2005; //需要登录
    const ERR_LOGIN_FAILED = -2006; //登录失败


    //权限错误
    const ERR_ALREADY_EXISTS = -3;

    //错误码对应的http_status与错误提示信息
    public static $STATUS_MSG = array(
        self::SUCCESS           =>  '成功',
        self::ERR_URI_NOT_FOUND =>  '资源不存在',
        self::ERR_PARAM         =>  '参数错误',
        self::ERR_CHECK_LOGIN   =>  '需要登录或者用户校验失败',
        self::ERR_NEED_CAPTCHA  =>  '需要验证码',
        self::ERR_APP_SECRET_CHECK_FAILED => '签名错误',

        self::ERR_LOGIN_FAILED  =>  '登陆失败',

        self::HTTP_500        =>  '系统错误',
        self::ERR_REQUEST_METHOD =>'request method not support.',
    );

    public static $HTTP_CODE = array(
        self::SUCCESS           =>  self::HTTP_200,

        self::ERR_URI_NOT_FOUND =>  self::HTTP_200,
        self::ERR_PARAM         =>  self::HTTP_200,
        self::ERR_CHECK_LOGIN   =>  self::HTTP_200,
        self::ERR_NEED_CAPTCHA  =>  self::HTTP_200,
        self::ERR_APP_SECRET_CHECK_FAILED => self::HTTP_200,

        self::ERR_LOGIN_FAILED  =>  self::HTTP_200,

        self::HTTP_500        =>  self::HTTP_200,
        self::ERR_REQUEST_METHOD =>self::HTTP_200,
    );
}
