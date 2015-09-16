<?php
/**
 * 过滤器 数据过滤
 */
// filter_var — 使用特定的过滤器过滤一个变量
// 验证电子邮箱格式
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "这是一个有效的电子邮箱格式.";
}


// 去除标签，去除或编码特殊字符
filter_var($string, FILTER_SANITIZE_STRING)) 


// 判断数字范围
$int_a = '1';
$options = array(
    'options' => array(
      'min_range' => 0,
      'max_range' => 3,
      )
);
if (filter_var($int_a, FILTER_VALIDATE_INT, $options) !== FALSE) {
    echo "整数1是有效的 (在0到3范围内).\n";
}

// 验证URL
// FILTER_FLAG_PATH_REQUIRED - 要求 URL 在主机名后存在路径（比如：eg.com/example1/）
// FILTER_FLAG_QUERY_REQUIRED - 要求 URL 存在查询字符串（比如："eg.php?age=37"）
if (filter_var($url, FILTER_VALIDATE_URL)) {
    echo "这是一个有效的URL地址.";
}


// 回调函数
function toDash($x){
   return str_replace("_","-",$x);
} 

echo filter_var("asdf_123",FILTER_CALLBACK,array("options"=>"toDash"));

// returns 'asdf-123'


// htmlspecialchars_decode()：把一些预定义的html实体转换为字符。
// 表单验证字符串
// 定义变量并设置为空值
$name = $email = $gender = $comment = $website = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = input($_POST["name"]);
  $email = input($_POST["email"]);
  $website = input($_POST["website"]);
  $comment = input($_POST["comment"]);
  $gender = input($_POST["gender"]);
}

function input($data) {
  $data = trim($data);			// 去除用户输入数据中不必要的字符（多余的空格、制表符、换行）
  $data = stripslashes($data);  // 删除用户输入数据中的反斜杠（\）
  $data = htmlspecialchars($data); // 把特殊字符转换为 HTML 实体
  return $data;
}

// 防止SQL注入
function clean($input)
{
    if (is_array($input))
    {
        foreach ($input as $key => $val)
         {
            $output[$key] = clean($val);
            // $output[$key] = $this->clean($val);
        }
    }
    else
    {
        $output = (string) $input;
        // if magic quotes is on then use strip slashes
        if (get_magic_quotes_gpc()) 
        {
            $output = stripslashes($output);
        }
        // $output = strip_tags($output);
        $output = htmlentities($output, ENT_QUOTES, 'UTF-8');
    }
// return the clean text
    return $output;
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

// 获取当前页面 URL
function current_url()
{
	$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$validURL = str_replace("&", "&amp;", $url);
	return validURL;
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
?>