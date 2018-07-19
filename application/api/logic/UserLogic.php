<?php
/**
 * @copyright 杭州融远信息科技有限公司
 * @author     xieqiyong66@gmail.com
 */

namespace app\api\logic;

use app\api\models\User;

class UserLogic
{
    public function loginLogic($account,$password)
    {
        $userinfo = User::getUserInfoByMobile($account,login_pwd($password));
        if(!$userinfo){
            return array('code'=>NO_DATA,'message'=>'无用户信息');
        }
        //生成token
        session_start();
        $access_token = api_token($userinfo['id'],time());
        $userinfo['access_token'] =  $access_token;
        $userinfo['timestamp'] = time();
        $userinfo['session_id'] = session_id();
        $res = User::updateUserInfoById($userinfo['id'],['last_datetime'=>time(),'last_ip'=>get_client_ip(),'security_code'=>$access_token]);
        if(!$res){
            return array('code'=>OPTION_FAILED,'message'=>'操作失败');
        }
        unset($userinfo['id']);
        return array('code'=>1,'message'=>'','data'=>$userinfo);
    }
}
