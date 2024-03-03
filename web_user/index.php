<!DOCTYPE html>
<html>
<head>
    <title>Почтовая служба</title>
    <link rel="stylesheet" href="StyleSheet_reg&enter.css" />
</head>
<body>
    <header class="header">
        <h1>Почтовая служба</h1>
        <p>Мы доставляем посылки по всей стране.</p>
    </header>
    <main class="main-content">
        <nav class="menu">
            <ul>
                <li>
                    <a href="#about-us">О нас</a>
                </li>
                <li>
                    <a href="#services">Услуги</a>
                </li>
                <li>
                    <a href="#contact-us">Свяжитесь с нами</a>
                </li>
            </ul>
        </nav>
        <section class="about-us" id="about-us">
            <h2>О нас</h2>
            <p>Мы являемся ведущей почтовой службой в стране с более чем 100-летним опытом работы. Мы предлагаем широкий спектр услуг, включая доставку посылок, писем и других почтовых отправлений.</p>
            <p>Мы гордимся нашей приверженностью качеству и обслуживанию клиентов. Наша команда опытных профессионалов стремится обеспечить своевременную и надежную доставку ваших посылок.</p>
        </section>
        <section class="services" id="services">
            <h2>Услуги</h2>
            <ul>
                <li>Доставка посылок</li>
                <li>Доставка писем</li>
                <li>Услуги экспресс-доставки</li>
                <li>Услуги отслеживания посылок</li>
                <li>Услуги хранения посылок</li>
            </ul>
        </section>
    </main>
    <section class="form-container">
        <h2>Вход</h2>
        <form action="testreg.php" method="post">
            <label for="email">Почта:</label>
            <input type="email" id="email" name="email" required />
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password"
                pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{10,}$"
                title="Длина пароля должна составлять не менее 10 символов и включать буквы и цифры." required />
            <p>
                <input type="submit" name="submit" value="Войти" />
                <br />
                <a href="reg.php">Зарегистрироваться</a>
            </p>
        </form>
    </section>
    <section class="contact-us" id="contact-us">
        <h2>Свяжитесь с нами</h2>
        <p>Если у вас есть какие-либо вопросы или вам нужна помощь, пожалуйста, свяжитесь с нами по следующим каналам:</p>
        <ul>
            <li>Телефон: 1-800-555-1212</li>
            <li>Электронная почта: info@postalservice.com</li>
            <li>Почтовый адрес: 123 Main Street, Anytown, CA 12345</li>
        </ul>
    </section>
    <script src="validatePassword.js"></script>
    <?php
    if (empty($_SESSION['email']) or empty($_SESSION['idUser'])) {
        echo "Вы вошли на сайт, как гость<br>";
    } else {
        echo "Вы вошли на сайт, как " . $_SESSION['email'] . "";
    }
    ?>
</body>
</html>
