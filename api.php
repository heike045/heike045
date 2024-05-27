<?php

 require("include/global.php");
 $action = isset($_GET['act']) ? addslashes($_GET['act']) : '';
 
 //卡密登入
 if($action == 'login'){
	$kami = isset($_GET['kami']) ? addslashes($_GET['kami']) : '';
	$imei = isset($_GET['imei']) ? addslashes($_GET['imei']) : '';
	if($kami == '') exit('100');//卡密为空
	if($imei == '') exit('101');//机器码为空
	$KMtime = array(
		'TK'=>24*3600,
		'ZK'=>7*24*3600,
		'YK'=>30*24*3600,
		'BNK'=>180*24*3600,
		'NK'=>365*24*3600
	);
	
	$date = time();
	$sql="select * from kami where kami='$kami'";
	$query=$db->query($sql);
	$khave=$db->fetch_array($query);
	$KMtype = $khave['type'];
	if(!$khave) exit('102');//卡密错误，不存在
	if($khave['new']!='y'){
		if($khave['imei']!=$imei) exit('103');//机器码有误
		//$udata = exit('200');
		
		$udata = array(
			'code'=>200,
			'kami'=>$khave['kami'],
			'vip'=>$khave['vip_time'],
			'date' => $khave['date'],
			'imei' => $khave['imei'],
			'yingyong' => $khave['yingyong']
		);
		
	}else{
		if($KMtype == 'YJK'){
			$sql="UPDATE `kami` SET `new`='n',`vip_time`='999999999',`date`='$date',`imei`='$imei' WHERE kami='$kami'";
		}else{
			$vip = time()+$KMtime[$KMtype];
			$sql="UPDATE `kami` SET `new`='n',`vip_time`=$vip,`date`='$date',`imei`='$imei' WHERE kami='$kami'";
		}
		$query=$db->query($sql);
		if($query){
			$sql="select * from kami where kami='$kami'";
			$query=$db->query($sql);
			$khave=$db->fetch_array($query);
			//$udata = exit('200');
			
			$udata = array(
				'code'=>200,
				'kami'=>$khave['kami'],
				'vip'=>$khave['vip_time'],
				'date' => $khave['date'],
				'imei' => $khave['imei'],
				'yingyong' => $khave['yingyong']
			);
		}
	}
	$jdata = json_encode($udata);
	echo $jdata;
	exit;
 }
 
 
 echo "<h1>Error</h1>";

?>
