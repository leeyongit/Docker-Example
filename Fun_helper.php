<?php
/**
 * 计算两个经纬度之间的距离
 *
 * @param folat $latitude1, $longitude1
 * @param folat $latitude2, $longitude2
 * $point1 = array('lat' => 40.770623, 'long' => -73.964367);
 * $point2 = array('lat' => 40.758224, 'long' => -73.917404);
 * $distance = getDistanceBetweenPointsNew($point1['lat'], $point1['long'], $point2['lat'], $point2['long']);
 * foreach ($distance as $unit => $value) {
 *    echo $unit.': '.number_format($value,4).'<br />';
 * }
 * @return array 
 * The example returns the following:
 * miles: 2.6025
 * feet: 13,741.4350
 * yards: 4,580.4783
 * kilometers: 4.1884
 * meters: 4,188.3894
 */
function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2) {
    $theta = $longitude1 - $longitude2;
    $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;
    $feet = $miles * 5280;
    $yards = $feet / 3;
    $kilometers = $miles * 1.609344;
    $meters = $kilometers * 1000;
    return compact('miles','feet','yards','kilometers','meters'); 
}

/**
 * 完美的CURL函数
 *
 * @param string $url
 * @param staing $ref
 * @param array  $post
 * @param string $ua
 * @return array $output 
 */
function xCurl($url,$ref=null,$post=array(),$ua="Mozilla/5.0 (X11; Linux x86_64; rv:2.2a1pre) Gecko/20110324 Firefox/4.2a1pre",$print=false) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);  // 自动设置header中的referer信息
    if(!empty($ref)) {
        curl_setopt($ch, CURLOPT_REFERER, $ref);  // 在HTTP请求中包含一个”referer”头的字符串
    }
    curl_setopt($ch, CURLOPT_URL, $url);          // 需要获取的URL地址
    curl_setopt($ch, CURLOPT_HEADER, 0);          // 启用时会将头文件的信息作为数据流输出
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  // 是否获取跳转后的页面
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // 讲curl_exec()获取的信息以文件流的形式返回，而不是直接输出
    if(!empty($ua)) {
        curl_setopt($ch, CURLOPT_USERAGENT, $ua); // 在HTTP请求中包含一个”user-agent”头的字符串
    }
    if(count($post) > 0){
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);   
    }
    $output = curl_exec($ch);
    curl_close($ch);
    if($print) {
        print($output);
    } else {
        return $output;
    }
}

/**
 * 获取用户真实 IP
 *
 * @return string $realip
 */
function getIp()
{
    static $realip;
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }

    return $realip;
}

/**
 * 获取 IP  地理位置
 * 淘宝IP接口
 *
 * @param string $ip
 * @return array $data
 */
function getCity($ip)
{
    $url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
    $ip=json_decode(file_get_contents($url));
    if((string)$ip->code=='1'){
        return false;
    }
    $data = (array)$ip->data;
    return $data;
}

/**
 * 截取中文UTF-8字符串
 *
 * @param string $str   要截取的字符串
 * @param string $start 中文UTF-8字符串的起始位置
 * @param int    $lenth 要截取中文UTF-8字符串的长度
 * @return string
 */
 function cutStrUtf8($str, $start, $lenth)
 {
    $len = strlen($str);
    $r = array();
    $n = 0;
    $m = 0;
    for($i = 0; $i < $len; $i++) {
        $x = substr($str, $i, 1);
        $a  = base_convert(ord($x), 10, 2);
        $a = substr('00000000'.$a, -8);
        if ($n < $start){
            if (substr($a, 0, 1) == 0) {
            }elseif (substr($a, 0, 3) == 110) {
                $i += 1;
            }elseif (substr($a, 0, 4) == 1110) {
                $i += 2;
            }
            $n++;
        }else{
            if (substr($a, 0, 1) == 0) {
                $r[ ] = substr($str, $i, 1);
            }elseif (substr($a, 0, 3) == 110) {
                $r[ ] = substr($str, $i, 2);
                $i += 1;
            }elseif (substr($a, 0, 4) == 1110) {
                $r[ ] = substr($str, $i, 3);
                $i += 2;
            }else{
                $r[ ] = '';
            }
            if (++$m >= $lenth){
                break;
            }
        }
    }
    $r = implode('',$r);
    return $r;
}

/**
 * 统计utf8中文字符串长度的函数
 *
 * @param string $str 要计算长度的字符串
 * @return int        返回字符串的长度
 */
function strLengthZh($str)
{
    if(empty($str)) {
        return 0;
    }
    if(function_exists('mb_strlen')) {
        return mb_strlen($str,'utf-8');
    } else {
        preg_match_all("/./u", $str, $ar);
        return count($ar[0]);
    }
}

/**
 * 抓取远程图片
 *
 * @param string $url 远程图片
 * @param string $filename 保存图片的文件名
 */
function grabImage($url, $filename = "")
{
    if ($url == "") return false;

    if ($filename == "") {
        $ext = strrchr($url, ".");
        if ($ext != ".gif" && $ext != ".jpg" && $ext != ".png") return false;
        $filename = date("dMYHis") . $ext;
    }

    ob_start();               //打开输出
    readfile($url);           //输出图片文件
    $img = ob_get_contents(); //得到浏览器输出
    ob_end_clean();           //清除输出并关闭
    $size = strlen($img);     //得到图片大小
    $fp2 = @fopen($filename, "a");
    fwrite($fp2, $img);       //向当前目录写入图片文件，并重新命名
    fclose($fp2);
    return $filename;         //返回新的文件名
}


?>
