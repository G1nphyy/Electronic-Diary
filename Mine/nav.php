<head>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>
<div class="nav" onclick="openNav()">
<?= isset($_SESSION['Icon_user']) && $_SESSION['Icon_user'] == '' ? '&#9776;' : '<img src="'.htmlspecialchars($_SESSION['Icon_user']). '"></img>' ?>
</div>
<div id="mySidenav" class="sidenav">
        <div class="content-nav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="welcome.php">Strona główna</a>
            <?php if($_SESSION['Rola_user'] !== 'Uczen'){
                echo '<a href="change_roles.php">Tablela Uczniów</a>';
                echo '<a href="dodaj_plan_lekcji.php">Plany Lekcji</a>';
                echo '<a href="frekfencja.php">Frekwencja</a>';
            }?>
            <?php if($_SESSION['Rola_user'] == 'Uczen'): ?>
                <a href="Zobacz_plan.php?klasa=<?=$_SESSION['Klasa_user']?>">Plan Lekcji</a>
                <a href="Oceny_ucznia.php">Moje Oceny</a>
                <a href="frekwencja_ucznia.php">Frekwencja</a>
            <?php endif; ?>
            <a href="index.php">Aktualności</a>
            <a href="Tests.php">Sprawdziany</a>
            <a href="Wiadomosci.php">Wiadomosci</a>
            <a href="Info.php">Informacje</a>
        </div>
        <div class="logout">
            <a href="logout.php">Wyloguj się</a>
        </div>
</div>
<script>
        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        function closeEditFormOnClickOutside(event) {
            if (currentOpenForm && !currentOpenForm.contains(event.target) && !event.target.classList.contains('ocena')) {
                currentOpenForm.style.display = 'none';
                currentOpenForm = null;
                document.removeEventListener('click', closeEditFormOnClickOutside);
            }
        }
        var navClickOutsideAdded = false;

        function closeNavClickOutside(event) {
            var sidenav = document.getElementById("mySidenav");
            if (!sidenav.contains(event.target)) {
                closeNav();
            }
        }

        function openNav() {
            var sidenav = document.getElementById("mySidenav");
            sidenav.style.width = "300px";
            var nav = document.querySelector('.nav');
            if (nav) {
                nav.classList.add('active');
            }
            if (!navClickOutsideAdded) {
                setTimeout(function() {
                    document.addEventListener('click', closeNavClickOutside);
                    navClickOutsideAdded = true;
                }, 250);
            }
        }

        function closeNav() {
            var sidenav = document.getElementById("mySidenav");
            document.removeEventListener('click', closeNavClickOutside);
            navClickOutsideAdded = false;
            sidenav.style.width = "0";
            var nav = document.querySelector('.nav');
            if (nav) {
                nav.classList.remove('active'); 
            }
        }
</script>
<style>
    .content-nav{
        max-height: 70dvh;
        overflow: auto;
    }
    header {
        background-color: #333;
        color: #fff;
        padding: 10px 0;
        position: fixed;
        top: 0;
        width: 100%;
        text-align: center;
        z-index: 1000;
    }
    a {
        color: #333;
        text-decoration: none;
        margin: 0 10px 10px 10px;
        padding: 8px 12px;
        border: 1px solid #333;
        border-radius: 4px;
        transition: background-color 0.3s ease, color 0.3s ease;
        display: inline-block;
    }
    a:hover{
        background-color: #333;
        color: #fff;
    }
    .nav:hover{
        background-color: #818181;
        color: #f1f1f1;
    }
    .nav{
        color: #b1b1b1;
        font-size: 2rem;
        position: absolute;
        top: calc(50% - 30px);
        right: 20px;
        user-select: none !important;
        cursor: pointer;
        padding: 10px 17px;
        transition: 0.3s;
        border-radius: 50%;
    }
    .nav img{
        position: relative;
        padding-top: 7px;
        width: 2rem;
        height: 2rem;
    }
    .sidenav {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 1;
        top: 0;
        right: 0;
        background-color: #111;
        overflow-x: hidden;
        transition: 0.5s;
        padding-top: 70px;
    }
    .nav::before{
        position: fixed !important;;
        width: 100vw;
        height: 100vh;
        content: '';
        background-color: #0000007a;
        top: 0;
        left: 0;
        display: none;
    }

    .sidenav a {
        padding: 8px 32px 8px 8px;
        text-decoration: none;
        font-size: 25px;
        color: #818181;
        display: block;
        transition: 0.3s;
        border: none;
        margin: 0 0 8px 16px;
        width: auto;
        border-radius: 10px 0 0 10px;
        text-wrap: nowrap;
    }

    .sidenav a:hover {
        color: #f1f1f1; 
    }
    .sidenav .logout a {
        text-align: center;
        bottom: 100px;
        margin: 0 70px;
        padding: 20px 15px;
        border-radius: 5px;
        position: absolute;
    }

    .sidenav .closebtn {
        position: absolute;
        top: 5px;
        left: 0;
        font-size: 36px;
        padding: 10px 20px;
        margin-right: 50px;
        border-radius: 50%;
        user-select: none;
    }
    a[href='logout.php']:hover{
        color: red !important;
    }
    .nav.active::before {
        display: block;
    }

    .nav::before {
        display: none;
    }
</style>