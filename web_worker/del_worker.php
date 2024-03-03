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
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Страница удаления сотрудников почтового отделения</title>
    <link rel="stylesheet" href="styles_del_worker.css" />
</head>
<body>
    <?php
    include("bd.php");
    $lock_result = mysqli_query($dblink, "LOCK TABLES worker WRITE, way WRITE, type_post WRITE, post_office WRITE, reviews WRITE;");
    if ($lock_result) {
        $idWorker = $_SESSION['idWorker'];
        $SQLstring = "SELECT post_office_work FROM worker WHERE idWorker = '$idWorker'";
        $result = mysqli_query($dblink, $SQLstring);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $post_office_work = (int) $row['post_office_work'];
        } else {
            // Обработка ошибки запроса
            echo "Ошибка выполнения запроса: " . mysqli_error($dblink);
        }

        $SQLstring = "SELECT idWorker, name, suname, post FROM worker WHERE post_office_work='$post_office_work' && post != 3";
        $resultX = mysqli_query($dblink, $SQLstring);

        if ($resultX->num_rows > 0) {
            echo "<h3>Список сотрудников вашего почтового отделения</h3>";
            echo "<table>";
            echo "<tr><th>id</th><th>Имя</th><th>Фамилия</th><th>Должность</th></tr>";
            while ($rowX = $resultX->fetch_assoc()) {
                $idpost = $rowX['post'];
                if ($idpost == 1)
                    $post = 'Стажер';
                else if ($idpost == 2)
                    $post = 'Клерк';
                else
                    $post = 'БОСС';

                echo "<tr id='Worker_" . $rowX['idWorker'] . "'>";
                echo "<td>" . $rowX['idWorker'] . "</td>";
                echo "<td>" . $rowX['name'] . "</td>";
                echo "<td>" . $rowX['suname'] . "</td>";
                echo "<td>" . $post . "</td>";

                echo '<td><button onclick="DEL(' . $rowX['idWorker'] . ')">Удалить</button></td>';
                echo "</tr>";
            }
            echo "</table>";
            echo '<button onclick="SAVE()">Сохранить</button>';
        } else {
            echo "В данном почтовом отделении нет сотрудников!";
        }
    }
    ?>

</body>
</html>
<script>
    function DEL(idWorker) {
        // Получаем ссылку на строку таблицы, которую нужно удалить
        var row = document.getElementById("Worker_" + idWorker);
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
        xmlhttp.open("GET", "DEL.php?idWorker=" + idWorker, true);
        xmlhttp.send();
    }
    function SAVE() {
        // Отправка AJAX-запроса на сервер
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "unlock-table.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Перенаправление на другую страницу после разблокировки таблицы
                window.location.href = "boss-page.php";
            }
        };
        xhr.send();
    }
</script>
