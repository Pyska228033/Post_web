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

$idMessage = $_SESSION['idMessage'];
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

if (isset($_POST['comment'])) {
    $comment = $_POST['comment'];
}


$senderpostoffice = stripslashes($senderpostoffice);
$senderpostoffice = htmlspecialchars($senderpostoffice);
$recipient = stripslashes($recipient);
$recipient = htmlspecialchars($recipient);
$recipientpostoffice = stripslashes($recipientpostoffice);
$recipientpostoffice = htmlspecialchars($recipientpostoffice);

$date_of_dispatch = stripslashes($date_of_dispatch);
$date_of_dispatch = htmlspecialchars($date_of_dispatch);

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


$recipient = (int) $recipient;
$idMessage;
$SQLstring = "UPDATE message SET recipient = '$recipient', senders_post_office = '$sender_postoffice', recipients_post_office = '$recipient_postoffice', date_of_dispatch = '$date_of_dispatch', Comment = '$comment' WHERE idMessage = $idMessage";

$result5 = mysqli_query($dblink, $SQLstring);

// Проверяем, есть ли ошибки
if ($result5 == 'TRUE') {

    echo '<script>alert("Отправление успешно Изменено! Номер отправления: ' . $idMessage . '");</script>';
    echo '<script>window.location.href = "account.php";</script>';

} else {
    echo "Ошибка!" . mysqli_error($dblink);

}

?>