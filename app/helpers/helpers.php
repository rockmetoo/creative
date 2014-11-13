<?php

function yen($amount)
{
    setlocale( LC_MONETARY, "ja_JP.UTF8" );
    return number_format($amount);
}

/**
 * 
 * @return array
 */
function lastSixMonthsName()
{
    $first  = strtotime('first day this month');
    $months = array();
    
    for ($i = 6; $i >= 1; $i--) {
        array_push($months, '"' . date('F', strtotime("-$i month", $first)) . '"');
    }
    
    return $months;
}

/**
 * to convert stdClass objects to multidimensional arrays
 * @param object $object
 */
function objectToArray($object)
{
    if (is_object($object)) {
        $object = get_object_vars($object);
    }

    if (is_array($object)) {
        return array_map(__FUNCTION__, $object);
    } else {
        // XXX: IMPORTANT - Return array
        return $object;
    }
}

/**
 * to convert multidimensional arrays to stdClass objects
 * @param array $array
 */
function arrayToObject($array)
{
    if (is_array($array)) {
        return (object) array_map(__FUNCTION__, $array);
    } else {
        // XXX: IMPORTANT - Return object
        return $array;
    }
}

function arrayToMap($array, $keyName)
{
    $assocArray = array_reduce($array, function ($result, $item) use($keyName)
    {
        $result[$item[$keyName]] = $item;
        return $result;
    }, array());
    
    return $assocArray;
}

/**
 * 
 * @param string $srchvalue
 * @param array $array
 * @return integer parrent array key
 */
function searchMultiDimension($srchvalue, $array)
{
    if (is_array($array) && count($array) > 0) {
        $foundkey = array_search($srchvalue, $array);
        
        if ($foundkey === FALSE) {
            foreach ($array as $key => $value)
            {
                if (is_array($value) && count($value) > 0) {
                    $foundkey = searchMultiDimension($srchvalue, $value);
                    if ($foundkey != FALSE)
                        return $key;
                }
            }
        }
        else
            return $foundkey;
    }
}