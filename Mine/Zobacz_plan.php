<?php 
session_start();
if (!$_SESSION['Login']) {
    header('Location: zaloguj.php');
    exit();
}

if (!isset($_GET['klasa']) && !isset($_GET['id'])) {
    die('Brak wybranej klasy.');
}

$klasa = $_GET['klasa'] ?? '';
$id = $_GET['id'] ?? '';

$current_date = $_GET['current_date'] ?? date('Y-m-d');

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'next') {
        $current_date = date('Y-m-d', strtotime($current_date . ' +1 week'));
    } elseif ($_GET['action'] == 'prev') {
        $current_date = date('Y-m-d', strtotime($current_date . ' -1 week'));
    }
}

require_once 'db.php';

$conn = new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_error) {
    die('Error connecting to database: '.$conn->connect_error);
}

$sql = 'SELECT * FROM `plany lekcji`';
if ($klasa != '') {
    $sql .= " WHERE `Klasa` =  '$klasa'";
}
$result = $conn->query($sql);
$planed = [];
while ($plans = $result->fetch_assoc()) {
    $planed[] = $plans;
}

$days = ['Poniedzialek', 'Wtorek', 'Sroda', 'Czwartek', 'Piatek'];
$times = [
    '07:10 - 07:55', '08:00 - 08:45', '08:50 - 09:35', 
    '09:40 - 10:25', '10:30 - 11:15', '11:20 - 12:05', 
    '12:10 - 12:55', '13:00 - 13:45', '13:50 - 14:35', 
    '14:40 - 15:25'
];
$filteredPlan = [];
$nauczyciel = false;

$startDate = new DateTime($current_date);
foreach ($days as $dayIndex => $day) {
    $filteredLessons = array_fill(0, count($times), []);
    $dayDate = clone $startDate;
    $dayDate->modify("+$dayIndex days");

    foreach ($planed as $plan_) {
        if (isset($plan_[$day])) {
            $lessons = json_decode($plan_[$day], true);

            foreach ($lessons as $index => $lesson) {
                $lesson['Data'] = $dayDate->format('Y-m-d');
                
                if ($id != '' && isset($lesson['Nauczyciel']) && $lesson['Nauczyciel'] == $id) {
                    $lesson['Klasa'] = $plan_['Klasa'];
                    array_push($filteredLessons[$index], $lesson);
                    $nauczyciel = true;
                } elseif ($id == '' && $klasa != '') {
                    $lesson['Klasa'] = $plan_['Klasa'];
                    $filteredLessons[$index][] = $lesson;
                }else{
                    $lesson['Klasa'] = $plan_['Klasa'];
                    $filteredLessons[$index][] = $lesson;
                }
            }
        }
    }
    $filteredPlan[$day] = $filteredLessons;
}


if ($nauczyciel) {
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $result = $conn->query($sql);
    $row12 = $result->fetch_assoc();
}
$plan = $filteredPlan;

function formatLesson($lesson) {
    if (count($lesson) < 1) {
        return '-';
    }
    return $lesson;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usun'])) {
    $sql = "DELETE FROM `plany lekcji` WHERE Klasa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $klasa);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Udało się usunąć plan lekcji klasy $klasa";
        header("Location: dodaj_plan_lekcji.php");
        exit();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$klasa == '' ? "Plan lekcji dla ".$row12['Imie']." ".$row12['Nazwisko'] : "Plan lekcji dla klasy ".$klasa ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding-top: 140px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            margin: 0 auto 20px auto;
            width: 90%;
            border-collapse: collapse;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        th.rotate {
            height: 140px;
            white-space: nowrap;
            min-width: 100px;
        }
        th.rotate > div {
            transform: 
                translate(25px, 51px)
                rotate(315deg);
            width: 30px;
        }
        .time-slot {
            font-weight: bold;
        }
        .button-container {
            text-align: center;
            margin-bottom: 50px;
        }
        .button-container button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .button-container button:hover {
            background-color: #45a049;
        }
        .form-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px 30px;
            background-color: #f0f0f0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            max-width: 400px; 
            width: 100%; 
            box-sizing: border-box;
            display: grid;
            gap: 10px;
        }

        .form-container select, .form-container input {
            width: 100%; 
            padding: 10px;
            margin-top: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; 
        }

        .form-container label {
            display: block;
            margin-top: 10px;
            font-size: 16px;
        }

        .form-container button {
            padding: 12px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .form-container button:hover {
            background-color: #45a049;
        }

        .form-container button:focus {
            outline: none; 
        }
        .form-close{
            position: absolute;
            right: 30px;
            cursor: pointer;
            top: 20px;
            font-size: 2em;
        }
        
        @media screen and (max-width: 829px){
            .table-container {
                min-width: 90%;
                overflow: auto;
            }

        }
        @media screen and (max-width:500px) {
            header h1{
                text-wrap: wrap;
                padding-right: 100px;
            }
        }
    </style>
</head>
<body>
<header>
    <h1><?=@$klasa == '' ? "Plan lekcji dla ".@$row12['Imie']." ".@$row12['Nazwisko'] : "Plan lekcji dla klasy ".@$klasa ?></h1>
    <?php include "nav.php"?>
</header>
<?php if ($plan): ?>
    <h1>Plan lekcji na tydzień rozpoczynający się od: <?=$current_date?></h1>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Godzina</th>
                    <?php foreach ($days as $day): ?>
                        <th class="rotate"><div><?=$day?></div></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($klasa != ""){
                        $sql = "SELECT * FROM zmiany_plan_lekcji WHERE klasa = '$klasa'";
                    }else{
                        $sql = "SELECT * FROM zmiany_plan_lekcji";
                    }
                    
                    $result = $conn->query($sql);
                    $rows = [];
                    while($row = $result->fetch_assoc()){
                        $rows[] = $row;
                    }
                    $przesuniecie = [];
                    foreach ($rows as $row) {
                        $command = $row['co_sie_dzieje'];
                        $command = explode(' ', $command);
                        $Rodzaj = $row['rodzaj'];
                        if ($Rodzaj == 'Przesun'){
                            $przesuniecie[] = [$command[1], $command[2], $row['data'] , $command[0]];
                        }
                    } 


                foreach ($times as $index => $time): ?>
                    <tr>
                        <td class="time-slot"><?=$time?></td>
                        <?php foreach ($days as $day): ?>
                            <td>
                                <?php
                                $dayPlan = isset($plan[$day]) ? $plan[$day] : [];
                                if (isset($dayPlan[$index]) && is_array($dayPlan[$index])) {

                                    foreach ($dayPlan[$index] as $lesson) {
                                        if (count($lesson) >= 3) {
                                            $teacherName = '';
                                            if (isset($lesson['Nauczyciel'])) {
                                                $sql = "SELECT * FROM users WHERE id = ?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bind_param('i', $lesson['Nauczyciel']);
                                                $stmt->execute();
                                                $userResult = $stmt->get_result();
                                                if ($userResult->num_rows > 0) {
                                                    $user = $userResult->fetch_assoc();
                                                    $teacherName = $user['Imie'] . ' ' . $user['Nazwisko'];
                                                }
                                                $stmt->close();
                                            }
                                            
                                            if ($id != '') {
                                                $data = $lesson['Data'];                                            
                                                $sql = "SELECT * FROM zmiany_plan_lekcji WHERE data = '$data'";
                                                $result = $conn->query($sql);
                                                $rows = [];
                                                while($row = $result->fetch_assoc()){
                                                    $rows[] = $row;
                                                }
                                                foreach ($rows as $row) {
                                                    $command = $row['co_sie_dzieje'];
                                                    $command = explode(' ', $command);
                                                    $lekcja_index = $command[0];
                                                    $Rodzaj = $row['rodzaj'];                                                    
                                                    $klasaa = $row['klasa'];
                                                    $datak = $row['data'];

                                                    if ($Rodzaj == 'Odwolaj'){
                                                        $Nauczyciel_odwolany = $command[1];
                                                    }

                                                    if ($Rodzaj == 'Zastepstwo'){
                                                        $id_nauczyciela_zastepstwo = $command[1];
                                                        $przedmiot_zasptepstwo = $command[2];
                                                        $sala_zastepstwo = $command[3];
                                                    }
                                                    if ($lekcja_index == $index){
                                                        break;
                                                    }
                                                }

                                                // echo "$lekcja_index - $index <br> $klasaa - {$lesson['Klasa']} <br> ".@$Nauczyciel_odwolany." - ".@$id_nauczyciela_zastepstwo." - $id <br> " ;
                                                if (
                                                    isset($lekcja_index) && $lekcja_index == $index && 
                                                    $rows && 
                                                    $klasaa == $lesson['Klasa'] && 
                                                    $Rodzaj == 'Zastepstwo' ? 
                                                    (
                                                        (isset($id_nauczyciela_zastepstwo) && $data == $command[1] && $id_nauczyciela_zastepstwo == $id) || 
                                                        (isset($id_nauczyciela_zastepstwo) && $id_nauczyciela_zastepstwo == $id || $lesson['Nauczyciel'] == $id)
                                                    ) 
                                                    : 
                                                    (
                                                        isset($Nauczyciel_odwolany) && $Nauczyciel_odwolany == $id &&
                                                        $lesson['Nauczyciel'] == $id &&
                                                        $datak == $data
                                                    )
                                                ) {    
                                                    switch($Rodzaj) {
                                                        case 'Odwolaj':
                                                            echo "Uczniowie Zwolnieni <br> <del>" . $lesson['Przedmiot'] . " - " . $lesson['Klasa'] . "<br>Sala: <b>" . $lesson['Sala'] . "</b> <br> </del>";
                                                            break;
                                                        case 'Przesun':
                                                            echo "<b>Lekcja przeniesiona</b> <br> <del>" . $lesson['Przedmiot'] . " - " . $lesson['Klasa'] . "<br>Sala: <b>" . $lesson['Sala'] . "</b> <br> </del>";
                                                            break;
                                                        case 'Zastepstwo':
                                                            $text = "";
                                                
                                                            if ($przedmiot_zasptepstwo !== $lesson['Przedmiot']) {
                                                                $text .= "<del>" . $lesson['Przedmiot'] . "</del> " . $przedmiot_zasptepstwo;
                                                            } else {
                                                                $text .= $lesson['Przedmiot'] . " ";
                                                            }
                                                
                                                            $text .= "- " . $lesson['Klasa'] . "<br>";
                                                
                                                            if ($sala_zastepstwo !== $lesson['Sala']) {
                                                                $text .= "Sala: <del>" . $lesson['Sala'] . "</del> " . $sala_zastepstwo . "<br>";
                                                            } else {
                                                                $text .= "Sala: " . $lesson['Sala'] . "<br>";
                                                            }
                                                
                                                            if ($id_nauczyciela_zastepstwo !== $lesson['Nauczyciel']) {
                                                                $sql = "SELECT * FROM users WHERE id = '$id_nauczyciela_zastepstwo'";
                                                                $result = $conn->query($sql);
                                                
                                                                if ($result && $result->num_rows > 0) {
                                                                    $substituteTeacher = $result->fetch_assoc();
                                                                    $text .= "<del><b>" . $teacherName . "</b></del> <b>" . $substituteTeacher['Imie'] . " " . $substituteTeacher['Nazwisko'] . "</b><br>";
                                                                } else {
                                                                    $text .= "<del><b>" . $teacherName . "</b></del><br>";
                                                                }
                                                            }
                                                
                                                            echo $text;
                                                            break;
                                                        default:
                                                            echo "-";
                                                            break;
                                                    }
                                                }else if (
                                                    (   $lesson['Nauczyciel'] == $id 
                                                        || 
                                                        (isset($id_nauczyciela_zastepstwo) && $id == $id_nauczyciela_zastepstwo && 
                                                        $lekcja_index == $index && 
                                                        $datak == $lesson['Data'] && 
                                                        $klasaa == $lesson['Klasa'] && !isset($Rodzaj))
                                                    )
                                                ) {                                                   
                                                    echo $lesson['Przedmiot'] . " - " . $lesson['Klasa'] . "<br> Sala: <b>" . $lesson['Sala'] . "</b> <br>";
                                                }
                                                                                                                                                                                                                                  
                                            } else if ($klasa != '') {
                                                $data = $lesson['Data'];                                            
                                                $sql = "SELECT * FROM zmiany_plan_lekcji WHERE data = '$data' and klasa = '$klasa'";
                                                $result = $conn->query($sql);
                                                $rows = [];
                                                while($row = $result->fetch_assoc()){
                                                    $rows[] = $row;
                                                }
                                                foreach ($rows as $row) {
                                                    $command = $row['co_sie_dzieje'];
                                                    $command = explode(' ', $command);
                                                    $lekcja_index = $command[0];
                                                    $Rodzaj = $row['rodzaj'];                                                                                                   
                                                    if ($Rodzaj == 'Zastepstwo'){
                                                        $id_nauczyciela_zastepstwo = $command[1];
                                                        $przedmiot_zasptepstwo = $command[2];
                                                        $sala_zastepstwo = $command[3];
                                                    }
                                                    if ($lekcja_index == $index){
                                                        break;
                                                    }
                                                }
                                                

                                                if(isset($lekcja_index) && $lekcja_index == $index && $rows){
                                                    switch($Rodzaj){
                                                        case('Odwolaj'):
                                                            echo "Uczniowie Zwolnieni <br> <del>".$lesson['Przedmiot'] . " - " . $lesson['Sala'] . "<br> <b>" . $teacherName . "</b> <br> </del> ";
                                                            break;
                                                        case('Przesun'):
                                                            echo "<b>Lekcja przeniesiona</b>  <br> <del>".$lesson['Przedmiot'] . " - " . $lesson['Sala'] . "<br> <b>" . $teacherName . "</b> <br> </del> ";
                                                            break;
                                                        case 'Zastepstwo':
                                                            $text = "";
                                                            if ($przedmiot_zasptepstwo !== $lesson['Przedmiot']) {
                                                                $text .= "<del>".$lesson['Przedmiot']. '</del> ' . $przedmiot_zasptepstwo;
                                                            } else {
                                                                $text .= $lesson['Przedmiot']. ' ';
                                                            }                                                            
                                                            if ($sala_zastepstwo !== $lesson['Sala']) {
                                                                $text .= "- <del>".$lesson['Sala']. '</del> ' . $sala_zastepstwo. "<br>";
                                                            } else {
                                                                $text .= "- ".$lesson['Sala']. '<br>';
                                                            }
                                                            if ($id_nauczyciela_zastepstwo !== $lesson['Nauczyciel']) {
                                                                $sql = "SELECT * FROM users WHERE id = '$id_nauczyciela_zastepstwo'";
                                                                $result = $conn->query($sql);                
                                                                if ($result && $result->num_rows > 0) {
                                                                    $substituteTeacher = $result->fetch_assoc();
                                                                    $text .= "<del><b>". $teacherName."</b></del> <b> ". $substituteTeacher['Imie']. " ". $substituteTeacher['Nazwisko']. "</b><br>";
                                                                } else {
                                                                    $text .= "<del><b>". $teacherName."</b></del><br>"; 
                                                                }
                                                            } else {
                                                                $text .= "<b>". $teacherName."</b> <br>";
                                                            }                                                        
                                                            echo $text;
                                                            break;
                                                            
                                                            
                                                        default:                                                        
                                                            break;
                                                        }
                                                    
                                                }else{
                                                    echo $lesson['Przedmiot'] . " - " . $lesson['Sala'] . "<br> <b>" . $teacherName . "</b> <br>";
                                                }
                                                
                                                if ($_SESSION['Rola_user'] == 'Admin'){
                                                    $sql = "SELECT * FROM zmiany_plan_lekcji WHERE data = '$data' and klasa = '$klasa'";
                                                    $result = $conn->query($sql);
                                                    $rows = [];
                                                    while ($row = $result->fetch_assoc()) {$rows[] = $row;}
                                                    $zmienione = true;
                                                    foreach ($rows as $row){ 
                                                        $inndex = explode(" ", $row['co_sie_dzieje'])[0];
                                                        
                                                        if (isset($inndex) && $inndex == $index && $rows){                                                                                                              
                                                            switch($row['rodzaj']){
                                                                case 'Odwolaj':
                                                                    ?>
                                                                    <button onclick="Cancer(<?=$row['id']?>)">Anuluj Odwołanie</button>
                                                                    <br>                                                                        
                                                                    <?php
                                                                    $zmienione = false;
                                                                    break;
                                                                case 'Przesun':
                                                                    ?>
                                                                    <button onclick="Cancer(<?=$row['id']?>)">Anuluj Przesunięcie</button>
                                                                    <br>
                                                                    <?php
                                                                    $zmienione = false;
                                                                    break;
                                                                case 'Zastepstwo':
                                                                    ?>
                                                                    <button onclick="Cancer(<?=$row['id']?>)">Anuluj Zastępstwo</button>
                                                                    <br>
                                                                    <?php
                                                                    $zmienione = false;
                                                                    break;
                                                                default:
                                                                    break;
                                                            }
                                                        }   
                                                    }
                                                    if ($zmienione){                                        
                                                        ?>
                                                        <button onclick="CancerLesson('<?=$lesson['Data']?>', <?=$index?>)">Odwołaj</button>
                                                        <button onclick="EdgingLesson('<?=$lesson['Data']?>', <?=$index?>)">Przesuń</button>
                                                        <button onclick="ProxyLesson('<?=$lesson['Data']?>', <?=$index?>)">Zastępstwo</button>
                                                        <br>
                                                        <?php                                                        
                                                    }
                                                    
                                                ?>

                                                    
                                                    <script>
                                                        function Cancer(id){
                                                            var xhr = new XMLHttpRequest();
                                                            xhr.open('POST', 'cancer_lesson_action.php', true);
                                                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                                            xhr.onreadystatechange = function () {
                                                                if (xhr.readyState == 4 && xhr.status == 200) {                                                                    
                                                                    window.location.reload();
                                                                }
                                                            };
                                                            xhr.send('id=' + id);
                                                        }


                                                        function CancerLesson(date, lessonIndex) {
                                                            sendRequest('cancel', date, lessonIndex, null, <?=$lesson['Nauczyciel']?>, null, null);
                                                        }

                                                        function EdgingLesson(date, lessonIndex) {
                                                            var form = document.createElement('div');
                                                            form.classList.add('form-container'); 
                                                            
                                                            var header = document.createElement('div');
                                                            header.classList.add('form-header');
                                                            var h2 = document.createElement('h2');
                                                            h2.textContent = 'Przesuń lekcje'; 
                                                            var close =document.createElement('div');
                                                            close.classList.add('form-close');
                                                            close.innerHTML = "&times;"
                                                            close.addEventListener('click', function(){
                                                                form.style.display = 'none';
                                                            })
                                                            header.appendChild(h2);
                                                            header.appendChild(close);

                                                            form.appendChild(header);

                                                            var label = document.createElement('label');
                                                            label.textContent = 'Nowa data:';
                                                            label.setAttribute('for','newDateTime');
                                                            form.appendChild(label);
                                                            
                                                            var inputDateTime = document.createElement('input');
                                                            inputDateTime.type = 'date';
                                                            inputDateTime.id = 'newDateTime';
                                                            inputDateTime.setAttribute('value', date);
                                                            form.appendChild(inputDateTime);
                                                            
                                                            var label = document.createElement('label');
                                                            label.textContent = 'Lekcja:';
                                                            label.setAttribute('for','Lesson');
                                                            form.appendChild(label);

                                                            var Lesson = document.createElement('select');
                                                            Lesson.id = 'Lesson';
                                                            for (let i = 0; i < 10; i++) {
                                                                var option = document.createElement('option');
                                                                option.textContent = i;
                                                                option.setAttribute('value', i);
                                                                Lesson.appendChild(option);
                                                            }
                                                            form.appendChild(Lesson);


                                                            var button = document.createElement('button');
                                                            button.textContent = 'Przesuń';
                                                            button.onclick = function() {
                                                                var newDateTime = inputDateTime.value.trim();
                                                                var Lesso = Lesson.value;
                                                                if (newDateTime !== '') {
                                                                    sendRequest('reschedule', date, lessonIndex, newDateTime, null, Lesso, null);
                                                                    form.remove(); 
                                                                }
                                                            };
                                                            form.appendChild(button);
                                                            if( document.body.querySelector(".form-container")){
                                                                document.body.removeChild(document.querySelector('.form-container'));                                                    
                                                            }
                                                            document.body.appendChild(form);
                                                            
                                                        }

                                                        function ProxyLesson(date, lessonIndex) {

                                                            var form = document.createElement('div');
                                                            form.classList.add('form-container'); 
                                                            
                                                            var header = document.createElement('div');
                                                            header.classList.add('form-header');
                                                            var h2 = document.createElement('h2');
                                                            h2.textContent = 'Zastępstwo'; 
                                                            var close =document.createElement('div');
                                                            close.classList.add('form-close');
                                                            close.innerHTML = "&times;"
                                                            close.addEventListener('click', function(){
                                                                form.style.display = 'none';
                                                            })
                                                            header.appendChild(h2);
                                                            header.appendChild(close);

                                                            form.appendChild(header);

                                                            var label = document.createElement('label');
                                                            label.textContent = 'Nauczyciel:';
                                                            label.setAttribute('for','newDateTime2');
                                                            form.appendChild(label);
                                                            
                                                            var Nauczyciel = document.createElement('select');
                                                            Nauczyciel.id = 'newDateTime2';

                                                            var Teachers =
                                                            <?php 
                                                                $sql = "SELECT Imie, Nazwisko, id FROM `users` WHERE `rola`  = 'Nauczyciel'";
                                                                $result = $conn->query($sql);                    
                                                                $rows = [];
                                                                while ($row = $result->fetch_assoc()){
                                                                    $rows[] = $row;
                                                                }
                                                                echo json_encode($rows);
                                                            ?>;
                                                            Teachers.forEach(Theacher => {
                                                                var option = document.createElement('option');
                                                                option.innerHTML = Theacher['Imie'] + " " + Theacher['Nazwisko'];
                                                                option.setAttribute('value', Theacher['id']);
                                                                Nauczyciel.appendChild(option);
                                                            });

                                                            form.appendChild(Nauczyciel);
                                                            
                                                            var label = document.createElement('label');
                                                            label.textContent = 'Lekcja:';
                                                            label.setAttribute('for','Lesson2');
                                                            form.appendChild(label);

                                                            var Lekcja = document.createElement('select');
                                                            Lekcja.id = 'Lesson2';

                                                            let Subjects = 
                                                            <?php
                                                                $sql = "SELECT * FROM users_oceny LIMIT 1";
                                                                $result = $conn->query($sql);
                                                                $result = $result->fetch_assoc();
                                                                $result = array_slice($result, 2);
                                                                echo json_encode($result, true);
                                                            ?>;
                                                            
                                                            Object.keys(Subjects).forEach(subject => {
                                                                var option = document.createElement('option');
                                                                option.textContent = subject;
                                                                option.setAttribute('value', subject);
                                                                Lekcja.appendChild(option);
                                                            });
                                                            form.appendChild(Lekcja);


                                                            var label = document.createElement('label');
                                                            label.textContent = 'Sala:';
                                                            label.setAttribute('for','Lesson3');
                                                            form.appendChild(label);

                                                            var Sala = document.createElement('input');
                                                            Sala.type = 'text';                
                                                            Sala.id = 'Lesson3';
                                                            
                                                            form.appendChild(Sala);


                                                            var button = document.createElement('button');
                                                            button.textContent = 'Zastąp';
                                                            button.onclick = function() {
                                                                var Nauczycie = Nauczyciel.value;
                                                                var Sal = Sala.value;
                                                                var Lekcj = Lekcja.value;
                                                                sendRequest('substitute', date, lessonIndex, null, Nauczycie, Lekcj, Sal);
                                                                form.remove(); 
                                                                
                                                            };
                                                            form.appendChild(button);
                                                            if( document.body.querySelector(".form-container")){
                                                                document.body.removeChild(document.querySelector('.form-container'));                                                    
                                                            }
                                                            document.body.appendChild(form);

                                                        }
                                                        function sendRequest(action, date, lessonIndex, newDateTime, Nauczyciel, Lekcja, Sala) {
                                                            var xhr = new XMLHttpRequest();
                                                            xhr.open('POST', 'lesson_action.php', true);
                                                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                                            xhr.onreadystatechange = function () {
                                                                if (xhr.readyState == 4 && xhr.status == 200) {
                                                                    window.location.reload();
                                                                }
                                                            };
                                                            xhr.send('action=' + action + '&date=' + date + '&indexLekcji=' + lessonIndex + '&klasa=' + '<?=$klasa?>' + '&newDateTime=' + newDateTime + '&Nauczyciel=' + Nauczyciel + '&lekcja=' + Lekcja + '&Sala=' + Sala);
                                                        }
                                                    </script>
                                                <?php
                                                }
                                            }else{
                                                echo '-';
                                            }
                                                
                                        }else{                                            
                                                                                                                              
                                        }                                        
                                    }


                                    
                                } else {
                                    echo "-";
                                    
                                }
                                
                                if (isset($dayPlan[$index]) && is_array($dayPlan[$index])) {       
                                    $s = [$dayPlan[$index]];
                                    $dayPlan[$index] = $s;                                                
                                    foreach ($dayPlan[$index] as $lessona) {                                                                                                                              
                                        foreach ($przesuniecie as $command) {
                                            $data_ = $command[0];
                                            $index_ = $command[1]; 
                                            $mk = false;                                                
                                            foreach($lessona as $lesson){
                                                if ($data_ == $lesson['Data'] && $index_ == $index) {
                                                    $mk = true;
                                                }
                                            }                               
                                            if($mk){                                                                                                 
                                                foreach($plan as $dayPlan_){                                                                                                               
                                                    foreach ($dayPlan_ as $indexA => $dayPlana) {                                                         
                                                        foreach ($dayPlana as $lekcjaA) {
                                                            if (count($lekcjaA) >= 0){                                                                
                                                                $lekcjaAData = $lekcjaA['Data'];                                                                     
                                                                if (isset($lekcjaA) && $lekcjaAData == $command[2] && $indexA == $command[3] && isset($lekcjaA['Nauczyciel']) && ($lekcjaA['Klasa'] == $klasa || $lekcjaA['Nauczyciel'] == $id)) {
                                                                    $okok = $lekcjaA['Nauczyciel'];
                                                                    $sql = "SELECT * FROM users WHERE id = '$okok'";
                                                                    $result = $conn->query($sql);
                                                                    $result = $result->fetch_assoc();                                                                        
                                                                    echo "Lekcja przesunięta<br>" . $lekcjaA['Przedmiot'] . " - " . $lekcjaA['Sala'] . "<br> <b>" . $result['Imie']." " . $result['Nazwisko']. "</b> <br>";                                                                        
                                                                    break 5;
                                                                }
                                                            }                                                        
                                                        }                                                                                                                       
                                                    }                                                      
                                                }
                                            }                                                                 
                                        }
                                        
                                    }
                                }
                                ?>
                                
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="button-container">
        <form method="GET">
            <input type="hidden" name="klasa" value="<?=$klasa?>">
            <input type="hidden" name="id" value="<?=$id?>">
            <input type="hidden" name="current_date" value="<?=$current_date?>">
            <button type="submit" name="action" value="prev">Poprzedni tydzień</button>
            <button type="submit" name="action" value="next">Następny tydzień</button>
        </form>
    </div>
    <?php if ($_SESSION['Rola_user'] === 'Admin' and isset($_GET['klasa'])): ?>
    <form action="Zobacz_plan.php?klasa=<?=$_GET['klasa']?>" method="POST">
        <div class="button-container">
            <button name="usun">Usuń Plan lekcji klasy <?=$_GET['klasa']?></button>
        </div>
    </form>

    <?php endif; ?>
<?php else: ?>
    <p>Brak planu lekcji dla wybranej klasy.</p>
<?php endif; ?>
<?php include 'footer.php' ?>
</body>
</html>
<?php $conn->close(); ?>
