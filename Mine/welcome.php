<?php
session_start();
require_once 'db.php';
$conn = new mysqli($server_name, $user_name, $password, $database);
if (!isset($_SESSION['Login']) || !$_SESSION['Login']) {
    header('Location: zaloguj.php');
    exit();
}
unset($_SESSION['login_e']);
unset($_SESSION['haslo_e']);
unset($_SESSION['alert']);
unset($_SESSION['cheaking_haslo']);
unset($_SESSION['cheaking_login']);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Witaj <?= $_SESSION['Imie_user'] ?>!</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            color: #333;
            margin: 0;
            padding-top: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-attachment: fixed;
        }
        .container{
            width: 100%;
            display: flex;
            <?php if($_SESSION['Rola_user'] != 'Uczen'){echo "justify-content: center;";} ?>
        }
        .left{
            width: 50%;
            display: flex;
            justify-content: right;
            align-items: start;
        }
        .right{
            display: flex;
            flex-direction: column;
        }

        .card table {
            font-size: 0.8em;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .card th, .card td {
            border: 1px solid #ddd; 
            padding: 8px;
            text-align: left;
        }

        .card th {
            background-color: #f2f2f2; 
            color: #333; 
        }

        .card tr:nth-child(even) {
            background-color: #f9f9f9; 
        }

        .card tr:hover {
            background-color: #f1f1f1; 
        }
        .card h2{
            cursor: pointer;
            font-size: 0.9em;
        }
        .card  {
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            margin: 20px;
            font-size: 1.4em;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border-radius: 10px 10px 0 0;
            cursor: pointer;
            margin: -20px -20px 20px -20px;
            transition: background-color 0.3s;
        }
        .card-header:hover {
            background-color: #0056b3;
        }
        .card-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease-out;
        }
        .card-content-open{
            min-height: 115px;
            display: grid;
            place-items: center;
            max-height: 690px;
            overflow: auto;
        }
        .expanded {
            max-height: 500px; 
            overflow: auto;
        }
        nav {
            margin-top: 10px;
        }
        .iframe-container{
            width: 80dvw;
            height: 80dvh;
            margin-bottom:50px ;
        }
        .iframe-container iframe {
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
        }
        .admin-content{
            border-radius: 6px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
            margin-top: 20px;
            font-size: 1.1em;
        }
        .admin-content h2{
            font-size: 1.5em;
            text-align: center;
            
        }
        .hide{
            max-height: 0;
            transition: max-height 0.5s ease-out;
            overflow: hidden;
        }
        .show{
            max-height: 500px;
            overflow: auto;
        }
        .date {
            font-size: 1.2em;
            margin-bottom: 20px;
            text-align: right;
            position: absolute;
            right: 1dvh;
            <?php if($_SESSION['Rola_user'] == 'Uczen'){echo "right: 10dvh;";} ?>
            top: 130px;
        }
        .hour {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
            text-align: right;
            position: absolute;
            right: 1dvh;
            <?php if($_SESSION['Rola_user'] == 'Uczen'){echo "right: 10dvh;";} ?>
            top: 180px;
        }
        .show:has(table){
            overflow: auto;
            display: block;
            width: 100%;
        }
        @media screen and (max-width:1255px) {  
            .container{
                margin-top: 100px;
                margin-bottom: 100px;
            }
            .left{
                width: 50%;
            }
            .left .plan_lekcji{
                width: 100%;
            }
            .right{
                width: 50%;
            }
        }
        @media screen and (max-width: 700px) {
            header h1{
                margin-right: 100px;
            }
        }
        <?php if($_SESSION['Rola_user'] == 'Uczen') : ?>
        @media screen and (max-width: 829px) {
            .container{
                flex-direction: column;
            }
            .left{
                width: 100%;
            }
            .right{
                width: 100%;
            }
            .date{
                right: 10px;
            }
            .hour{
                right: 10px;
            }
        }
        <?php endif; ?>
    </style>
</head>
<body>
    <header>
        <h1>Witaj <?= $_SESSION['Rola_user'] == 'Uczen' ? "Uczniu" : ($_SESSION['Rola_user'] == 'Nauczyciel' ? "Nauczycielu" : "Adminie") ?> <?= htmlspecialchars($_SESSION['Imie_user']) ?></h1>
        <?php include 'nav.php' ?>
    </header>
    <div class="container">
        <?php if ($_SESSION['Rola_user'] == 'Admin'): ?>
            <div class="date" id='date'></div>
            <div class="hour" id="hour"></div>
            <div class="admin-content">

                <p class="Admin_definicja">
                    Admin - admin «Potoczne określenie administratora strony internetowej, systemu komputerowego, sieci lub dowolnej platformy technologicznej.<br>
                    Admin jest odpowiedzialny za zarządzanie, konfigurację, utrzymanie i ochronę systemu, co obejmuje zarządzanie użytkownikami, nadzorowanie bezpieczeństwa,<br> aktualizacje oprogramowania oraz rozwiązywanie problemów technicznych.».</p><br>
                <h2>Zakres obowiązków</h2><br>
                <p class="Admin_definicja">
                    Admin tworzy, usuwa i zarządza kontami użytkowników, nadaje uprawnienia i monitoruje ich aktywność.<br>
                    Odpowiada za ustawienia konfiguracyjne systemu lub strony, aby wszystko funkcjonowało zgodnie z wymaganiami organizacji.<br>
                    Prowadzi regularne aktualizacje oprogramowania, instaluje łatki bezpieczeństwa i wykonuje kopie zapasowe danych.<br>
                    Zabezpiecza system przed zagrożeniami zewnętrznymi i wewnętrznymi, zarządza firewallami, antywirusami i innymi narzędziami bezpieczeństwa.<br>
                </p><br>
                <h2>Narzędzia i umiejętności</h2><br>
                <p class="Admin_definicja">
                    Admin musi posiadać rozległą wiedzę na temat systemów operacyjnych, sieci komputerowych, baz danych i aplikacji.<br>
                    Używa narzędzi do monitorowania wydajności systemu i rozwiązywania problemów.<br>
                    Często wymagana jest znajomość języków skryptowych lub programowania w celu automatyzacji zadań i dostosowywania funkcji systemu.<br>
                    Admin (administrator) pełni kluczową rolę w każdej organizacji wykorzystującej technologię informatyczną, zapewniając sprawne i bezpieczne<br> funkcjonowanie systemów oraz ochronę przed potencjalnymi zagrożeniami.
                </p>
            </div>
        <?php endif; ?>
        <?php if ($_SESSION['Rola_user'] == 'Nauczyciel'): ?>
            <div class="date" id='date'></div>
            <div class="hour" id="hour"></div>
            <div class="iframe-container">
                <iframe src="ZN_Pedagogika_2016_13_ egna ek.pdf"></iframe>
            </div>
        <?php endif; ?>
        <?php if ($_SESSION['Rola_user'] == 'Uczen'): ?>
            <div class="date" id='date'></div>
            <div class="hour" id="hour"></div>
            <div class="left">
                <div class="card plan_lekcji">
                    <div class="card-header">Plan Lekcji</div>
                    <div class="card-content-open">
                        <?php       
                        
                            function get_moved_lesson($data_today){
                                //Sigma?
                                




                            }
                            function handleLessonChanges($rows, $index, $lesson, $conn, $data_today) {
                                $was = [];
                                foreach ($rows as $row) {
                                    $lekcja_index = explode(" ", $row['co_sie_dzieje'] )[0];
                                    $Rodzaj = $row['rodzaj'];
                                    $przedmiot_zasptepstwo = explode(" ", $row['co_sie_dzieje'] )[2] ?? '';
                                    $sala_zastepstwo = explode(" ", $row['co_sie_dzieje'] )[3] ?? '';
                                    $id_nauczyciela_zastepstwo = explode(" ", $row['co_sie_dzieje'] )[1] ?? '';
                                    $data_przesunieta = explode(" ", $row['co_sie_dzieje'] )[1] ?? '';
                                    $index_przesunieta = explode(" ", $row['co_sie_dzieje'] )[2] ?? '';
                                    $flag = true;

                                    // Checking if the current lesson matches the affected lesson
                                    if (isset($lekcja_index) && $lekcja_index == $index && !array_search($index, $was) && $row['data'] == $data_today) {
                                        array_push($was, $index);
                                        
                                        switch ($Rodzaj) {
                                            case 'Odwolaj':
                                                echo "Uczniowie Zwolnieni <br> <del>" ;
                                                foreach ($lesson as $key => $val) {
                                                    echo "$key: $val<br>";
                                                }
                                                echo "</del>";
                                                $flag = false;
                                                break;
                                            
                                            case 'Przesun':
                                                echo "<b>Lekcja przeniesiona</b> <br> <del>"; 
                                                foreach ($lesson as $key => $val) {
                                                    echo "$key: $val<br>";
                                                }
                                                echo "</del>";
                                                $flag = false;
                                                break;
                                            
                                            case 'Zastepstwo':
                                                $text = "";
                                                if ($przedmiot_zasptepstwo !== $lesson->Przedmiot) {
                                                    $text .= "Przedmiot: <del>" . $lesson->Przedmiot . '</del> ' . $przedmiot_zasptepstwo . "<br>";
                                                } else {
                                                    $text .= "Przedmiot: ".$lesson->Przedmiot . '<br>';
                                                }

                                                if ($id_nauczyciela_zastepstwo !== $lesson->Nauczyciel) {
                                                    $sql = "SELECT * FROM users WHERE id = '$id_nauczyciela_zastepstwo'";
                                                    $result = $conn->query($sql);
                                                    if ($result && $result->num_rows > 0) {
                                                        $substituteTeacher = $result->fetch_assoc();
                                                        $sql = "SELECT * FROM users WHERE id = '$lesson->Nauczyciel'";
                                                        $result = $conn->query($sql);
                                                        if ($result && $result->num_rows > 0) {
                                                            $Naaaa = $result->fetch_assoc();
                                                        }
                                                        $text .= "Nauczyciel: <del><b>" . $Naaaa["Imie"]. " ". $Naaaa["Nazwisko"] . "</b></del> <b>" . $substituteTeacher['Imie'] . " " . $substituteTeacher['Nazwisko'] . "</b><br>";
                                                    } else {
                                                        $text .= "Nauczyciel: <del><b>" . $lesson->Nauczyciel . "</b></del><br>";
                                                    }
                                                } else {
                                                    $text .= "Nauczyciel: <b>" . $lesson->Nauczyciel . "</b><br>";
                                                }

                                                
                                                if ($sala_zastepstwo !== $lesson->Sala) {
                                                    $text .= "Sala: <del>" . $lesson->Sala . '</del> ' . $sala_zastepstwo . "<br>";
                                                } else {
                                                    $text .= "Nauczyciel: " . $lesson->Sala . '<br>';
                                                }

                                                echo $text;
                                                $flag = false;
                                                break;

                                            default:                                                
                                                break;
                                        }
                                    } else if ( !array_search($index, $was) && $flag && $index_przesunieta != $index){
                                        // foreach ($lesson as $key => $val) {
                                        //     echo "$key: $val<br>";
                                        // }
                                        continue;
                                    }
                                    if ($index_przesunieta == $index && $data_przesunieta == $data_today) {
                                        echo "<b>Lekcja przeniesiona</b> <br>"; 
                                        echo "Z dnia: ". $row["data"]. "<br> Lekcji: ". $lekcja_index;

                                    
                                    }
                                }
                            }

                            function PlanLekcji($offset, $conn) {
                                $daysInPolish = [
                                    'Mon' => 'Poniedzialek',
                                    'Tue' => 'Wtorek',
                                    'Wed' => 'Sroda',
                                    'Thu' => 'Czwartek',
                                    'Fri' => 'Piatek',
                                    'Sat' => 'Sobota',
                                    'Sun' => 'Niedziela'
                                ];
                                
                                $dayOfWeek = date('D', strtotime("+$offset day"));
                                
                                // Check if day exists in the Polish days array
                                if (!array_key_exists($dayOfWeek, $daysInPolish)) {
                                    echo "Nieznany dzień tygodnia!";
                                    return;
                                }
                                
                                $dayPolish = $daysInPolish[$dayOfWeek];
                            
                                // Connection error check
                                if ($conn->connect_error) {
                                    die("Błąd połączenia: " . $conn->connect_error);
                                }
                            
                                // Check if the column for the current day exists in the table
                                $sql = "SELECT COLUMN_NAME 
                                        FROM INFORMATION_SCHEMA.COLUMNS 
                                        WHERE TABLE_NAME = 'plany lekcji' 
                                        AND COLUMN_NAME = '$dayPolish'";
                                $result = $conn->query($sql);
                                
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $columnName = $row["COLUMN_NAME"];
                                        $klasa = $_SESSION['Klasa_user'];
                            
                                        // Fetch class schedule
                                        $sqlData = "SELECT `$columnName` FROM `plany lekcji` WHERE Klasa = '$klasa'";
                                        $resultData = $conn->query($sqlData);
                            
                                        // Check for schedule changes on the current date
                                        $data_today = date("Y-m-d", strtotime("+$offset day"));
                                        $sql_question = "SELECT * 
                                                        FROM `zmiany_plan_lekcji`
                                                        WHERE `data` = '$data_today' 
                                                           OR (`rodzaj` = 'Przesun' AND 
                                                            FIND_IN_SET('$data_today', REPLACE(`co_sie_dzieje`, ' ', ',')) > 0);
                                                        ";
                                        $resultChanges = $conn->query($sql_question);
                            
                                        $rows = [];
                                        while ($row = $resultChanges->fetch_assoc()) {
                                            $rows[] = $row;
                                        }
                                        // print_r($rows);
                                        // echo $data_today;
                            
                                        // Display schedule
                                        if ($resultData->num_rows > 0) {
                                            while ($dataRow = $resultData->fetch_assoc()) {
                                                $dzien = json_decode($dataRow[$columnName]);
                                                $c = -1;
                                                echo "<div class='hide'><table>";
                                                foreach ($dzien as $value) {
                                                    $c++;
                                                    echo "<tr>";
                                                    foreach ($rows as $row) {
                                                        $lekcja_index = explode(" ", $row['co_sie_dzieje'] )[0];
                                                        $Rodzaj = $row['rodzaj'];
                                                        $przedmiot_zasptepstwo = explode(" ", $row['co_sie_dzieje'] )[2] ?? '';
                                                        $sala_zastepstwo = explode(" ", $row['co_sie_dzieje'] )[3] ?? '';
                                                        $id_nauczyciela_zastepstwo = explode(" ", $row['co_sie_dzieje'] )[1] ?? '';
                                                        if ($Rodzaj == "Przesun") {
                                                            $index_przesun = explode(" ", $row['co_sie_dzieje'] )[2] ?? '';
                                                            $data_przesun = explode(" ", $row['co_sie_dzieje'] )[1] ?? '';
                                                            if($data_today == $data_przesun && $c == $index_przesun){
                                                                $JD = true;
                                                            }
                                                        }
                                                    }
                                                    if (is_object($value)) {
                                                        echo "<td>$c.</td><td>";
                                                        if(isset($JD) && $JD){
                                                            handleLessonChanges($rows, $c, $value, $conn, $data_today);
                                                            unset($JD);
                                                        }
                                                        if (empty($rows)) {
                                                            foreach ($value as $key => $val) {
                                                                echo "$key: $val<br>";
                                                            }                                                            
                                                        } else {
                                                            handleLessonChanges($rows, $c, $value, $conn, $data_today);
                                                        }
                                                        echo "</td>";
                                                    } else{
                                                        if(isset($JD) && $JD){
                                                            
                                                            echo "<td>$c.</td><td>";
                                                            handleLessonChanges($rows, $c, $value, $conn, $data_today);
                                                            echo "</td>";
                                                            unset($JD);
                                                        }else{
                                                            echo "<td>$c.</td><td>-</td>";
                                                        }
                                                    }

                                                    echo "</tr>";
                                                }
                                                echo "</table></div>";
                                            }
                                        } else {
                                            echo "Brak lekcji w $columnName.<br>";
                                        }
                                    }
                                } else {
                                    echo "Brak lekcji w dniu $dayPolish.";
                                }
                            }
                            
                            
                            echo "<h2 onclick='headerOpen(this)'>Dzisiejszy plan lekcji &#x25BE;</h2>";
                            PlanLekcji(0, $conn);
                            
                            echo "<h2 onclick='headerOpen(this)'>Jutrzejszy plan lekcji &#x25BE;</h2>";
                            PlanLekcji(1, $conn);
                        ?>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="card">
                    <div class="card-header" onclick="toggleContent(this)">Najbliższy Sprawdzian</div>
                    <div class="card-content">
                        <?php
                            function SprawdzianNajblizej($user_id, $conn) {
                                $today = date('Y-m-d');
                                $tommorow = date('Y-m-d', strtotime('+1 day'));
                                $klasa = $_SESSION['Klasa_user'];
                                $sql = "SELECT * FROM tests WHERE (data = '$today' OR data = '$tommorow') and klasa = '$klasa' ORDER BY data ASC";
                                $result = $conn->query($sql);
                                $rows = [];
                                while($row = $result->fetch_assoc()) {
                                    $rows[] = $row;
                                }
                                
                                    if ($rows) {
                                        echo "Najbliższy Test(y):<br>";
                                        foreach ($rows as $exam){
                                            echo $exam['przedmiot'] . " - " . $exam['data'].":<b> ".$exam['kategoria']."</b><br>";
                                        }
                                    } else {
                                        echo "Brak nadchodzących sprawdzianów.";
                                    }
                            }

                            SprawdzianNajblizej($_SESSION['user_id'], $conn);
                        ?>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" onclick="toggleContent(this)">Ostatnie oceny</div>
                    <div class="card-content">
                        <?php 
                            $id = $_SESSION['user_id'];
                            $sql = "SELECT * FROM `users_oceny` WHERE id_ucznia = '$id'";
                            $result = $conn->query($sql);

                            $rows = [];
                            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                                $rows[] = $row;
                            }
                            if ($rows) {
                                $rows = $rows[0];
                                $przedmioty = array_slice($rows,2);
                                foreach ($przedmioty as $key => $val) {
                                    if($val!=="" && $val!= NULL) {
                                        $osan = explode("$", $val);                        ;                                                    
                                        echo "<b>$key</b>: $osan[0]<br>";
                                    }else{
                                        echo "<b>$key</b>: Brak ocen<br>";
                                    }
                                }
                            }else{
                                echo '<p><b>BRAK OCEN</b></p>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <script>
        function updateHour() {
            let date = new Date();
            let hour = date.getHours();
            let minutes = date.getMinutes();
            minutes = minutes.toString().padStart(2, '0');
            hour = hour.toString().padStart(2, '0');
            let fullHour = hour + ":" + minutes;

            let hourbox = document.getElementById('hour');
            hourbox.innerHTML = fullHour;
        }
        function updateDate() {
            let date = new Date();
            const daysInPolish = ['Niedziela', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota'];
            let day = date.getDate();
            let month = date.getMonth();
            let year = date.getFullYear();
            let dayOfWeek = date.getDay(); 
            const monthsInPolish = ['stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca', 'sierpnia', 'września', 'października', 'listopada', 'grudnia'];
            let fullDate = day + " " + monthsInPolish[month] + " " + year + ",<br>" + daysInPolish[dayOfWeek];
            let datebox = document.getElementById('date');
            datebox.innerHTML = fullDate;
        }
        updateDate();
        updateHour();
        setInterval(updateHour, 1000);
        setInterval(updateDate, 1000);

        function headerChcek(){
            if(window.outerWidth <= 1255){
                let header = document.querySelector('header');
                let headerHeight = header.offsetHeight;
                let content =document.querySelector(".container");
                content.style.marginTop = headerHeight + 'px';
                let date = document.getElementById('date');
                let hour = document.getElementById('hour');
                date.style.top = headerHeight + 10 + 'px';
                hour.style.top = headerHeight + 60 + 'px';
            }else{

                let content =document.querySelector(".container");
                content.style.marginTop = 0 ;
                let date = document.getElementById('date');
                let hour = document.getElementById('hour');
                date.style.top = 130 + 'px';
                hour.style.top = 180 + 'px';
            }
        }

        window.addEventListener('resize',headerChcek)
        headerChcek();
    </script>

    <?php include 'footer.php' ?>
    <script>
        function toggleContent(element) {
            const card = element.parentElement.querySelector('.card-content');
            card.classList.toggle('expanded');
            setTimeout(function() {
                checkContentOverflow();
            }, 221);
            
        }
        function headerOpen(element) {
            const table = element.nextElementSibling;
            table.classList.toggle('show');
            setTimeout(function() {
                checkContentOverflow();
            }, 221);
        }
    </script>
</body>
</html>
