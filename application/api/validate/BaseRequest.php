<?php
/**
 * @copyright 杭州融远信息科技有限公司
 * @author     xieqiyong66@gmail.com
 */

namespace app\api\validate;

use think\Validate;

class BaseRequest extends Validate
{
    public function __construct(array $options = [])
    {
        parent::__construct($options);
    }
}
