<?php
require_once 'db.php';
session_start();
$conn = new mysqli($server_name, $user_name, $password, $database);

if (!isset($_SESSION['Login']) && !$_SESSION['Login'] && $_SESSION['Rola_user'] != 'Admin_d') {
    header('Location: zaloguj.php');
    exit();
}

$limit = 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$offset = ($page - 1) * $limit; 

$search = isset($_GET['search']) ? $_GET['search'] : ''; 

try {
    $search_query = $search ? "WHERE nazwa_szkoly LIKE '%$search%' OR adres_szkoly LIKE '%$search%' or kod_szkoly LIKE '%$search%' " : "";
    $total_sql = "SELECT COUNT(*) as total FROM schools $search_query";
    $total_result = $conn->query($total_sql);
    $total_row = $total_result->fetch_assoc();
    $total_records = $total_row['total'];
    $total_pages = ceil($total_records / $limit); 

    $sql = "SELECT * FROM schools $search_query LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela szkół</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f3f7fa;
        }

        header {
            background: #0066cc;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .main {
            margin: 20px;
            margin-top: 120px;
        }

        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-container input[type="text"] {
            width: 40%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-container button {
            padding: 10px 20px;
            font-size: 16px;
            background: #0066cc;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .search-container button:hover {
            background: #004a99;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background: #0066cc;
            color: white;
        }

        table th,
        table td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table tr:nth-child(even) {
            background: #f9f9f9;
        }

        table tbody tr:hover {
            background: #eaf3ff;
        }

        th {
            text-transform: uppercase;
        }

        .no-data {
            text-align: center;
            margin: 20px;
            font-size: 18px;
            color: #888;
        }

        .pagination {
            text-align: center;
            margin: 20px 0;
        }

        .pagination a {
            display: inline-block;
            margin: 0 5px;
            padding: 10px 20px;
            background: #0066cc;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .pagination a.disabled {
            background: #ccc;
            pointer-events: none;
        }

        .edit-input {
            width: 90%;
        }

        @media (max-width: 768px) {
            .search-container input[type="text"] {
                width: 80%;
            }

            table th, table td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Tabela szkół</h1>
        <?php if (isset($_SESSION['Login']) && $_SESSION['Login'] == true) {
            include 'nav.php';
        } ?>
    </header>
    
    <div class="main">
        <div class="search-container">
            <form method="get" action="">
                <input type="text" name="search" placeholder="Wyszukaj szkołę..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Szukaj</button>
            </form>
        </div>

        <div class="table-container">
            <?php
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<thead>
                        <tr>
                            <th>Id Szkoły</th>
                            <th>Nazwa Szkoły</th>
                            <th>Adres Szkoły</th>
                            <th>Dyrektor Szkoły</th>
                            <th>Zastępca Dyrektora Szkoły</th>
                            <th>Kod Szkoły</th>
                            <th>Pedagog</th>
                            <th>Psychiatra</th>
                            <th>Lekarz</th>
                            <th>Data Dołączenia</th>
                            <th>Typ Szkoły</th>
                            <th>Status Public/Private</th>
                            <th>Internat</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <form method='post' action='Admin_save_or_delete.php?".(isset($_GET['page']) ? "page=" . $_GET['page'] : "") . (isset($_GET['search']) ? (isset($_GET['page']) ? "&" : "") . "search=" . urlencode($_GET['search']) : "") . "'>
                                <td><input class='edit-input' type='text' name='Id_szkoly' value='{$row['Id_szkoly']}' hidden>{$row['Id_szkoly']}</td>
                                <td><input class='edit-input' type='text' name='nazwa_szkoly' value='{$row['nazwa_szkoly']}'></td>
                                <td><input class='edit-input' type='text' name='adres_szkoly' value='{$row['adres_szkoly']}'></td>
                                <td><input class='edit-input' type='text' name='dyrektor_szkoly' value='{$row['dyrektor_szkoly']}'></td>
                                <td><input class='edit-input' type='text' name='zastepca_dyrektora_szkoly' value='{$row['zastepca_dyrektora_szkoly']}'></td>
                                <td><input class='edit-input' type='text' name='kod_szkoly' value='{$row['kod_szkoly']}'></td>
                                <td><input class='edit-input' type='text' name='pedagog' value='{$row['pedagog']}'></td>
                                <td><input class='edit-input' type='text' name='psyhiatra' value='{$row['psyhiatra']}'></td>
                                <td><input class='edit-input' type='text' name='lekarz' value='{$row['lekarz']}'></td>
                                <td><input class='edit-input' type='text' name='data_dolaczenia' value='{$row['data_dolaczenia']}'></td>
                                <td><input class='edit-input' type='text' name='typ_szkoly' value='{$row['typ_szkoly']}'></td>
                                <td><input class='edit-input' type='text' name='status_public_private' value='{$row['status_public_private']}'></td>
                                <td><input class='edit-input' type='text' name='internat' value='{$row['internat']}'></td>
                                <td>
                                    <button type='submit' name='action' value='save'>Zapisz</button>
                                    <button type='submit' name='action' value='delete'>Usuń</button>
                                </td>
                            </form>
                        </tr>";
                }

                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<div class='no-data'>Nie znaleziono szkół.</div>";
            }
            ?>
        </div>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">Poprzednie</a>
            <?php else: ?>
                <a class="disabled">Poprzednie</a>
            <?php endif; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Następne</a>
            <?php else: ?>
                <a class="disabled">Następne</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
