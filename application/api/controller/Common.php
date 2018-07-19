<?php
/**
 * @copyright 杭州融远信息科技有限公司
 * @author     xieqiyong66@gmail.com
 */

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\response;
use think\Cache;
use app\api\models\User;

class Common extends Controller
{
    public function __construct(Request $request = null)
    {
        //解决跨域问题
        header("Access-Control-Allow-Origin:*");
        parent::__construct();
    }

    public static function json($data = null)
    {
        $arr = [];
        $arr["success"] = true;
        $arr["err_code"] = 1;
        $arr["err_msg"] = "";
        $arr["data"] = $data;
        return json($arr);
    }

    public static function _error($msg = '',$code){
        if(empty($msg)){
            $err = "";
        }else{
            $err = $msg;
        }
        $res = array('err_code' => $code, "err_msg" => $err, "success" => false, "data"=> null);
        return json($res);
    }

    //验证api_token
    public function check_token($access_token,$time)
    {
       $res = User::getUserInfoByCode($access_token);
       if($res){
            if($access_token == api_token($res['id'],$time)){
                return true;
            }
       }
       return false;
    }
}
