<?php
/**
 * @copyright 杭州融远信息科技有限公司
 * @author     xieqiyong66@gmail.com
 */

namespace app\api\validate;

class LoginRequest extends BaseRequest
{
    protected $rule =   [
        'password'  => 'require|min:6',
        'mobile'    => 'require|min:11',
    ];
}
