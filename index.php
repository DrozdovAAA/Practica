<!DOCTYPE html>
<html>
<head>
    <title>Вывод датчиков</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style2.css">
    <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
</head>
<body>
<body>  
<article id='result_form'></article>
    <article id="content"></article>  

<?php 
    $con = mysqli_connect("localhost", "root", "") or die(mysqli_error($con));// подключение к серверу (адрес   сервера, пользователь, пароль)
    // выбираем БД
    $db = mysqli_select_db($con,"arduino") or die(mysqli_error($con));
  include('test1.1.php');
    $res = mysqli_query($con,"SELECT id FROM `test` ORDER BY id DESC LIMIT 1");
    $e1 = mysqli_fetch_assoc($res);
        echo '<table class="vit" id="second"><tr><td><form action="index.php" id="osForm" method="get"><input id="placeholder" placeholder="Введите название территории для подробного описания" type="text" list="cars" name="name"/><datalist id="cars">';
        for($i = 1; $i <= $e1['id']; $i++){
        $q = mysqli_query($con,"SELECT * FROM `test` where id=".$i);
        $e2 = mysqli_fetch_assoc($q);
        echo '<option id='.$i.'>T'.$i.'</option>';
    }
        echo "</datalist><br><input value='Ок' type='submit' id='btn' class='sub'></form></tr></table><br><br>";
//третья таблица
if(isset($_GET['inTable'])) {
    $t = htmlspecialchars($_GET['inTable']);
    $q1 = mysqli_query($con,"SELECT * FROM `test` where id='$t'");
    $e = mysqli_fetch_assoc($q1);
    $r = mysqli_query($con,"UPDATE `test` SET `status` = 1 WHERE ID = '$t'");
    $event = mysqli_query($con,"UPDATE `test` SET `dateService`=CURTIME() WHERE id='$t'");
    }
if(isset($_GET['outTable'])) {
    $t = htmlspecialchars($_GET['outTable']);
    $q1 = mysqli_query($con,"SELECT * FROM `test` where id='$t'");
    $e = mysqli_fetch_assoc($q1);
    $r = mysqli_query($con,"UPDATE `test` SET `status` = 0 WHERE ID = '$t'");
    }
    echo "<table id='three'>
        <tr><th colspan='17'>На обслуживании</th></tr>

        <tr>
            <td></td>
            <th colspan='3'>Датчик открытия дверей</th>
            <th colspan='3'>Температурный режим</th><th colspan='2'>Убрать из списка</th>
        </tr>";
    for($i = 1; $i <= $e1['id']; $i++){

        $q = mysqli_query($con,"SELECT * FROM `test` where id=".$i);
        $e = mysqli_fetch_assoc($q);
if($e['status'] == 1){
 
    if($e['door'] != 0){$r = mysqli_query($con,"UPDATE `test` SET `served`= 0 WHERE id=".$i);}
    if($e['temperature'] >= 30){$rT = mysqli_query($con,"UPDATE `test` SET `servedT`= 0 WHERE id=".$i);}
    switch ($e['door']){
        case 1: echo "
        <tr><th rowspan='2'>T".$i."</th><td rowspan='2'><div style='background-color:red'></div></td><td rowspan='2'><p>Открыта</p></td><th>Время начала события</th>";
        break;
        case 2: echo "<tr><th rowspan='2'>T".$i."</th><td rowspan='2'><div style='background-color:black'></div></td><td rowspan='2'><p>Проблема в датчике</p></td><th>Время начала события</th>";
        break;
        case 3: echo "<tr><th rowspan='2'>T".$i."</th><td rowspan='2'><div style='background-color:black'></div></td><td rowspan='2'><p>Проблема в датчике</p></td><th>Время начала события</th>";
        break;
        case 0: 
       if ($e['temperature'] >= 30 ){
echo "<tr><th rowspan='2'>T".$i."</th><td rowspan='2'><div style='background-color:lightgreen'></div></td><td colspan='2' rowspan='2'><p>Закрыта</p></td>";
$r = mysqli_query($con,"UPDATE `test` SET `served`= 1 WHERE id=".$i);
    }else{
        echo "<tr><th rowspan='2'>T".$i."</th><td rowspan='2'><div style='background-color:lightgreen'></div></td><td colspan='2' rowspan='2'><p>Закрыта</p></td>";
}
break;
}
        if($e['temperature'] == 999 ){echo '<td rowspan="2"><div style="background-color:black"></div></td><td rowspan="2"><p><p>Проблема в <br>датчике</p></p></td><th>Время начала события</th><th>Время начала обслуживания</th><td rowspan="2"><form action="index.php" method="GET" onsubmit="return Click2()"><input value='.$i.' type="text" name="outTable" style="display: none;"><input id='.$i.'   type="submit" value="Снять с обслуживания"></td></form></tr>';}
        else if ($e['temperature'] >= 32 ){echo '<td rowspan="2"><div style="background-color:red"></div></td><td rowspan="2"><p><p>'.$e["temperature"].'C<sup>o</sup></p></p></td><th>Время начала события</th><th>Время начала обслуживания</th><td rowspan="2"><form action="index.php" method="GET" onsubmit="return Click2()"><input value='.$i.' type="text" name="outTable" style="display: none;"><input id='.$i.'   type="submit" value="Снять с обслуживания"></td></form></tr>';}
        else if ($e['temperature'] >= 30 ){echo '<td rowspan="2"><div style="background-color:yellow"></div></td><td rowspan="2"><p><p>'.$e["temperature"].'C<sup>o</sup></p></p></td><th>Время начала события</th><th>Время начала обслуживания</th><td rowspan="2"><form action="index.php" method="GET" onsubmit="return Click2()"><input value='.$i.' type="text" name="outTable" style="display: none;"><input id='.$i.'   type="submit" value="Снять с обслуживания"></td></form></tr>';}
        else if($e['temperature'] < 30 && $e['door'] != 0){echo '<td rowspan="2"><div style="background-color:lightgreen"></div></td><td
         colspan="2" rowspan="2"><p><p>'.$e["temperature"].'C<sup>o</sup></p></p></td><th>Время начала обслуживания</th><td rowspan="2"><form action="index.php" method="GET" onsubmit="return Click2()"><input value='.$i.' type="text" name="outTable" style="display: none;"><input id='.$i.'   type="submit" value="Снять с обслуживания"></td></form></tr>';
        }else{
            echo '<td rowspan="2"><div style="background-color:lightgreen"></div></td><td
         colspan="2" rowspan="2"><p><p>'.$e["temperature"].'C<sup>o</sup></p></p></td><th>Время начала обслуживания</th><td rowspan="2"><form action="index.php" method="GET" onsubmit="return Click2()"><input value='.$i.' type="text" name="outTable" style="display: none;"><input id='.$i.'   type="submit" value="Снять с обслуживания"></td></form></tr>';
         }
    if ($e['door'] != 0 && $e['temperature'] >= 30 ) {
        echo '<tr><td>'.$e["dateEvent"].'</td><td>'.$e["dateEventT"].'</td><td>'.$e["dateService"].'</td></tr>'; 
    }
    if ($e['door'] != 0 && $e['temperature'] < 30 ) {
        echo '<tr><td>'.$e["dateEvent"].'</td><td>'.$e["dateService"].'</td></tr>';     
    }
    if ($e['door'] == 0 && $e['temperature'] >= 30 ) {
                echo '<tr><td>'.$e["dateEventT"].'</td><td>'.$e["dateService"].'</td></tr>';     
    }
    if ($e['door'] == 0 && $e['temperature'] < 30 ) {
                echo '<tr><td>'.$e["dateService"].'</td></tr>';     
    }
    }
}
echo '</table>';
            
    mysqli_close($con);
?>

    
    <script> 
        function Click2(){
         return confirm('Вы точно хотите снять объект с обслуживания?');
     }

    function show()  
        {  
            $.ajax({  
            
                url: "test.php",  
                cache: false,  
                success: function(html){  
                    $("#content").html(html); 
                } 
            });  
        }   
        $(document).ready(function(){ 
        
            show();  
            setInterval('show()',5000);  
        });  

    
    </script>  


</body>  
</html>  
