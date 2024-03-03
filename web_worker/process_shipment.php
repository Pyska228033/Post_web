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

$idWorker = $_SESSION['idWorker'];
$SQLstring = "SELECT post_office_work FROM worker WHERE idWorker='$idWorker'";
$result = mysqli_query($dblink, $SQLstring);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $post_office_work = (int) $row['post_office_work'];
} else {
    // Обработка ошибки запроса
    echo "Ошибка выполнения запроса: " . mysqli_error($dblink);
}
date_default_timezone_set('Europe/Moscow');
$date = date('Y-m-d');

$SQLstring = "SELECT DISTINCT t1.message_ID
        FROM Way t1
        JOIN (
            SELECT message_ID, MAX(date) AS max_date
            FROM Way
            GROUP BY message_ID
        ) t2
        ON t1.message_ID = t2.message_ID AND t1.date = t2.max_date
        WHERE t1.post_offis_id != '$post_office_work' AND NOT EXISTS (
            SELECT *
            FROM Way t3
            WHERE t3.message_ID = t1.message_ID AND t3.date = '$date' AND t3.post_offis_id = '$post_office_work'
        )";

$result = mysqli_query($dblink, $SQLstring);

if ($result) {
    echo "<link rel='stylesheet' href='StyleSheetShipment_page.css' />";
    echo "<h2>Прибыли следующие посылки:</h2>";
    echo "<table class='shipment-table'>";
    echo "<tr><th>idMessage</th><th>senders_post_office</th><th>recipients_post_office</th><th>date_of_dispatch</th><th>date_of_receipt</th><th>Comment</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        $messageID = $row['message_ID'];

        $SQLstring = "SELECT idMessage, senders_post_office, recipients_post_office, date_of_dispatch, date_of_receipt, Comment FROM message WHERE idMessage = '$messageID' AND status != 4";
        $result1 = mysqli_query($dblink, $SQLstring);

        // Перебираем результаты запроса и выводим каждую строку в виде таблицы
        while ($row1 = mysqli_fetch_array($result1)) {
            echo "<tr id='message_" . $row1['idMessage'] . "'>";
            echo "<td>" . $row1['idMessage'] . "</td>";
            echo "<td>" . $row1['senders_post_office'] . "</td>";
            echo "<td>" . $row1['recipients_post_office'] . "</td>";
            echo "<td>" . $row1['date_of_dispatch'] . "</td>";
            echo "<td>" . $row1['date_of_receipt'] . "</td>";
            echo "<td>" . $row1['Comment'] . "</td>";
            echo '<td><button onclick="approveShipment(' . $row1['idMessage'] . ')">ОБРАБОТАТЬ</button></td>';
            echo "</tr>";
        }
    }
    echo "</table>";
}
?>
<script>
        function approveShipment(package_number) {
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
        xmlhttp.open("GET", "approve_shipment.php?package_number=" + package_number, true);
        xmlhttp.send();
    }
</script>

