
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
    <title>Отзыв</title>
    <link rel="stylesheet" href="StyleSheet_Review.css" />
</head>
<body>
    <h1>ОТЗЫВ</h1>

    <?php
    include("bd.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $text_review = $_POST['text_review'];
        $user = $_SESSION['idUser'];
        date_default_timezone_set('Europe/Moscow');
        $date_of_creation = date('Y-m-d');
        $status = 1;

        mysqli_autocommit($dblink, FALSE); // Отключаем автоматическую фиксацию транзакций

        mysqli_query($dblink, "LOCK TABLES reviews WRITE");

        try {
            $SQLstring = "INSERT INTO reviews (user, text_review, date_of_creation, status)
                          VALUES ('$user', '$text_review', '$date_of_creation', '$status')";
            mysqli_query($dblink, $SQLstring);

            mysqli_query($dblink, "UNLOCK TABLES");

            if (mysqli_commit($dblink)) {
                echo "<p>Отзыв успешно отправлен!</p>";
            } else {
                throw new Exception("Ошибка при фиксации транзакции.");
            }
        } catch (Exception $e) {
            mysqli_rollback($dblink);
            echo "<p>Произошла ошибка: " . $e->getMessage() . "</p>";
        }

        mysqli_autocommit($dblink, TRUE); // Включаем автоматическую фиксацию транзакций
    }
    ?>

    <form method="POST" action="Review.php">
        <label for="text_review">Текст отзыва:</label>
        <br />
        <textarea id="text_review" name="text_review" rows="4" cols="50"></textarea>
        <br />
        <br />
        <input type="submit" value="Отправить" />
    </form>
</body>
</html>
