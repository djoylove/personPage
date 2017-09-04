<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return redirect('/web');
});

Route::group(['middleware' => []], function()
{
    $nameSpace = '\App\Http\Controllers\Api\Base';
    Route::post('/base/login', $nameSpace.'\AuthCtrl@authenticate');
    Route::get('/base/gregwar_code', $nameSpace. '\AuthCtrl@validatePic');


});


$api = app('Dingo\Api\Routing\Router');


$api->version('v1', function ($api) {
    $api->group(['middleware' => ['jwt.auth']], function ($api) {
        $api->any('{module}/{action}', function ($module, $action) {
            $moduleNames = explode('_', $module);
            $name = '';
            foreach ($moduleNames as $split) {
                $name = $name . ucfirst($split);
            }
            $controller = ucfirst($name) . 'Ctrl';
            if (!class_exists($controller)) {
                $controllerClass = 'App\\Http\\Controllers\\Api\\V1\\' . $controller;
                if (class_exists($controllerClass)) {
                    $controllerObj = app($controllerClass);
                    $actionNames = explode('_', $action);
                    $actionName = '';
                    foreach ($actionNames as $index => $split) {
                        if ($index == 0) {
                            $actionName = $split;
                        } else {
                            $actionName = $actionName . ucfirst($split);
                        }
                    }
                    return $controllerObj->$actionName();
                } else {
                    throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException('404 NOT FOUND.');
                }
            }
        });
    });

});