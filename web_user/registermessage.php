
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
                    echo "<option value='" . $row['name'] . "' data-cost='" . $row['cost'] . "'>" . $row['description'] . ', ' . $row['cost'] . 'руб.' . "</option>";
                }
            }
            ?>
        </datalist>


        <p>
            <label>
                Почтовое отделение отправителя:<br />
            </label>
            <input list="posts" type="text" name="senderpostoffice" id="senderpostoffice_input" onchange="checkSenderpostoffice()" />
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
                Дата отправления:<br />
            </label>
            <input type="date" name="date_of_dispatch" value="<?php date_default_timezone_set('Europe/Moscow'); echo date('Y-m-d'); ?>" />

        </p>

        <p>
            <label for="service_input">Выберите услугу:</label>
            <input list="services" type="text" name="service" id="service_input" onchange="checkService(); checkService1()" />
            <span id="error" style="color: red;"></span>
        </p>

        <p id="cost_label">
            Стоимость отправления: <span id="total_cost">150 руб.</span>
        </p>


        <p>
            <label>
                Комментарий к отправке(укажите здесь адрес по которому курьер сможет забрать посылку):<br />
            </label>
            <textarea name="comment" rows="4" cols="50"></textarea>

        </p>

        <h2>Информация о карте</h2>

        <div class="card-form">
        <div class="card-number">
            <label for="card_number_input">Номер карты:</label>
            <input type="text" name="card_number" id="card_number_input" required />
        </div>

        <div class="card-holder">
            <label for="card_holder_input">Владелец карты:</label>
            <input type="text" name="card_holder" id="card_holder_input" required />
        </div>

        <div class="card-expiration">
            <label for="expiration_date_input">Срок действия:</label>
            <input type="text" name="expiration_date" id="expiration_date_input" required />
        </div>

        <div class="card-cvv">
            <label for="cvv_input">CVV:</label>
            <input type="text" name="cvv" id="cvv_input" required />
        </div>
        </div>
        <style>
        .card-form {
        display: flex;
        flex-direction: column;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 5px;
        background-color: #f9f9f9;
        max-width: 400px;
        margin-bottom: 20px;
        }
        .card-form label {
            font-weight: bold;
        }
        .card-form input[type="text"] {
            padding: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .card-form input[type="text"]:focus {
                outline: none;
                border-color: #007bff;
        }
        .card-form input[type="text"]:invalid {
                border-color: #dc3545;
        }
        .card-form input[type="text"]::placeholder {
                color: #999;
        }
        .card-form .card-number {
            display: flex;
            justify-content: space-between;
        }
        .card-form .card-number input[type="text"] {
                flex-grow: 1;
                margin-right: 10px;
        }
        .card-form .card-holder,
        .card-form .card-expiration,
        .card-form .card-cvv {
            display: flex;
            justify-content: space-between;
        }
        .card-form .card-holder input[type="text"],
        .card-form .card-expiration input[type="text"],
        .card-form .card-cvv input[type="text"] {
                flex-grow: 1;
                margin-right: 10px;
                max-width: 200px;
        }
        </style>


        <input type="submit" value="Зарегистрировать отправление" id="submitButton"/>
    </form>
        <script src="ShoPopaloNeVodi.js"></script>
</div>

</body>
</html>

<script>
        function checkService1() {
        var serviceInput = document.getElementById("service_input");
    var costLabel = document.getElementById("cost_label");
    var totalCost = document.getElementById("total_cost");

    if (serviceInput.value === "") {
        costLabel.style.display = "block";
        totalCost.textContent = "150 руб.";
    } else {
        var selectedOption = document.querySelector("#services option[value='" + serviceInput.value + "']");
        if (selectedOption) {
            var cost = parseInt(selectedOption.getAttribute("data-cost"), 10) + 150;
            totalCost.textContent = cost + " руб.";
        }
    }
    }
</script>
