<?php
session_start(); //  вся процедура работает на сессиях. Именно в ней хранятся данные  пользователя, пока он находится на сайте. Очень важно запустить их в  самом начале странички!!!
if (isset($_POST['login'])) {
    $login = $_POST['login'];
    if ($login == '') {
        unset($login);
    }
} //заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
if (isset($_POST['password'])) {
    $password = $_POST['password'];
    if ($password == '') {
        unset($password);
    }
}
//заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную
if (empty($login) or empty($password)) //если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
{
    exit("Вы ввели не всю информацию, вернитесь назад и заполните все поля!");
}
//если логин и пароль введены,то обрабатываем их, чтобы теги и скрипты не работали, мало ли что люди могут ввести
$login = stripslashes($login);
$login = htmlspecialchars($login);
$password = stripslashes($password);
$password = htmlspecialchars($password);
//удаляем лишние пробелы
$login = trim($login);
$password = trim($password);
// подключаемся к базе
include("bd.php"); // файл bd.php должен быть в той же папке, что и все остальные, если это не так, то просто измените путь
$SQLstring = "SELECT * FROM worker WHERE login='$login'";
$result = mysqli_query($dblink, $SQLstring); //извлекаем из базы все данные о пользователе с введенным логином
$myrow = mysqli_fetch_array($result);
if (empty($myrow['login'])) {
    //если пользователя с введенным логином не существует
    echo '<script>alert("Извините, введённый вами login или пароль неверный.");</script>';
    echo '<script>window.location.href = "index.php";</script>';
    exit();
} else {
    //если существует, то сверяем пароли
    if ($myrow['password'] == $password)
    {
        //если пароли совпадают, то запускаем пользователю сессию! Можете его поздравить, он вошел!
        $_SESSION['login'] = $myrow['login'];
        $_SESSION['idWorker'] = $myrow['idWorker'];
        $_SESSION['post'] = $myrow['post'];//эти данные очень часто используются, вот их и будет "носить с собой" вошедший пользователь

        if (isset($_SESSION['post'])) {
            if ($_SESSION['post'] == '2') {
                header('Location: clerk-page.php');
                exit();
            } elseif ($_SESSION['post'] == '1') {
                header('Location: intern-page.php');
                exit();
            } elseif ($_SESSION['post'] == '3') {
                header('Location: boss-page.php');
                exit();
            }
        } else {
            // Перенаправить на страницу входа или другую страницу по умолчанию
            header('Location: index.php');
            exit();
        }
        exit();
    }
    else
    {
        echo '<script>alert("Извините, введённый вами login или пароль неверный.");</script>';
        echo '<script>window.location.href = "index.php";</script>';
        exit();
    }
}
?>