<?php 
session_start();

if (!isset($_SESSION['Login'])) {
    header('Location: zaloguj.php');
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
            min-width: 40%;
            max-width: 90%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .info {
            margin-bottom: 10px;
            position: relative;
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
        .left{
            width: 60%;
            padding: 10px 20px;
        }
        .right{
            display: flex;
            flex-direction: column;
            width: 40%;
            padding: 10px 20px;
        }
        .right img{
            width: 300px;
        }
        .row{
            flex-direction: row-reverse;
            display: flex;
        }
        .custom-select {
            position: relative;
            display: inline-block;
        }

        .selected-option {
            padding: 5px;
            cursor: pointer;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .selected-option img {
            max-width: 50px;
            max-height: 50px;
        }

        .options {
            display: none;
            position: absolute;
            background-color: white;
            border: 1px solid #ccc;
            z-index: 1;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
        }

        .option {
            padding: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        img#selected-icon.bez{
            width: 100%;
            max-width: 100%;
            max-height: 100%;
            text-align: center;
        }

        .option img {
            max-width: 50px;
            max-height: 50px;
        }
        .reset-pass{
            user-select: none;
            text-decoration: underline #3333ff;
            cursor: pointer;
            color: #2222aa;
        }
        form[action="changepassword.php"]{
            position: absolute;
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            top: 150%;
            left: 25%;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);

        }

        .option:hover {
            background-color: #f1f1f1;
        }
        @media screen and (max-width: 800px) {
            .container{
                min-width: 80%;
            }
            .row{
                flex-direction: column;
            }
            .left{
                width: auto;
            }
            .right{
                width: auto;
                display: flex;
                justify-content: center;
                align-items: center;
            }
        }
        @media screen and (max-width: 450px) {
            header h1{
                padding-right: 100px;
            }
            .table-container{
                overflow: auto;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Informacje o tobie</h1>
        <?php include 'nav.php'; ?>
    </header>
    <div class="container">
        <div class="row">
            <div class="left">
                <div class="info"><strong>Imię:</strong> <?php echo htmlspecialchars($_SESSION['Imie_user']); ?></div>
                <div class="info"><strong>Nazwisko:</strong> <?php echo htmlspecialchars($_SESSION['Nazwisko_user']); ?></div>
                <div class="info"><strong>E-mail:</strong> <?php echo htmlspecialchars($_SESSION['E-mail_user']); ?></div>
                <div class="info"><strong>Rola:</strong> <?php echo htmlspecialchars($_SESSION['Rola_user']) == 'Admin_d' ? 'Admin Dziennika Elektroniczengo © 
                                                                                                                                        <script>
                                                                                                                                            const data = new Date;
                                                                                                                                          document.write(data.getFullYear())
                                                                                                                                        </script>                           
                                                                                                                                        DDDziennik': $_SESSION['Rola_user']; ?>
                </div>

                <?php
                    $znakzapytaia = $_SESSION['Szkola_user'];
                    $sql = "SELECT * FROM schools WHERE id_szkoly = '$znakzapytaia'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();

                    if(!empty($row) and $_SESSION['Rola_user'] != 'Admin_d'):
                ?>
                <div class="info"><strong>Szkoła:</strong> <?php echo htmlspecialchars(isset($row['nazwa_szkoly']) ? $row['nazwa_szkoly']. " [". $row['kod_szkoly'] . "]" : "-"  );?></div>
                <div class="info"><strong>Dyrektor:</strong> <?php echo htmlspecialchars(isset($row['dyrektor_szkoly']) ? $row['dyrektor_szkoly'] : "-"  );?></div>
                <div class="info"><strong>Adres:</strong> <?php echo htmlspecialchars(isset($row['adres_szkoly']) ? $row['adres_szkoly'] : "-"  );?></div>
                <div class="info"><strong>Typ Szkoły:</strong> <?php echo htmlspecialchars(isset($row['typ_szkoly']) ? $row['typ_szkoly'] : "-"  );?></div>
                <div class="info"><strong>Pedagog:</strong> <?php echo htmlspecialchars(isset($row['pedagog']) ? $row['pedagog'] : "-"  );?></div>
                <div class="info"><strong>Psyhiatra:</strong> <?php echo htmlspecialchars(isset($row['psyhiatra']) ? $row['psyhiatra'] : "-"  );?></div>
                <div class="info"><strong>Lekarz:</strong> <?php echo htmlspecialchars(isset($row['lekarz']) ? $row['lekarz'] : "-"  );?></div>
                <?php endif;?>
                <?php if ($_SESSION['Rola_user'] == 'Nauczyciel') : ?>
                <div class="info"><strong>Czego uczysz:</strong> 
                <?php 
                    if (isset($_SESSION['Czego_uczy_user'])) {
                        $subjects = explode(';', htmlspecialchars($_SESSION['Czego_uczy_user']));
                        echo implode(', ', $subjects);
                    }
                ?>
                </div>

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

                <?php  if ($_SESSION['Klasa_user'] != '') : ?>
                    <div class="info">
                    <?php 
                        $klasa = $_SESSION['Klasa_user'];
                        $sql = "SELECT * FROM users WHERE Klasa = '$klasa' and Rola = 'Uczen' and  nalezy_id_szkoly = ". $_SESSION['Szkola_user'];
                        $result = $conn->query($sql);
                        $rows = [];
                        while ($row = $result->fetch_assoc()) {
                            $rows[] = $row;
                        }
                    ?>
                    <strong>Twoja Klasa<br>[<?php $c = 0; foreach($rows as $row){if ($row['id'] !== $_SESSION['user_id']){$c++;}} echo $c?> osoby]: </strong>
                    <div class="table-container">
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
                </div>
                <?php endif; if(password_verify(" ", $_SESSION["Haslo_user"])):?>
                    <div class="info"><strong>Akcje:</strong>  <span class="reset-pass" onclick="formemachen()">Zresetuj hasło</span></div>
                    <script>
                        function formemachen() {
                            if (!document.querySelector('form')) {
                                
                                const form = document.createElement('form');
                                form.action = 'changepassword.php';
                                form.method = 'post';

                                form.innerHTML = `
                                    <input type="password" name="password" placeholder="Nowe hasło" required>
                                    <span class="password-error"></span>
                                    <input type="password" name="password_repeat" placeholder="Powtórz nowe hasło" required>
                                    <span class="password-repeat-error"></span>
                                    <input type="submit" value="Akceptuj" disabled>
                                `;

                                document.querySelector(".info:has(.reset-pass)").appendChild(form);

                                const passwordInput = document.querySelector("input[name='password']");
                                const passwordRepeatInput = document.querySelector("input[name='password_repeat']");
                                const submitButton = document.querySelector("input[type='submit']");
                                const passwordError = document.querySelector(".password-error");
                                const passwordRepeatError = document.querySelector(".password-repeat-error");

                                const validatePasswords = () => {
                                    let isFormValid = true;

                                    if (passwordInput.value === "") {
                                        passwordError.textContent = "Nie podano Hasła";
                                        isFormValid = false;
                                    }
                                    else if (passwordInput.value.length < 8) {
                                        passwordError.textContent = "Hasło musi być dłuższe niż 8 znaków";
                                        isFormValid = false;
                                    }
                                    else if (passwordInput.value.includes(" ")) {
                                        passwordError.textContent = "Hasło nie może posiadać spacji";
                                        isFormValid = false;
                                    } else {
                                        passwordError.textContent = ""; 
                                    }

                                    if (passwordRepeatInput.value !== passwordInput.value) {
                                        passwordRepeatError.textContent = "Hasła nie są takie same";
                                        isFormValid = false;
                                    } else {
                                        passwordRepeatError.textContent = ""; 
                                    }

                                    submitButton.disabled = !isFormValid;
                                };

                                passwordInput.addEventListener('input', validatePasswords);
                                passwordRepeatInput.addEventListener('input', validatePasswords);

                            } else {
                                document.querySelector('form').remove();
                            }
                        }
                        </script>

                <?php endif;?>

            </div>
            
            <div class="right">
                <div class="custom-select">
                    <div class="selected-option">
                        <img src="<?=htmlspecialchars($_SESSION['Icon_user'])?>" alt="Bez zdjęcia profilowego" id="selected-icon" class="bez">
                    </div>
                    <div class="options">
                        <?php
                        $directory = 'avatars';
                        $files = scandir($directory);

                        foreach($files as $file) {
                            if ($file !== '.' && $file !== '..') {
                                $filePath = $directory . '/' . $file;
                                $selected = ($_SESSION['Icon_user'] == $filePath) ? 'selected' : '';
                                echo '<div class="option" data-value="'.htmlspecialchars($filePath).'"><img src="'.htmlspecialchars($filePath).'" alt="Zdjęcie profilowe"></div>';
                            }
                        }
                        ?>
                        <div class="option" data-value="">Bez zdjęcia profilowego</div>
                    </div>
                </div>
                <form action="changeavatar.php" method="post">
                    <input type="hidden" name="icon" id="icon" value="<?=htmlspecialchars($_SESSION['Icon_user'])?>">
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php' ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectedOption = document.querySelector('.selected-option');
            const options = document.querySelector('.options');
            const hiddenInput = document.getElementById('icon');
            const selectedIcon = document.getElementById('selected-icon');

            selectedOption.addEventListener('click', function() {
                options.style.display = options.style.display === 'block' ? 'none' : 'block';
            });

            document.querySelectorAll('.option').forEach(option => {
                option.addEventListener('click', function() {
                    const value = option.getAttribute('data-value');
                    hiddenInput.value = value;
                    const form = document.querySelector('.right form');
                    selectedIcon.src = value;
                    options.style.display = 'none';
                    form.submit();
                });
            });

            document.addEventListener('click', function(e) {
                if (!selectedOption.contains(e.target)) {
                    options.style.display = 'none';
                }
            });
        });

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
<?php include "disabled_functions.html"?>