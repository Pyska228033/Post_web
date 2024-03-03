<?php

session_start();
    if(isset($_SESSION['LAST_ACTIVITY']))
    {
        if(time() - $_SESSION['LAST_ACTIVITY'] > 120)
        {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit();
        }
    }
$_SESSION['LAST_ACTIVITY'] = time();

?>

<?php
// Подключение к базе данных
include("bd.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Личный кабинет стажера</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="StyleSheetIntern_page.css" />

    <script>
        $(document).ready(function () {
            function refreshReviews() {
                $.ajax({
                    url: 'get_reviews.php', // Файл для получения отзывов из базы данных
                    success: function (data) {
                        $('#reviewsTable').html(data); // Обновление содержимого таблицы
                    }
                });
            }

            setInterval(refreshReviews, 500); 
        });
    </script>
</head>
<body>
    <h1>Личный кабинет стажера</h1>

    <h2>Список отзывов</h2>
    <table id="reviewsTable">
        <!-- Содержимое таблицы будет обновлено через AJAX -->
    </table>

    <?php
    mysqli_close($dblink);
    ?>
</body>
</html>
