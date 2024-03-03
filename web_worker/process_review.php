<?php
session_start();
include("bd.php");

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    if (isset($_POST['accept'])) {
        // Обновляем запись в базе данных, меняя значение поля "status" на 2
        $updateSql = "UPDATE reviews SET status = 2 WHERE idReviews = " . $id;
        mysqli_query($dblink, $updateSql);
        echo "Отзыв принят.";
    } elseif (isset($_POST['reject'])) {
        // Обновляем запись в базе данных, меняя значение поля "status" на 3
        $updateSql = "UPDATE reviews SET status = 3 WHERE idReviews = " . $id;
        mysqli_query($dblink, $updateSql);
        echo "Отзыв отклонен.";
    } else {
        echo "Неверное действие.";
    }
} else {
    echo "Идентификатор отзыва не указан.";
}

mysqli_close($dblink);
?>
