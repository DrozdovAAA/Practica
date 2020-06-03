<!DOCTYPE html>
<html>
<head>
	<title>Вывод датчиков</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
	<script src="/path/to/jquery.ui.draggable.js" type="text/javascript"></script>
</head>
<body>
<?php 

	function time_elapsed_string($datetime,$client , $full = false) {
	$connect = 'true';
    $now = new DateTime($client);
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'г.',
        'm' => 'мес.',
        'w' => 'нед.',
        'd' => 'д.',
        'h' => 'ч.',
        'i' => 'мин.',
        's' => 'сек.',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
        	if(!($v=='сек.')){
        		$connect = 'false';
        	}
            $v = $diff->$k . ' ' . $v;
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    $lastInput = $string ? implode(', ', $string) . ' назад' : 'just now';
    $output['0']=$connect; 
    $output['1']=$lastInput;
    return $output;
	}

	$con = mysqli_connect("localhost", "root", "") or die(mysqli_error($con));// подключение к серверу (адрес 	сервера, пользователь, пароль)
	// выбираем БД
	$db = mysqli_select_db($con,"arduino") or die(mysqli_error($con));
	$res = mysqli_query($con,"SELECT id FROM `test` ORDER BY id DESC LIMIT 1");
	$e1 = mysqli_fetch_assoc($res);
	$y = 'Вы уверены?';
	for($i = 1; $i <= $e1['id']; $i++){
		$insertDate = mysqli_query($con,"UPDATE `test` SET `dateClient`=CURTIME() WHERE id=".$i);
	}
//Создание и заполнеине таблицы
	echo "<table id='first'>
		<tr><th colspan='17'>События на объектах</th></tr>

		<tr>
			<td></td>
 			<th colspan='3'>Датчик открытия дверей</th>
 			<th colspan='3'>Температурный режим</th><th>Отправить на <br>обслуживание</th>
 		</tr>";
	for($i = 1; $i <= $e1['id']; $i++){
		$q = mysqli_query($con,"SELECT * FROM `test` where id=".$i);
		$e = mysqli_fetch_assoc($q);
		$interval = time_elapsed_string($e['date'],$e['dateClient']);
		if($interval['0']=='false' && $e['status'] == 0){
			echo "<tr><th>".$e['name']."</th>";
			echo "<td colspan=6>";
			echo 'Нет связи с устройством. Последний сеанс '.$interval['1'];
			echo "</td><td><form action='index.php' method='GET' onsubmit='return Click1()'><input value=".$i." type='text' name='inTable' style='display: none;'><input id=".$i."   type='submit' value='На обслуживание'></td></form>";
		}else{
			if($e['status'] == 0){
				if($e['door'] != 0){$r = mysqli_query($con,"UPDATE `test` SET `served`= 0 WHERE id=".$i);}
				if($e['temperature'] >= 30){$rT = mysqli_query($con,"UPDATE `test` SET `servedT`= 0 WHERE id=".$i);}
	switch ($e['door']){

		case 1: 
		
		echo "<tr><th>T".$i."</th><td><div style='background-color:red'></div></td><td><p>Открыта</p></td><td>".$e['dateEvent']."</td>";
		if ($e['temperature']<30){echo '<td><div style="background-color:lightgreen"></div></td><td colspan="2"><p><p>'.$e["temperature"].'C<sup>o</sup></p></p></td><td><form action="index.php" method="GET" onsubmit="return Click1()"><input value='.$i.' type="text" name="inTable" style="display: none;"><input id='.$i.'   type="submit" value="На обслуживание"></td></form>';}
		break;
		case 2: echo "<tr><th>T".$i."</th><td><div style='background-color:white'></div></td><td><p>Проблема в соединении</p></td><td>".$e['dateEvent']."</td>";
		if ($e['temperature']<30){echo '<td><div style="background-color:lightgreen"></div></td><td colspan="2"><p><p>'.$e["temperature"].'C<sup>o</sup></p></p></td><td><form action="index.php" method="GET" onsubmit="return Click1()"><input value='.$i.' type="text" name="inTable" style="display: none;"><input id='.$i.'   type="submit" value="На обслуживание"></td></form>';}
		break;
		case 3: echo "<tr><th>T".$i."</th><td><div style='background-color:black'></div></td><td><p>Проблема в датчике</p></td><td>".$e['dateEvent']."</td>";
		if ($e['temperature']<30){echo '<td><div style="background-color:lightgreen"></div></td><td colspan="2"><p><p>'.$e["temperature"].'C<sup>o</sup></p></p></td><td><form action="index.php" method="GET" onsubmit="return Click1()"><input value='.$i.' type="text" name="inTable" style="display: none;"><input id='.$i.'type="submit" value="На обслуживание"></td></form>';}
		break;
		case 0: 
				$r = mysqli_query($con,"UPDATE `test` SET `served`= 1 WHERE id=".$i);
if ($e['temperature']>=30){
echo "<tr><th>T".$i."</th><td><div style='background-color:lightgreen'></div></td><td colspan='2'><p>Закрыта</p></td>";
	}
}



		if ($e['temperature'] == 998 ){echo '<td><div style="background-color:black"></div></td><td></td><td>'.$e["dateEventT"].'</td><td><form action="index.php" method="GET" onsubmit="return Click1()"><input value='.$i.' type="text" name="inTable" style="display: none;"><input id='.$i.'   type="submit" value="На обслуживание"></td></form>';}
				//Ошибка датчика
		else if($e['temperature'] == 999 ){echo '<td><div style="background-color:white"></div></td><td><p><p>'.$e["temperature"].'C<sup>o</sup></p></p></td><td>'.$e["dateEventT"].'</td><td><form action="index.php" method="GET" onsubmit="return Click1()"><input value='.$i.' type="text" name="inTable" style="display: none;"><input id='.$i.'   type="submit" value="На обслуживание"></td></form>';}
		else if ($e['temperature'] >= 32 ){echo '<td><div style="background-color:red"></div></td><td><p><p>'.$e["temperature"].'C<sup>o</sup></p></p></td><td>'.$e["dateEventT"].'</td><td><form action="index.php" method="GET" onsubmit="return Click1()"><input value='.$i.' type="text" name="inTable" style="display: none;"><input id='.$i.'   type="submit" value="На обслуживание"></td></form>';}
		else if ($e['temperature'] >= 30 ){echo '<td><div style="background-color:yellow"></div></td><td><p><p>'.$e["temperature"].'C<sup>o</sup></p></p></td><td>'.$e["dateEventT"].'</td><td><form action="index.php" method="GET" onsubmit="return Click1()"><input value='.$i.' type="text" name="inTable" style="display: none;"><input id='.$i.'   type="submit" value="На обслуживание"></td></form>';
	}else{
		$rT = mysqli_query($con,"UPDATE `test` SET `servedT`= 1 WHERE id=".$i);
	}
			//echo "<p>".$e['temperature']."C<sup>o</sup></p>";	
		echo "</tr>";	
	}

}
}
		echo "</table>";		
	mysqli_close($con);
?>
<script>
	/* $("input[type='submit']").on("click",function(){
	 	if(confirm('?')){
	 		$("form").submit();
	 	}
	 });
	 */
	 function Click1(){
	 	 return confirm('Вы точно хотите отправить объект на обслуживание?');
	 }
	 
	
</script>
</body>
</html>
