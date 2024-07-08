<?php 
session_start();
if (!$_SESSION['Login']) {
    header('Location: Index.php');
    exit();
}
if($_SESSION['Rola_user'] == 'Uczen'){
    header('Location: Index.php');
}

require_once 'db.php';

$conn = new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_error) {
    die('Error connecting to database: '.$conn->connect_error);
}

$sql = 'SELECT `Klasa` FROM `plany lekcji`';
$result = $conn->query($sql);

$rows = [];
if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    $result->free();
} else {
    echo 'Error executing query: '.$conn->error;
}

$times = [
    '07:10 - 07:55', '08:00 - 08:45', '08:50 - 09:35', 
    '09:40 - 10:25', '10:30 - 11:15', '11:20 - 12:05', 
    '12:10 - 12:55', '13:00 - 13:45', '13:50 - 14:35', 
    '14:40 - 15:25'
];
$days = ['Poniedzialek', 'Wtorek', 'Sroda', 'Czwartek', 'Piatek'];



$sql = "SELECT * from users WHERE Rola = 'Nauczyciel'";
$result = $conn->query($sql);
$rows_teachers = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rows_teachers[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plany Lekcji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0; 
            display: flex;
            justify-content: center;
            align-items: flex-start; 
            height: 100vh;
            background-color: #f7f7f7;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            width: 100%;
            margin: 150px 0; 
        }
        .container h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .form-group input[type="text"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group input[type="text"]:focus {
            border-color: #007bff;
        }
        .form-group button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-bottom: 15px;
            color: green;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .label {
            display: none;
            gap: 5px;
        }
        .my_plan{
            width: 100%;
            margin: 0 auto 10px auto;
            padding: 10px 0;
            text-align: center;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .my_plan:hover{
            background-color: #218838;
        }
    </style>
    <script>
        function toggleLabel(checkbox) {
            var labelDiv = checkbox.nextElementSibling;
            if (checkbox.checked) {
                labelDiv.style.display = 'grid';
            } else {
                labelDiv.style.display = 'none';
            }
        }

        function prepareFormData() {
            var days = ['poniedzialek', 'wtorek', 'sroda', 'czwartek', 'piatek'];
            var times = [
                '07:10 - 07:55', '08:00 - 08:45', '08:50 - 09:35', 
                '09:40 - 10:25', '10:30 - 11:15', '11:20 - 12:05', 
                '12:10 - 12:55', '13:00 - 13:45', '13:50 - 14:35', 
                '14:40 - 15:25'
            ];

            var formData = {
                klasa: document.getElementById('klasa').value.trim(),
                poniedzialek: [],
                wtorek: [],
                sroda: [],
                czwartek: [],
                piatek: []
            };

            days.forEach(function(day) {
                var dayData = [];
                times.forEach(function(time) {
                    var checkbox = document.getElementsByName(day + '[' + time + ']')[0];
                    if (checkbox.checked) {
                        var lessonData = {
                            Przedmiot: document.getElementsByName('przedmiot_' + day + '[' + time + ']')[0].value.trim(),
                            Nauczyciel: document.getElementsByName('nauczyciel_' + day + '[' + time + ']')[0].value.trim(),
                            Sala: document.getElementsByName('sala_' + day + '[' + time + ']')[0].value.trim()
                        };
                        dayData.push(lessonData);
                    } else {
                        dayData.push(null); 
                    }
                });
                formData[day] = dayData;
            });

            return formData;
        }

        function submitForm() {
            var formData = prepareFormData();
            console.log(formData); 

            var jsonFormData = JSON.stringify(formData);
            document.getElementById('formDataJson').value = jsonFormData;
            document.getElementById('scheduleForm').submit();
        }
    </script>
</head>
<body>
    <header>
        <h1>Plany Lekcji</h1>
        <?php include 'nav.php'?>
    </header>
    <div class="container">
        <?php if($_SESSION['Rola_user'] == 'Nauczyciel') {
            $is = false;
            foreach($rows_teachers as $row) {
                $is = array_search($_SESSION['user_id'], $row);
            }
        }
        if(isset($is) && $is) :?>
            <a href="Zobacz_plan.php?id=<?= $_SESSION['user_id']?>" class="my_plan">Zobacz swój plan</a>
        <?php endif ?>

        <table>
            <thead>
                <tr>
                    <th>Klasa</th>
                    <th>Odnośnik</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= $row['Klasa'] ?></td>
                        <td><a href="Zobacz_plan.php?klasa=<?= $row['Klasa'] ?>">Zobacz plan klasy <?= $row['Klasa'] ?></a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <table>
            <thead>
                <tr>
                    <th>Nauczyciel</th>
                    <th>Odnośnik</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows_teachers as $row): ?>
                   <tr>
                        <td><?=$row['Imie']." ".$row['Nazwisko']?></td>
                        <td><a href="Zobacz_plan.php?id=<?= $row['id'] ?>">Zobacz plan nauczyciela <?= $row['Imie'] ?></a></td>
                   </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <?php if ($_SESSION['Rola_user'] === 'Admin'): ?>
        <h1>Dodaj Plan Lekcji</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?php print_r($_SESSION['message']); unset($_SESSION['message'])?></div>
        <?php endif; ?>
        <form id="scheduleForm" action="process_form.php" method="post">
            <div class="form-group">
                <label for="klasa">Klasa:</label>
                <input type="text" id="klasa" name="klasa" required>
            </div>
            <div class="tablecontainer">
                <table>
                    <thead>
                        <tr>
                            <th>Godzina</th>
                            <?php foreach ($days as $day): ?>
                                <th><?= $day ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($times as $time): ?>
                            <tr>
                                <td><?= $time ?></td>
                                <?php foreach ($days as $day): ?>
                                    <td>
                                        <input type="checkbox" name="<?= strtolower($day) ?>[<?= $time ?>]" onclick="toggleLabel(this)">
                                        <div class="label">
                                            <?php 
                                            $sql = "SELECT * FROM users_oceny";
                                            $result = $conn->query($sql);
                                            if ($result) {
                                                $rowse = $result->fetch_all(MYSQLI_ASSOC);
                                                $subjectColumns = !empty($rowse) ? array_keys($rowse[0]) : [];
                                                $subjectColumns = array_slice($subjectColumns, 2);
                                            } else {
                                                echo "Error retrieving data: " . $conn->error;
                                                $rowse = [];
                                                $subjectColumns = [];
                                            }
                                            ?>
                                            <select name="przedmiot_<?= strtolower($day) ?>[<?= $time ?>]">
                                                <?php foreach ($subjectColumns as $subject) : ?>
                                                    <option value="<?= $subject ?>"><?= $subject ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                            <?php
                                            $sql = "SELECT * FROM users WHERE Rola = 'Nauczyciel'";
                                            $result = $conn->query($sql);
                                            if ($result) {
                                                $rowsn = $result->fetch_all(MYSQLI_ASSOC);
                                            } else {
                                                echo "Error retrieving data: " . $conn->error;
                                                $rowsn = [];
                                            }
                                            ?>
                                            <select name="nauczyciel_<?= strtolower($day) ?>[<?= $time ?>]">
                                                <?php foreach ($rowsn as $Nauczyciel) : ?>
                                                    <option value="<?= $Nauczyciel['id'] ?>"><?= $Nauczyciel['Imie']." ".$Nauczyciel['Nazwisko']?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <input type="number" name="sala_<?= strtolower($day) ?>[<?= $time ?>]" placeholder="Sala">
                                        </div>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <input type="hidden" id="formDataJson" name="formDataJson">
            <br>
            <div class="form-group">
                <button type="button" onclick="submitForm()">Dodaj Plan</button>
            </div>
        </form>
        <?php endif; ?>
    </div>

</body>
</html>
