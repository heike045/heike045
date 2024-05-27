<?php

 include("include/option.php");
 include("include/http.php");
 
 date_default_timezone_set('PRC');
 $action = isset($_GET['action']) ? addslashes($_GET['action']) : '';
 

 //用户登陆
 if($action == 'login'){
	$user = isset($_POST['user']) ? addslashes($_POST['user']) : '';
	$password = isset($_POST['password']) ? addslashes($_POST['password']) : '';
	$dianshu = isset($_POST['dianshu']) ? addslashes($_POST['dianshu']) : '';
	if(dirname($_SERVER["REQUEST_URI"]) == '\\' || dirname($_SERVER["REQUEST_URI"]) == '/'){
		$av_url = 'http://'.$_SERVER['SERVER_NAME'];
	}else {
		$av_url = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"]);
	}
	if($user == '') exit('101');
	if($password == '') exit('102');
	$pass = $password;
	$sql="select * from user where user='$user' and `password`='$pass'";
	$query=$db->query($sql);
	$have=$db->fetch_array($query);
	if($have){
		if($have['lock']=='y') exit('112');
		$token = md5($user.getcode());
		$sql="UPDATE `user` SET `token`='$token' WHERE user='$user'";
		$query=$db->query($sql);
		if($query){
			if(substr($have['pic'],0,4)=='http'){
				$pic = $have['pic'];
			}else{
				$pic = $av_url.$have['pic'];
			}
			$udata = array(
				'uid'=>$have['uid'],
				'user'=>$have['user'],
				'dianshu'=>$have['dianshu'],
				'vip'=>$have['vip'],
				'token'=>$token
			);
			$jdata = json_encode($udata);
			echo $jdata;
			exit;
		}
	}else{
		exit('110');
	}
 }


 echo "<h1>Error</h1>";


function getIp() {
	$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
	if (!ip2long($ip)) {
		$ip = '';
	}
	return $ip;
}
function getcode(){ 
	$str = null;  
	$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";  
	$max = strlen($strPol)-1;  
	for($i=0;$i<32;$i++){
		$str.=$strPol[rand(0,$max)];
	}  
	return $str; 
}

function md5Sign($prestr, $key) {
	$prestr = $prestr . $key;
	return md5($prestr);
}
?>
