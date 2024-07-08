<?php 
session_start();

if (!isset($_SESSION['Login'])) {
    header('Location: Index.php');
    exit();
}
require_once 'db.php'; 
$conn = new mysqli($server_name, $user_name, $password, $database);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informacje o tobie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            margin: 120px auto;
            width: 40%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .info {
            margin-bottom: 10px;
        }
        .info strong {
            display: inline-block;
            width: 150px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        thead tr {
            background-color: #f2f2f2;
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #ff6f61;
            color: white;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #ffecd9;
        }

        tbody td {
            color: #333;
        }

        tbody td a {
            color: #ff6f61;
            text-decoration: none;
        }

        tbody td a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Informacje o tobie</h1>
        <?php include 'nav.php'; ?>
    </header>
    <div class="container">

        <div class="info"><strong>Imię:</strong> <?php echo htmlspecialchars($_SESSION['Imie_user']); ?></div>
        <div class="info"><strong>Nazwisko:</strong> <?php echo htmlspecialchars($_SESSION['Nazwisko_user']); ?></div>
        <div class="info"><strong>E-mail:</strong> <?php echo htmlspecialchars($_SESSION['E-mail_user']); ?></div>
        <div class="info"><strong>Rola:</strong> <?php echo htmlspecialchars($_SESSION['Rola_user']); ?></div>
        <?php if ($_SESSION['Rola_user'] == 'Nauczyciel') : ?>
        <div class="info"><strong>Czego uczysz:</strong> <?php echo htmlspecialchars($_SESSION['Czego_uczy_user']); ?></div>
        <div class="info"><strong>Klasa której jestes wychowawcą:</strong> <?php echo htmlspecialchars($_SESSION['Klasa_user']); ?></div>
        <?php endif; ?>
        <?php if ($_SESSION['Rola_user'] == 'Uczen') : ?>
        <div class="info"><strong>Klasa:</strong> <?php echo htmlspecialchars($_SESSION['Klasa_user']); ?></div>
        <?php 

            $klasa = $_SESSION['Klasa_user'];
            $sql = "SELECT * FROM users WHERE Klasa = '$klasa' and Rola = 'Nauczyciel'";
            $result = $conn->query($sql);
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        ?>
        <div class="info"><strong>Wychowawca:</strong> <?php foreach ($rows as $row) {
            echo $row ["Imie"] ." ". $row["Nazwisko"] ." [".$row['E-mail']."] ";
        }?></div>
        <?php endif; ?>

        <?php if ($_SESSION['Klasa_user'] != '') : ?>
            <div class="info">
            <?php 
                $klasa = $_SESSION['Klasa_user'];
                $sql = "SELECT * FROM users WHERE Klasa = '$klasa' and Rola = 'Uczen'";
                $result = $conn->query($sql);
                $rows = [];
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
            ?>
                <strong>Twoja Klasa<br>[<?=count($rows)?> osoby]: </strong>

                <table>
                    <thead>
                        <tr>
                            <th>Imię</th>
                            <th>Nazwisko</th>
                            <th>E-mail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row) : ?>
                            <?php if ($row['id'] !== $_SESSION['user_id']) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['Imie'])?></td>
                                    <td><?= htmlspecialchars($row['Nazwisko'])?></td>
                                    <td><?= htmlspecialchars($row['E-mail'])?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
