<?php
//$root = str_replace('/index.php','',$_SERVER['SCRIPT_NAME']);
//if ($root != '') header('Location: http://shows.airy.me');

function clean($str) {
	return stripslashes(nl2br($str));

}

function clean_seo($str) {
	$str = str_replace(array('`',"'",'"','&'),' ',$str);
	$str = str_replace(' ','-',$str);
	return $str;
}


?>
