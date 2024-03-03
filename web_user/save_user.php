<html>
    <head>
        <link rel="stylesheet" href="StyleSheet_save_user.css" />
    </head>
        <body>

            <?php
            if (isset($_POST['name'])) {
                $name = $_POST['name'];
                if ($name == '') {
                    unset($name);
                }
            } //заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
            if (isset($_POST['sname'])) {
                $sname = $_POST['sname'];
                if ($sname == '') {
                    unset($sname);
                }
            } //заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
            if (isset($_POST['email'])) {
                $email = $_POST['email'];
                if ($email == '') {
                    unset($email);
                }
            } //заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
            if (isset($_POST['phone_number'])) {
                $phone_number = $_POST['phone_number'];
                if ($phone_number == '') {
                    unset($phone_number);
                }
            } //заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
            if (isset($_POST['password'])) {
                $password = $_POST['password'];
                if ($password == '') {
                    unset($password);
                }
            }
            //если логин и пароль введены, то обрабатываем их, чтобы теги и скрипты не работали, мало ли что люди могут ввести
            $name = stripslashes($name);
            $name = htmlspecialchars($name);
            $sname = stripslashes($sname);
            $sname = htmlspecialchars($sname);
            $email = stripslashes($email);
            $email = htmlspecialchars($email);
            $phone_number = stripslashes($phone_number);
            $phone_number = htmlspecialchars($phone_number);
            $password = stripslashes($password);
            $password = htmlspecialchars($password);
            //удаляем лишние пробелы
            $name = trim($name);
            $sname = trim($sname);
            $email = trim($email);
            $phone_number = trim($phone_number);
            $password = trim($password);
            // подключаемся к базе
            include("bd.php"); // файл bd.php должен быть в той же папке, что и все остальные, если это не так, то просто измените путь
            // проверка на существование пользователя с таким же логином
            $SQLstring = "SELECT idUser FROM user WHERE email='$email'";
            $result = mysqli_query($dblink, $SQLstring);
            $myrow = mysqli_fetch_array($result);
            if (!empty($myrow['idUser'])) {
                echo '<script>alert("Извините, введённый вами логин уже зарегистрирован. Введите другой логин.");</script>';
            }
            else{
                // если такого нет, то сохраняем данные
                $SQLstring = "INSERT INTO user (name,surname,email,phone_number,password) VALUES('$name','$sname','$email','$phone_number','$password')";
                $result2 = mysqli_query($dblink, $SQLstring);
                // Проверяем, есть ли ошибки
                if ($result2 == 'TRUE') {
                    echo "<div class='centered-container'>";
                    echo "<div class='form-container'>";
                    echo "<p>";
                    echo "Вы успешно зарегистрированы! Теперь вы можете зайти на сайт. <form action='index.php' method='GET'><input type='submit' value='Главная страница'></form>";
                    echo "</p>";
                    echo "</div>";
                    echo "</div>";
                } 
                else {
                    echo "Ошибка! Вы не зарегистрированы.";
                }
            }

            ?>
        </body>
</html>
