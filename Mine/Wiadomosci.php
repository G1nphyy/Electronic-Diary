<?php
session_start();
if (!$_SESSION['Login']) {
    header('Location: Index.php');
    exit();
}

require_once 'db.php';
$conn = new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wiadomości</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 95%;
            width: 100%;
            margin: 150px auto;
            display: flex;
        }
        .tabs{
            display: flex;
            flex-direction: column;
            padding: 10px 20px 0 10px;
            height: 170px;
            
        }
        table {
            width: 97%;
            border-collapse: collapse;
        }
        .tcontainer {
            width: 100%;
            display: flex;
            align-items: start;
            justify-content: center;
            border-left: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        .message {
            cursor: pointer;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
        }
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            width: 80%;
            max-width: 800px;
            border-radius: 8px;
            background-color: #fafafa;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .modal-header, .modal-footer {
            padding: 10px 20px;
            background-color: #f1f1f1;
            border-bottom: 1px solid #ddd;
        }
        .modal-header {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .modal-footer {
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            text-align: right;
        }
        .close, .closen {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            user-select: none;
        }
        .close:hover, .closen:hover,
        .close:focus, .closen:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .modal-body {
            padding: 20px;
        }
        #modalBody{
            border-top: 1px solid #ddd;
            padding: 15px 0 0 0;
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
            
        }
        .btn:hover {background-color: #0069d9; transform: translateY(5px);}
        .btn:active {
            background-color: #0069d9;
            box-shadow: 0 2px #666;
            transform: translateY(10px);
        }
        .recipient-item {
            padding: 5px;
            cursor: pointer;
        }
        .recipient-item:hover {
            background-color: #f1f1f1;
        }
        .send {
            min-width: 600px;
            background: #efefef;
            padding: 20px;
            border-radius: 8px 8px 0 0 ;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            transition: height 0.5s ease-in-out, opacity 0.5s ease-in-out;
            z-index: 10000;
            height: 0;
            opacity: 0;
            display: flex;
            flex-direction: column;
            overflow: auto;
        }
        #composeMessageSection {
            position: fixed;
            bottom: 0;
            right: 10px;
            max-width: 98vw;
            
        }

        #recipientSearch, #recipient, #messageTitle, #messageContent {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 16px;
            font-family: inherit;
        }

        #recipientSearch {
            margin-bottom: 15px;
        }

        #recipient {
            background-color: #f0f0f0;
        }
        .tabs {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f4f4f4;
        }
        .tab.active {
            background-color: #007bff;
            color: white;
        }
        .btnk {
            margin: 5px;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            background-color: #f0f0f0;
            border-radius: 3px;
        }

        .btnk.active {
            background-color: #d0d0d0;
        }

        .btnk:hover {
            background-color: #e0e0e0;
        }
        .editor {
            border: 1px solid #ccc;
            padding: 10px;
            min-height: 100px;
            margin-bottom: 10px;
            border-radius: 5px;
            white-space: pre-wrap;
            overflow-wrap: break-word;
        }
        textarea {
            white-space: pre-wrap;
            overflow-wrap: break-word;
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            font-family: inherit;
            margin-bottom: 10px;
        }

        h2 {
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"], .btnh {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover , .btnh:hover {
            background-color: #45a049;
        }
        .headerrr{
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

    </style>
</head>
<body>
    <header>
        <h1>Wiadomości</h1>
        <?php include 'nav.php'; ?>
    </header>
    <div class="container">
        <div class="tabs">
            <div class="tab" data-category="received">Odebrane</div>
            <div class="tab" data-category="sent">Wysłane</div>
            <div class="tab active" data-category="all">Wszystkie</div>
        </div>
        <div class="tcontainer">
            <table>
                <thead>
                    <tr>
                        <th>Do</th>
                        <th>Tytuł</th>
                        <th>Data</th>
                        <th>Odczytane</th>
                    </tr>
                </thead>
                <tbody id="messageTableBody">
                </tbody>
            </table>
        </div>
    </div>
    <button class="btn" id="composeButton">Napisz wiadomość</button>

    <div id="composeMessageSection" style="display: none;">
        <div class="send" >
            <div class="headerrr">
                <h2>Napisz wiadomość</h2>
                <div class="closen" id="times">&times;</div>
            </div>
            <form id="composeMessageForm">
                <div>
                    <label for="recipientSearch">Szukaj odbiorcy:</label>
                    <input type="text" id="recipientSearch" name="recipientSearch" placeholder="Wpisz imię, nazwisko lub E-mail">
                    <div id="recipientList"><?= isset($_SESSION['alert_s']) ? $_SESSION['alert_s'] : '' ?></div>
                </div>

                <div>
                    <label for="recipient">Odbiorca:</label>
                    <input type="text" id="recipient" name="recipient" readonly style="background-color: #f0f0f0;" required>
                </div>

                <div>
                    <label for="messageTitle">Tytuł:</label>
                    <input type="text" id="messageTitle" name="messageTitle" required>
                </div>

                <div>
                    <label for="messageContent">Treść:</label>
                    <div>
                        <button type="button" class="btnk" onclick="formatText('bold', this)"><b>B</b></button>
                        <button type="button" class="btnk" onclick="formatText('italic', this)"><i>I</i></button>
                        <button type="button" class="btnk" onclick="formatText('underline', this)"><u>U</u></button>
                    </div>
                    <div id="editor" class="editor" contenteditable="true"></div>
                    <textarea id="messageContent" name="messageContent" rows="4" required style="display: none;"></textarea>
                </div>

                <div style="text-align: right;">
                    <button type="submit" class="btnk" onclick="syncContent()">Wyślij</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function formatText(command, button) {
            document.execCommand(command, false, null);
            button.classList.toggle('active');
            document.getElementById('editor').focus();
        }

        function syncContent() {
            var editorContent = document.getElementById('editor').innerHTML;
            document.getElementById('messageContent').value = editorContent;
            var textarea = document.getElementById('messageContent');
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight + 10) + 'px';
        }

        document.getElementById('composeMessageForm').addEventListener('submit', function(event) {
            syncContent();
        });
    </script>

    <div id="messageModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Wiadomość</h2>
            </div>
            <div class="modal-body">
                <p align="right"><b>Data:</b> <span id="modalData"></span></p>
                <p><strong>Do:</strong> <span id="modalDo"></span></p>
                <p><strong>Od:</strong> <span id="modalOd"></span></p>
                <p><strong>Tytuł:</strong> <span id="modalTytul"></span></p>
                <div id="modalBody"></div>
            </div>
            <div class="modal-footer">
                <button class="btnh" id="replyButton">Odpowiedz</button>
            </div>
        </div>
    </div>

    <script>
        var user_info = `<?= $_SESSION['Imie_user'] ?> <?= $_SESSION['Nazwisko_user'] ?> [<?= $_SESSION['E-mail_user']?>]`;
        var modal = document.getElementById('messageModal');
        var modalDo = document.getElementById('modalDo');
        var modalOd = document.getElementById('modalOd');
        var modalData = document.getElementById('modalData');
        var modalTytul = document.getElementById('modalTytul');
        var modalBody = document.getElementById('modalBody');
        var span = document.getElementsByClassName('close')[0];
        var composeButton = document.getElementById('composeButton');
        var composeSection = document.getElementById('composeMessageSection');
        var send = document.querySelector('.send');
        var times =document.getElementById('times');


        times.addEventListener('click' , () => {
            send.style.height = 0;
            send.style.opacity = 0;
            setTimeout(() => {
                composeSection.style.display = 'none';
            }, 500); 
        })

        
        document.addEventListener('DOMContentLoaded', function() {
            composeButton.addEventListener('click', function() {
                if (composeSection.style.display === 'none' || composeSection.style.display === '') {
                    composeSection.style.display = 'block';
                    send.style.height = send.scrollHeight + 'px';
                    send.style.opacity = 1;
                } else {
                    send.style.height = 0;
                    send.style.opacity = 0;
                    setTimeout(() => {
                        composeSection.style.display = 'none';
                    }, 500); 
                }
            });


            document.getElementById('replyButton').addEventListener('click', function() {
                const recipientEmail = modalOd.textContent.trim();
                const originalTitle = modalTytul.textContent.trim();
                const replyTitle = `Re: ${originalTitle}`;
                const replyMessage = `<br><br><br><br><br><br><br><br><br>Re: ${modalOd.textContent}<br>-----------------------------------------<br>${modalBody.textContent.trim()}`;
                document.getElementById('recipient').value = recipientEmail;
                document.getElementById('messageTitle').value = replyTitle;
                document.getElementById('editor').innerHTML = replyMessage;
                modal.style.display = 'none';
                composeSection.style.display = 'block';
                send.style.height = send.scrollHeight + 'px';
                send.style.opacity = 1;
            }); 
            
            let th = document.querySelector('tr th:first-child');
            document.querySelectorAll('.tab').forEach(function(tab) {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.tab').forEach(function(tab) {
                        tab.classList.remove('active');
                    });
                    this.classList.add('active');
                    loadMessages(this.getAttribute('data-category'));
                    if (this.getAttribute('data-category') === 'received'){
                        th.innerHTML = 'Od';
                    }else{
                        th.innerHTML = 'Do';
                    }
                });
            });
            

            span.onclick = function() {
                modal.style.display = 'none';
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }

            document.getElementById('recipientSearch').addEventListener('input', function() {
                const searchQuery = this.value;

                if (searchQuery.length >= 2) {
                    fetch('search_users.php?query=' + encodeURIComponent(searchQuery))
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            const recipientList = document.getElementById('recipientList');
                            recipientList.innerHTML = '';
                            data.forEach(user => {
                                const userItem = document.createElement('div');
                                userItem.textContent = `${user.Imie} ${user.Nazwisko} [${user['E-mail']}]`;
                                userItem.className = 'recipient-item';
                                userItem.addEventListener('click', function() {
                                    document.getElementById('recipient').value = `${user.Imie} ${user.Nazwisko} [${user['E-mail']}]`;
                                    document.getElementById('recipientList').innerHTML = '';
                                });
                                recipientList.appendChild(userItem);
                            });
                            if (data.length === 0) {
                                let x = '<?= isset($_SESSION['alert_s']) ? $_SESSION['alert_s'] : '' ?>';
                                let y = document.getElementById('recipientList');
                                if (y) {
                                    y.innerHTML = x;
                                } else {
                                }
                            }
                        });
                }
            });

            document.getElementById('composeMessageForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                
                fetch('send_message.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(result => {
                    alert(result);
                    document.location.reload();;
                    this.reset();
                    
                });
            });



            function loadMessages(category) {
                const userId = <?= json_encode($_SESSION['user_id']) ?>;
                fetch('load_messages.php?category=' + encodeURIComponent(category) + '&user_id=' + encodeURIComponent(userId))
                    .then(response => response.json())
                    .then(data => {
                        const messageTableBody = document.getElementById('messageTableBody');
                        messageTableBody.innerHTML = '';
                        data.forEach(row => {
                            const messageRow = document.createElement('tr');
                            messageRow.classList.add('message');
                            messageRow.setAttribute('data-od', row.od);
                            messageRow.setAttribute('data-do', row.do);
                            messageRow.setAttribute('data-tytul', row.tytul);
                            messageRow.setAttribute('data-tresc', row.tresc);
                            messageRow.setAttribute('data-data', row.data);
                            messageRow.setAttribute('data-odczytane', row.odczytane);
                            messageRow.setAttribute('data-id', row.id);
                            let x;
                            if(row.do === user_info){
                                x = '<b>Do ciebie</b>';
                                if(!row.odczytane){
                                    messageRow.setAttribute('style', 'background-color: #ff4444af;');
                                }
                            }else{
                                x = row.do;
                            }
                            if(th.textContent === 'Od'){
                                x = row.od
                            }

                            messageRow.innerHTML = `
                                <td>${x}</td>
                                <td>${row.tytul}</td>
                                <td>${row.data}</td>
                                <td>${row.odczytane == 0 ? 'Nie' : 'Tak'}</td>
                            `;
                            messageTableBody.appendChild(messageRow);

                            messageRow.addEventListener('click', function() {

                                modalDo.textContent = `${row.do}`;
                                modalOd.textContent = `${row.od}`;
                                
                                modalData.textContent = row.data;
                                modalTytul.textContent = row.tytul;
                                modalBody.innerHTML = row.tresc.replace(/\n/g, '<br>');
                                modal.style.display = 'block';
                                id = this.getAttribute('data-id');
                                fetch('odczytaj.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({ id: id })
                                
                                })
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Network response was not ok');
                                        }
                                        return response.text();
                                    });

                            });
                        });
                    });
            }

            loadMessages('all');
        });

    </script>
</body>
</html>
