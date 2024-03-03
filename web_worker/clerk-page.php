<?php

session_start();
if (isset($_SESSION['LAST_ACTIVITY'])) {
    if (time() - $_SESSION['LAST_ACTIVITY'] > 120) {
        session_unset();
        session_destroy();
        echo '<script>alert("В связи с неактивностью в течении 2-ух минут вы были направлены на главную страницу")</script>';
        header('Location: index.php');
        exit();
    }
}
$_SESSION['LAST_ACTIVITY'] = time();

?>


<!DOCTYPE html>
<html>
<head>
    <title>Личный кабинет работника почты</title>
    <link rel="stylesheet" href="StyleSheetClerk_page.css" />

</head>
<body>
    <h1>Личный кабинет работника почты</h1>
    <form method="post">
        <input type="text" name="user_number" />
        <button type="submit">Найти пользователя</button>
    </form>
    <?php
    include("bd.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['user_number'])) {
            $user_number = $_POST['user_number'];
            if ($user_number != '') {
                $user_number = trim($user_number);
                $_SESSION['user_number'] = (int) $user_number;
                setcookie('number_cookie', $user_number, time() + 3600, '/');

                $SQLstring = "SELECT * FROM message WHERE sender='$user_number'";
                $resultX = mysqli_query($dblink, $SQLstring);
                //чтобы блокировка была неправильной необходимо удалить строки 46, 85
                sleep(5);
                mysqli_query($dblink, "LOCK TABLES massege FOR UPDATE;");
                if ($resultX->num_rows != 0) {
                    $idWorker = $_SESSION['idWorker'];
                    $SQLstring = "SELECT * FROM message WHERE sender='$user_number' AND (locked=0 OR locked = '$idWorker')";
                    $resultX = mysqli_query($dblink, $SQLstring);

                    if ($resultX->num_rows != 0) {
                        echo "<table>";
                        echo "<tr><th>idMessage</th><th>senders_post_office</th><th>recipients_post_office</th><th>date_of_dispatch</th><th>date_of_receipt</th><th>Comment</th></tr>";

                        while ($row = $resultX->fetch_assoc()) {
                            $packageId = $row['idMessage'];

                            echo "<tr id='message_" . $row['idMessage'] . "'>";
                            echo "<td>" . $row['idMessage'] . "</td>";
                            echo "<td>" . $row['senders_post_office'] . "</td>";
                            echo "<td>" . $row['recipients_post_office'] . "</td>";
                            echo "<td>" . $row['date_of_dispatch'] . "</td>";
                            echo "<td>" . $row['date_of_receipt'] . "</td>";
                            echo "<td>" . $row['Comment'] . "</td>";
                            $status = $row['status'];
                            if($status == 1)
                            {
                                echo '<td><button onclick="DeLMessage(' . $row['idMessage'] . ')">УДАЛИТЬ</button></td>';
                                echo '<td><button onclick="editMessage(' . $row['idMessage'] . ')">ИЗМЕНИТЬ</button></td>';
                            }
                            if ($status == 4) {
                                echo '<td><button onclick="DeLMessage(' . $row['idMessage'] . ')">УДАЛИТЬ</button></td>';
                            }
                            if ($status != 5 && $status != 1 && $status != 4) {
                                echo '<td><button id="cancelBtn_' . $row['idMessage'] . '" onclick="cancelShipment(' . $row['idMessage'] . ')">ОТМЕНИТЬ</button></td>';
                            } else if ($status == 5) {
                                echo '<td><button disabled = true disabled style="background-color: lightgrey;">ОТМЕНЕННО</button></td>';
                            }
                            echo "</tr>";

                            // Заблокировать посылку для других пользователей
                            $SQLstring = "UPDATE message SET locked='$idWorker' WHERE idMessage='$packageId' AND locked=0";
                            mysqli_query($dblink, $SQLstring);
                            mysqli_query($dblink, "UNLOCK TABLES");
                        }
                         echo "</table>";
                         echo '<button onclick="deleteTableAndRefresh(event)">Сохранить изменения</button>';
                    } else {
                        echo "Пользователь с данным номером недоступна для обработки другими пользователями";
                    }
                } else {
                    echo "Пользователя с данным номером нет!";
                }
            }
        }
    }
    ?>

    <script>
        function deleteTableAndRefresh() {
        var button = event.target;
        // Удаление таблицы
        var table = document.getElementsByTagName("table")[0];
        table.parentNode.removeChild(table);

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                // Обработка ответа от сервера
                console.log(this.responseText);
            }
        };
        xmlhttp.open("GET", "UpdateAndSave.php", true);
        xmlhttp.send();
        button.parentNode.removeChild(button);
    }
    </script>

            <script>
            function cancelShipment(package_number) {
                var cancelBtn = document.getElementById("cancelBtn_" + package_number);

                var xmlhttp = new XMLHttpRequest();

                xmlhttp.onload = function () {
                    if (this.status === 200) {
                        cancelBtn.disabled = true;
                        cancelBtn.style.backgroundColor = "lightgrey";
                        cancelBtn.innerHTML = "ОТМЕНЕННО";
                    } else {
                        console.log('Request failed');
                    }
                };
                xmlhttp.onerror = function () {
                    console.log('Request failed');
                }
                xmlhttp.open("GET", "cancelShipment.php?package_number=" + package_number, true);
                xmlhttp.send();
            }
    </script>

    <script>
        function DeLMessage(package_number) {
        // Получаем ссылку на строку таблицы, которую нужно удалить
        var row = document.getElementById("message_" + package_number);

        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onload = function () {
            if (this.status === 200) {
                // Удаляем строку таблицы
                row.parentNode.removeChild(row);
            } else {
                console.log('Request failed');
            }
        };
        xmlhttp.onerror = function () {
            console.log('Request failed');
        }
        xmlhttp.open("GET", "DeLMessage.php?package_number=" + package_number, true);
        xmlhttp.send();
    }
    </script>

    <script>
        function editMessage(messageId) {
            window.location.href = "edit_message.php?idMessage=" + messageId;
        }
    </script>


    <br />
    <a href="registermessage.php" target="_blank">
        <button>Зарегистрировать посылку</button>
    </a>

    <a href="process_shipment.php" target="_blank">
        <button>Обработать отправление</button>
    </a>
    <a href="receive_payment.php" target="_blank">
        <button>Принять платеж</button>
    </a>
    <?php
    if (isset($_COOKIE['number_cookie'])) {
        $number_cookie = $_COOKIE['number_cookie'];
        echo "<p>Значение куки 'number_cookie': $number_cookie</p>";
    }
    ?>
</body>
</html>
