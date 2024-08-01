<?php
session_start();
if (!isset($_SESSION['Login']) || $_SESSION['Rola_user'] == 'Uczen') {
    header('Location: zaloguj.php');
    exit();
}

if ($_SESSION['Rola_user'] !== 'Uczen') {
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

        if($_SESSION['Rola_user'] == 'Nauczyciel'){
            $rola_filter = 'Uczen';
        }
        
        $allIdsSql = "SELECT id FROM users WHERE Rola != 'Admin'";
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
        $allClassResult = $conn->query($allClassSql);
        $allClass = $allClassResult->fetch_all(MYSQLI_ASSOC);
        $allClass = array_column($allClass, 'Klasa');


        $allRolesSql = "SELECT Rola FROM users WHERE Rola != 'Admin' ORDER BY Rola";
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
            $subjectColumns = array_slice($subjectColumns, 11);
        } else {
            echo "Error retrieving data: " . $conn->error;
            $rows = [];
            $subjectColumns = [];
        }

        $countSql = "SELECT COUNT(*) AS total FROM users WHERE Rola != 'Admin'";
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
        
        $times = [
            '07:10 - 07:55', '08:00 - 08:45', '08:50 - 09:35', 
            '09:40 - 10:25', '10:30 - 11:15', '11:20 - 12:05', 
            '12:10 - 12:55', '13:00 - 13:45', '13:50 - 14:35', 
            '14:40 - 15:25'
        ];

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['multiAddAttendance'])) {
            if (isset($_POST['selected_students']) && is_array($_POST['selected_students'])) {
                $selected_students = $selectedIds;
                $status = $_POST['status'];
                unset($_SESSION['selectedIds']);
                $insertSql = "INSERT INTO attendance (`user_id`, `date`, `status`, `lekcja`) VALUES ";
                $valueStrings = [];
        
                foreach ($selected_students as $student_id) {
                    $currentDate = $_POST['data'];
                    $godzina = $_POST['czas'];
                    $currentDate = $currentDate." ".$times[$godzina-1];
                    $lekcja = $_POST["lekcja"];
                    $valueStrings[] = "('$student_id', '$currentDate', '$status', '$lekcja')";
                }
        
                $insertSql .= implode(", ", $valueStrings);
        
                if ($conn->query($insertSql)) {
                    header("Location: frekfencja.php?page={$_GET['page']}&search={$_GET['search']} ");
                } else {
                    echo "Błąd podczas dodawania frekwencji: " . $conn->error;
                }
            } else {
                echo "Nie wybrano żadnych uczniów.";
            }
        }

    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frekwencja</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f0f0;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 120px auto 20px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .table-container {
            overflow-x: auto;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
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

        .pagination {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .pagination a {
            padding: 10px 15px;
            margin: 0 5px;
            text-decoration: none;
            color: #4CAF50;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .pagination .dot {
            padding: 10px 0;
            margin: 0;
        }

        .pagination .page {
            border: none;
            padding: 7px 10px;
            margin: 0;
            user-select: none;
            color: #4CAF50;
        }

        .pagination .page:hover {
            background-color: transparent;
            color: #4CAF50;
        }

        .pagination a:hover {
            background-color: #4CAF50;
            color: white;
        }

        .message {
            color: red;
            margin-top: 10px;
        }

        input[type="checkbox"] {
            position: relative;
            cursor: pointer;
            appearance: none;
            background-color: #e0ffe0;
            display: grid;
            place-content: center;
            transform: translateY(-0.075em);
            width: 100%;
            height: 10%;
            margin: 0;
            padding: 10%;
        }

        input[type="checkbox"]::before {
            content: "";
            width: 0.65em;
            height: 0.65em;
            transform: scale(0);
            box-shadow: inset 1em 1em #00aa71;
            transition: 120ms all ease-in-out;
            transform-origin: bottom left;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }

        input[type="checkbox"]:checked::before {
            transform: scale(2.5);
        }

        .filtry {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .filtry form {
            display: inline-block;
        }

        .filtry select, 
        .filtry input[type="text"], 
        .filtry button {
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }

        .filtry select:focus, 
        .filtry input[type="text"]:focus, 
        .filtry button:focus {
            border-color: #4CAF50;
            outline: none;
        }

        button {
            padding: 10px 20px;
            font-size: 1em;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        #select-all-button, #deselect-all-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #select-all-button:hover, #deselect-all-button:hover {
            background-color: #d32f2f;
        }

        label {
            margin-right: 10px;
        }
        .multi-add-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 100%;
            max-height: 60%;
            overflow: auto;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .multi-add-box h2 {
            margin-top: 0;
            text-align: center;
        }

        .multi-add-box form {
            display: flex;
            flex-direction: column;
        }

        .multi-add-box label,
        .multi-add-box select,
        .multi-add-box input {
            margin-bottom: 10px;
            width: 100%;
        }

        .multi-add-box button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            text-align: center;
        }

        .multi-add-box button:hover {
            background-color: #0056b3;
        }

        .multi-add-box div {
            margin-bottom: 10px;
        }
        .headerr{
            cursor: move;
        }
        .close{
            font-size: 2em;
            user-select: none;
            position: absolute;
            cursor: default;
            right: 7px;
            top: 10px;
            padding: 0 10px;
            border-radius: 50%;
            transition: all .3s ;
        }
        .close:hover{
            background-color: #f01111a1;
            color: white;
        }
        @media screen and (max-width: 580px) {
            .filtry {
                display: grid;
                grid-template: '1fr 1fr' ;
            }
        }
        @media screen and (max-width:550px){
            .filtry{
                grid-template: '1fr';
            }
            .multi-add-box{
                width: 90%;
            }
        }
</style>

</head>
<body>
    <header>
        <h1>Frekwencja</h1>
        <?php include 'nav.php'?>
        
    </header>
    <div class="container">
        <div class="filtry">
            <form id="searchForm" method="post" action="">
                <input id="oho" type="text" name="search" placeholder="Szukaj..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Szukaj</button>
            </form>
            <form action="" method="post" id="filters">
                <label for="ile_ludzi">Ilość wyświetlanych osób:</label>
                <select id="ile_ludzi" name="ilu_ludzi" onchange="this.form.submit()">
                    <option value="1" <?=$limit == 1 ? 'selected' : ''?>>1</option>
                    <option value="2" <?=$limit == 2 ? 'selected' : ''?>>2</option>
                    <option value="5" <?=$limit == 5 ? 'selected' : ''?>>5</option>
                    <option value="10" <?=$limit == 10 ? 'selected' : ''?>>10</option>
                    <option value="20" <?=$limit == 20 ? 'selected' : ''?>>20</option>
                    <option value="WSZYSCY" <?=$limit == count($allIds) ? 'selected' : ''?>>WSZYSCY (ostrożnie)</option>
                </select>
            </form>
            <form action="" method="post">
            <label for="Klasa_filter">Klasa:</label>
                <select id="Klasa_filter" name="klasa_filter" onchange="this.form.submit()">
                    <?php if (!empty($allClass)): ?>
                        <?php foreach(array_unique($allClass) as $osoba): ?>
                            <option value="<?= htmlspecialchars($osoba) ?>" <?=($Klasa != '' and htmlspecialchars($osoba) == $Klasa) ? 'selected' : "" ?>> <?= htmlspecialchars($osoba)?></option>
                        <?php endforeach; ?>
                    <?php endif ?>
                    <option value="all" <?=$Klasa == 'all' ? 'selected' : ''?>>WSZYSCY</option>
                </select>
            </form>
            <?php if ($_SESSION['Rola_user'] != "Nauczyciel") : ?>
            <form action="" method="post">
                <label for="rola_filter">Rola:</label>
                <select id="rola_filter" name="rola_filter" onchange="this.form.submit()">
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
            <form method="post" action="">
                <table>
                    <thead>
                        <tr>
                            <th>Imie</th>
                            <th>Nazwisko</th>
                            <th>Klasa</th>
                            <th>Wybór</th>
                            <th>Sprawdż</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($rows as $osoba): ?>
                        <tr>
                            <td><?= htmlspecialchars($osoba['Imie']) ?></td>
                            <td><?= htmlspecialchars($osoba['Nazwisko']) ?></td>
                            <td><?= htmlspecialchars($osoba['Klasa']) ?></td>
                            <td>
                            <input name="selected_students[]" type="checkbox" class="user-checkbox" value="<?= htmlspecialchars($osoba['id'])?>" <?= in_array($osoba['id'], $selectedIds) ? 'checked' : ''?>>
                            </td>
                            <td>
                                <a href="attendance.php?id=<?=$osoba['id']?>">Sprawdź</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="pagination">
                    <?php if ($totalPages > 1): ?>
                        <a href="?page=1">1</a>
                        <?php if ($totalPages > 3): ?>
                            <span class="dot">...</span>
                            <a href="?page=<?= $prevPage ?>&search=<?= urlencode($search) ?>">&laquo; Poprzednia</a>
                            <a class="page"><?=$page?></a>
                            <a href="?page=<?= $nextPage ?>&search=<?= urlencode($search) ?>">Następna &raquo;</a>
                            <span class="dot">...</span>
                        <?php endif; ?>
                        <a href="?page=<?= $totalPages?>"><?= $totalPages?> </a>
                    <?php endif; ?>
                </div>

                <br><br>
                <label>Status:</label>
                <label><input type="radio" name="status" value="Obecny" required> Obecny </label>
                <label><input type="radio" name="status" value="Spóźniony"> Spóźniony </label>
                <label><input type="radio" name="status" value="Nieobecny"> Nieobecny </label>
                <input type="date" name="data" id="data">
                <select name="czas" id="czas">
                </select>

                <select name="lekcja" id="lekcja">
                    <?php
                        $sql = "SELECT * FROM users_oceny";
                        $result = $conn->query($sql);
                        $rows = $result->fetch_all(MYSQLI_ASSOC);
                        $subjectColumns = !empty($rows) ? array_keys($rows[0]) : [];
                        $subjectColumns = array_slice($subjectColumns, 2);
                        $array = explode(',',$_SESSION['Czego_uczy_user']) ;
                    if($_SESSION["Rola_user"] == "Admin"):
                    ?>
                    <?php foreach ($subjectColumns as $subject): ?>
                        <option value="<?= $subject ?>"><?= $subject ?></option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if($_SESSION['Rola_user'] == "Nauczyciel"): ?>
                        <?php foreach ($array as $row): ?>
                            <option value="<?=$row?>"><?=$row?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </select>
                <script>
                    var currentDate = new Date();
                    var currentHour = currentDate.getHours();
                    var currentMinutes = currentDate.getMinutes();
                    var day = currentDate.getDate();
                    var month = currentDate.getMonth() + 1; 
                    var year = currentDate.getFullYear();
                    if (month < 10) month = '0' + month;
                    if (day < 10) day = '0' + day;
                    var formattedDate = year + '-' + month + '-' + day;
                    document.getElementById('data').value = formattedDate;
                    var times = [
                        { start: '07:10', end: '07:55' },
                        { start: '08:00', end: '08:45' },
                        { start: '08:50', end: '09:35' },
                        { start: '09:40', end: '10:25' },
                        { start: '10:30', end: '11:15' },
                        { start: '11:20', end: '12:05' },
                        { start: '12:10', end: '12:55' },
                        { start: '13:00', end: '13:45' },
                        { start: '13:50', end: '14:35' },
                        { start: '14:40', end: '15:25' }
                    ];
                    var currentIndex = -1;
                    for (var i = 0; i < times.length; i++) {
                        var startHour = parseInt(times[i].start.split(':')[0]);
                        var startMinutes = parseInt(times[i].start.split(':')[1]);
                        var endHour = parseInt(times[i].end.split(':')[0]);
                        var endMinutes = parseInt(times[i].end.split(':')[1]);
                        if (currentHour > startHour || (currentHour === startHour && currentMinutes >= startMinutes)) {
                            if (currentHour < endHour || (currentHour === endHour && currentMinutes <= endMinutes)) {
                                currentIndex = i;
                                break;
                            }
                        }
                    }
                    var selectElement = document.getElementById('czas');
                    for (var j = 0; j < times.length; j++) {
                        var option = document.createElement('option');
                        option.value = j + 1;
                        option.textContent = 'Lekcja ' + `${j+1}` + ": " + times[j].start + ' - ' + times[j].end;
                        if (j === currentIndex) {
                            option.selected = true; 
                        }
                        selectElement.appendChild(option);
                    }
                </script>
                <br><br>
                <button type="submit" name="multiAddAttendance">Dodaj frekwencję dla wybranych</button>
            </form>
        </div>
        <button id="bulk-adding">Multi-dodawanie</button>

    <script>
        let bulk = document.getElementById('bulk-adding');
        
        bulk.addEventListener('click', function(){
            fetch('toggle_session.php')
                .then(response => response.json())
                .then(data => {
                    location.reload();
                })
                .catch(error => {
                    console.error('Error toggling session:', error);
                });
        });
    </script>

    <br>
    <br>
    <button id="select-all-button">Zaznacz Wszystkich</button>
    <button id="deselect-all-button">Odznacz Wszystkich</button>

    <div class="multi-add-box" id="multi-add-box" style="<?= isset($_SESSION['show']) && $_SESSION['show'] ? 'display: block;' : 'display: none;' ?>">
        <form method="post" action="save_attendance.php">
            <input type="hidden" name="selectedIds" value="<?= implode(',', $selectedIds) ?>">
            <div class="headerr" id="multi-add-box_header">
                <h2>Frekwencja:</h2>
                <div class="close" id="close">&times;</div>
            </div>
            <script>
                let close = document.getElementById('close');
                close.addEventListener('click', function(){
                    let show = <?php echo isset($_SESSION['show']) && $_SESSION['show'] ? 'true' : 'false'; ?>;
                    if (!show) {
                        <?php $_SESSION['show'] = true; ?>;
                        location.reload();
                    } else {
                        <?php $_SESSION['show'] = false; ?>;
                        let element = document.getElementById('multi-add-box');
                        element.style.display = 'none';
                    }
                });
            </script>
            <input type="date" name="data" id="data1">
            <select name="czas" id="czas1">
            </select>
            <select name="lekcja" id="lekcja1">
            
                <?php
                    $sql = "SELECT * FROM users_oceny";
                    $result = $conn->query($sql);
                    $rows = $result->fetch_all(MYSQLI_ASSOC);
                    $subjectColumns = !empty($rows) ? array_keys($rows[0]) : [];
                    $subjectColumns = array_slice($subjectColumns, 2);
                    $array = explode(',',$_SESSION['Czego_uczy_user']) ;
                if($_SESSION["Rola_user"] == "Admin"):
                ?>
                <?php foreach ($subjectColumns as $subject): ?>
                    <option value="<?= $subject ?>"><?= $subject ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
                <?php if($_SESSION['Rola_user'] == "Nauczyciel"): ?>
                    <?php foreach ($array as $row): ?>
                        <option value="<?=$row?>"><?=$row?></option>
                    <?php endforeach; ?>
                <?php endif; ?>

            </select>
            
            <script>
                var currentDate = new Date();
                var currentHour = currentDate.getHours();
                var currentMinutes = currentDate.getMinutes();
                var day = currentDate.getDate();
                var month = currentDate.getMonth() + 1; 
                var year = currentDate.getFullYear();
                if (month < 10) month = '0' + month;
                if (day < 10) day = '0' + day;
                var formattedDate = year + '-' + month + '-' + day;
                document.getElementById('data1').value = formattedDate;
                var times = [
                    { start: '07:10', end: '07:55' },
                    { start: '08:00', end: '08:45' },
                    { start: '08:50', end: '09:35' },
                    { start: '09:40', end: '10:25' },
                    { start: '10:30', end: '11:15' },
                    { start: '11:20', end: '12:05' },
                    { start: '12:10', end: '12:55' },
                    { start: '13:00', end: '13:45' },
                    { start: '13:50', end: '14:35' },
                    { start: '14:40', end: '15:25' }
                ];
                var currentIndex = -1;
                for (var i = 0; i < times.length; i++) {
                    var startHour = parseInt(times[i].start.split(':')[0]);
                    var startMinutes = parseInt(times[i].start.split(':')[1]);
                    var endHour = parseInt(times[i].end.split(':')[0]);
                    var endMinutes = parseInt(times[i].end.split(':')[1]);
                    if (currentHour > startHour || (currentHour === startHour && currentMinutes >= startMinutes)) {
                        if (currentHour < endHour || (currentHour === endHour && currentMinutes <= endMinutes)) {
                            currentIndex = i;
                            break;
                        }
                    }
                }
                var selectElement = document.getElementById('czas1');
                for (var j = 0; j < times.length; j++) {
                    var option = document.createElement('option');
                    option.value = times[j].start + ' - ' + times[j].end;
                    option.textContent = 'Lekcja ' + `${j+1}` + ": " + times[j].start + ' - ' + times[j].end;
                    if (j === currentIndex) {
                        option.selected = true; 
                    }
                    selectElement.appendChild(option);
                }
            </script>
            <div class="br"></div>
            <div class="br"></div>
            <?php foreach ($selectedIds as $ID) : ?>
                <?php 
                    $sql = "SELECT * FROM users WHERE id = '$ID'";
                    $result = $conn ->query($sql);
                    $row = $result->fetch_assoc();
                ?>
                <div>
                    <label for="multi-attendance-<?= $ID ?>"><?=$row['Imie']?> <?=$row['Nazwisko']?> :</label>
                    <select name="attendance_status[<?= $ID ?>]" id="multi-attendance-<?= $ID ?>">
                        <option value="Obecny">Obecny</option>
                        <option value="Nieobecny">Nieobecny</option>
                        <option value="Spóźniony">Spóźniony</option>
                    </select>
                </div>
            <?php endforeach; ?>
            <button type="submit" id="frek">Zapisz frekwencję</button>
            <script>
                let frek =document.getElementById('frek');
                frek.addEventListener('click', function(){
                    <?php $_SESSION['show'] = false?>
                });
            </script>
        </form>
    </div>
    </div>
    <?php include 'footer.php' ?>
</body>
</html>
<script>

    dragElement(document.getElementById("multi-add-box"));
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
        const checkboxes = document.querySelectorAll('.user-checkbox');
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
    });
</script>