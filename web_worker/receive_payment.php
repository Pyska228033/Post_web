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
    <title>Платежи</title>
    <link rel="stylesheet" href="StyleSheetPayment_page.css" />

</head>
<body>
    <h1>Платежи</h1>
    <form method="post">
        <input type="text" name="package_number" placeholder="Введите номер посылки" value="<?php if (isset($_POST['package_number']))
            echo $_POST['package_number'] ?>" />
        <button type="submit">Рассчитать стоимость</button>
    </form>
    <br />
    <?php
    include("bd.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['package_number'])) {
            $package_number = $_POST['package_number'];
            if ($package_number != '') {
                $package_number = trim($package_number);
                $_SESSION['package_number'] = (int) $package_number;


                $SQLstring = "SELECT * FROM message WHERE idMessage='$package_number'";
                $resultX = mysqli_query($dblink, $SQLstring);
                //чтобы блокировка работала неправильно нужно либо удалить LOCK TABLES либо 46 строку перенести на 57 строку
                    sleep(5);
                    mysqli_query($dblink, "LOCK TABLES massege FOR UPDATE;");
                    if ($resultX->num_rows == 1) {
                        $idWorker = $_SESSION['idWorker'];
                        $SQLstring = "SELECT * FROM message WHERE idMessage='$package_number' AND (locked=0 OR locked = '$idWorker')";
                        $resultX = mysqli_query($dblink, $SQLstring);

                        if ($resultX->num_rows == 1) {
                            $row = $resultX->fetch_assoc();
                            $packageId = $row['idMessage'];
                            // Заблокировать посылку для других пользователей

                            $SQLstring = "SELECT * FROM message WHERE idMessage='$package_number' AND locked=0";
                            $resultY = mysqli_query($dblink, $SQLstring);
                            if ($resultY->num_rows == 1) {
                                $SQLstring = "UPDATE message SET locked='$idWorker' WHERE idMessage='$packageId'";
                                mysqli_query($dblink, $SQLstring);
                            }
                            mysqli_query($dblink, "UNLOCK TABLES");
                            $SQLstring = "SELECT Services_idServices FROM list_of_services WHERE Message_ID='$package_number'";
                            $result = mysqli_query($dblink, $SQLstring);
                            $sum = 150;
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $serviceId = $row['Services_idServices'];
                                    $SQLstring = "SELECT name, cost FROM services WHERE idServices='$serviceId'";
                                    $result1 = mysqli_query($dblink, $SQLstring);
                                    // Вывод результатов на странице
                                    if ($result1->num_rows > 0) {
                                        while ($row1 = $result1->fetch_assoc()) {
                                            echo $row1['name'] . ":" . $row1['cost'] . "руб." . "<br>";
                                            $sum += (int) $row1['cost'];
                                        }
                                    } else {
                                        echo "Результаты не найдены";
                                    }
                                }
                            }
                            echo "Доставка:" . 150 . "руб." . "<br>";
                            echo "ИТОГО " . $sum . "руб.";
                            $_SESSION['sum'] = (float) $sum;


                            // Генерируем QR-код
                            include('phpqrcode/qrlib.php');
                            $url = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
                            $path = 'D:\Ampps\www\web_worker\href-qr.jpeg';
                            QRcode::png($url, $path);
                        } else {
                            echo "Посылка с данным номером недоступна для обработки другими пользователями";
                        }
                    }
                    else{
                        echo "Посылки с данным номером нет!";
                    }
            }
        }
    }
    ?>


    <script>
        function EntQR() {
            var qrContainer = document.getElementById("qrContainer");
            qrContainer.innerHTML = '<img src="href-qr.jpeg">';
        }
    </script>
    <script>
        function NoQR() {
            var qrContainer = document.getElementById("qrContainer");
            qrContainer.innerHTML = '';
        }
    </script>


    <form method="post" action="">
        <p>
            <label for="payment">Выберите способ оплаты:</label>
            <select name="payment" onchange="if(this.value=='4') EntQR(); else NoQR()">
                <option value='1'>Наличными</option>
                <option value='2'>Картой</option>
                <option value='4'>QR - кодом</option>
            </select>
        </p>
        <div id="qrContainer"></div>

        <button type="submit" name="pay">Оплатить</button>

    </form>
    
    <br />

    <?php
    if (isset($_POST['pay']) && isset($_SESSION['package_number'])) {
        $package_number = (int) $_SESSION['package_number'];
        $idWorker = $_SESSION['idWorker'];
        $SQLstring = "SELECT * FROM message WHERE idMessage='$package_number' AND locked = '$idWorker'";
        $resultX = mysqli_query($dblink, $SQLstring);

        if ($resultX->num_rows == 1) {
            if (isset($_POST['payment'])) {
                $payment = $_POST['payment'];
                if ($payment == '') {
                    unset($payment);
                }
            }
            $payment = (int) $payment;

            date_default_timezone_set('Europe/Moscow');
            $date_payment = date('Y-m-d');

            $SQLstring = "SELECT * FROM payment WHERE message='$package_number'";
            $result = mysqli_query($dblink, $SQLstring);

            // Проверяем, есть ли запись в таблице payment с message = $package_number
            if (mysqli_num_rows($result) == 0) {

                // Добавление платежа
                $sum = (float) $_SESSION['sum'];
                $SQLstring = "INSERT INTO payment (message, sum, date_payment, payment_methods) VALUES('$package_number', '$sum', '$date_payment', '$payment')";
                $result = mysqli_query($dblink, $SQLstring);
                $SQLstring = "UPDATE message SET status = 3 WHERE idMessage = $package_number";
                $result1 = mysqli_query($dblink, $SQLstring);

                // Добавление пути
                $idWorker = $_SESSION['idWorker'];
                $SQLstring = "SELECT post_office_work FROM worker WHERE idWorker='$idWorker'";
                $resultK = mysqli_query($dblink, $SQLstring);
                if ($resultK) {
                    $row = mysqli_fetch_assoc($resultK);
                    $post_office_work = (int) $row['post_office_work'];
                } else {
                    echo "Ошибка выполнения запроса: " . mysqli_error($dblink);
                }
                $SQLstring = "INSERT INTO way (message_ID,post_offis_id,date,accepted_worker) VALUES('$package_number','$post_office_work','$date_payment','$idWorker')";
                $result8 = mysqli_query($dblink, $SQLstring);


                echo "<div class='centered-container'>";
                echo "<div class='form-container'>";
                echo "<p>";
                echo "Платёж принят!<form action='clerk-page.php' method='GET'><input type='submit' value='На главную.'></form>";
                echo "</p>";
                echo "</div>";
                echo "</div>";
                $SQLstring = "UPDATE message SET locked=0 WHERE idMessage='$package_number'";
                mysqli_query($dblink, $SQLstring);

            } else {

                $SQLstring = "UPDATE message SET locked=0 WHERE idMessage=$package_number";
                mysqli_query($dblink, $SQLstring);
                echo "Поссылка с данным номером уже оплачивалась!";

            }
        }
    }
    ?>
</body>
</html>

