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
    <title>Большой мальчик</title>
    <link rel="stylesheet" href="StyleSheetBoss_page.css" />
</head>
<body>
    <h1>Страница Начальства</h1>
    <?php
    include("bd.php");
    $idWorker = $_SESSION['idWorker'];
    $SQLstring = "SELECT post_office_work FROM worker WHERE idWorker = '$idWorker'";
    $result = mysqli_query($dblink, $SQLstring);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $post_office_work = (int) $row['post_office_work'];

        // Получение количества отправленных посылок
        $SQLstringSent = "SELECT COUNT(*) AS sent_count FROM message WHERE senders_post_office = '$post_office_work'";
        $resultSent = mysqli_query($dblink, $SQLstringSent);
        $rowSent = mysqli_fetch_assoc($resultSent);
        $sent_count = $rowSent['sent_count'];

        // Получение количества обработанных посылок
        $SQLstringProcessed = "SELECT COUNT(*) AS processed_count FROM way WHERE post_offis_id = '$post_office_work'";
        $resultProcessed = mysqli_query($dblink, $SQLstringProcessed);
        $rowProcessed = mysqli_fetch_assoc($resultProcessed);
        $processed_count = $rowProcessed['processed_count'];

        echo "<div class='info'>";
        echo "<label>Почтовое отделение №$post_office_work</label><br>";
        echo "<label>Отправлено посылок:</label> $sent_count<br>";
        echo "<label>Обработано посылок:</label> $processed_count<br>";
        echo "</div>";

        // Запрос для получения информации о сотрудниках
        $SQLstringWorkers = "SELECT name, suname, login, password FROM worker WHERE post_office_work = '$post_office_work'";
        $resultWorkers = mysqli_query($dblink, $SQLstringWorkers);

        if ($resultWorkers && mysqli_num_rows($resultWorkers) > 0) {
            echo "<h2>Ваши сотрудники</h2>";
            echo "<table>";
            echo "<tr><th>Имя</th><th>Фамилия</th><th>Логин</th><th>Пароль</th></tr>";
            while ($rowWorker = mysqli_fetch_assoc($resultWorkers)) {
                echo "<tr>";
                echo "<td>" . $rowWorker['name'] . "</td>";
                echo "<td>" . $rowWorker['suname'] . "</td>";
                echo "<td>" . $rowWorker['login'] . "</td>";
                echo "<td>" . $rowWorker['password'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Нет информации о сотрудниках";
        }
    } else {
        // Обработка ошибки запроса
        echo "Ошибка выполнения запроса: " . mysqli_error($dblink);
    }
    ?>

    <div class="button-container">
        <a href="reg_worker.php" target="_blank">Зарегистрировать нового сотрудника</a>
        <a href="del_worker.php" target="_blank">Удалить сотрудника</a>
    </div>
</body>
</html>

