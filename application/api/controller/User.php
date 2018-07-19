<?php
/**
 * @copyright 杭州融远信息科技有限公司
 * @author     xieqiyong66@gmail.com
 */

namespace app\api\controller;


class User extends Common
{
    public function user_order()
    {
        $access_token = input('access_token','','trim');
        $timestamp = input('timestamp','','intval');
        $res = $this->check_token($access_token,$timestamp);
        if(!$res){
            return self::_error('非法请求',WRONG_REQUEST);
        }
    }
}
