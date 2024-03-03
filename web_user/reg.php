<html>
    <head>
    <title>Регистрация</title>
       <link rel="stylesheet" href="StyleSheet_reg&enter.css">
    </head>
    <body>
        <div class="form-container">
            <h2>Регистрация</h2>
            <form action="save_user.php" method="post">
    <!--**** save_user.php - это адрес обработчика.  То есть, после нажатия на кнопку "Зарегистрироваться", данные из полей  отправятся на страничку save_user.php методом "post" ***** -->
            <p>
                <label>Ваше Имя:<br></label>
                <input name="name" type="text" required>
            </p>
<!--**** В текстовое поле (name="login" type="text") пользователь вводит свой логин ***** -->
            <p>
                <label>Ваша Фамилия:<br></label>
                <input name="sname" type="text" required>
            </p>
<!--**** В текстовое поле (name="login" type="text") пользователь вводит свой логин ***** -->
            <p>
                <label>Электронная почта:<br></label>
                <input name="email" type="email" required>
            </p>
<!--**** В текстовое поле (name="login" type="text") пользователь вводит свой логин ***** -->
            <p>
                <label>Номер телефона:<br></label>
                <input type="tel" id="phone_number" name="phone_number"
                       pattern="\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}" 
                       title="Введите номер в формате +7XXXXXXXXXX" 
                       required oninput="formatPhone(this)">
                <script>
                function formatPhone(input) {
                    const value = input.value.replace(/\D/g, '');
                    const newValue = '+7 (' + value.substr(1, 3) + ') ' + value.substr(4, 3) + '-' + value.substr(7, 2) + '-' + value.substr(9, 2);
                    input.value = newValue;
                }
                </script>
            </p>
<!--**** В текстовое поле (name="login" type="text") пользователь вводит свой логин ***** -->
            <p>
                <label>Ваш пароль:<br></label>
                <input name="password" type="password" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{10,}$"
                   title="Длина пароля должна составлять не менее 10 символов и включать буквы и цифры."
                   required>
            </p>
<!--**** В поле для паролей (name="password" type="password") пользователь вводит свой пароль ***** --> 
            <p>
                <input type="submit" name="submit" value="Зарегистрироваться">
<!--**** Кнопочка (type="submit") отправляет данные на страничку save_user.php ***** --> 
            </p>
            </form>
        </div>
        <script src="validatePassword.js"></script>
    </body>
    </html>