<?php

// 应用公共文件

function page_count($count, $page_size) {
	if ($count % $page_size == 0) {
		return $count / $page_size;
	}
	else {
		return floor($count / $page_size) + 1;
	}
}

//合并ArrayList
//$array(array) 这个$array里面是要合并的array
function array_merger($array) {
	for($i = 0; $i < count($array); $i++) {
		$childArray = $array[$i];
		foreach($childArray as $child) {
			$tmpList[] = $child;
		}
	}
	return $tmpList;
}

//----过滤危险字符(string)----
//$str(string)目标字符
function replace_sql($str) {
	$str = trim(strtolower($str));	
	$str = str_replace("<", "&lt;", $str);	
	$str = str_replace(">", "&gt;", $str);	
	$str = str_replace(" ", "", $str);	
	$str = str_replace("'", "", $str);	
	$str = str_replace(";", "", $str);	
	$str = str_replace("select", "", $str);	
	$str = str_replace(" and ", "", $str);	
	$str = str_replace("exec", "", $str);	
	$str = str_replace("insert", "", $str);	
	$str = str_replace("delete", "", $str);	
	$str = str_replace("update", "", $str);	
	$str = str_replace("count", "", $str);	
	$str = str_replace("*", "", $str);	
	$str = str_replace("%", "", $str);	
	$str = str_replace("chr", "", $str);	
	$str = str_replace("char", "", $str);	
	$str = str_replace("mid", "", $str);	
	$str = str_replace("master", "", $str);	
	$str = str_replace("truncate", "", $str);	
	$str = str_replace("declare", "", $str);
	$str = trim($str);
	return $str;
}

//获取随机字符(string)
//$type(int)返回类型(1-7)
//$length(int)字符长度
function rand_str($type, $length) {
	$str1 = "a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z";
	$str2 = "A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
	$str3 = "0,1,2,3,4,5,6,7,8,9";
	
	if ($type == 1) {
		$content = $str1;
	}
	else if ($type == 2) {
		$content = $str2;
	}
	else if ($type == 3) {
		$content = $str3;
	}
	else if ($type == 4) {
		$content = $str1 . "," . $str2;
	}
	else if ($type == 5) {
		$content = $str2 . "," . $str3;
	}
	else if ($type == 6) {
		$content = $str1 . "," . $str3;
	}
	else if ($type == 7) {
		$content = $str1 . "," . $str2 . "," . $str3;
	}
	
	$strs = explode(",", $content);
	$output = "";
	for($i = 0; $i < $length; $i++) { 
		do {
			$r = rand(0, strlen($content));
		}
		while(empty($strs[$r]));

		$output .= $strs[$r];
	}
	
	return $output;
} 

//----截取字符串(string)----
//$sourcestr(string)目标字符
//$cutlength(int)截取长度
function get_string($str, $len = 12, $dot = true) {
	$i = 0;
    $l = 0;
    $c = 0;
    $a = [];
    while ($l < $len) {
		$t = substr($str, $i, 1);
        
		if (ord($t) >= 224) {
			$c = 3;
            $t = substr($str, $i, $c);
            $l += 2;
        }		
		elseif (ord($t) >= 192) {
			$c = 2;
            $t = substr($str, $i, $c);
            $l += 2;
        }
		else {
			$c = 1;
            $l++;            
		}
            
		// $t = substr($str, $i, $c);
        $i += $c;
        
		if($l > $len) 
			break;
        $a[] = $t;
    }
    
	$re = implode('', $a);
    if (substr($str, $i, 1) !== false) {
		array_pop($a);
        
		($c == 1) and array_pop($a);
        $re = implode('', $a);
        $dot and $re .= '...';
    }
    
	return $re;
}

//生成缩略图(void)
//$srcFile(string)源图路径
//$toFile(string)缩略图路径
//$toW(int)目标路径
//$toH(int)目标路径
//$file_type(string)图片文件的类型
function image_resize($srcFile, $toFile, $toW, $toH, $file_type = "png") {
	if($toFile == "") $toFile = $srcFile;

	$info = "";
	$data = getimagesize($srcFile, $info);
	
	if($file_type == "png") {
		$im = imagecreatefrompng($srcFile);
	}
	elseif($file_type == "gif") {
		if(!function_exists("imagecreatefromgif")) {
			echo "你的GD库不能使用GIF格式的图片，请使用Jpeg或PNG格式！<a href='javascript:go(-1);'>返回</a>";
			exit();
		}
		$im = imagecreatefromgif($srcFile);
	}
	else {
		if(!function_exists("imagecreatefromjpeg")) {
			echo "你的GD库不能使用jpeg格式的图片，请使用其它格式的图片！<a href='javascript:go(-1);'>返回</a>";
			exit();
		}
		$im = imagecreatefromjpeg($srcFile);  
	}
	$srcW = ImageSX($im);
	$srcH = ImageSY($im);
	$toWH = $toW / $toH;
	$srcWH = $srcW / $srcH;
	if($toWH <= $srcWH) {
		$ftoW = $toW;
		$ftoH = $ftoW * ($srcH / $srcW);
	}
	else {
		$ftoH = $toH;
		$ftoW = $ftoH * ($srcW / $srcH);
	}  
	if($srcW > $toW || $srcH > $toH) {
		if(function_exists("imagecreatetruecolor")) {
			@$ni = imagecreatetruecolor($ftoW, $ftoH);
			if($ni) 
				imagecopyresampled($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
			else {
				$ni = imagecreate($ftoW, $ftoH);
				imagecopyresized($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
			}
		}
		else {
			$ni = imagecreate($ftoW, $ftoH);
			imagecopyresized($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
		}
				
		if($file_type == "png") imagepng($ni, $toFile);
		elseif($file_type == "gif") imagegif($ni, $toFile);
		else {
			if(function_exists('imagejpeg')) 
				imagejpeg($ni, $toFile);
		}

		imagedestroy($ni);
	}
	imagedestroy($im);
}

//Html编码
function html_encoding($s) {
	if(!empty($s)) {
		$s = str_replace("<", "&lt;", $s);
		$s = str_replace(">", "&gt;", $s);
		$s = str_replace(" ", "&nbsp;", $s);
		$s = str_replace("\"", "&quot;", $s);
		return $s;
	}
	else	
		return null;	
}

//Html解码
function html_decoding($s) {
	if(!empty($s)) {
		$s = str_replace("&lt;", "<", $s);
		$s = str_replace("&gt;", ">", $s);
		$s = str_replace("&nbsp;", " ", $s);
		$s = str_replace("&quot;", "\"", $s);
		return $s;
	}
	else	
		return null;	
}

//检测一个数组里面是否存在目标对象
function array_exists($arr, $item) {
	$exists = false;
	foreach($arr as $item2) {
		if($item2 == $item) {
			$exists = true;
			break;
		}
	}

	return $exists;
}

//随机颜色码
//是否在前面加上#号
function rand_color($s = false) {
	$string = "0123456789ABCDEF";
	$rand = '';
	for($i = 0; $i < 6; $i++) {
		$rand .= substr($string, mt_rand(0, strlen($string) - 1), 1);
	}
	if($s) {
		$color = "#" . $rand;
	}
	else {
		$color = $rand;
	}
	return $color;
}

//生成随机颜色的数组（不重复）
function rand_colors($length = 10, $s = false) {
	$arr = [];
	for($i = 0; $i < $length; $i++) {
		$tmp = "";
		do {
			$tmp = rand_color($s);			
			$arr[$i] = $tmp;
		}
		while(!in_array($tmp, $arr));
	}
	return $arr;
}

//获取数组里面最大的值
//$arr:array<double>
function arr_max_val($arr = []) {
	$c = count($arr);
	if($c == 0)
		return 0;	
	
	$tmpVal = 0;
	for($i = 0; $i < $c; $i++) {
		if($i == 0)
			$tmpVal = max($arr[$i], $arr[$i+1]);		
		
		if($i > 0 && $i + 1 < $c)
			$tmpVal = max($tmpVal, $arr[$i + 1]);		
	}
	
	return $tmpVal;
}

//xml转换成json
function xml_to_json($source) {  
	if(is_file($source))             //传的是文件，还是xml的string的判断  
		$xml_array = simplexml_load_file($source);	
	else
		$xml_array = simplexml_load_string($source);
	
	$json = json_encode($xml_array);  //php5，以及以上，如果是更早版本，請下載JSON.php  
	return $json;  
}  

//json转换成xml
function json_to_xml($source, $charset = 'utf-8') {
	if(empty($source))
		return false;  
	
	$array = json_decode($source);  //php5，以及以上，如果是更早版本，請下載JSON.php  
	$xml  = '<?xml version="1.0" encoding="' . $charset . '"?>';  
	$xml .= change($array);  
	return $xml;  
}     
function change($source) {  
	$string = ""; 
	foreach($source as $k => $v) { 
		$string .= "<" . $k . ">"; 
		if(is_array($v) || is_object($v)) {       //判断是否是数组，或者，对像 
			$string .= change($v);        //是数组或者对像就的递归调用 
		}
		else { 
			$string .= $v;                        //取得标签数据 
		} 
		$string .= "</" . $k .">";  
	}  
	return $string;  
}  

//生成连续日期(按日)
function date_range($d1, $d2) {
	$timestamp1 = strtotime($d1);
	$timestamp2 = strtotime($d2);
	if($timestamp1 == $timestamp2) return;
	if($timestamp1 > $timestamp2) return '日期错误';
	$n = round(($timestamp2 - $timestamp1) / 3600 / 24);
	$y = date('Y', $timestamp1);
	$m = date('m', $timestamp1);
	$d = date('d', $timestamp1);
	$arr = [];
	for($i = 0; $i < $n + 1; $i++) {
		$arr[] = date('Y-m-d', mktime(0, 0, 0, $m, $d + $i, $y));
	}
	return $arr;
}

//返回相对大的日期
function date_max($d1, $d2) {
	$dt1 = strtotime($d1);
	$dt2 = strtotime($d2);
	if($dt1 == $dt2) {
		//相等
		return $d1;
	}
	elseif($dt1 > $dt2) {
		//1大于2
		return $d1;
	}
	elseif($d2 > $d1) {
		//2大于1
		return $d2;
	}
}
//返回相对小的日期
function date_min($d1, $d2) {
	$dt1 = strtotime($d1);
	$dt2 = strtotime($d2);
	if($dt1 == $dt2) {
		//相等
		return $d1;
	}
	elseif($dt1 < $dt2) {
		//1小于2
		return $d1;
	}
	elseif($d2 < $d1) {
		//2小于1
		return $d2;
	}
}

//字符过滤
function str_filter($str) {
	$str = htmlspecialchars($str, ENT_QUOTES);
	return $str;
}

//删除数组中的一个元素
function array_remove_value(&$arr, $var) {
	foreach ($arr as $key => $value) {
		if(is_array($value))
			array_remove_value($arr[$key], $var);		
		else {
			$value = trim($value);
			if($value == $var)
				unset($arr[$key]);			
			else
				$arr[$key] = $value;			
		}
	}
	
	$tmp_arr = [];
	foreach($arr as $value)
		$tmp_arr[] = $value;
	
	$arr = $tmp_arr;
	unset($tmp_arr);
}

function dir_path($path) { 
	$path = str_replace('\\', '/', $path); 
	if (substr($path, -1) != '/') $path = $path . '/'; 
	return $path; 
}
	
function dir_list($path, $exts = '', $list = []) { 
	$path = dir_path($path); 
	$files = glob($path . '*'); 
	foreach($files as $v) { 
		if (!$exts || preg_match("/\.($exts)/i", $v)) { 
			$list[] = $v; 
			if (is_dir($v)) { 
				$list = dir_list($v, $exts, $list); 
			} 
		} 
	} 
	return $list; 
} 

function deldir($dir) {
	//先删除目录下的文件：
	$dh = opendir($dir);
	while ($file = readdir($dh)) {
		if($file != "." && $file != "..") {
			$fullpath = $dir . "/" . $file;
			if(!is_dir($fullpath)) {
				unlink($fullpath);
			} 
			else {
				deldir($fullpath);
			}
		}
	}

	closedir($dh);
	//删除当前文件夹：
	return rmdir($dir);
}

/**
 * 验证输入的邮件地址是否合法
 *
 * @access  public
 * @param   string      $email      需要验证的邮件地址
 *
 * @return bool
 */
function is_email($user_email) {
    $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
    if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false) {
    	return preg_match($chars, $user_email);
    }
	else {
        return false;
    }
}

/**
 * 验证输入的手机号是否合法
 *
 * @access  public
 * @param   string      $mobile      需要验证的手机号
 *
 * @return bool
 */
function is_mobile($mobile) {
    $chars = "/^0?1[3|4|5|6|7|8][0-9]\d{8}$/";
    return preg_match($chars, $mobile);
}

//公用格式的返回json函数
function res_result($data = NULL, $code = 0, $msg = NULL) {
	
	$res = [
		"data" => $data,
		"code" => $code,
		"msg" => $msg,
		"time" => time()
	];
	
	return $res;
}

function http_post($url, $postdata) {
	$header = [
		"User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36"
	];
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
}

function http_post_json($url, $postdata) {
	$data_string = json_encode($postdata, JSON_UNESCAPED_UNICODE);
	
	$header = [
		"User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36",
		"Content-Type: application/json; charset=utf-8",
		"Content-Length: " . strlen($data_string)
	];
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
}
