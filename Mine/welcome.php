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
    <title>Witaj <?= htmlspecialchars($_SESSION['Imie_user']) ?>!</title>
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
    <script>
        function toggleContent(element) {
            const card = element.parentElement.querySelector('.card-content');
            card.classList.toggle('expanded');
        }
        function headerOpen(element) {
            const table = element.nextElementSibling;
            table.classList.toggle('show');
        }
    </script>
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
                    <div class="card-header">Dzisiejszy Plan Lekcji</div>
                    <div class="card-content-open">
                        <?php
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
                                if (!array_key_exists($dayOfWeek, $daysInPolish)) {
                                    echo "Nieznany dzień tygodnia!";
                                    return;
                                }
                                
                                $dayPolish = $daysInPolish[$dayOfWeek];
                                
                                if ($conn->connect_error) {
                                    die("Błąd połączenia: " . $conn->connect_error);
                                }

                                $sql = "SELECT COLUMN_NAME 
                                        FROM INFORMATION_SCHEMA.COLUMNS 
                                        WHERE TABLE_NAME = 'plany lekcji' 
                                        AND COLUMN_NAME = '$dayPolish'";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        $columnName = $row["COLUMN_NAME"];
                                        $klasa = $_SESSION['Klasa_user'];
                                        $sqlData = "SELECT `$columnName` FROM `plany lekcji` WHERE Klasa = '$klasa'";
                                        $resultData = $conn->query($sqlData);
                            
                                        if ($resultData->num_rows > 0) {
                                            while($dataRow = $resultData->fetch_assoc()) {
                                                $dzien = json_decode($dataRow[$columnName]);
                                                $c = 0;
                                                echo "<div class='hide'><table>";
                                                foreach ($dzien as $value) {
                                                    $c++;
                                                    echo "<tr>";
                                                    if (is_object($value)) {
                                                        echo "<td>$c.</td><td>";
                                                        
                                                        foreach ($value as $key => $val) {
                                                            echo "$key: $val<br>";
                                                        }
                                                        echo"</td>";
                                                    } else {
                                                        echo "<td>$c.</td> <td>-</td>";
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
                                $sql = "SELECT * FROM tests WHERE data = '$today' OR data = '$tommorow' and klasa = '$klasa' ORDER BY data ASC";
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
                                        $val = explode(",",$val);
                                        $val = $val[count($val)-1];
                                        list($ocena,$wagaiopis) = explode(":",$val);
                                        list($waga,$opis) = explode("-",$wagaiopis);
                                        echo "<b>$key</b>: $ocena<br>";
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
</body>
</html>
