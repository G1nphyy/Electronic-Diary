<?php
session_start();

if (!isset($_SESSION['Login']) && !$_SESSION['Login'] && $_SESSION['Rola_user'] != 'Admin_d') {
    header('Location: zaloguj.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>Dodawanie nowej szkoły</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        h1{
            font-size: 24px;
            font-weight: bold !important;
            margin: 21.44px 0;
        }
        summary{
            display: list-item !important;
        }
        .nav{
            font-size: 3rem !important;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding-top: 150px; 
            padding-bottom: 50px;
            padding-left: 20px; 
            padding-right: 20px;
        }
        form {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            transition: transform 0.3s ease-in-out;
        }
        .show{
            display: flex !important;
            gap: 10px !important;
        }
        .show button{
            margin: 0 !important;
        }
        form:hover {
            transform: scale(1.02);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
            font-size: 16px;
        }

        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f9f9f9;
            transition: border-color 0.3s, background-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        select:focus {
            border-color: #007bff;
            background-color: #fff;
            outline: none;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #6e9ffe, #7eb5f9);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }

        input[type="submit"]:hover {
            background: linear-gradient(135deg, #5c88e5, #6fa3f1);
        }

        input[type="submit"]:active {
            background-color: #5c88e5;
            box-shadow: 0 3px 10px rgba(0, 123, 255, 0.2);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group select {
            padding-right: 30px;
        }

        input[type="text"], input[type="date"], select {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s ease forwards;
        }
        button {
            margin-top: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #0056b3;
        }

        input[type="submit"] {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.7s ease forwards;
        }
        .btn-primary{
            margin: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Dodaj nową szkołę</h1>
        <?php include 'nav.php';?>
    </header>
    <form action="Admin_add_school.php" method="POST">

        <div class="form-group">
            <label for="nazwa_szkoly">Nazwa szkoły:</label>
            <input type="text" id="nazwa_szkoly" name="nazwa_szkoly" required>
        </div>

        <div class="form-group">
            <label for="adres_szkoly">Adres szkoły:</label>
            <input type="text" id="adres_szkoly" name="adres_szkoly" required>
        </div>

        <div class="form-group">
            <label for="dyrektor_szkoly">Dyrektor szkoły:</label>
            <input type="text" id="dyrektor_szkoly" name="dyrektor_szkoly">
        </div>

        <div class="form-group">
            <label for="zastepca_dyrektora_szkoly">Zastępca dyrektora szkoły:</label>
            <input type="text" id="zastepca_dyrektora_szkoly" name="zastepca_dyrektora_szkoly">
        </div>

        <div class="form-group">
            <label for="kod_szkoly">Kod Szkoły:</label>
            <span class="input-group">
                <input type="text" class="form-control" id="kod_szkoly" name="kod_szkoly" readonly required>
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" onclick="Random_code()">
                        <span class="glyphicon glyphicon-refresh"></span> Generuj Kod
                    </button>
                </span>
            </span>
        </div>


        <script>
            function Random_code() {
                const length = 8;
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                let result = '';
                for (let i = 0; i < length; i++) {
                    result += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Admin_check_code.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.status === 200) {
                        if (this.responseText == 'exists') {
                            Random_code(); 
                        } else {
                            document.getElementById('kod_szkoly').value = result; 
                        }
                    }
                };
                xhr.send('kod_szkoly=' + result);
            }
        </script>

        <div class="form-group">
            <label for="pedagog">Pedagog:</label>
            <input type="text" id="pedagog" name="pedagog">
        </div>

        <div class="form-group">
            <label for="psyhiatra">Psychiatra:</label>
            <input type="text" id="psyhiatra" name="psyhiatra">
        </div>

        <div class="form-group">
            <label for="lekarz">Lekarz:</label>
            <input type="text" id="lekarz" name="lekarz">
        </div>

        <div class="form-group">
            <label for="data_dolaczenia">Data dołączenia:</label>
            <input type="date" id="data_dolaczenia" name="data_dolaczenia" required>
            <script>
                const today = new Date();
                const formattedDate = today.getFullYear() + '-' + 
                                    String(today.getMonth() + 1).padStart(2, '0') + '-' + 
                                    String(today.getDate()).padStart(2, '0');

                document.getElementById("data_dolaczenia").value = formattedDate;
            </script>
        </div>
        

        <div class="form-group">
            <label for="typ_szkoly">Typ szkoły:</label>
            <select id="typ_szkoly" name="typ_szkoly" required>
                <option value="Liceum">Liceum</option>    
                <option value="Szkoła Podstawowa">Szkoła Podstawowa</option>    
                <option value="Technikum">Technikum</option>
                <option value="Szkoła Branżowa">Szkoła Branżowa</option>  
            </select>
        </div>

        <div class="form-group">
            <label for="status_public_private">Status (Publiczna/Prywatna):</label>
            <select id="status_public_private" name="status_public_private" required>
                <option value="Publiczna">Publiczna</option>
                <option value="Prywatna">Prywatna</option>
            </select>
        </div>

        <div class="form-group">
            <label for="internat">Internat (Tak/Nie):</label>
            <select id="internat" name="internat">
                <option value="Tak">Tak</option>
                <option value="Nie">Nie</option>
            </select>
        </div>

        <input type="submit" value="Dodaj szkołę">
    </form>
</body>
</html>
<?php include "disabled_functions.html"?>