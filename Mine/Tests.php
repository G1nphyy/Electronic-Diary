<?php
session_start();
if (!$_SESSION['Login']){
    header('Location: Index.php');
}

require_once 'db.php';
$conn = new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$_SESSION['klasa_filter'] = isset($_POST['klasa_filter']) ? $_POST['klasa_filter'] : (isset($_SESSION['klasa_filter']) ? $_SESSION['klasa_filter'] : 'all');
$klasa_filter = $_SESSION['klasa_filter'];




$x = $_SESSION['Klasa_user'];
$sql = "SELECT * FROM tests";

if($_SESSION['Rola_user'] == 'Uczen'){
    $sql .= " WHERE `klasa` LIKE '$x'";
}else if ($klasa_filter != 'all') {
    $Klasa = $klasa_filter;
    $sql .= " WHERE `klasa` LIKE '$Klasa'";
}else{
    $Klasa = 'all';
}

$sql .= ' ORDER BY data';

$result = $conn->query($sql);

$tests = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tests[] = $row;
    }
}




$week_offset = isset($_GET['week_offset']) ? intval($_GET['week_offset']) : 0;




$sql = "SELECT users.*, users_oceny.*
                FROM users 
                LEFT JOIN users_oceny ON users.id = users_oceny.id_ucznia 
                WHERE users.Rola != 'Admin'";


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

$allClassSql = "SELECT Klasa FROM users WHERE Rola != 'Admin' ORDER BY Klasa";
$allClassResult = $conn->query($allClassSql);
$allClass = $allClassResult->fetch_all(MYSQLI_ASSOC);
$allClass = array_column($allClass, 'Klasa');


function group_tests_by_week($tests) {
    $weeks = [];
    foreach ($tests as $test) {
        $week_start = date("Y-m-d", strtotime('monday this week', strtotime($test['data'])));
        if (!isset($weeks[$week_start])) {
            $weeks[$week_start] = [];
        }
        $weeks[$week_start][] = $test;
    }
    return $weeks;
}

$grouped_tests = group_tests_by_week($tests);

function get_four_weeks_dates($offset) {
    $weeks = [];
    $current_date = strtotime('monday this week') + ($offset * 4 * 7 * 86400);
    for ($i = 0; $i < 4; $i++) {
        $week = [];
        for ($j = 0; $j < 5; $j++) {
            $week[] = date("Y-m-d", strtotime("+$j days", $current_date));
        }
        $weeks[] = $week;
        $current_date = strtotime("+1 week", $current_date);
    }
    return $weeks;
}

$four_weeks_dates = get_four_weeks_dates($week_offset);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_test':
                if (isset($_POST['przedmiot'], $_POST['kategoria'], $_POST['nazwa'], $_POST['opis'], $_POST['data'])) {
                    $przedmiot = $_POST['przedmiot'];
                    $kategoria = $_POST['kategoria'];
                    $nazwa = $_POST['nazwa'];
                    $opis = $_POST['opis'];
                    $data = $_POST['data'];
                    $klasa = $_POST['klasa'];
                    $lekcja = $_POST['lekcja'];
                    $data_utworzenia = date('Y-m-d H:i:s'); 
                    $insert_sql = "INSERT INTO tests (przedmiot, kategoria, nazwa, opis, data, klasa, data_utworzenia, lekcja) VALUES ('$przedmiot', '$kategoria', '$nazwa', '$opis', '$data', '$klasa', '$data_utworzenia', '$lekcja')";

                    $conn->query($insert_sql);

                }
                break;
            case 'edit_test':
                if (isset($_POST['test_id'], $_POST['przedmiot'], $_POST['kategoria'], $_POST['nazwa'], $_POST['opis'], $_POST['data'])) {
                    $test_id = $_POST['test_id'];
                    $przedmiot = $_POST['przedmiot'];
                    $kategoria = $_POST['kategoria'];
                    $nazwa = $_POST['nazwa'];
                    $opis = $_POST['opis'];
                    $data = $_POST['data'];
                    $klasa = $_POST['klasa'];
                    $lekcja = $_POST['lekcja'];
            
                    $update_sql = "UPDATE tests SET przedmiot='$przedmiot', kategoria='$kategoria', nazwa='$nazwa', opis='$opis', data='$data', klasa='$klasa', lekcja='$lekcja' WHERE id=$test_id";
            
                    $conn->query($update_sql);
                }
                    break;
            case 'delete_test':
                if (isset($_POST['test_id'])) {
                    $test_id = $_POST['test_id'];
                    $delete_sql = "DELETE FROM tests WHERE id = $test_id";
                    $conn->query($delete_sql);
                }
                break;
            default:
                break;
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sprawdziany</title>
    <style>
        body {
            margin: 0;
            padding: 140px 0 0 0;
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        nav {
            text-align: center;
            margin-top: 10px;
        }
        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        td {
            height: 100px;
            vertical-align: top;
            position: relative;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
            margin: 20px;
            display: inline-block;
        }
        a:hover {
            text-decoration: underline;
        }
        .test p{
            padding: 0;
            margin: 0;
        }
        .one_test  p {
            padding: 5px 0 0 0;
            border: 1px solid #fff;
        }
        .one_test p:hover {
            border: 1px solid #464;
            cursor: pointer;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {

            margin: 15% auto;
            padding: 20px;
            width: fit-content;
            background-color: #fbfbfb;
            border-radius: 10px;
            box-shadow: 0 0 20px #00000051;
        }
        .modal-content p {
            padding: 20px 60px;
        }
        .close , .closen{
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
            padding: 10px 20px;
            border-radius: 50%;
            position: relative;
            cursor: pointer;

        }
        .closen{
            position: absolute;
            right: 20px;
            top: 20px;
        }
        .close::before , .closen::before{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
            border-radius: 50%;
            content: '';
            background: #ff3333a3;
            width: 0;
            height: 0;
            transition: all 0.3s ease-in-out;
            scale: 1;
            opacity: 0;

        }
        .close:hover::before , .close:focus::after , .closen:hover::before, .closen:focus::before{
            width: 100%;
            height: 100%;
            opacity: 1;
        }
        .close:hover, .close:focus, .closen:hover, .closen:focus {
            color: black;
            scale: 1.1;
            text-decoration: none;
            cursor: pointer;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 20px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            outline: none;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px 5px 0 0;
            position: fixed;
            bottom: 0;
            right: 20px;
            transition: all .3s ease-in-out;
            z-index: 100 !important;
            
        }
        .btn:hover {background-color: #0069d9; transform: translateY(5px);}
        .btn:active {
            background-color: #0069d9;
            box-shadow: 0 2px #666;
            transform: translateY(10px);
        }
        .dodaj_test {
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            position: fixed;
            z-index: 100;
            background-color: #ffffff;
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 20px 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 500px;
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translate(-50%, -60%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        .dodaj_test h2 {
            margin-top: 10px;
            color: #333;
            font-family: 'Arial', sans-serif;
            text-align: center;
        }
        .dodaj_test_header  {
            cursor: move;
        }

        .dodaj_test form {
            display: flex;
            flex-direction: column;
        }

        .dodaj_test label {
            margin-bottom: 5px;
            color: #555;
            font-family: 'Arial', sans-serif;
        }

        .dodaj_test input,
        .dodaj_test select,
        .dodaj_test textarea {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            width: calc(100% - 22px);
        }

        .dodaj_test button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Arial', sans-serif;
            font-size: 16px;
        }

        .dodaj_test button:hover {
            background-color: #218838;
        }

        .dodaj_test input[type="text"],
        .dodaj_test input[type="date"],
        .dodaj_test input[type="number"],
        .dodaj_test select,
        .dodaj_test textarea {
            width: calc(100% - 20px); 
        }

        .dodaj_test input[type="text"]:focus,
        .dodaj_test input[type="date"]:focus,
        .dodaj_test input[type="number"]:focus,
        .dodaj_test select:focus,
        .dodaj_test textarea:focus {
            border-color: #80bdff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        #filter{
            width: 30%;
            text-align: center;
        }
        .open-add-form{
            cursor: pointer;
            position: absolute;
            right: 5px;
            bottom: 5px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Sprawdziany</h1>
        <?php include 'nav.php'; ?>
    </header>
    
    <div style="width: 80%; margin: 0 auto;">
        <a href="?week_offset=<?php echo $week_offset - 1; ?>">&lt; Poprzednie Cztery Tygodnie</a>
        <a href="?week_offset=<?php echo $week_offset + 1; ?>" style="float: right;">Kolejne Cztery Tygodnie &gt;</a>
    </div>


    <?php if($_SESSION['Rola_user'] != 'Uczen') :?>
    
    <form method="post" id="filter">
        <label for="klasa_filter">Klasa:</label>
        <select name="klasa_filter" id="klasa_filter" onchange="this.form.submit()">
            <?php if (!empty($allClass)): ?>
                <?php foreach(array_unique($allClass) as $osoba): ?>
                    <option value="<?= htmlspecialchars($osoba) ?>" <?=($Klasa != '' and htmlspecialchars($osoba) == $Klasa) ? 'selected' : "" ?>> <?= htmlspecialchars($osoba)?></option>
                <?php endforeach; ?>
            <?php endif ?>
            <option value="all" <?=$Klasa == 'all' ? 'selected' : ''?>>Wszystkie</option>
        </select>
    </form>
    <div class="dodaj_test" id="edytuj_test" style="display: none">

        <div class="dodaj_test_header" id="edytuj_test_header">
            <div class="closen">
                &times;
            </div>
            <h2>Edytuj test</h2>

        </div>
        <form method="post">
            <input type="hidden" name="action" value="edit_test">
            <input type="hidden" id="edit_test_id" name="test_id">
            <label for="przedmiot">Przedmiot:</label>
            <select id="przedmiot" name="przedmiot" required>
                <?php foreach($subjectColumns as $subject):?>
                    <option value="<?= $subject ?>" selected><?= $subject ?></option>
                <?php endforeach;?>     
            </select>
            <br><br>
            <label for="kategoria">Kategoria:</label>
            <select id="kategoria_" name="kategoria" required>
                <option value="Sprawdzian">Sprawdzian</option>
                <option value="Kartkówka">Kartkówka</option>
                <option value="Odpowiedź Ustna">Odpowiedź Ustna</option>
                <option value="" id="okl_" >Własne (wpisz)</option>
            </select>
            <input type="text" id="Input_" style="display: none">
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const select = document.getElementById('kategoria_');
                    const customInput = document.getElementById('Input_');
                    select.addEventListener('change', () => {
                        const selectedOption = select.value;
                        if (selectedOption === '') {
                            customInput.style.display = 'inline-block';
                        } else {
                                customInput.style.display = 'none';
                        }
                    });
                    customInput.addEventListener('input', () => {
                        const customValue = customInput.value;
                        const customOption = document.getElementById('okl_');
                        if (customOption) {
                            customOption.value = customValue; 
                        }
                    });
                });
            </script>
            <br><br>
            <label for="klasa">Klasa:</label>
            <select name="klasa" id="klasa">
                <?php if (!empty($allClass)): ?>
                    <?php foreach(array_unique($allClass) as $osoba): ?>
                        <option value="<?= htmlspecialchars($osoba) ?>"> <?= htmlspecialchars($osoba)?></option>
                    <?php endforeach; ?>
                <?php endif ?>
            </select>
            <br><br>
            <label for="lekcja">Lekcja:</label>
            <input type="number" name="lekcja" id="lekcja" min="1" max="10" style="width: 100px">
            <br><br>
            <label for="nazwa">Nazwa:</label>
            <input type="text" id="nazwa" name="nazwa" required><br><br>
            <label for="opis">Opis:</label><br>
            <textarea id="opis" name="opis" rows="4" cols="50" required></textarea><br><br>
            <label for="data">Data:</label>
            <input type="date" id="data" name="data" required><br><br>
            <button type="submit">Edytuj test</button>
        </form>
    </div>
    <button onclick="open_form()" class="btn">Dodaj test</button>
    
    <div style="display:none;" class="dodaj_test" id="dodaj_test">
        <div class="dodaj_test_header" id="dodaj_test_header">
            <h2>Dodaj nowy test</h2>
        </div>
        <form method="post">
            <input type="hidden" name="action" value="add_test">
            <label for="przedmiot">Przedmiot:</label>
            <select id="przedmiot" name="przedmiot" required>
                <?php foreach($subjectColumns as $subject):?>
                    <option value="<?= $subject ?>" selected><?= $subject ?></option>
                <?php endforeach;?>     
            </select>
            <br><br>
            <label for="kategoria">Kategoria:</label>
            <select id="kategoria" name="kategoria" required>
                <option value="Sprawdzian">Sprawdzian</option>
                <option value="Kartkówka">Kartkówka</option>
                <option value="Odpowiedź Ustna">Odpowiedź Ustna</option>
                <option value="" id="okl" >Własne (wpisz)</option>
            </select>
            <input type="text" id="Input" style="display: none">
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const select = document.getElementById('kategoria');
                    const customInput = document.getElementById('Input');
                    select.addEventListener('change', () => {
                        const selectedOption = select.value;
                        if (selectedOption == '') {
                            customInput.style.display = 'inline-block';
                        } else {
                                customInput.style.display = 'none';
                        }
                    });
                    customInput.addEventListener('input', () => {
                        const customValue = customInput.value;
                        const customOption = document.getElementById('okl');
                        if (customOption) {
                            customOption.value = customValue; 
                        }
                    });
                });
            </script>
            <br><br>
            <label for="klasa">Klasa:</label>
            <select name="klasa" id="klasa">
                <?php if (!empty($allClass)): ?>
                    <?php foreach(array_unique($allClass) as $osoba): ?>
                        <option value="<?= htmlspecialchars($osoba) ?>"> <?= htmlspecialchars($osoba)?></option>
                    <?php endforeach; ?>
                <?php endif ?>
            </select>
            <br><br>
            <label for="lekcja">Lekcja:</label>
            <input type="number" name="lekcja" id="lekcja" min="1" max="10" style="width: 100px">
            <br><br>
            <label for="nazwa">Nazwa:</label>
            <input type="text" id="nazwa" name="nazwa" required><br><br>
            <label for="opis">Opis:</label><br>
            <textarea id="opis" name="opis" rows="4" cols="50" required></textarea><br><br>
            <label for="data">Data:</label>
            <input type="date" id="data1" name="data" required><br><br>
            <button type="submit">Dodaj test</button>
        </form>
    </div>
    <?php endif; ?>
    <table>
        <tr>
            <th>Poniedziałek</th>
            <th>Wtorek</th>
            <th>Środa</th>
            <th>Czwartek</th>
            <th>Piątek</th>
        </tr>
        <?php foreach ($four_weeks_dates as $week): ?>
            <tr>
                <?php
                $tests_by_day = [];
                foreach ($week as $date) {
                    $day_name = date('l', strtotime($date));
                    $tests_by_day[$day_name] = [];
                    foreach ($tests as $test) {
                        if ($test['data'] == $date) {
                            $tests_by_day[$day_name][] = $test;
                        }
                    }
                    usort($tests_by_day[$day_name], function($a, $b) {
                        return $a['lekcja'] <=> $b['lekcja'];
                    });
                }
                ?>
                <?php foreach ($week as $date): ?>
                    <?php if(date('d.m.Y', strtotime($date)) == date('d.m.Y')){
                        echo '<td style="box-shadow: 0 0 10px #00000051;">';        
                    }else{
                        echo "<td>";
                    } ?>
                        <?php
                        $day_name = date('l', strtotime($date));
                        if (!empty($tests_by_day[$day_name])) {
                            foreach ($tests_by_day[$day_name] as $test) {
                                echo "<div class='one_test'>";
                                echo "<div class='test' data-id='{$test['id']}' data-klasa='{$test['klasa']}' data-przedmiot='{$test['przedmiot']}' data-lekcja='{$test['lekcja']}' data-kategoria='{$test['kategoria']}' data-nazwa='{$test['nazwa']}' data-opis='{$test['opis']}' data-data_utworzenia='{$test['data_utworzenia']}' data-data='{$test['data']}'><p><strong>{$test['przedmiot']}</strong><br>{$test['kategoria']}</p></div>";
                                if($_SESSION['Rola_user'] !== 'Uczen'){
                                    echo "<br><button onclick='editTest({$test['id']})'>Edytuj</button>";
                                    echo "<form method='post' style='display: inline;'><input type='hidden' name='action' value='delete_test'><input type='hidden' name='test_id' value='{$test['id']}'><button type='submit'>Usuń</button></form>";
                                }
                                echo '</div>';
                            }
                        }

                        echo "<div>" . date('d.m.Y', strtotime($date)) . "</div>";
                        ?>
                        <?php if ($_SESSION['Rola_user'] !== 'Uczen'): ?>
                            <button class="open-add-form" data-date="<?php echo date('d.m.Y', strtotime($date)); ?>">+</button>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modalContent"></p>
        </div>
    </div>
    <script>
        function editTest(test_id) {
            var test = document.querySelector(`.test[data-id='${test_id}']`);
            var przedmiot = test.getAttribute('data-przedmiot');
            var kategoria = test.getAttribute('data-kategoria');
            var nazwa = test.getAttribute('data-nazwa');
            var opis = test.getAttribute('data-opis');
            var data = test.getAttribute('data-data');
            var klasa = test.getAttribute('data-klasa');
            var lekcja = test.getAttribute('data-lekcja');

            document.getElementById('edit_test_id').value = test_id;
            document.getElementById('przedmiot').value = przedmiot;
            document.getElementById('kategoria').value = kategoria;
            document.getElementById('nazwa').value = nazwa;
            document.getElementById('opis').value = opis;
            document.getElementById('data').value = data;
            document.getElementById('klasa').value = klasa;
            document.getElementById('lekcja').value = lekcja;

            document.getElementById('edytuj_test').style.display = 'block';
        }

        function open_form() {
            document.getElementById("dodaj_test").style.display = (document.getElementById("dodaj_test").style.display === 'block') ? 'none' : 'block';
        }
        document.addEventListener('DOMContentLoaded', (event) => {
            const addButton = document.querySelectorAll('.open-add-form');
            const dodajTestForm = document.getElementById('dodaj_test');
            const dataInput = document.getElementById('data1'); 

            addButton.forEach(button => {
                button.addEventListener('click', () => {
                    const dateStr = button.getAttribute('data-date');
                    const dateParts = dateStr.split('.');
                    const yyyy = dateParts[2];
                    const mm = dateParts[1];
                    const dd = dateParts[0];
                    const formattedDate = `${yyyy}-${mm}-${dd}`;
                    dataInput.value = formattedDate;
                    dataInput.dispatchEvent(new Event('change'));
                    dodajTestForm.style.display = 'block';
                });
            });


            var modal = document.getElementById("myModal");
            var editform = document.getElementById("edytuj_test");
            var span = document.getElementsByClassName("close")[0];
            var span2 = document.getElementsByClassName("closen")[0];
        
            document.querySelectorAll('.test').forEach(item => {
                item.addEventListener('click', event => {
                    var klasa = item.getAttribute('data-klasa');
                    var przedmiot = item.getAttribute('data-przedmiot');
                    var lekcja = item.getAttribute('data-lekcja');
                    var kategoria = item.getAttribute('data-kategoria');
                    var nazwa = item.getAttribute('data-nazwa');
                    var opis = item.getAttribute('data-opis');
                    var data_utworzenia = item.getAttribute('data-data_utworzenia');
                    var data = item.getAttribute('data-data');

                    var content = `<strong>Przedmiot:</strong> ${przedmiot}<br>
                                   <strong>Kategoria:</strong> ${kategoria}<br>
                                   <strong>Nazwa:</strong> ${nazwa}<br>
                                   <strong>Opis:</strong> ${opis}<br>
                                   <strong>Klasa:</strong> ${klasa}<br>
                                   <strong>Lekcja:</strong> ${lekcja}<br>
                                   <strong>Data utworzenia:</strong> ${data_utworzenia}<br>
                                   <strong>Data:</strong> ${data}`;

                    document.getElementById("modalContent").innerHTML = content;
                    modal.style.display = "block";
                });
            });

            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
            span2.onclick = function(){
                editform.style.display = "none";
            }

        });


        dragElement(document.getElementById("dodaj_test"));
        dragElement(document.getElementById("edytuj_test"));
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
    </script>
</body>
</html>