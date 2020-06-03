<?php
$con = mysqli_connect("localhost", "root", "") or die(mysqli_error($con));// подключение к серверу (адрес сервера, пользователь, пароль)
$db = mysqli_select_db($con,"arduino") or die(mysqli_error($con));
 
// выбираем БД
if(isset($_REQUEST["door"])){
	$door = $_REQUEST["door"];
}else{
	$door = "2";
}
if(isset($_REQUEST["temperature"])){
	$temperature = $_REQUEST["temperature"];
}else{
	$temperature = "999";
}
$r = mysqli_query($con,"UPDATE `test` SET `door`='{$door}',`temperature`='{$temperature}',`date`=CURTIME() WHERE id=1");
if($door==1||$door==2){
	$event = mysqli_query($con,"UPDATE `test` SET `dateEvent`=CURTIME() WHERE served=1 AND id=1");
}
if($temperature>=30){
	$event = mysqli_query($con,"UPDATE `test` SET `dateEventT`=CURTIME() WHERE servedT=1 AND id=1");
}

print_r($_REQUEST);
mysqli_close($con);
?>
