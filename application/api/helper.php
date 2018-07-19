<?php
use think\Log;
use think\Config;
/**
 * @copyright 杭州融远信息科技有限公司
 * @author     xieqiyong66@gmail.com
 */

/**
 * 接口token
 * @return string
 */
function api_token($uid,$time="")
{
    $time = empty($time)?time():$time;
    $str = sha1(base64_encode(md5($uid.config('WEB_SECRET').get_client_ip().$time)));
    return $str;
}

function login_pwd($str)
{
    return md5($str.config('USER_PASSWORD'));
}

/**
 * 获取用户真实ip
 */
function get_client_ip()
{
    //判断服务器是否允许$_SERVER
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $realip = $_SERVER['REMOTE_ADDR'];
        }
    } else {
        //不允许就使用getenv获取
        if (getenv("HTTP_X_FORWARDED_FOR")) {
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }
    return $realip;
}


function ErrorLog($error){

    $logName =  'error/'.date('Ymd',time()). '.log';

    formatLog("Error", $error,get_caller_info(), $logName);
}

function InfoLog($info){
    $logName = 'info/'.date('Ymd',time()). '.log';
    formatLog("Info", $info, get_caller_info(), $logName);
}

function formatLog($type, $info, $caller, $logName){
    $str = "\n";
    $str .= "------------------------------  " . $type . "  ------------------------------\n";
    $str .= "| Time		:	" . date('Y-m-d H:i:s',time()) . "\n";
    $str .= "| Class	:	" . $caller["class"] . "\n";
    $str .= "| Func		:	" . $caller["func"] . "\n";
    $str .= "| Line		:	" . $caller["line"] . "\n";
    $str .= "| File		:	" . $caller["file"] . "\n";
    $str .= "| Content	:	\n| " . json_encode($info, JSON_UNESCAPED_UNICODE) . "\n";
    $str .= "---------------------------------------------------------------------";
    if($type == "Error"){
        file_put_contents(config('LOG_PATH').$logName,$str,FILE_APPEND);
    }else if($type == "Info"){
        file_put_contents(config('LOG_PATH').$logName,$str,FILE_APPEND);
    }
    $info['systemInfo'] = [
        'Class' => $caller["class"],
        'Func' => $caller["func"],
        'Line' => $caller["line"],
        'File' => $caller["file"],
    ];

    return true;
}

function get_caller_info() {
    $file = '';
    $func = '';
    $class = '';
    $line = -1;
    $trace = debug_backtrace();
    if (isset($trace[2])) {
        $file = $trace[1]['file'];
        $line = $trace[1]['line'];
        $func = $trace[2]['function'];
        $class = $trace[2]['class'];
        if ((substr($func, 0, 7) == 'include') || (substr($func, 0, 7) == 'require')) {
            $func = '';
        }
    }
    return ["file" => $file, "func" => $func, "class" => $class, "line" => $line];
}

function chmodr($path, $filemode)
{
    if (!is_dir($path))
        return chmod($path, $filemode);

    $dh = opendir($path);
    while (($file = readdir($dh)) !== false)
    {
        if($file != '.' && $file != '..')
        {
            try{
                $fullpath = $path . DIRECTORY_SEPARATOR . $file;
                if(is_link($fullpath))
                    return FALSE;
                elseif(!is_dir($fullpath) && !chmod($fullpath, $filemode))
                    return FALSE;
                elseif(!chmodr($fullpath, $filemode))
                    return FALSE;
            }catch(\Exception $e){
                $log = ["msg" => "error occured when chmod", "path" => $fullpath];
                ErrorLog($log);
            }
        }
    }

    closedir($dh);

    if(chmod($path, $filemode))
        return TRUE;
    else
        return FALSE;
}
