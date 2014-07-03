<?php

// PHP stdClass to Array and Array to stdClass – stdClass Object 

// stdClass 对象转换成数组
function objectToArray($d) {
	if (is_object($d)) {
		// Gets the properties of the given object
		// with get_object_vars function
		$d = get_object_vars($d);
	}

	if (is_array($d)) {
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return array_map(__FUNCTION__, $d);
	}
	else {
		// Return array
		return $d;
	}
}

// 数组转换成 stdClass 对象
function arrayToObject($d) {  
    if (is_array($d)) {  
        /* 
        * Return array converted to object 
        * Using __FUNCTION__ (Magic constant) 
        * for recursive call 
        */  
        return (object) array_map(__FUNCTION__, $d);  
    }  
    else {  
        // Return object  
        return $d;  
    }  
}  

/**
* 计算两个经纬度之间的距离
*
* @param folat $latitude1, $longitude1
* @param folat $latitude2, $longitude2
* $point1 = array('lat' => 40.770623, 'long' => -73.964367);
* $point2 = array('lat' => 40.758224, 'long' => -73.917404);
* $distance = get_distance($point1['lat'], $point1['long'], $point2['lat'], $point2['long']);
* foreach ($distance as $unit => $value) {
* echo $unit.': '.number_format($value,4).'<br />';
* }
* @return array
* The example returns the following:
* miles: 2.6025
* feet: 13,741.4350
* yards: 4,580.4783
* kilometers: 4.1884
* meters: 4,188.3894
*/
function get_distance($latitude1, $longitude1, $latitude2, $longitude2) {
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
* @param array $post
* @param string $ua
* @return array $output
*/
function xcurl($url,$ref=null,$post=array(),$ua="Mozilla/5.0 (X11; Linux x86_64; rv:2.2a1pre) Gecko/20110324 Firefox/4.2a1pre",$print=false) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_AUTOREFERER, true); // 自动设置header中的referer信息
    if(!empty($ref)) {
        curl_setopt($ch, CURLOPT_REFERER, $ref); // 在HTTP请求中包含一个”referer”头的字符串
    }
    curl_setopt($ch, CURLOPT_URL, $url); // 需要获取的URL地址
    curl_setopt($ch, CURLOPT_HEADER, 0); // 启用时会将头文件的信息作为数据流输出
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 是否获取跳转后的页面
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 讲curl_exec()获取的信息以文件流的形式返回，而不是直接输出
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
function get_realip()
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
* 获取 IP 地理位置
* 淘宝IP接口
*
* @param string $ip
* @return array $data
*/
function get_city($ip)
{
    $url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
    $ip=json_decode(file_get_contents($url));
    if((string)$ip->code=='1'){
        return false;
    }
    $data = (array)$ip->data;
    return $data;
}
