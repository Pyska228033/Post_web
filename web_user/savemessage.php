<?php

session_start();
if (isset($_SESSION['LAST_ACTIVITY'])) {
    if (time() - $_SESSION['LAST_ACTIVITY'] > 120) {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit();
    }
}
$_SESSION['LAST_ACTIVITY'] = time();

?>


<?php

if (isset($_POST['senderpostoffice'])) {
    $senderpostoffice = $_POST['senderpostoffice'];
    if ($senderpostoffice == '') {
        unset($senderpostoffice);
    }
}
if (isset($_POST['recipient'])) {
    $recipient = $_POST['recipient'];
    if ($recipient == '') {
        unset($recipient);
    }
}

if (isset($_POST['recipientpostoffice'])) {
    $recipientpostoffice = $_POST['recipientpostoffice'];
    if ($recipientpostoffice == '') {
        unset($recipientpostoffice);
    }
}

if (isset($_POST['date_of_dispatch'])) {
    $date_of_dispatch = $_POST['date_of_dispatch'];
    if ($date_of_dispatch == '') {
        unset($date_of_dispatch);
    }
}


if (isset($_POST['service'])) {
    $servic = $_POST['service'];
    if ($servic == '') {
        unset($servic);
    }
}


if (isset($_POST['comment'])) {
    $comment = $_POST['comment'];
}

$sender = stripslashes($sender);
$sender = htmlspecialchars($sender);
$senderpostoffice = stripslashes($senderpostoffice);
$senderpostoffice = htmlspecialchars($senderpostoffice);
$recipient = stripslashes($recipient);
$recipient = htmlspecialchars($recipient);
$recipientpostoffice = stripslashes($recipientpostoffice);
$recipientpostoffice = htmlspecialchars($recipientpostoffice);
$weightg = stripslashes($weightg);
$weightg = htmlspecialchars($weightg);
$widthm = stripslashes($widthm);
$widthm = htmlspecialchars($widthm);
$heightm = stripslashes($heightm);
$heightm = htmlspecialchars($heightm);
$lengthm = stripslashes($lengthm);
$lengthm = htmlspecialchars($lengthm);
$date_of_dispatch = stripslashes($date_of_dispatch);
$date_of_dispatch = htmlspecialchars($date_of_dispatch);
$date_of_receipt = stripslashes($date_of_receipt);
$date_of_receipt = htmlspecialchars($date_of_receipt);
$servic = htmlspecialchars($servic);
$servic = stripslashes($servic);
$comment = stripslashes($comment);
$comment = htmlspecialchars($comment);

// подключаемся к базе
include("bd.php"); // файл bd.php должен быть в той же папке, что и все остальные, если это не так, то просто измените путь

    $SQLstring = "SELECT idPost_office FROM post_office WHERE name='$senderpostoffice'";
    $result2 = mysqli_query($dblink, $SQLstring);
    if ($result2) {
        $row2 = mysqli_fetch_assoc($result2);
        $sender_postoffice = (int) $row2['idPost_office'];
    } else {
        echo "Ошибка выполнения запроса: " . mysqli_error($dblink);
    }

    $SQLstring = "SELECT idPost_office FROM post_office WHERE name='$recipientpostoffice'";
    $result3 = mysqli_query($dblink, $SQLstring);
    if ($result3) {
        $row3 = mysqli_fetch_assoc($result3);
        $recipient_postoffice = (int) $row3['idPost_office'];
    } else {
        echo "Ошибка выполнения запроса: " . mysqli_error($dblink);
    }

    $status = 1;
    $sender = (int) $_SESSION['idUser'];
    $recipient = (int) $recipient;
    $idtypeofmessage = 1;
    $SQLstring = "INSERT INTO message (sender,recipient,senders_post_office,recipients_post_office, type_of_message, status, date_of_dispatch, date_of_receipt, Comment) VALUES('$sender','$recipient','$sender_postoffice','$recipient_postoffice','$idtypeofmessage','$status', '$date_of_dispatch', '$date_of_dispatch', '$comment')";
    $result5 = mysqli_query($dblink, $SQLstring);
    $idmessage = mysqli_insert_id($dblink);
    if($servic != '')
    {
        $SQLstring = "SELECT idServices,cost FROM services WHERE name='$servic'";
        $result6 = mysqli_query($dblink, $SQLstring);
        if($result6)
        {
            $row6 = mysqli_fetch_assoc($result6);
            $servicID = $row6['idServices'];
            $sum = $row6['cost'];
        }

        date_default_timezone_set('Europe/Moscow');
        $servDate = date('Y-m-d');
    $SQLstring = "INSERT INTO list_of_services (Message_ID,Services_idServices,date_services) VALUES('$idmessage','$servicID','$servDate')";
    $result7 = mysqli_query($dblink, $SQLstring);
    }


    $date_payment = $servDate;
    $payment = 2;
    $SQLstring = "SELECT * FROM payment WHERE message='$idmessage'";
    $result = mysqli_query($dblink, $SQLstring);

    // Проверяем, есть ли запись в таблице payment с message = $package_number
    if (mysqli_num_rows($result) == 0) {

        // Добавление платежа
        $SQLstring = "INSERT INTO payment (message, sum, date_payment, payment_methods) VALUES('$idmessage', '$sum', '$date_payment', '$payment')";
        $result = mysqli_query($dblink, $SQLstring);

    }
    // Проверяем, есть ли ошибки
    if ($result5 == 'TRUE') {

        echo '<script>alert("Отправление успешно зарегистрировано! Номер отправления: ' . $idmessage . '");</script>';
        echo '<script>window.location.href = "account.php";</script>';

    } else {
        echo "Ошибка!". mysqli_error($dblink);

    }


?>