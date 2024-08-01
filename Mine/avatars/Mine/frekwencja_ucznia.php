<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$filter_date_from = '';
$filter_date_to = '';
$filter_status = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $filter_date_from = $_POST['filter_date_from'] ?? '';
    $filter_date_to = $_POST['filter_date_to'] ?? '';
    $filter_status = $_POST['filter_status'] ?? '';
}

$conn = new mysqli($server_name, $user_name, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM attendance WHERE user_id = '$user_id'";

if (!empty($filter_date_from)) {
    $sql .= " AND DATE(date) >= '$filter_date_from'";
}

if (!empty($filter_date_to)) {
    $sql .= " AND DATE(date) <= '$filter_date_to'";
}

if (!empty($filter_status)) {
    $sql .= " AND status = '$filter_status'";
}

$sql .= " ORDER BY date DESC";

$result = $conn->query($sql);
if ($result === false) {
    die('Error preparing statement: ' . $conn->error);
}


$attendance_data = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frekwencja Ucznia</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            margin: 120px 0 0 0 ;
            padding: 20px 0 0 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 80%;
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        p {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.1em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
        }
        tbody tr:hover {
            background-color: #f5f5f5;
        }

        .filters {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .filters select {
            padding: 8px;
            font-size: 0.9em;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .filters button {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .filters button:hover {
            background-color: #45a049;
        }
        @media screen and (max-width: 644px) {
            .container form{
                display: grid;
                grid-template: '1fr 1fr 1fr 1fr';
                gap: 5px;
            }
        }
        @media screen  and (max-width: 420px){
            .container form{
                display: grid;
                grid-template: '1fr 1fr';
                gap: 5px;
            }    
            .table-container{
                min-width: 90%;
                overflow: auto;
            }
            header h1{
                padding-right: 100px;
                text-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Frekwencja Ucznia</h1>
        <?php include 'nav.php'; ?>
    </header>
    <div class="container">
        
        <p>Frekwencja dla <?php echo htmlspecialchars($_SESSION['Imie_user'] . " " . $_SESSION['Nazwisko_user']); ?>!</p>

        <form method="post" class="filters">
            <label for="filter_date_from">Od:</label>
            <input type="date" id="filter_date_from" name="filter_date_from" value="<?php echo htmlspecialchars($filter_date_from); ?>">

            <label for="filter_date_to">Do:</label>
            <input type="date" id="filter_date_to" name="filter_date_to" value="<?php echo htmlspecialchars($filter_date_to); ?>">

            <label for="filter_status">Status:</label>
            <select id="filter_status" name="filter_status">
                <option value="">-- Wybierz --</option>
                <option value="Obecny" <?php echo ($filter_status == 'Obecny') ? 'selected' : ''; ?>>Obecny</option>
                <option value="Nieobecny" <?php echo ($filter_status == 'Nieobecny') ? 'selected' : ''; ?>>Nieobecny</option>
                <option value="Spóźniony" <?php echo ($filter_status == 'Spóźniony') ? 'selected' : ''; ?>>Spóźniony</option>
            </select>

            <button type="submit">Filtruj</button>
        </form>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Lekcja</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendance_data as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo htmlspecialchars($row['lekcja']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include 'footer.php' ?>
    <script>
        window.addEventListener('resize', function() {
        let subjects = document.querySelectorAll('.container');
        let footer = document.querySelector('footer');
        
        if (footer) {
            let footerStyles = window.getComputedStyle(footer);
            
            let sizeOfFooter = footerStyles.height;
            
            subjects.forEach(function(subject) {
                subject.style.marginBottom =sizeOfFooter;
            });
        }
    });
    </script>
</body>
</html>
