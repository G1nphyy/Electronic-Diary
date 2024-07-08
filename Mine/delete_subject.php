<?php
session_start();
if ($_SESSION['Rola_user'] !== 'Admin') {
    header('Location: Index.php');
    exit();
}

$usun = isset($_POST['usun']) ? $_POST['usun'] : header("Location: change_roles.php?page={$_GET['page']}&search={$_GET['search']}");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuń przedmiot</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        .form-container p {
            margin-bottom: 20px;
            font-size: 18px;
        }
        .form-container input[type="submit"] {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .form-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .form-container input[type="submit"]:last-of-type {
            background-color: #dc3545;
        }
        .form-container input[type="submit"]:last-of-type:hover {
            background-color: #c82333;
        }
        b {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form action="" method="post">
            <p>Czy jesteś pewien, że chcesz usunąć przedmiot <b><?= htmlspecialchars($usun) ?></b>?</p>
            <input type="hidden" name="usun" value="<?= htmlspecialchars($usun) ?>">
            <input type="submit" name="check" value="TAK">
            <input type="submit" name="check" value="NIE">
        </form>
        <?php
            if (isset($_POST['check'])) {
                if ($_POST['check'] === 'TAK') {
                    $usun = $_POST['usun'];
                    if (!empty($usun)) {
                        require_once 'db.php';
                        $conn = new mysqli($server_name, $user_name, $password, $database);
                        if ($conn->connect_error) {
                            die('ERROR: '. $conn->connect_error);
                        } else {
                            $stmt = $conn->prepare("ALTER TABLE `users_oceny` DROP COLUMN `$usun`");
                            if ($stmt) {
                                $stmt->execute();
                                if ($stmt->error) {
                                    echo "Error: " . $stmt->error;
                                } else {
                                    header("Location: change_roles.php?page=" . urlencode($_GET['page']) . "&search=" . urlencode($_GET['search']));
                                    exit();
                                }
                                $stmt->close();
                            } else {
                                echo "Error: " . $conn->error;
                            }
                            $conn->close();
                        }
                    } else {
                        echo "Error: Column name cannot be empty.";
                    }
                } else {
                    header("Location: change_roles.php?page=" . urlencode($_GET['page']) . "&search=" . urlencode($_GET['search']));
                    exit();
                }
            }
        ?>
    </div>
</body>
</html>
