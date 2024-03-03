<?php
session_start();
include("bd.php");
sleep(5);
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $worker = $_SESSION['idWorker'];
    mysqli_query($dblink, "LOCK TABLES reviews FOR UPDATE;");
    $updateSql = "UPDATE reviews SET worker = '" . $worker . "' WHERE idReviews = " . $id;
    mysqli_query($dblink, $updateSql);
    mysqli_query($dblink, "UNLOCK TABLES");

    $selectSql = "SELECT text_review FROM reviews WHERE idReviews = '$id' AND worker = $worker";
    $result = mysqli_query($dblink, $selectSql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $textReview = $row['text_review'];
        echo "<p>" . $textReview . "</p>";

        // Выводим кнопки "Принять" и "Отклонить"
        echo '<form action="process_review.php" method="POST">';
        echo '<input type="hidden" name="id" value="' . $id . '">';
        echo '<input type="submit" name="accept" value="Принять">';
        echo '<input type="submit" name="reject" value="Отклонить">';
        echo '</form>';
    } else {
        echo "Отзыв не найден.";
    }
} else {
    echo "Идентификатор отзыва не указан.";
}

mysqli_close($dblink);
?>
