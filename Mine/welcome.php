<?php
session_start();
if(!isset($_SESSION['Login']) || !$_SESSION['Login']){
    header('Location: index.php');
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
    <title>Witaj <?= $_SESSION['Imie_user']?>!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            margin: 120px 0 0 20px;
        }
        h1 {
            margin: 20px 0;
        }
        .Admin_definicja{
            text-align: left !important;
        }
        iframe{
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
        }
        <?php if ($_SESSION['Rola_user'] == 'Nauczyciel'): ?>
            .container{
                background: #fff;
                padding: 0;
                box-shadow: none;
                border-radius: none;
                text-align: none;
                margin: 120px 0 0 20px;
            }
        <?php endif; ?>
    </style>
</head>
<body>
    <header>
        <h1>Witaj <?= $_SESSION['Rola_user'] == 'Uczen' ? "Uczniu" : ($_SESSION['Rola_user'] == 'Nauczyciel' ? "Nauczycielu" : "Adminie" )?> <?= htmlspecialchars($_SESSION['Imie_user']) ?></h1>
        <?php include 'nav.php'?>
    </header>
    <div class="container">
        <?php if($_SESSION['Rola_user'] == 'Admin'): ?>
            <p class="Admin_definicja">
                Admin - admin «Potoczne określenie administratora strony internetowej, systemu komputerowego, sieci lub dowolnej platformy technologicznej. <br> 
                Admin jest odpowiedzialny za zarządzanie, konfigurację, utrzymanie i ochronę systemu, co obejmuje zarządzanie użytkownikami, nadzorowanie bezpieczeństwa,<br> aktualizacje oprogramowania oraz rozwiązywanie problemów technicznych.».</p><br>
                <h2>Zakres obowiązków</h2><br>
                <br>
                <p class="Admin_definicja">
                    Admin tworzy, usuwa i zarządza kontami użytkowników, nadaje uprawnienia i monitoruje ich aktywność.<br>
                    Odpowiada za ustawienia konfiguracyjne systemu lub strony, aby wszystko funkcjonowało zgodnie z wymaganiami organizacji.<br>
                    Prowadzi regularne aktualizacje oprogramowania, instaluje łatki bezpieczeństwa i wykonuje kopie zapasowe danych.<br>
                    Zabezpiecza system przed zagrożeniami zewnętrznymi i wewnętrznymi, zarządza firewallami, antywirusami i innymi narzędziami bezpieczeństwa.<br>
                    <br>
                </p class="Admin_definicja">
                <h2>Narzędzia i umiejętności</h2><br>
                <br>
                <p class="Admin_definicja">
                    Admin musi posiadać rozległą wiedzę na temat systemów operacyjnych, sieci komputerowych, baz danych i aplikacji.<br>
                    Używa narzędzi do monitorowania wydajności systemu i rozwiązywania problemów.<br>
                    Często wymagana jest znajomość języków skryptowych lub programowania w celu automatyzacji zadań i dostosowywania funkcji systemu.<br>
                    <br>
                    </p>
                    <p>
                Admin (administrator) pełni kluczową rolę w każdej organizacji wykorzystującej technologię informatyczną, zapewniając sprawne i bezpieczne <br> funkcjonowanie systemów oraz ochronę przed potencjalnymi zagrożeniami.
            </p>
        <?php endif; ?>
        <?php if($_SESSION['Rola_user'] == 'Nauczyciel'): ?>
            <div class="iframe">
                <iframe src="ZN_Pedagogika_2016_13_ egna ek.pdf" width="1300" height="750">
            </div>
        <?php endif; ?>
        <?php if($_SESSION['Rola_user'] == 'Uczen') : ?>

            CUMCUMCUM

        <?php endif; ?>
    </div>
</body>
</html>
