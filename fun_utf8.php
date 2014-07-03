<?php

/**
 * 截取中文UTF-8字符串
 *
 * @param string $str   要截取的字符串
 * @param string $start 中文UTF-8字符串的起始位置
 * @param int    $lenth 要截取中文UTF-8字符串的长度
 * @return string
 */
 function utf8_substr($str, $start, $lenth)
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
function utf8_strlen($str)
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



?>
