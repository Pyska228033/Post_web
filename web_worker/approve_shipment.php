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
include("bd.php");
$package_number = $_GET['package_number'];
// Начало транзакции
mysqli_begin_transaction($dblink);
try {
    date_default_timezone_set('Europe/Moscow');
    $date = date('Y-m-d');
    // Блокирование записи в таблице message для предотвращения одновременной обработки другими пользователями
    $SQLstring = "SELECT * FROM message WHERE idMessage='$package_number' FOR UPDATE SKIP LOCKED";
    mysqli_query($dblink, $SQLstring);
    $waySQLstring = "SELECT * FROM way WHERE message_ID='$package_number' FOR UPDATE SKIP LOCKED";
    mysqli_query($dblink, $waySQLstring);

    $checkSQLstring = "SELECT * FROM way WHERE message_ID='$package_number' AND date ='$date' ";
    $checkResult = mysqli_query($dblink, $checkSQLstring);

   // if(mysqli_num_rows($checkResult) == 0){

        $post_office_work = '';
        $idWorker = $_SESSION['idWorker'];
        $SQLstring = "SELECT post_office_work FROM worker WHERE idWorker='$idWorker'";
        $resultK = mysqli_query($dblink, $SQLstring);
        if ($resultK) {
            $row = mysqli_fetch_assoc($resultK);
            $post_office_work = (int) $row['post_office_work'];
        } else {
            // Обработка ошибки запроса
            echo "Ошибка выполнения запроса: " . mysqli_error($dblink);
        }

        $SQLstring = "SELECT sender, senders_post_office, recipients_post_office, status FROM message WHERE idMessage='$package_number'";
        $result3 = mysqli_query($dblink, $SQLstring);
        if ($result3) {
            $row = mysqli_fetch_assoc($result3);
            $sender = (int) $row['sender'];
            $SQLstring = "SELECT email FROM user WHERE idUser='$sender'";
            $result9 = mysqli_query($dblink, $SQLstring);
            if($result9)
            {
                $row7 = mysqli_fetch_assoc($result9);
                $senderemail = $row7['email'];
            }

            $senders_post_office = (int) $row['senders_post_office'];
            $recipients_post_office = (int) $row['recipients_post_office'];
            $status = (int) $row['status'];
        } else {
            // Обработка ошибки запроса
            echo "Ошибка выполнения запроса: " . mysqli_error($dblink);
        }

        if (($status != 5 && $recipients_post_office == $post_office_work) || ($status == 5 && $senders_post_office == $post_office_work)) {
            $SQLstring = "UPDATE message SET status = 4 WHERE idMessage = $package_number";
            $result = mysqli_query($dblink, $SQLstring);

            $to = 'vfeodosij@yandex.ru';
            $subject = 'Доставка';
            $message = 'Отправление №' . $package_number . ' отправленно';

            $headers = 'From: vasilyevfedor2003@gmail.com';

            // Отправляем письмо
            $mail_sent = mail($to, $subject, $message, $headers);

            if ($mail_sent) {
                echo 'Письмо успешно отправлено';
            } else {
                echo 'Ошибка при отправке письма';
            }

        } else {
            $SQLstring = "INSERT INTO way (message_ID,post_offis_id,date,accepted_worker) VALUES('$package_number','$post_office_work','$date','$idWorker')";
            $result8 = mysqli_query($dblink, $SQLstring);
        }
    //}

    // Фиксация транзакции
    mysqli_commit($dblink);
} catch (Exception $e) {
    mysqli_rollback($dblink);
    echo "Ошибка: " . $e->getMessage();
}
http_response_code(200);
?>