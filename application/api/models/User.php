<?php
/**
 * @copyright 杭州融远信息科技有限公司
 * @author     xieqiyong66@gmail.com
 */

namespace app\api\models;

use think\Model;
use think\Cache;

class User extends Base
{
    protected  $name = 'users';


    protected $hidden = [
        "passwd",
        "last_datetime",
        "last_ip"
    ];

    //获取用户信息
    public static function getUserInfoById($id,$feild="*")
    {
        $results = Cache::get('user_info_'.$id);
        if(!$results){
            $results = self::where('id',$id)->field($feild)->find();
            Cache::set('user_info_'.$id,$results);
        }
        return $results;
    }

    //获取用户信息
    public static function getUserInfoByMobile($account,$password)
    {
        $where = ['mobile'=>$account,'passwd'=>$password];
        $results = self::where($where)->find();
        return empty($results)?null:$results->toArray();
    }

    //修改用户信息
    public static function updateUserInfoById($id,$data=array())
    {
        $result = self::where('id',$id)->update($data);
        return $result;
    }

    //根据code查询
    public static function getUserInfoByCode($access_token)
    {
        $results = self::where('security_code',$access_token)->find();
        return empty($results)?null:$results->toArray();
    }
}
