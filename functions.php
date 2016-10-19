<?php
    /**
     * 不转义中文字符和\/的 json 编码方法
     * @param array $arr 待编码数组
     * @return string
     */
    function json_encode_no_zh($arr) {
    	$str = str_replace ( "\\/", "/", json_encode ( $arr ) );
    	$search = "#\\\u([0-9a-f]+)#ie";
     
    	if (strpos ( strtoupper(PHP_OS), 'WIN' ) === false) {
    		$replace = "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))";//LINUX
    	} else {
    		$replace = "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))";//WINDOWS
    	}
     
    	return preg_replace ( $search, $replace, $str );
    }

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

// 获取用户的真实  IP
function getRealIpAddr()  
{  
    if (!emptyempty($_SERVER['HTTP_CLIENT_IP']))  
    {  
        $ip=$_SERVER['HTTP_CLIENT_IP'];  
    }  
    elseif (!emptyempty($_SERVER['HTTP_X_FORWARDED_FOR']))  
    //to check ip is pass from proxy  
    {  
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];  
    }  
    else  
    {  
        $ip=$_SERVER['REMOTE_ADDR'];  
    }  
    return $ip;  
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

// 检测用户位置 使用下面的函数，可以检测用户是在哪个城市访问你的网站
function detect_city($ip) {
    
    $default = 'UNKNOWN';

    $curlopt_useragent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)';
    
    $url = 'http://ipinfodb.com/ip_locator.php?ip=' . urlencode($ip);
    $ch = curl_init();
    
    $curl_opt = array(
        CURLOPT_FOLLOWLOCATION  => 1,
        CURLOPT_HEADER      => 0,
        CURLOPT_RETURNTRANSFER  => 1,
        CURLOPT_USERAGENT   => $curlopt_useragent,
        CURLOPT_URL       => $url,
        CURLOPT_TIMEOUT         => 1,
        CURLOPT_REFERER         => 'http://' . $_SERVER['HTTP_HOST'],
    );
    
    curl_setopt_array($ch, $curl_opt);
    
    $content = curl_exec($ch);
    
    if (!is_null($curl_info)) {
        $curl_info = curl_getinfo($ch);
    }
    
    curl_close($ch);
    
    if ( preg_match('{<li>City : ([^<]*)</li>}i', $content, $regs) )  {
        $city = $regs[1];
    }
    if ( preg_match('{<li>State/Province : ([^<]*)</li>}i', $content, $regs) )  {
        $state = $regs[1];
    }

    if( $city!='' && $state!='' ){
      $location = $city . ', ' . $state;
      return $location;
    }else{
      return $default; 
    }
    
}

/**
 * 抓取远程图片
 *
 * @param string $url 远程图片
 * @param string $filename 保存图片的文件名
 */
function get_image($url, $filename = "")
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

// 根据 URL 下载图片
function imagefromURL($image,$rename)
{
    $ch = curl_init($image);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    $rawdata=curl_exec ($ch);
    curl_close ($ch);
    $fp = fopen("$rename",'w');
    fwrite($fp, $rawdata); 
    fclose($fp);
}

/**
 *  二维数组排序
 * 
 * @param array  $arr
 * @param string $keys
 * @param string $type
 * @return array $nev_array
 */
function array_sort($arr,$keys,$type='asc'){ 
	$keysvalue = $new_array = array();
	foreach ($arr as $k=>$v){
		$keysvalue[$k] = $v[$keys];
	}
	if($type == 'asc'){
		asort($keysvalue);
	}else{
		arsort($keysvalue);
	}
	reset($keysvalue);
	foreach ($keysvalue as $k=>$v){
		$new_array[$k] = $arr[$k];
	}
	return $new_array; 
}

/**
 * 不转义中文字符和\/的 json 编码方法
 * @param array $arr 待编码数组
 * @return string
 */
function json_encode_no_zh($arr) {
	$str = str_replace ( "\\/", "/", json_encode ( $arr ) );
	$search = "#\\\u([0-9a-f]+)#ie";
 
	if (strpos ( strtoupper(PHP_OS), 'WIN' ) === false) {
		$replace = "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))";//LINUX
	} else {
		$replace = "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))";//WINDOWS
	}
 
	return preg_replace ( $search, $replace, $str );
}

/**
 * 面包屑
 * @param int $id 分类ID
 * @return string
 */
function breadcrumbs($id) {
    $cate = $GLOBALS['cats'];
    $cats = array();
    foreach ($cate as $val) {
    	$cats[$val['cat_id']] = $val;
    }

    if($cats[$id]['parent_id'] > 0) {
        return breadcrumbs($cats[$id]['parent_id']) . ' -> ' . $cats[$id]['title'];
    }
    else {
        return $cats[$id]['title'];
    }
}

/**
 * 文件下载
 * @param $filepath 文件路径
 * @param $filename 文件名称
 */

function file_down($filepath, $filename = '') {
    if(!$filename) $filename = basename($filepath);
    if(is_ie()) $filename = rawurlencode($filename);
    $filetype = fileext($filename);
    $filesize = sprintf("%u", filesize($filepath));
    if(ob_get_length() !== false) @ob_end_clean();
    header('Pragma: public');
    header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: pre-check=0, post-check=0, max-age=0');
    header('Content-Transfer-Encoding: binary');
    header('Content-Encoding: none');
    header('Content-type: '.$filetype);
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Content-length: '.$filesize);
    readfile($filepath);
    exit;
}

/**
* 转换字节数为其他单位
*
*
* @param    string  $filesize   字节大小
* @return   string  返回大小
*/
function sizecount($filesize) {
    if ($filesize >= 1073741824) {
        $filesize = round($filesize / 1073741824 * 100) / 100 .' GB';
    } elseif ($filesize >= 1048576) {
        $filesize = round($filesize / 1048576 * 100) / 100 .' MB';
    } elseif($filesize >= 1024) {
        $filesize = round($filesize / 1024 * 100) / 100 . ' KB';
    } else {
        $filesize = $filesize.' Bytes';
    }
    return $filesize;
}

/**
 * 程序执行时间
 *
 * @return  int 单位ms
 */
function execute_time() {
    $stime = explode ( ' ', SYS_START_TIME );
    $etime = explode ( ' ', microtime () );
    return number_format ( ($etime [1] + $etime [0] - $stime [1] - $stime [0]), 6 );
}


/**
 * 获取当前页面完整URL地址
 */
function get_url() {
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
    $path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
    $relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.safe_replace($_SERVER['QUERY_STRING']) : $path_info);
    return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}
