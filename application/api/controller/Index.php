<?php
namespace app\api\controller;

use think\Request;
use app\api\logic\UserLogic;
use think\Log;

class Index extends Common
{
    public function index()
    {

    }

    public function login(Request $request)
    {
        $validate = validate('LoginRequest');
        if(!$validate->check($request->param())) {
            return self::_error($validate->getError(),PARAM_ERROR);
        }
        $passwd = input('password','','trim');
        $account = input('mobile','','trim');
        $login = new UserLogic();
        $result = $login->loginLogic($account,$passwd);
        if($result['code'] != 1){
            return self::_error($result['message'],$result['code']);
        }
        return self::json($result['data']);
    }
}
