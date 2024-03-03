<?php
    //  вся процедура работает на сессиях. Именно в ней хранятся данные  пользователя, пока он находится на сайте. Очень важно запустить их в  самом начале странички!!!
    session_start();
    ?>
    <html>
    <head>
    <title>Главная страница</title>
        <link rel="stylesheet" href="StyleSheet_reg&enter_worker.css" />

    </head>
    <body>
        <div class="form-container">
            <h2>Вход</h2>
            <form action="testreg_worker.php" method="post">

                <!-- Email input field -->
                <label for="login">Логин:</label>
                <input type="text" id="login" name="login" required />

                <!-- Password input field with pattern for validation -->
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password"
                    pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{10,}$"
                    title="Длина пароля должна составлять не менее 10 символов и включать буквы и цифры."
                    required />

                <!--**** В поле для паролей (name="password" type="password") пользователь вводит свой пароль ***** -->

                <p>
                    <input type="submit" name="submit" value="Войти" />

                    <!--**** Кнопочка (type="submit") отправляет данные на страничку testreg.php ***** -->
                    <br />
                    <!--**** ссылка на регистрацию, ведь как-то же должны гости туда попадать ***** -->
                    <a href="reg_worker.php">Зарегистрироваться</a>
                </p>
            </form>

        </div>
    <br />
        <script src="validatePassword_worker.js"></script>



            <?php
    // Проверяем, пусты ли переменные логина и id пользователя
                if (empty($_SESSION['login']) or empty($_SESSION['idWorker']))
                {
                }
                else
                {

    // Если не пусты, то мы выводим ссылку
                    echo "Вы вошли на сайт, как ".$_SESSION['login']."";
                }
            ?>
    </body>
</html>