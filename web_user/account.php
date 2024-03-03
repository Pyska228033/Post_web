<?php
session_start();
    if(isset($_SESSION['LAST_ACTIVITY']))
    {
        if(time() - $_SESSION['LAST_ACTIVITY'] > 120)
        {
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
    <title>Пользователь</title>
    <link rel="stylesheet" href="StyleSheet_account.css" />
</head>
<body>

    <?php
    include("bd.php");
    $user_number = $_SESSION['idUser'];
    $SQLstring = "SELECT * FROM message WHERE sender='$user_number'";
    $resultX = mysqli_query($dblink, $SQLstring);

    if ($resultX->num_rows != 0) {
        $SQLstring = "SELECT * FROM message JOIN types_of_message_statuses ON message.status = types_of_message_statuses.idTypes_of_statuses WHERE sender='$user_number' AND (locked=0 OR locked = '$user_number')";
        $resultX = mysqli_query($dblink, $SQLstring);

        if ($resultX->num_rows != 0) {
            $SQLstring = "SELECT * FROM user WHERE idUser='$user_number'";
            $resultUser = mysqli_query($dblink, $SQLstring);
            $rowUser = mysqli_fetch_assoc($resultUser);
            $name = $rowUser['name'];

            echo "<p>Добро пожаловать, $name!</p>";
            echo "<table>";
            echo "<tr><th>idMessage</th><th>senders_post_office</th><th>recipients_post_office</th><th>date_of_dispatch</th><th>date_of_receipt</th><th>Statys</th><th>Comment</th></tr>";

            while ($row = $resultX->fetch_assoc()) {
                   $packageId = $row['idMessage'];

                   echo "<tr id='message_" . $row['idMessage'] . "'>";
                   echo "<td>" . $row['idMessage'] . "</td>";
                   echo "<td>" . $row['senders_post_office'] . "</td>";
                   echo "<td>" . $row['recipients_post_office'] . "</td>";
                   echo "<td>" . $row['date_of_dispatch'] . "</td>";
                   echo "<td>" . $row['date_of_receipt'] . "</td>";
                   echo "<td>" . $row['Type_status'] . "</td>";
                   echo "<td>" . $row['Comment'] . "</td>";
                   $status = $row['status'];
                   if($status == 1)
                   {
                      echo '<td><button onclick="DeLMessage(' . $row['idMessage'] . ')">УДАЛИТЬ</button></td>';
                      echo '<td><button onclick="editMessage(' . $row['idMessage'] . ')">ИЗМЕНИТЬ</button></td>';
                   }
                   if ($status == 4)
                   {
                        echo '<td><button onclick="DeLMessage(' . $row['idMessage'] . ')">УДАЛИТЬ</button></td>';
                   }
                   if ($status != 5 && $status != 1 && $status != 4) {
                       echo '<td><button id="cancelBtn_' . $row['idMessage'] . '" onclick="cancelShipment(' . $row['idMessage'] . ')">ОТМЕНИТЬ</button></td>';
                   } else if ($status == 5) {
                              echo '<td><button disabled = true disabled style="background-color: lightgrey;">ОТМЕНЕННО</button></td>';
                   }
                   echo "</tr>";

                   // Заблокировать посылку для других пользователей
                   $SQLstring = "UPDATE message SET locked='$user_number' WHERE idMessage='$packageId' AND locked=0";
                   mysqli_query($dblink, $SQLstring);
            }
            echo "</table>";
            echo '<button onclick="deleteTableAndRefresh(event)">Разрешить редактирование другим пользователям</button>';
        } else {
               echo "Сотрудник ещё работает с вашими отправлениями, ожидайте.";
        }
    }
    ?>
    <a href="registermessage.php" target="_blank">
        <button>Зарегистрировать посылку</button>
    </a>

    <a href="Review.php" target="_blank">
        <button>Оставить отзыв</button>
    </a>

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
        function deleteTableAndRefresh() {
        var button = event.target;
        
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
        function editMessage(messageId) {
            window.location.href = "edit_message.php?idMessage=" + messageId;
        }
    </script>


    <script>
        // Перезагрузка страницы каждую минуту
        setTimeout(function() {
            location.reload();
        }, 60000);
    </script>

    <script>
        //разблокировка записей при закрытии страницы
        window.addEventListener('unload', function () {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    console.log(this.responseText);
                }
            };

            xmlhttp.open("GET", "UpdateAndSave.php", true);
            xmlhttp.send();
        });
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



</body>
</html>