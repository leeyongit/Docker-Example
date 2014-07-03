<?php

// PHP stdClass to Array and Array to stdClass – stdClass Object 

// Function to Convert stdClass Objects to Multidimensional Arrays
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

// Function to Convert Multidimensional Arrays to stdClass Objects
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
