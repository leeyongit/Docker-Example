<?php
// cURL功能
function xcurl($url,$ref=null,$post=array(),$ua="Mozilla/5.0 (X11; Linux x86_64; rv:2.2a1pre) Gecko/20110324 Firefox/4.2a1pre",$print=false) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    if(!empty($ref)) {
        curl_setopt($ch, CURLOPT_REFERER, $ref);
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 是否获取跳转后的页面
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 将会以字符串的形式返回那个cURL句柄获取的内容
    if(!empty($ua)) {
        curl_setopt($ch, CURLOPT_USERAGENT, $ua); // 修改User-Agent 伪造浏览器和操作系统信息
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

?>
