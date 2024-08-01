<?php
require 'db.php';
session_start();

if (isset($_SESSION['Login']) && $_SESSION['Rola_user'] === 'Uczen') {
    $id_ucznia = $_SESSION['user_id'];

    $conn = new mysqli($server_name, $user_name, $password, $database);
    if ($conn->connect_error) {
        die('ERROR: ' . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM users_oceny WHERE id_ucznia = ?");
    $stmt->bind_param("i", $id_ucznia);
    $stmt->execute();
    $result = $stmt->get_result();
    $oceny = $result->fetch_assoc();

    if (!$oceny) {
        echo "Nie znaleziono ocen dla podanego ID ucznia.";
        die();
    }

    function parseOceny($ocenyStr) {
        if (empty($ocenyStr)) {
            return [];
        }

        $oceny = explode(',', $ocenyStr);
        $parsedOceny = [];

        foreach ($oceny as $ocena) {
            list($ocenaVal, $wagaAndOpis) = explode(':', $ocena);
            list($waga, $opis) = explode('-', $wagaAndOpis);
            $parsedOceny[] = [
                'ocena' => (float) $ocenaVal,
                'waga' => (float) $waga,
                'opis' => $opis 
            ];
        }

        return $parsedOceny;
    }

    function calculateAverage($parsedOceny) {
        $totalWeightedGrade = 0;
        $totalWeight = 0;

        foreach ($parsedOceny as $ocena) {
            $totalWeightedGrade += $ocena['ocena'] * $ocena['waga'];
            $totalWeight += $ocena['waga'];
        }

        return $totalWeight ? $totalWeightedGrade / $totalWeight : 0;
    }

    $ocenySubjects = array_slice($oceny, 2);
    $subjectAverages = [];
    $overallTotalWeighted = 0;
    $overallTotalWeight = 0;

    foreach ($ocenySubjects as $subject => $ocenaStr) {
        $parsedOceny = parseOceny($ocenaStr);
        $average = calculateAverage($parsedOceny);
        $subjectAverages[$subject] = $average;
        $overallTotalWeighted += $average * count($parsedOceny);
        $overallTotalWeight += count($parsedOceny);
    }

    $overallAverage = $overallTotalWeight ? $overallTotalWeighted / $overallTotalWeight : 0;
} else {
    echo "Nie podano ID ucznia.";
    die();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oceny <?php echo htmlspecialchars($_SESSION['Imie_user'] . " " . $_SESSION['Nazwisko_user']); ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
        }
        .container {
            display: flex;
            flex-direction: column;
            width: 100%;
            margin-top: 80px;
        }
        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            height: calc(100vh - 100px);
            overflow-y: auto;
            position: fixed;
            top: 100px;
            left: 0;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar ul li {
            padding: 15px;
            cursor: pointer;
            border-bottom: 1px solid #444;
        }
        .sidebar ul li:hover {
            background-color: #444;
        }
        .content {
            
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }
        h2, h3 {
            color: #333;
        }
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
            color: #555;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .average {
            margin: 10px 0;
            font-size: 1.2em;
            font-weight: bold;
        }
        .average.green {
            color: #4CAF50;
        }
        .average.red {
            color: #FF0000;
        }
        .hidden {
            display: none;
        }
        td:has(.ocena){
            display: grid;
            place-items: center;
        }
        .ocena {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
            margin: 5px;
            transition: all 0.3s ease;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0);
        }

        .ocena1 {
            background-color: #ff6666;
            color: white;
        }

        .ocena2 {
            background-color: #ff9933;
            color: white;
        }

        .ocena3 {
            background-color: #ffff66;
            color: #333;
        }

        .ocena4 {
            background-color: #66ff99;
            color: #333;
        }

        .ocena5 {
            background-color: #33cc33;
            color: white;
        }

        .ocena6 {
            background-color: #66ccff;
            color: #333;
        }

        .ocena:hover {
            transform: scale(1.05);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        @media screen and (max-width: 850px) {
            .sidebar{
                width: 150px;
                text-wrap: wrap;
            }
            footer.content-overflow{
                width: 100% !important;
                left: 0 !important;
            }
            .container{
                margin-top: 62.5px;
            }
            .content{
                margin-left: 0;
            }
            .sidebar{
                display: none;
            }
            .content-overflow{
                font-size: 0.7em;
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
        .barinnav ul{
            list-style: none;
            padding: 0;
        }
        .barinnav li{
            font-size: 1.4em;
            cursor: pointer;
            transition: all .3s linear;
        }
        .barinnav li:hover{
            background-color: #333 ;
            color: #f1f1f1;
        }
        details {
            width: 70%;
            background-color: #111;
            border: 1.5px solid #ddd;
            border-radius: 8px;
            margin: 0 auto;
        }
        summary {
            padding: 10px;
            cursor: pointer;
            outline: none;
            font-weight: bold;
        }

    </style>
    <script>
        function headerChcek(){
            let header = document.querySelector('header');
            let headerHeight = header.offsetHeight;
            let content =document.querySelector(".container");
            content.style.marginTop = headerHeight + 'px';
        }

        function showSubject(subject) {
            document.querySelectorAll('.subject').forEach(function(elem) {
                elem.classList.add('hidden');
            });
            document.getElementById(subject).classList.remove('hidden');
        }
        function addSidenav() {

            let sidebar = document.querySelector(".container > .sidebar");
            let nav = document.querySelector('#mySidenav > .content-nav');

            if (window.outerWidth <= 850 && !nav.querySelector('details')) {
                let details = document.createElement('details');
                let summary = document.createElement('summary');
                summary.textContent = 'Wybierz przemiot'; 
                details.appendChild(summary);
                details.appendChild(sidebar.cloneNode(true));
                nav.appendChild(details);
                let ocs = document.querySelector('details > .sidebar');
                ocs.setAttribute('class', 'barinnav');

            } else if (window.innerWidth > 850) {
                let detailsElement = nav.querySelector('details');
                if (detailsElement) {
                    detailsElement.remove();
                }
            }
        }
        window.addEventListener('resize', function() {
            addSidenav();
            headerChcek();

            let subjects = document.querySelectorAll('.subject');
            let footer = document.querySelector('.content-overflow');
            
            if (footer) {
                let footerStyles = window.getComputedStyle(footer);
                
                let sizeOfFooter = footerStyles.height;
                
                subjects.forEach(function(subject) {
                    subject.style.marginBottom =sizeOfFooter;
                });
            }
        });
        document.addEventListener("DOMContentLoaded", function() {
            addSidenav();
            headerChcek();
        });


        
    </script>
</head>
<body>
    <header>
        <h1>Oceny <?php echo htmlspecialchars($_SESSION['Imie_user'] . " " . $_SESSION['Nazwisko_user']); ?></h1>
        <?php include 'nav.php'; ?>
    </header>

    <div class="container">
        <div class="sidebar">
            <ul>
                <?php foreach ($ocenySubjects as $subject => $ocenaStr) : ?>
                    <li onclick="showSubject('<?php echo htmlspecialchars($subject); ?>')"><?php echo htmlspecialchars($subject); ?></li>
                <?php endforeach; ?>
                <li onclick="showSubject('koniec')">Koniec</li>
            </ul>
        </div>
        <div class="content">
            <?php foreach ($ocenySubjects as $subject => $ocenaStr) : ?>
                <div id="<?php echo htmlspecialchars($subject); ?>" class="subject hidden">
                    <h2><?php echo htmlspecialchars($subject); ?></h2>
                    <div class="table-container">
                        <table>
                            <tr>
                                <th>Ocena</th>
                                <th>Waga</th>
                                <th>Opis</th>
                            </tr>
                            <?php foreach (parseOceny($ocenaStr) as $ocena) : ?>
                                <tr>
                                    <td><div class="ocena ocena<?=$ocena['ocena']?>"><?php echo $ocena['ocena']; ?></div></td>
                                    <td><?php echo $ocena['waga']; ?></td>
                                    <td><?php echo $ocena['opis']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    <div class="average <?php echo $subjectAverages[$subject] < 1.85 ? 'red' : 'green'; ?>">
                        Średnia z przedmiotu: <?php echo number_format($subjectAverages[$subject], 2); ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <div id="koniec" class="subject hidden">
                <h2>Średnie końcowe z przedmiotów</h2>
                <table>
                    <tr>
                        <th>Przedmiot</th>
                        <th>Średnia</th>
                    </tr>
                    <?php foreach ($subjectAverages as $subject => $average) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($subject); ?></td>
                            <td class="<?php echo $average < 1.85 ? 'red' : 'green'; ?>"><?php echo number_format($average, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="average <?php echo $overallAverage < 1.85 ? 'red' : 'green'; ?>">
                    Średnia końcowa ze wszystkich przedmiotów: <?php echo number_format($overallAverage, 2); ?>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php' ?>
    <style>
        footer.content-overflow{
            position: fixed;
            left: 250px;
            bottom: 0;
            width: calc(100% - 250px);
        }
        .content{
            width: auto !important;
        }
    </style>
        
    
    
</body>
</html>
