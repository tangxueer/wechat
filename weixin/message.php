<?php
function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
    static $recursive_counter = 0;
    if (++$recursive_counter > 1000) {
        die('possible deep recursion attack');
    }
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            arrayRecursive($array[$key], $function, $apply_to_keys_also);
        } else {
            $array[$key] = $function($value);
        }
 
        if ($apply_to_keys_also && is_string($key)) {
            $new_key = $function($key);
            if ($new_key != $key) {
                $array[$new_key] = $array[$key];
                unset($array[$key]);
            }
        }
    }
    $recursive_counter--;
}


function JSON($array) {
	arrayRecursive($array, 'urlencode', true);
	$json = json_encode($array);
	return urldecode($json);
}


$lastID = (int) $_GET['lastID'];
$backValue=array();

$db = new PDO("mysql:host=".SAE_MYSQL_HOST_M.";port=".SAE_MYSQL_PORT.";dbname=".SAE_MYSQL_DB, SAE_MYSQL_USER, SAE_MYSQL_PASS);
$db -> query("SET NAMES 'UTF8'");
    
$wxQuery="SELECT * FROM message WHERE mid > ".$lastID." ORDER BY mid LIMIT  3";
$wxResult=$db->query($wxQuery);

while ($wxRow=$wxResult->fetch()) {
	$recordID = $wxRow[0];
	$fromusername = $wxRow[1];
	$nickname = $wxRow[3];
	$picurl = $wxRow[4];
	$content = $wxRow[5];
	$picture = $wxRow[6];
		     
	$picurl = substr_replace($picurl,"logo",14,3);
	$content = $nickname." : ".$content;
	
	if(empty($picture))
	{       
        $backValue[$recordID] = $content."!"."@".$picurl;       
    }else{
        $picture=substr_replace($picture,"logo",14,3);
	    $backValue[$recordID] = $content."!".$picture."@".$picurl;  
    }
    
}

echo JSON($backValue);


?>