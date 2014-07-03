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
