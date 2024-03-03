
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
        <h1>Регистрация отправления</h1>
    <form action="savemessage.php" method="post">
        <datalist id="users">
            <?php
            include("bd.php");

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
                    echo "<option value='" . $row['name'] . "'>" . $row['description'] . ', ' . $row['cost'] . 'руб.' . "</option>";
                }
            }
            ?>
        </datalist>


        <p>
            <label>
                Отправитель:<br />
            </label>
            <input list="users" name="sender" type="text" autofocus required />


        </p>


        <?php
        // Получаем id работника из текущей сессии
        $idWorker = $_SESSION['idWorker'];

        $sql = "SELECT name FROM post_office WHERE idPost_office = (SELECT post_office_work FROM worker WHERE idWorker = '$idWorker')";
        $result = mysqli_query($dblink, $sql);
        if ($result) {
            $myrow = mysqli_fetch_assoc($result);
        } else
            echo "Ошибка";

        $senderPostOffice = $myrow['name'];
        ?>

        <p>
            <label>
                Почтовое отделение отправителя:<br />
            </label>
            <input list="posts" type="text" name="senderpostoffice" value="<?php echo $senderPostOffice; ?>" id="senderpostoffice_input" onchange="checkSenderpostoffice()" />
            <span id="error1" style="color: red;"></span>

        </p>

        <p>
            <label>
                Получатель:<br />
            </label>
            <input list="users" type="text" name="recipient" required />

        </p>

        <p>
            <label>
                Почтовое отделение получателя:<br />
            </label>
            <input list="posts" type="text" name="recipientpostoffice" id="recipientpostoffice_input" onchange="checkRecipientpostoffice()" required />
            <span id="error2" style="color: red;"></span>
        </p>

        <p>
            <label>
                Праметры отправления:<br />
            </label>
            Вес (g): <input type="number" name="weightg" step="0.1" />
            <br />
            Ширина (m): <input type="number" name="widthm" step="0.01" />
            <br />
            Высота (m): <input type="number" name="heightm" step="0.001" />
            <br />
            Длинна (m): <input type="number" name="lengthm" step="0.01" />
            <br />
        </p>

        <p>
            <label>
                Дата отправления:<br />
            </label>
            <input type="date" name="date_of_dispatch" value="<?php date_default_timezone_set('Europe/Moscow'); echo date('Y-m-d'); ?>" />

        </p>

        <p>
            <label>
                Дата доставки:<br />
            </label>
            <input type="date" name="date_of_receipt" />
        </p>

          <p>
        <label for="service_input">Выберите услугу:</label>
        <input list="services" type="text" name="service" id="service_input" onchange="checkService()">
        <span id="error" style="color: red;"></span>
        <p>
            <label>
                Комментарий к отправке:<br />
            </label>
            <textarea name="comment" rows="4" cols="50"></textarea>

        </p>


        <input type="submit" value="Зарегистрировать отправление" id="submitButton"/>
    </form>
        <script src="ShoPopaloNeVodi.js"></script>
</div>

</body>
</html>

