<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Api\ApiStatusCode;

/**
 * Created by PhpStorm.
 * User: xiaod
 * Date: 2017/9/2
 * Time: 11:29
 */
class TestCtrl extends BaseCtrl
{
    public function test() {
        return $this->responseSuc(ApiStatusCode::SUCCESS,"hello test");
    }
}