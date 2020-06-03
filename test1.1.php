<!DOCTYPE html>
<html>
<head>
    <title>Вывод датчиков</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style2.1.css">
    <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
</head>
<body>
<body>  
     <?php if (isset($_GET['name'])){

	echo "<table class='vit'>
	<tr><th colspan='20'>Мониторинг объектов</th></tr>
	<tr><td></td><th colspan='2'>Датчик открытия дверей</th><th colspan='2'>Температурный режим</th></tr>
	<tr>
		<th>";
		}else{
			echo "<table class='vit'>
	<tr><th colspan='20'>Мониторинг объектов</th></tr>
	<tr><th colspan='2'>Датчик открытия дверей</th><th colspan='2'>Температурный режим</th></tr>
	<tr><td colspan='4'>Нет показания датчиков, выберите объект</td></tr>
	";
		}?><?php 
			if (isset($_GET['name'])){
				echo htmlspecialchars($_GET['name']); 
				$t = htmlspecialchars($_GET['name']);}?></th>	
		<?php 
			if(isset($_GET['name'])){
			$q1 = mysqli_query($con,"SELECT * FROM `test` where name='$t'");
			$e = mysqli_fetch_assoc($q1);
		switch ($e['door']){
		case 1: echo "<td><div style='background-color:red'></div></td><td><p>Открыта</p></td>";
		break;
		case 2: echo "<td><div style='background-color:white'></div></td><td><p>Проблема в соединении</p></td>";
		break;
		case 3: echo "<td><div style='background-color:black'></div></td><td><p>Проблема в датчике</p></td>"; 		break;
		case 0: echo "<td><div style='background-color:lightgreen'></div></td><td><p>Закрыта</p></td>";
		break;
		
}
if ($e['temperature'] == 998 ){echo '<td><div style="background-color:black"></div></td>';}
				//Ошибка датчика
		else if($e['temperature'] == 999 ){echo '<td><div style="background-color:white"></div></td><td><p><p>'.$e["temperature"].'C<sup>o</sup></p></p></td></td>';}
		else if ($e['temperature'] >= 60 ){echo '<td><div style="background-color:red"></div></td><td><p><p>'.$e["temperature"].'C<sup>o</sup></p></p></td>';}
		else if ($e['temperature'] >= 30 ){echo '<td><div style="background-color:yellow"></div></td><td><p><p>'.$e["temperature"].'C<sup>o</sup></p></p></td>';}
		else {echo '<td><div style="background-color:lightgreen"></div></td><td><p><p>'.$e["temperature"].'C<sup>o</sup></p></p></td>';}
			//echo "<p>".$e['temperature']."C<sup>o</sup></p>";	
		echo "</tr>";}
			?>
	
		</table>
<script>

</script>
</body>  
</html>  
