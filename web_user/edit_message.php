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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="StyleSheet1.css" />

    <title>Регистрация отправления</title>
</head>
<body>

    <div class="form-container">
        <h1>Изменение отправления</h1>
        <form action="saveChangemessage.php" method="post">
            <datalist id="users">
                <?php
                include("bd.php");
                $idMessage = $_GET['idMessage'];
                $_SESSION['idMessage'] = $idMessage;

                $sql = "SELECT idUser, email, phone_number FROM user";
                $result = mysqli_query($dblink, $sql);
                // вывод списка доступных значений
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['idUser'] . "'>" . $row['email'] . " " . $row['phone_number'] . "</option>";
                    }
                }
                ?>
            </datalist>

            <datalist id="posts">
                <?php
                $sql = "SELECT name, address, city FROM post_office";
                $result = mysqli_query($dblink, $sql);
                // вывод списка доступных значений
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['name'] . "'>" . $row['city'] . ', ' . $row['address'] . "</option>";
                    }
                }
                ?>
            </datalist>

            <datalist id="services">
                <?php
                $sql = "SELECT name, description, cost FROM services";
                $result = mysqli_query($dblink, $sql);
                // вывод списка доступных значений
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['name'] . "' data-cost='" . $row['cost'] . "'>" . $row['description'] . ', ' . $row['cost'] . 'руб.' . "</option>";
                    }
                }
                ?>
            </datalist>

            <?php
            $sql = "SELECT recipient, senders_post_office, recipients_post_office, date_of_dispatch, Comment FROM message WHERE idMessage = $idMessage";
            $result = mysqli_query($dblink, $sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                ?>
                <p>
                    <label>
                        Почтовое отделение отправителя:<br />
                    </label>
                    <input list="posts" type="text" name="senderpostoffice" id="senderpostoffice_input" onchange="checkSenderpostoffice()" value="<?php echo $row['senders_post_office']; ?>" />
                    <span id="error1" style="color: red;"></span>
                </p>

                <p>
                    <label>
                        Получатель:<br />
                    </label>
                    <input list="users" type="text" name="recipient" required value="<?php echo $row['recipient']; ?>" />
                </p>

                <p>
                    <label>
                        Почтовое отделение получателя:<br />
                    </label>
                    <input list="posts" type="text" name="recipientpostoffice" id="recipientpostoffice_input" onchange="checkRecipientpostoffice()" required value="<?php echo $row['recipients_post_office']; ?>" />
                    <span id="error2" style="color: red;"></span>
                </p>

                <p>
                    <label>
                        Дата отправления:<br />
                    </label>
                    <input type="date" name="date_of_dispatch" value="<?php echo $row['date_of_dispatch']; ?>" />
                </p>

                <p>
                    <label>
                        Комментарий к отправке(укажите здесь адрес по которому курьер сможет забрать посылку):<br />
                    </label>
                    <textarea name="comment" rows="4" cols="50">
                        <?php echo $row['Comment']; ?>
                    </textarea>
                </p>
                <?php
            }
            ?>

            <input type="submit" value="Сохранить изменения" id="submitButton" />
        </form>
        <script src="ShoPopaloNeVodi.js"></script>
    </div>


</body>
</html>

