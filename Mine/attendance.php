<?php
session_start();
if (!isset($_SESSION['Login']) || $_SESSION['Rola_user'] == 'Uczen') {
    header('Location: zaloguj.php');
    exit();
}

require_once 'db.php';
$conn = new mysqli($server_name, $user_name, $password, $database);
if ($conn->connect_errno != 0) {
    echo "Error: " . $conn->connect_error;
} else {
    $user_id = isset($_GET['id']) ? $conn->real_escape_string($_GET['id']) : '';

    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit();
    }

    $sql_attendance = "SELECT * FROM attendance WHERE user_id = '$user_id' ";

    $result_attendance = $conn->query($sql_attendance);
    $attendance = [];
    if ($result_attendance->num_rows > 0) {
        while ($row = $result_attendance->fetch_assoc()) {
            $attendance[] = $row;
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['Update_frekwencja'])) {
            $cum = $_POST['Update_frekwencja'];
            $id = $_POST['id'];
            $sql = "UPDATE attendance SET status = '$cum' WHERE id = '$id'";
            $result = $conn->query($sql);
            if (!$result) {
                echo "WYSTĄPIŁ BŁĄD";
            } else {
                header("Location: attendance.php?id={$_GET['id']}");
            }
        }
        if (isset($_POST["ids"])) {
            $id = $_POST["ids"];
            $sql = "DELETE FROM attendance WHERE id = '$id'";
            $result = $conn->query($sql);
            if (!$result) {
                echo "WYSTĄPIŁ BŁĄD";
            } else {
                header("Location: attendance.php?id={$_GET['id']}");
            }
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan frekwencji - <?= htmlspecialchars($user['Imie'] . ' ' . $user['Nazwisko']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            font-size: 0.9em;
        }
        .container {
            width: 90%;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
            white-space: nowrap;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .message {
            color: red;
            margin-top: 10px;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        form {
            display: inline-block;
        }
        select {
            padding: 5px;
            margin-right: 10px;
        }
        input[type="submit"] {
            padding: 5px 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Plan frekwencji - <?= htmlspecialchars($user['Imie'] . ' ' . $user['Nazwisko']) ?></h2>
        <table>
            <tr>
                <th>Data</th>
                <th>Lekcja</th>
                <th>Status</th>
                <th>Zaktualizuj</th>
            </tr>
            <?php foreach ($attendance as $record): ?>
            <tr>
                <td><?= htmlspecialchars($record['date']) ?></td>
                <td><?= htmlspecialchars($record['lekcja']) ?></td>
                <td><?= htmlspecialchars($record['status']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?=$record['id']?>">
                        <select name="Update_frekwencja" onchange="this.form.submit()">
                            <option value="Obecny" <?=$record['status'] == "Obecny" ? 'selected' : ''?>>Obecny</option>
                            <option value="Spóźniony" <?=$record['status'] == "Spóźniony" ? 'selected' : '' ?>>Spóźniony</option>
                            <option value="Nieobecny" <?=$record['status'] == "Nieobecny" ? 'selected' : '' ?>>Nieobecny</option>
                        </select>
                    </form>
                    <form method="POST">
                        <input type="hidden" name="ids" value="<?=$record['id']?>">
                        <input type="submit" value="Usuń">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <a href="frekfencja.php" class="back-button">Powrót do frekwencji</a>
    </div>
    <?php include 'footer.php' ?>
</body>
</html>
