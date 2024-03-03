<html>
    <head>
    <title>Регистрация</title>
       <link rel="stylesheet" href="StyleSheet_reg&enter_worker.css">
    </head>
    <body>
        <div class="form-container">
            <h2>Регистрация</h2>
            <form action="save_worker.php" method="post">
    <!--**** save_user.php - это адрес обработчика.  То есть, после нажатия на кнопку "Зарегистрироваться", данные из полей  отправятся на страничку save_user.php методом "post" ***** -->
            <p>
                <label>Имя:<br></label>
                <input name="name" id="name" type="text" onkeyup="generateLogin()" required>
            </p>
<!--**** В текстовое поле (name="login" type="text") пользователь вводит свой логин ***** -->
            <p>
                <label>Фамилия:<br></label>
                <input name="sname" type="text" required>
            </p>

            <p>
                <label>Должность:<br></label>
                <input list="posts" name="post" type="text" required>
                <datalist id="posts">
                    <?php
                    include("bd.php");

                    // выполнение запроса к базе данных
                    $sql = "SELECT name FROM type_post";
                    $result = mysqli_query($dblink, $sql);
                    // вывод списка доступных значений
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['name'] . "'>";
                        }
                    }
                    ?>
                </datalist>

            </p>

<!--**** В текстовое поле (name="login" type="text") пользователь вводит свой логин ***** -->
            <p>
                <label>Офис работы:<br></label>
                <input list="post_offices_work" name="post_office_work" type="text" required >
                <datalist id="post_offices_work">
                    <?php
                    include("bd.php");

                    // выполнение запроса к базе данных
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
            </p>

                <!--**** В поле для паролей (name="password" type="password") пользователь вводит свой пароль ***** -->
            <p>
                <label>Пароль:<br></label>
                <input name="password" type="password" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{10,}$"
                        title="Длина пароля должна составлять не менее 10 символов и включать буквы и цифры."
                        required />
            </p>

<!--**** В текстовое поле (name="login" type="text") пользователь вводит свой логин ***** -->

            <p>
                <label>login:<br></label>
                <input name="login" id="login" type="text" required />

                <script>
                        function generateLogin() {
                            var name = document.getElementById('name').value;
                            var login = name.replace(/\s/g, '').toLowerCase();

                            var xhr = new XMLHttpRequest();
                            xhr.open('POST', 'generateLogin.php', true);
                            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState == 4 && xhr.status == 200) {
                                    var generatedLogin = xhr.responseText;
                                    document.getElementById('login').value = generatedLogin;
                                }
                            };
                            xhr.send('name=' + name);
                        }
                </script>
            </p>
            

            <p>
                <input type="submit" name="submit" value="Зарегистрироваться">
<!--**** Кнопочка (type="submit") отправляет данные на страничку save_user.php ***** --> 
            </p>
            </form>
        </div>
        <script src="validatePassword.js"></script>
    </body>
    </html>