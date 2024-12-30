<?php
session_start();
if (!isset($_SESSION['Login']) && !$_SESSION['Login'] && $_SESSION['Rola_user'] != 'Admin_d') {
    header('Location: zaloguj.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>Dodawanie nowej osoby</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
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
            padding-top: 100px;
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
        }
        .show{
            display: flex !important;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
            font-size: 16px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f9f9f9;
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
        }

        input[type="submit"]:hover {
            background: linear-gradient(135deg, #5c88e5, #6fa3f1);
        }
        .dropdown{
            padding: 10px 3px;
        }
        .dropdown div{
            padding: 5px;
            border-bottom: 1px solid #ccc;
            cursor: pointer;
        }
        .dropdown div:hover{
            background-color: #f0f0f0;
            transition: background-color 0.3s ease;
        }
    </style>
</head>
<body>
    <?php 
    if (isset($_SESSION['isis'])): ?>
        <div id="success-box" class="alert-box">
            <strong>Sukces!</strong> <?= htmlspecialchars($_SESSION['isis']) ?>
            <div class="timer-bar"></div>
        </div>
        <?php unset($_SESSION['isis']); ?>
    

        <style>
            .alert-box {
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background-color: #28a745;
                color: white;
                padding: 15px 20px;
                border-radius: 5px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                z-index: 99999;
                text-align: center;
                width: 400px;
                font-size: 16px;
                font-weight: bold;
            }

            .timer-bar {
                position: absolute;
                bottom: 0;
                left: 0;
                height: 5px;
                background-color: #155724;
                animation: countdown 3s linear forwards;
                width: 100%;
            }

            @keyframes countdown {
                from {
                    width: 100%;
                }
                to {
                    width: 0%;
                }
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const alertBox = document.getElementById('success-box');
                if (alertBox) {
                    setTimeout(() => {
                        alertBox.style.display = 'none';
                    }, 3000);
                }
            });
        </script>
    <?php endif; ?>
    <header>
        <h1>Dodaj nową osobę</h1>
        <?php include 'nav.php'; ?>
    </header>
    <form action="Admin_add_person.php" method="POST">

        <div class="form-group">
            <label for="imie">Imię:</label>
            <input type="text" id="imie" name="imie" required>
        </div>

        <div class="form-group">
            <label for="nazwisko">Nazwisko:</label>
            <input type="text" id="nazwisko" name="nazwisko" required>
        </div>

        <div class="form-group">
            <label for="klasa">Klasa:</label>
            <input type="text" id="klasa" name="klasa" required>
        </div>

        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="haslo">Hasło:</label>
            <input type="password" id="haslo" name="haslo" required>
        </div>

        <div class="form-group">
            <label for="rola">Rola:</label>
            <select id="rola" name="rola" required>
                <option value="Uczen">Uczeń</option>
                <option value="Nauczyciel">Nauczyciel</option>
                <option value="Admin">Administrator</option>
                <option value="Admin_d">Admin_d</option>
            </select>
        </div>

        <div class="form-group">
            <label for="czego_uczy">Czego uczy (jeśli dotyczy):</label>
            <input type="text" id="czego_uczy" name="czego_uczy">
        </div>

        <div class="form-group school-selector">
            <label for="szkola">Szkoła: </label>
            <input type="hidden" name="id_szkoly" value="">                                                                                             
            <input type="text" class="school-search" id="szkola" placeholder="Wyszukaj szkołę" autocomplete="off" required>
            <div class="dropdown"></div>
        </div>

        <input type="submit" value="Dodaj osobę">
    </form>


    <script>

        const searchInputs = document.querySelectorAll('.school-search');

        searchInputs.forEach((input) => {
            const parentDiv = input.closest('.school-selector');
            const dropdown = parentDiv.querySelector('.dropdown');

            input.addEventListener('input', function () {
                const query = this.value;

                if (query.length >= 2) {
                    fetch(`search_schools.php?query=${encodeURIComponent(query)}`)
                        .then((response) => response.json())
                        .then((data) => {
                            dropdown.innerHTML = ''; 

                            data.forEach((school) => {
                                const item = document.createElement('div');
                                item.classList.add('dropdown-item');
                                item.textContent = `${school.nazwa_szkoly}`;
                                item.dataset.value = school.Id_szkoly;

                                item.addEventListener('click', () => {
                                    input.value = `${school.nazwa_szkoly}`;
                                    try{
                                        parentDiv.querySelector('input[name="id_szkoly"]').value = school.Id_szkoly;
                                    } catch (error) {
                                        parentDiv.querySelector('input[name="school_filter"]').value = school.Id_szkoly;
                                    }
                                    dropdown.style.display = 'none';
                                });

                                dropdown.appendChild(item);
                            });

                            const item = document.createElement('div');
                                item.classList.add('dropdown-item');
                                item.textContent = `Brak`;
                                item.dataset.value = '.';

                                item.addEventListener('click', () => {
                                    input.value = `Brak`;
                                    parentDiv.querySelector('input[name="id_szkoly"]').value = '.';
                                    dropdown.style.display = 'none';
                                });

                                dropdown.appendChild(item);

                            dropdown.style.display = 'block'; 
                        })
                        .catch((error) => console.error('Błąd:', error));
                } else {
                    dropdown.style.display = 'none'; 
                }
            });

            document.addEventListener('click', (event) => {
                if (!parentDiv.contains(event.target)) {
                    dropdown.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
<?php include "disabled_functions.html"?>