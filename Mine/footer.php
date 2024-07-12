

<style>
    footer {
        height: auto;
        width: 100%;
        background-color: #282828;
        color: #f6f6f6;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 30px;
        box-sizing: border-box;
        flex-wrap: wrap;
        position: fixed;
        bottom: 0;
        font-size: 16px;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }
    footer.content-overflow {
        position: relative;
    }
    footer a{
        padding: 8px 12px ;
        border: 1px solid #333;
        border-radius: 4px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }


    .footer-left, .footer-right {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }

    .footer-left p, .footer-right p {
        margin: 0;
        padding: 0 10px;
        color: #f6f6f6;
    }

    .footer-right a {
        margin: 0 5px;
        text-decoration: none;
        color: #f6f6f6;
        display: inline-block;
        margin: auto 0;
    }

    .footer-right a:hover {
        color: #007BFF;
        background-color: #333;
    }

    .footer-logo {
        height: 40px;
        margin-right: 10px;
    }

    .footer-social-icons img {
        height: 24px;
        width: 24px;
        transition: transform 0.3s;
    }

    .footer-social-icons a {
        margin: 0 5px;
    }

    .footer-social-icons a:hover img {
        transform: scale(1.2);
    }

    @media screen and (max-width: 600px) {
        footer {
            flex-direction: column;
            align-items: flex-start;
        }

        .footer-left, .footer-right {
            width: 100%;
            justify-content: space-between;
            margin: 5px 0;
        }

        .footer-left p, .footer-right p {
            padding: 0 5px;
        }

        .footer-social-icons {
            margin-top: 10px;
        }
    }

</style>

<footer>
    <div class="footer-left">
        <img src="favicon.ico" alt="Logo" class="footer-logo">
        <p>&copy; 2024 DDDziennik</p>
    </div>
    <div class="footer-right">
        <p>Contact: <a href="mailto:dddziennik@dziennik.com">dddziennik@dziennik.com</a></p>
        <div class="footer-social-icons">
            <a href="https://www.facebook.com/JustinBieber" target="_blank">
                <img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook">
            </a>
            <a href="https://x.com/G1nphy" target="_blank">
                <img src="https://cdn-icons-png.flaticon.com/512/733/733579.png" alt="Twitter">
            </a>
            <a href="https://www.linkedin.com/in/ginphy-k%C4%99dziora-92b4a8318/" target="_blank">
                <img src="https://cdn-icons-png.flaticon.com/512/733/733561.png" alt="LinkedIn">
            </a>
            <a href="https://www.instagram.com/g1nphy/" target="_blank">
                <img src="https://cdn-icons-png.flaticon.com/512/733/733558.png" alt="Instagram">
            </a>
        </div>
    </div>
</footer>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        function checkContentOverflow() {
            const footer = document.querySelector('footer');
            if (document.body.scrollHeight + footer.offsetHeight > window.innerHeight) {
                footer.classList.add('content-overflow');
            } else {
                footer.classList.remove('content-overflow');
            }
        }

        window.addEventListener('load', checkContentOverflow);
        window.addEventListener('resize', checkContentOverflow);
    });
</script>