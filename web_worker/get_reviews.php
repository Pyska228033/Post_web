<?php
include("bd.php");

$sql = "SELECT idReviews, user, date_of_creation FROM reviews WHERE status = 1 AND worker IS NULL";
$result = mysqli_query($dblink, $sql);

if (mysqli_num_rows($result) > 0) {
    echo '<tr><th>ID</th><th>User</th><th>Date of Creation</th><th>Action</th></tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['idReviews'];
        $user = $row['user'];
        $date = $row['date_of_creation'];

        echo '<tr>';
        echo '<td>' . $id . '</td>';
        echo '<td>' . $user . '</td>';
        echo '<td>' . $date . '</td>';
        echo '<td>';
        echo '<a href="view_review.php?id=' . $id . '" target="_blank">Смотреть</a>';
        echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="4">Нет доступных отзывов.</td></tr>';
}

mysqli_close($dblink);
?>