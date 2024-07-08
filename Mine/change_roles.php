<?php
session_start();
if (!$_SESSION['Login'] || $_SESSION['Rola_user'] == 'Uczen') {
    header('Location: Index.php');
    exit();
}
require_once 'db.php';
$conn = new mysqli($server_name, $user_name, $password, $database);
if ($conn->connect_errno != 0) {
    echo "Error: " . $conn->connect_error;
} else {
    $search = '';
    $isset = true;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search = isset($_POST['search']) ? $conn->real_escape_string($_POST['search']) : '';
        $_SESSION['search'] = $search;

        $isset = false;
    } elseif (isset($_SESSION['search'])) {
        $search = $_SESSION['search'];
    }
    $_SESSION['klasa_filter'] = isset($_POST['klasa_filter']) ? $_POST['klasa_filter'] : (isset($_SESSION['klasa_filter']) ? $_SESSION['klasa_filter'] : 'all');
    $klasa_filter = $_SESSION['klasa_filter'];

    $_SESSION['rola_filter'] = isset($_POST['rola_filter']) ? $_POST['rola_filter'] : (isset($_SESSION['rola_filter']) ? $_SESSION['rola_filter'] : 'allR');
    $rola_filter = $_SESSION['rola_filter'];

    $allIdsSql = "SELECT id FROM users WHERE Rola != 'Admin'";

    if($_SESSION['Rola_user'] == 'Nauczyciel'){
        $allIdsSql = "SELECT id FROM users WHERE Rola = 'Uczen'";
    }
    
    if ($search) {
        $allIdsSql .= " AND (Imie LIKE '%$search%' OR Nazwisko LIKE '%$search%' OR `E-mail` LIKE '%$search%')";
    }
    if ($klasa_filter != 'all') {
        $Klasa = $klasa_filter;
        $allIdsSql .= "AND users.Klasa LIKE '$Klasa'";
    }else{
        $Klasa = 'all';
    }
    if ($rola_filter != 'allR') {
        $Rola = $rola_filter;
        $allIdsSql .= "AND users.Rola LIKE '$Rola'";
    }else{
        $Rola = 'allR';
    }
    


    $allIdsResult = $conn->query($allIdsSql);
    $allIds = $allIdsResult->fetch_all(MYSQLI_ASSOC);
    $allIds = array_column($allIds, 'id');


    $allClassSql = "SELECT Klasa FROM users WHERE Rola != 'Admin' ORDER BY Klasa";
    if($_SESSION['Rola_user'] == 'Nauczyciel'){
        $allClassSql = "SELECT Klasa FROM users WHERE Rola = 'Uczen' ORDER BY Klasa";
    }
    $allClassResult = $conn->query($allClassSql);
    $allClass = $allClassResult->fetch_all(MYSQLI_ASSOC);
    $allClass = array_column($allClass, 'Klasa');

    $allRolesSql = "SELECT Rola FROM users WHERE Rola != 'Admin' ORDER BY Rola";
    if($_SESSION['Rola_user'] == 'Nauczyciel'){
        $allRolesSql = "SELECT Rola FROM users WHERE Rola = 'Uczen' ORDER BY Rola";
    }
    $allRolesResult = $conn->query($allRolesSql);
    $allRoles = $allRolesResult->fetch_all(MYSQLI_ASSOC);
    $allRoles = array_column($allRoles, 'Rola');


    $_SESSION['ilu_ludzi'] = isset($_POST['ilu_ludzi']) ? ($_POST['ilu_ludzi'] == "WSZYSCY" ? count($allIds) : $_POST['ilu_ludzi']) : (isset($_SESSION['ilu_ludzi']) ? $_SESSION['ilu_ludzi'] : 5);
    $limit = $_SESSION['ilu_ludzi'];
    if($isset){
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $page = $page > 0 ? $page : 1;
    }else{
        $page = 1;
    }
    $offset = ($page - 1) * $limit;

    $sql = "SELECT users.*, users_oceny.*
            FROM users 
            LEFT JOIN users_oceny ON users.id = users_oceny.id_ucznia 
            WHERE users.Rola != 'Admin'";
    if($_SESSION['Rola_user'] == 'Nauczyciel'){
        $sql = "SELECT users.*, users_oceny.*
                FROM users 
                LEFT JOIN users_oceny ON users.id = users_oceny.id_ucznia 
                WHERE users.Rola = 'Uczen'";
    }
    if ($search) {
        $sql .= " AND (users.Imie LIKE '%$search%' OR users.Nazwisko LIKE '%$search%' OR users.`E-mail` LIKE '%$search%')";
    }
    if ($klasa_filter != 'all') {
        $sql .= "AND users.Klasa LIKE '$Klasa'";
    }
    if ($rola_filter != 'allR') {
        $Rola = $rola_filter;
        $sql .= "AND users.Rola LIKE '$Rola'";
    }

    $sql .= " LIMIT $limit OFFSET $offset";

    $result = $conn->query($sql);
    if ($result) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);

        $subjectColumns = !empty($rows) ? array_keys($rows[0]) : [];
        $subjectColumns = array_slice($subjectColumns, 10);
    } else {
        echo "Error retrieving data: " . $conn->error;
        $rows = [];
        $subjectColumns = [];
    }

    $countSql = "SELECT COUNT(*) AS total FROM users WHERE Rola != 'Admin'";
    if($_SESSION['Rola_user'] == 'Nauczyciel'){
        $countSql = "SELECT COUNT(*) AS total FROM users WHERE Rola = 'Uczen'";
    }
    if ($search) {
        $countSql .= " AND (Imie LIKE '%$search%' OR Nazwisko LIKE '%$search%' OR `E-mail` LIKE '%$search%')";
    }
    if ($klasa_filter != 'all') {
        $countSql .= "AND users.Klasa LIKE '$Klasa'";
    }
    if ($rola_filter != 'allR') {
        $Rola = $rola_filter;
        $countSql .= "AND users.Rola LIKE '$Rola'";
    }

    $totalCount = $conn->query($countSql)->fetch_assoc()['total'];
    $totalPages = ceil($totalCount / $limit);

    $prevPage = $page > 1 ? $page - 1 : 1;
    $nextPage = $page < $totalPages ? $page + 1 : $totalPages;


    $_SESSION['selectedIds'] = $_SESSION['selectedIds'] ?? [];
    $selectedIds = $_SESSION['selectedIds'];  

    $bulk = false;
    if($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['Bulk_add_grade'])){
        $bulk = true;
    }

    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela Uczniów</title>
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
            margin: 120px auto 20px auto;
            background: #fff;
            padding: 40px 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow: auto;
            border-radius: 10px;
        }
        .table-container {
            overflow-x: auto; 
            margin-bottom: 20px; 
        }
        table {
            width: auto; 
            min-width: 100%; 
            table-layout: auto;
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
        input[type='number'] {
            width: 60px;
            padding: 5px;
        }
        .oceny {
            display: flex;
            flex-wrap: nowrap;
        }
        .ocena {
            margin: 5px;
            cursor: pointer;
            border: 1px solid #ccc;
            padding: 5px;
            transition: background-color 0.3s ease;
        }
        .ocena1 { background-color: #ffcccc; }
        .ocena2 { background-color: #ffe6cc; }
        .ocena3 { background-color: #ffffcc; }
        .ocena4 { background-color: #e6ffcc; }
        .ocena5 { background-color: #ccffcc; }
        .ocena6 { background-color: #ccffff; }

        .ocena:hover {
            background-color: #e0e0e0; 
        }
        .edit-form {
            display: none;
            margin-top: 10px;
            position: absolute;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 10px;
            border-radius: 5px;
            z-index: 1000;
        }
        .edit-form > * {
            margin: 10px 0;
        }
        .edit-form input[type="number"] {
            width: 50px;
        }
        .form-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .form-inline select, .form-inline input, .form-inline button {
            padding: 5px;
        }
        .pagination {
            display: flex;
            justify-content: center;
        }
        .pagination a {
            padding: 5px 10px;
            margin: 10px
            text-decoration: none;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .pagination .dot{
            padding: 10px 0;
            margin: 0;
        }
        .pagination .page{
            border: none;
            padding: 7px 5px;
            margin: 0;
            user-select: none;
        }
        .pagination .page:hover{
            background-color: transparent;
            color: #333;
        }
        .pagination a:hover {
            background-color: #ddd;
        }
        .actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        form {
            display: inline-block;
        }
        .form-inline {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .actions .form-inline{
            <?php if ($_SESSION['Rola_user'] == 'Nauczyciel') : ?>
            display: grid;
            grid-template: '1fr 1fr';
            <?php endif; ?>
        }
        .form-inline input[type="number"],
        .form-inline select {
            width: auto;
        }
        .form-inline button {
            padding: 5px 10px;
        }
        .message {
            font-weight: bold;
            color: red;
            margin-top: 10px;
        }
        label.disabled {
            opacity: 0.5;
        }
        label.disabled * {
            pointer-events: none;
        }
        td:has(.user-checkbox) {
            position: relative;
            padding: 0;
            margin: 0;
            height: inherit;
        }
        .user-checkbox {
            z-index: 0;
            position: absolute;
            top: 0;
            left: 0;
            width: 110%;
            height: 100%;
            margin: 0;
            padding: 0;
            cursor: pointer;
            appearance: none;
            background-color: #e0ffe0;
            display: grid;
            place-content: center;
            transform: translateY(-0.075em);
            transform: translateX(-0.65em);
        }
        .user-checkbox::before{
            content: "";
            width: 0.65em;
            height: 0.65em;
            transform: scale(0);
            box-shadow: inset 1em 1em #00aa71;
            transition: 120ms transform ease-in-out;
            transform-origin: bottom left;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }
        .user-checkbox:checked::before{
            transform: scale(2.5);
        }
        .box {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f9f9f9;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 400px; 
            max-width: 90%; 
            overflow: auto;
            max-height: 60%
        }
        .box input[type="text"],
        .box input[type="number"],
        .box select,
        .box button {
            width: calc(100% - 10px);
            padding: 10px 0 10px 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .box input[type="number"] {
            width: calc(50% - 5px); 
        }

        .box select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #fff;
        }

        .box button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .box button:hover {
            background-color: #45a049;
        }
        .headerr{
            cursor: move;
            display: flex;
            position: relative;
            text-align: center;
        }
        .headerr h2{
            width: 100%;
        }
        .box .close{
            font-size: 3em;
            user-select: none;
            position: absolute;
            cursor: default;
            right: 0;
            top: 0;
            padding: 0 15px;
            border-radius: 50%;
            transition: all .3s ;
        }
        .box .close:hover{
            background-color: #f01111a1;
            color: white;
        }
        .filters {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .filters form {
            margin-right: 10px;
        }

        .filters select,
        .filters input[type="text"],
        .filters button {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .filters select {
            width: 150px;
        }

        .filters input[type="text"] {
            width: 200px;
        }

        .filters button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .filters button:hover {
            background-color: #45a049;
        }

        .mega-actions button,
        .mega-actions input[type="submit"],
        .mega-actions input[type="number"],
        .mega-actions select {
            padding: 10px;
            margin-bottom: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .mega-actions button,
        .mega-actions input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .mega-actions button:hover,
        .mega-actions input[type="submit"]:hover {
            background-color: #45a049;
        }

        .mega-actions input[type="text"] {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
        }

        .mega-actions form {
            margin-bottom: 10px;
        }

        .mega-actions label {
            display: block;
            margin-bottom: 5px;
        }

        .mega-actions .toggle-label {
            margin-right: 5px;
        }

        .mega-actions .form-inline {
            display: flex;
            align-items: center;
        }

        .mega-actions .message {
            margin-top: 10px;
            color: #ff6347;
        }


    </style>
    <script>
        let currentOpenForm = null;

        function toggleEditForm(gradeFormId) {
            var editForm = document.getElementById('edit-form-' + gradeFormId);

            if (editForm) {
                if (currentOpenForm && currentOpenForm !== editForm) {
                    currentOpenForm.style.display = 'none';
                }
                
                editForm.style.display = editForm.style.display === 'none' ? 'grid' : 'none';
                
                if (editForm.style.display === 'grid') {
                    currentOpenForm = editForm;
                    document.addEventListener('click', closeEditFormOnClickOutside);
                } else {
                    currentOpenForm = null;
                    document.removeEventListener('click', closeEditFormOnClickOutside);
                }
            } else {
                console.error('Element with ID edit-form-' + gradeFormId + ' not found.');
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>Tabela Uczniów</h1>
        <?php include 'nav.php'?>
    </header>
    <div class="container">
        <div class="filters">
            <form id="searchForm" method="post" action="">
                <input id="oho" type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Search</button>
            </form>
            <form action="" method="post" id="filters">
                <select name="ilu_ludzi" onchange="this.form.submit()">
                    <option value="1" <?=$limit == 1 ? 'selected' : ''?>>1</option>
                    <option value="2" <?=$limit == 2 ? 'selected' : ''?>>2</option>
                    <option value="5" <?=$limit == 5 ? 'selected' : ''?>>5</option>
                    <option value="10" <?=$limit == 10 ? 'selected' : ''?>>10</option>
                    <option value="20" <?=$limit == 20 ? 'selected' : ''?>>20</option>
                    <option value="WSZYSCY" <?=$limit == count($allIds) ? 'selected' : ''?>>WSZYSCY (ostrożnie)</option>
                </select>
            </form>
            <form action="" method="post">
                <select name="klasa_filter" onchange="this.form.submit()">
                    <?php if (!empty($allClass)): ?>
                        <?php foreach(array_unique($allClass) as $osoba): ?>
                            <option value="<?= htmlspecialchars($osoba) ?>" <?=($Klasa != '' and htmlspecialchars($osoba) == $Klasa) ? 'selected' : "" ?>> <?= htmlspecialchars($osoba)?></option>
                        <?php endforeach; ?>
                    <?php endif ?>
                    <option value="all" <?=$Klasa == 'all' ? 'selected' : ''?>>WSZYSCY</option>
                </select>
            </form>
            <?php if ($_SESSION['Rola_user'] == 'Admin') : ?>
            <form action="" method="post">
                <select name="rola_filter" onchange="this.form.submit()">
                    <?php if (!empty($allRoles)): ?>
                        <?php foreach(array_unique($allRoles) as $RolaK): ?>
                            <option value="<?= htmlspecialchars($RolaK) ?>" <?=($Rola != '' and htmlspecialchars($RolaK) == $Rola) ? 'selected' : "" ?>> <?= htmlspecialchars($RolaK)?></option>
                        <?php endforeach; ?>
                    <?php endif ?>
                    <option value="allR" <?=$Rola == 'allR' ? 'selected' : ''?>>Wszystkie</option>
                </select>
            </form>
            <?php endif; ?>
        </div>
        <div class="table-container">
            <table>
                <tr>
                    <th>✅</th>
                    <th>Imie</th>
                    <th>Nazwisko</th>
                    <th>Klasa</th>
                    <th>Email</th>
                    <?php if ($_SESSION['Rola_user'] == 'Admin') : ?>
                    <th>Rola</th>
                    <?php foreach ($subjectColumns as $subject): ?>
                        <th><?= htmlspecialchars($subject) ?></th>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if ($_SESSION['Rola_user'] == 'Nauczyciel') : ?>
                        <?php
                            $array = explode(',',$_SESSION['Czego_uczy_user']);
                            foreach ($array as $subject){
                                echo "<th>".htmlspecialchars($subject)."</th>";
                            }
                        ?>
                    <?php endif; ?>
                    <th>Actions</th>
                    <th>Średnia</th>
                    <th>Średnia Roczna</th>
                </tr>
                <?php if (!empty($rows)): ?>
                    <?php foreach($rows as $osoba): ?>
                    <tr >                           
                        <td><input type="checkbox" class="user-checkbox" value="<?= htmlspecialchars($osoba['id'])?>" <?= in_array($osoba['id'], $selectedIds) ? 'checked' : ''?>></td>
                        <td><?= htmlspecialchars($osoba['Imie']) ?></td>
                        <td><?= htmlspecialchars($osoba['Nazwisko']) ?></td>
                        <td><?= htmlspecialchars($osoba['Klasa']) ?></td>
                        <td><?= htmlspecialchars($osoba['E-mail']) ?></td>
                        <?php if($_SESSION['Rola_user'] == 'Admin') : ?>
                        <td><?= htmlspecialchars($osoba['Rola']) ?></td>
                        <?php foreach ($subjectColumns as $subject): ?>
                            <td>
                                <div class="oceny">
                                    <?php
                                        if (isset($osoba[$subject]) && $osoba[$subject] !== '') {
                                        
                                            $oceny = explode(",", $osoba[$subject]);
                                            $wszystkie_oceny = [];
                                            foreach ($oceny as $mark) {
                                                list($ocena, $wagaAndOpis) = explode(":", $mark);
                                                list($waga, $opis) = explode("-", $wagaAndOpis);            
                                                $wszystkie_oceny[] = ['ocena' => $ocena, 'waga' => $waga, 'opis' => $opis];
                                            }
                                            foreach ($wszystkie_oceny as $index => $ocena) {
                                                $gradeFormId = $osoba['id'] . '-' . $subject . '-' . $index;
                                                echo '<div class="ocena ocena'.$ocena['ocena'].' " onclick="toggleEditForm(\''.$gradeFormId.'\')" title=" Waga: '.$ocena['waga'].'">'.$ocena['ocena'].'</div>';
                                            }                                    
                                        } else {
                                            echo "-";
                                        }
                                    ?>
                                </div>
                                <?php
                                if (isset($oceny) && !empty($oceny)) {
                                    foreach ($wszystkie_oceny as $index => $ocena) {
                                        $gradeFormId = $osoba['id'].'-'.$subject.'-'.$index;
                                        ?>
                                        <div id="edit-form-<?= $gradeFormId ?>" class="edit-form" style="display: none">
                                            <p>Waga: <?= $ocena['waga']?> <br>
                                            Opis: <?= $ocena['opis']?></p>
                                            <form action="update_grade.php?page=<?= $page ?>&search=<?= $search?>" method="post">
                                                <input type="hidden" name="user_id" value="<?= $osoba['id'] ?>">
                                                <input type="hidden" name="subject" value="<?= $subject ?>">
                                                <input type="hidden" name="grade_id" value="<?= $index ?>">
                                                <input type="number" placeholder='Ocena' min="1" max = "6" name="edited_grade" id="edited_grade_<?= $gradeFormId ?>" value="<?= htmlspecialchars($ocena['ocena']) ?>" class="liczba">
                                                <input type="number" placeholder='Waga' name="waga" value="<?= htmlspecialchars($ocena['waga']) ?>" class="liczba">
                                                <button type="submit">Edit</button>
                                            </form>
                                            <form action="delete_grade.php?page=<?= $page ?>&search=<?= $search?>" method="post">
                                                <input type="hidden" name="user_id" value="<?= $osoba['id'] ?>">
                                                <input type="hidden" name="subject" value="<?= $subject ?>">
                                                <input type="hidden" name="grade_id" value="<?= $index ?>">
                                                <button type="submit">Delete</button>
                                            </form>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($_SESSION['Rola_user'] == 'Nauczyciel') : ?>
                        <?php foreach ($array as $subject): ?>
                            <td>
                            <div class="oceny">
                                <?php
                                if (isset($osoba[$subject]) && $osoba[$subject] !== '') {
                                
                                    $oceny = explode(",", $osoba[$subject]);
                                    $wszystkie_oceny = [];
                                    
                                    foreach ($oceny as $mark) {
                                        list($ocena, $wagaAndOpis) = explode(":", $mark);
                                        list($waga, $opis) = explode("-", $wagaAndOpis);
                                        
                                        $wszystkie_oceny[] = ['ocena' => $ocena, 'waga' => $waga, 'opis' => $opis];
                                    }
                                    
                                    foreach ($wszystkie_oceny as $index => $ocena) {
                                        $gradeFormId = $osoba['id'] . '-' . $subject . '-' . $index;
                                        echo '<div class="ocena ocena'.$ocena['ocena'].'" onclick="toggleEditForm(\''.$gradeFormId.'\')" title=" Waga: '.$ocena['waga'].'">'.$ocena['ocena'].'</div>';
                                    }
                                    
                                } else {
                                    echo "-";
                                }
                                ?>
                            </div>
                            <?php
                            if (isset($oceny) && !empty($oceny)) {
                                foreach ($wszystkie_oceny as $index => $ocena) {
                                    $gradeFormId = $osoba['id'].'-'.$subject.'-'.$index;
                                    ?>
                                    <div id="edit-form-<?= $gradeFormId ?>" class="edit-form" style="display: none">
                                        <p>Waga: <?= $ocena['waga']?> <br>
                                        Opis: <?= $ocena['opis']?></p>
                                        <form action="update_grade.php?page=<?= $page ?>&search=<?= $search?>" method="post">
                                            <input type="hidden" name="user_id" value="<?= $osoba['id'] ?>">
                                            <input type="hidden" name="subject" value="<?= $subject ?>">
                                            <input type="hidden" name="grade_id" value="<?= $index ?>">
                                            <input type="number" placeholder='Ocena' min="1" max="6" name="edited_grade" id="edited_grade_<?= $gradeFormId ?>" value="<?= htmlspecialchars($ocena['ocena']) ?>" class="liczba">
                                            <input type="number" placeholder='Waga' name="waga" value="<?= htmlspecialchars($ocena['waga']) ?>" class="liczba">
                                            <button type="submit">Edit</button>
                                        </form>
                                        <form action="delete_grade.php?page=<?= $page ?>&search=<?= $search?>" method="post">
                                            <input type="hidden" name="user_id" value="<?= $osoba['id'] ?>">
                                            <input type="hidden" name="subject" value="<?= $subject ?>">
                                            <input type="hidden" name="grade_id" value="<?= $index ?>">
                                            <button type="submit">Delete</button>
                                        </form>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            </td>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <td class="actions">
                            <?php if ($_SESSION['Rola_user'] == 'Admin') : ?> 
                            <form method="post" action="update_role.php?page=<?= $page ?>&search=<?= $search?>">
                                <input type="hidden" name="user_id" value="<?= $osoba['id'] ?>">
                                <select name="new_role" onchange="this.form.submit()">
                                    <option value="Uczen" <?= $osoba['Rola'] == 'Uczen' ? 'selected' : '' ?>>Uczen</option>
                                    <option value="Nauczyciel" <?= $osoba['Rola'] == 'Nauczyciel' ? 'selected' : '' ?>>Nauczyciel</option>
                                    <option value="Admin" <?= $osoba['Rola'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </form>
                            <?php endif; ?>
                            <form action="add_grade.php?page=<?= $page ?>&search=<?= $search?>" method="post" class="form-inline">
                                <input type="hidden" name="user_id" value="<?= $osoba['id'] ?>">
                                <select name="subject_grade">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                </select>
                                <input type="number" name="subject_waga" placeholder="Waga Oceny">
                                <select name="subject">
                                    <?php if ($_SESSION['Rola_user'] == 'Admin') : ?>
                                    <?php foreach ($subjectColumns as $subject): ?>
                                        <option value="<?= $subject ?>"><?= $subject ?></option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                    <?php
                                        if ($_SESSION['Rola_user'] == 'Nauczyciel'){
                                            $array = explode(',',$_SESSION['Czego_uczy_user']);
                                            foreach ($array as $subject){
                                                echo '<option value="'.$subject.'">'.$subject.'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                                <input type="text" name="opis" placeholder="opis">
                                <button type="submit">Dodaj ocenę</button>
                            </form>
                            <?php if($osoba['Rola'] === 'Nauczyciel'): ?>
                                <form action="Czego_uczy.php?page=<?= $page ?>&search=<?= $search?>" method="post">
                                    Czego uczy:
                                    <input type="hidden" name="id_osoby" value="<?=$osoba['id']?>">
                                    <select name="Czego_uczy" onchange="this.form.submit()">
                                        <option value="" selected></option>
                                        <?php foreach ($subjectColumns as $subject): ?>
                                            <option value="<?= $subject ?>" <?= $osoba['Czego_uczy'] === $subject ? 'selected' : ''?>><?= $subject ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            <?php endif; ?>
                            <?php if ($osoba['Rola'] === 'Uczen' and $_SESSION['Rola_user'] === 'Admin'): ?>
                            <br>
                                <form action="Set_klasa.php" method="post">
                                    <label for="Klasa">Ustaw Klasę: </label>
                                    <input type="hidden" name="id_user" value="<?=$osoba['id']?>">
                                    <input type="text" placeholder="Klasa np. (1A)" id="Klasa" name="Klasa">
                                    <input type="submit" value="Ustaw Klasę">
                                </form>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                            $srednie = [];
                            if ($_SESSION['Rola_user'] == 'Admin'){
                                foreach ($subjectColumns as $subject){
                                    echo $subject.': ';
                                    if (isset($osoba[$subject]) && $osoba[$subject] !== '') {
                                        $srednia = 0;
                                        
                                        $ilosc_ocen = 0;
                                        $oceny = explode(",", $osoba[$subject]);
                                        $wszystkie_oceny = [];
                                        foreach ($oceny as $mark) {
                                            list($ocena, $wagaAndOpis) = explode(":", $mark);
                                            list($waga, $opis) = explode("-", $wagaAndOpis);
                                            $wszystkie_oceny[] = ['ocena' => $ocena, 'waga' => $waga, 'opis' => $opis];
                                        }
                                        foreach ($wszystkie_oceny as $index => $ocena) {
                                            $ilosc_ocen += $ocena['waga'];
                                            $srednia += $ocena["ocena"]*$ocena['waga'];
                                        }
                                        $srednia = round(($srednia/$ilosc_ocen),2);
                                        echo round($srednia,2) > 1.85 ? round($srednia,2) : '<span class="message">'.round($srednia,2)."</span>";         
                                        array_push($srednie,$srednia);                 
                                    } else {
                                        echo "-";
                                    }
                                    echo "<br>";
                                }
                            }else if($_SESSION['Rola_user'] == 'Nauczyciel'){
                                foreach ($array as $subject){
                                    echo $subject.': ';
                                    if (isset($osoba[$subject]) && $osoba[$subject] !== '') {
                                        $srednia = 0;
                                        
                                        $ilosc_ocen = 0;
                                        $oceny = explode(",", $osoba[$subject]);
                                        $wszystkie_oceny = [];
                                        foreach ($oceny as $mark) {
                                            list($ocena, $wagaAndOpis) = explode(":", $mark);
                                            list($waga, $opis) = explode("-", $wagaAndOpis);
                                            $wszystkie_oceny[] = ['ocena' => $ocena, 'waga' => $waga, 'opis' => $opis];
                                        }
                                        foreach ($wszystkie_oceny as $index => $ocena) {
                                            $ilosc_ocen += $ocena['waga'];
                                            $srednia += $ocena["ocena"]*$ocena['waga'];
                                        }
                                        $srednia = round(($srednia/$ilosc_ocen),2);
                                        echo round($srednia,2) > 1.85 ? round($srednia,2) : '<span class="message">'.round($srednia,2)."</span>";         
                                        array_push($srednie,$srednia);                 
                                    } else {
                                        echo "-";
                                    }
                                    echo "<br>";
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if(isset($srednie) and !empty($srednie)){
                                $zmienna = array_sum($srednie) / count($srednie);
                                if(round($zmienna, 2) >1.85){
                                    echo round($zmienna, 2);
                                }else{
                                    echo'<p class="message">'.round($zmienna, 2).'</p>';
                                }
                                unset($srednie);
                                unset($zmienna);
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>

                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12">Nie Znaleziono urzytkownika(ów) o podanych wartościach</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
        <div class="pagination">
            <?php if ($totalPages > 1): ?>
                <a href="?page=1">1</a>
                <?php if ($totalPages >= 3): ?>
                    <span class="dot">...</span>
                    <a href="?page=<?= $prevPage ?>&search=<?= urlencode($search) ?>">Previous</a>
                    <a class="page"><?=$page?></a>
                    <a href="?page=<?= $nextPage ?>&search=<?= urlencode($search) ?>">Next</a>
                    <span class="dot">...</span>
                <?php endif; ?>
                <a href="?page=<?= $totalPages?>"><?= $totalPages?> </a>
            <?php endif; ?>
        </div>
        <div class="mega-actions">
            <button id="select-all-button">Zaznacz Wszystkich</button>
            <button id="deselect-all-button">Odznacz Wszystkich</button>
            <br>
            <br>
            <form method="post">
                <input type="submit" value="Masowo dodaj różne oceny z opisem" name="Bulk_add_grade" id="Bulk_button">
            </form>
            <br>
            <br>
            <form method="post" class="mega_form">
                <?php if ($_SESSION['Rola_user'] == 'Admin'): ?>
                <label>
                    <input type="checkbox" name="czy_new_role" class="toggle-label">    
                    Zmień rolę urzytkownika(ów): 
                    <select name="new_role">
                        <option value="Uczen">Uczen</option>
                        <option value="Nauczyciel">Nauczyciel</option>
                        <option value="Admin">Admin</option>
                    </select>
                </label>
                <br>
                <?php endif; ?>
                <label>
                    <input type="checkbox" name="czy_new_grade" class="toggle-label"> 
                    Dodaj ocenę: 
                    <select name="subject_grade">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>
                    <input type="number" name="subject_waga" placeholder="Waga Oceny">
                    <select name="subject">
                        <?php if ($_SESSION['Rola_user'] == 'Admin') : ?>
                        <?php foreach ($subjectColumns as $subject): ?>
                            <option value="<?= $subject ?>"><?= $subject ?></option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <?php
                            if ($_SESSION['Rola_user'] == 'Nauczyciel'){
                                $array = explode(',',$_SESSION['Czego_uczy_user']);
                                foreach ($array as $subject){
                                    echo '<option value="'.$subject.'">'.$subject.'</option>';
                                }
                            }
                        ?>
                    </select>
                    <input type="text" name="subject_opis" placeholder="Opis">
                </label>
                <?php if ($_SESSION['Rola_user'] == 'Admin'): ?>
                <br>
                <label>
                    <input type="checkbox" name="czy_new_uczy" class="toggle-label"> 
                    Czego uczy:
                    <select name="Czego_uczy">
                        <option value="" selected></option>
                        <?php foreach ($subjectColumns as $subject): ?>
                            <option value="<?= $subject ?>"><?= $subject ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <br>
                <label>
                    <input type="checkbox" name="czy_new_class" class="toggle-label"> 
                    Ustaw Klasę: 
                    <input type="text" placeholder="Klasa np. (1A)" id="Klasa" name="Klasa">
                </label>
                <?php endif; ?>
                <br>
                <button id="action-button">Wykonaj</button>
            </form>
            
            <br>
            <?php if ($_SESSION['Rola_user'] == 'Admin'): ?>
            <br>
            <br>
            <form action="add_subject.php?page=<?= $page ?>&search=<?= $search?>" method="post" class="form-inline">
                <input type="text" name="Name_of_subject" placeholder="Nazwa przedmiotu">
                <label for="after">Dodaj przedmiot za: </label>
                <select name="After" id="after">
                    <?php foreach($subjectColumns as $subject):?>
                        <option value="<?= $subject ?>" selected><?= $subject ?></option>
                    <?php endforeach;?>        
                </select>
                <button type="submit">Dodaj przedmiot</button>
            </form>
            <?php 
                if (isset($_SESSION['alert_column'])){
                    echo '<p class="message">'.$_SESSION['alert_column'].'</p>';
                    unset($_SESSION['alert_column']);
                }
            ?>
            <br>
            <form action="delete_subject.php?page=<?= $page ?>&search=<?= $search?>" method="post" class="form-inline">
                <select name="usun" id="usun">
                    <?php foreach($subjectColumns as $subject):?>
                        <option value="<?= $subject ?>"><?= $subject ?></option>
                    <?php endforeach;?>        
                </select>
                <button type="submit">Usuń przedmiot</button>
            </form>
            <br><br>  
            <?php endif; ?>
        </div>
        <div class="box" style="display: none" id="Bulk_form">
            <div class="headerr" id="Bulk_form_header">
                <h2 align="center">Dodaj oceny</h2>
                <div class="close">&times;</div>
            </div>
            <form method="post" action="Multi_grade_and_desc.php?page=<?= $page ?>&search=<?= $search?>">
                <input type="text" id="opis" name="opis" placeholder="Opis">
                <input type="number" name="waga" id="waga" placeholder="Waga">
                <select name="przedmiot">
                    <?php if ($_SESSION['Rola_user'] == 'Admin') : ?>
                    <?php foreach ($subjectColumns as $subject): ?>
                        <option value="<?= $subject ?>"><?= $subject ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <?php
                        if ($_SESSION['Rola_user'] == 'Nauczyciel'){
                            $array = explode(',',$_SESSION['Czego_uczy_user']);
                            foreach ($array as $subject){
                                echo '<option value="'.$subject.'">'.$subject.'</option>';
                            }
                        }
                    ?>
                </select>
                <br><br>
                <?php foreach ($selectedIds as $id): ?>
                    <?php
                        $sql = "SELECT * FROM users WHERE id = '$id'";
                        $result = $conn->query($sql);
                        $row =  $result->fetch_array();

                    ?>
                    <div class="grade-entry">
                        <p><?= $row['Imie']. " ". $row["Nazwisko"]. " - ". $row['Klasa']?>:
                            <select name="grades[<?= $id ?>]" placeholder="Ocena">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                        </p>
                    </div>
                <?php endforeach; ?>

                <br>
                <button type="submit">Zapisz Oceny</button>
            </form>
        </div>

        <script>

            var bulk = <?php echo $bulk ? 'true' : 'false'; ?>;
            if (bulk) {
                var selectedIds = <?php echo json_encode($selectedIds); ?>;
                let x = document.getElementById("Bulk_button");
                let y =document.querySelector(".box");
                let close =document.querySelector('.box .close');
                y.style.display = "block";
                x.addEventListener("click", () => {
                    if (y.style.display === "none") {
                        y.style.display = "block";
                    } else {
                        y.style.display = "none";
                    }
                });
                close.addEventListener("click", () => {
                    if (y.style.display === "none") {
                        y.style.display = "block";
                    } else {
                        y.style.display = "none";
                    }
                });
            }
            dragElement(document.getElementById("Bulk_form"));
            function dragElement(elmnt) {
                var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
                if (document.getElementById(elmnt.id + "_header")) {
                    document.getElementById(elmnt.id + "_header").onmousedown = dragMouseDown;
                } else {
                    elmnt.onmousedown = dragMouseDown;
                }

                function dragMouseDown(e) {
                    e = e || window.event;
                    e.preventDefault();
                    pos3 = e.clientX;
                    pos4 = e.clientY;
                    document.onmouseup = closeDragElement;
                    document.onmousemove = elementDrag;
                }

                function elementDrag(e) {
                    e = e || window.event;
                    e.preventDefault();
                    pos1 = pos3 - e.clientX;
                    pos2 = pos4 - e.clientY;
                    pos3 = e.clientX;
                    pos4 = e.clientY;
                    elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
                    elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
                }

                function closeDragElement() {
                    document.onmouseup = null;
                    document.onmousemove = null;
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const toggleLabels = document.querySelectorAll('.toggle-label');

                toggleLabels.forEach(checkbox => {
                    const label = checkbox.closest('label');

                    function toggleLabel() {
                        if (checkbox.checked) {
                            label.classList.remove('disabled');
                        } else {
                            label.classList.add('disabled');
                        }
                    }
                    toggleLabel();
                    checkbox.addEventListener('change', toggleLabel);
                });
            });
            document.addEventListener('DOMContentLoaded', function() {
                const checkboxes = document.querySelectorAll('.user-checkbox');
                const actionButton = document.getElementById('action-button');
                const selectAllButton = document.getElementById('select-all-button');
                const deselectAllButton = document.getElementById('deselect-all-button');

                const allIds = <?= json_encode($allIds) ?>;
                let phpSelectedIds = <?= json_encode($selectedIds) ?>;

                function updateSession(selectedIds) {
                    const page = encodeURIComponent('<?= $page ?>');
                    const search = encodeURIComponent('<?= $search ?>');

                    fetch('update_session.php?page=' + page + '&search=' + search, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ selectedIds: selectedIds })
                    });
                }

                function getCombinedIds() {
                    let selectedIds = Array.from(checkboxes)
                                            .filter(checkbox => checkbox.checked)
                                            .map(checkbox => checkbox.value);
                    let unselectedIds = Array.from(checkboxes)
                                            .filter(checkbox => !checkbox.checked)
                                            .map(checkbox => checkbox.value);
                    phpSelectedIds = phpSelectedIds.filter(id => !unselectedIds.includes(id));
                    return [...new Set([...selectedIds, ...phpSelectedIds])];
                }

                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updateSession(getCombinedIds());
                    });
                });

                selectAllButton.addEventListener('click', function() {
                    updateSession(allIds);
                    setTimeout(function() { window.location.reload(); }, 20);
                });

                deselectAllButton.addEventListener('click', function() {
                    const sessionSelectedItems = <?= isset($_SESSION['selectedIds']) ? json_encode($_SESSION['selectedIds']) : [] ?>;
                    const itemsToDeselect = sessionSelectedItems.filter(item => !allIds.includes(item));
                    updateSession(itemsToDeselect);
                    setTimeout(function() { window.location.reload(); }, 20);
                });

                actionButton.addEventListener('click', function(event) {
                    event.preventDefault(); 
                    const page = encodeURIComponent('<?= $page ?>');
                    const search = encodeURIComponent('<?= $search ?>');
                    let combinedIds = getCombinedIds();

                    let formData = new FormData(document.querySelector('.mega_form'));
                    let data = {
                        combinedIds: combinedIds,
                        czy_new_role: formData.get('czy_new_role'),
                        new_role: formData.get('new_role'),
                        czy_new_grade: formData.get('czy_new_grade'),
                        subject_grade: formData.get('subject_grade'),
                        subject_waga: formData.get('subject_waga'),
                        subject_opis: formData.get('subject_opis'),
                        subject: formData.get('subject'),
                        czy_new_uczy: formData.get('czy_new_uczy'),
                        Czego_uczy: formData.get('Czego_uczy'),
                        czy_new_class: formData.get('czy_new_class'),
                        Klasa: formData.get('Klasa')
                    };

                    if (combinedIds.length > 0) {
                        fetch('process_selected_users.php?page=' + page + '&search=' + search, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                window.location.href = window.location.href;
                            } else {
                                alert('Error performing action: ' + data.message);
                            }
                        })
                        .catch(error => {
                            alert('Network error: ' + error.message);
                            console.error('Error:', error);
                        });
                    } else {
                        alert('No users selected.');
                    }
                });
            });
        </script>
    </div>
    
</body>
</html>
<?php $conn->close(); ?>