<?php 
session_start();
if (!$_SESSION['Login']) {
    header('Location: Index.php');
    exit();
}

if (!isset($_GET['klasa']) && !isset($_GET['id'])) {
    die('Brak wybranej klasy.');
}

$klasa = $_GET['klasa'] ?? '';
$id = $_GET['id'] ?? '';

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

foreach ($days as $day) {
    $filteredLessons = array_fill(0, count($times), []); 

    foreach ($planed as $plan_) {
        if (isset($plan_[$day])) {
            $lessons = json_decode($plan_[$day], true);

            foreach ($lessons as $index => $lesson) {
                if ($id != '' && isset($lesson['Nauczyciel']) && $lesson['Nauczyciel'] == $id) {
                    $lesson['Klasa'] = $plan_['Klasa'];
                    $filteredLessons[$index][] = $lesson;
                    $nauczyciel = true;
                } elseif ($id == '' && $klasa != '') {
                    $lesson['Klasa'] = $plan_['Klasa']; 
                    $filteredLessons[$index][] = $lesson;
                }
            }
        }
    }

    $filteredPlan[$day] = $filteredLessons;
}
if($nauczyciel){
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
    </style>
</head>
<body>
<header>
    <h1><?=$klasa == '' ? "Plan lekcji dla ".$row12['Imie']." ".$row12['Nazwisko'] : "Plan lekcji dla klasy ".$klasa ?></h1>
    <?php include "nav.php"?>
</header>
<?php if ($plan): ?>
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
            <?php foreach ($times as $index => $time): ?>
                <tr>
                    <td class="time-slot"><?=$time?></td>
                    <?php foreach ($days as $day): ?>
                        <td>
                            <?php 
                            $dayPlan = isset($plan[$day]) ? $plan[$day] : [];
                            if (isset($dayPlan[$index]) && is_array($dayPlan[$index])) {
                                foreach ($dayPlan[$index] as $lesson) {
                                    if (count($lesson) >= 2) {
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
                                                echo $lesson['Przedmiot'] . " - " . $lesson['Klasa'] . "<br> Sala: <b> " . $lesson['Sala'] . "</b> <br>";
                                            } else if ($klasa != '') {
                                                echo $lesson['Przedmiot'] . " - " . $lesson['Sala'] . "<br> <b>" . $teacherName . "</b> <br>";
                                            }else{
                                                echo '-';
                                            }
                                    }else{
                                        echo "-";
                                    }
                                }
                            } else {
                                echo "-";
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
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
</body>
</html>
<?php $conn->close(); ?>
