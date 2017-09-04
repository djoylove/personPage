<?php
/**
 * Created by PhpStorm.
 * User: xiaod
 * Date: 2017/9/2
 * Time: 11:55
 */

namespace App\Http\Controllers\Api\V1;

use JWTAuth;



class AuthCtrl extends BaseCtrl
{

    public function logout() {
        JWTAuth::invalidate(JWTAuth::getToken());
        return $this->response->array(['code' => 0]);
    }


}