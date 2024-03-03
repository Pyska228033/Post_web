<?php
session_start(); //  вся процедура работает на сессиях. Именно в ней хранятся данные  пользователя, пока он находится на сайте. Очень важно запустить их в  самом начале странички!!!
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if ($email == '') {
        unset($email);
    }
} //заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
if (isset($_POST['password'])) {
    $password = $_POST['password'];
    if ($password == '') {
        unset($password);
    }
}
//заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную
if (empty($email) or empty($password)) //если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
{
    exit("Вы ввели не всю информацию, вернитесь назад и заполните все поля!");
}
//если логин и пароль введены,то обрабатываем их, чтобы теги и скрипты не работали, мало ли что люди могут ввести
$email = stripslashes($email);
$email = htmlspecialchars($email);
$password = stripslashes($password);
$password = htmlspecialchars($password);

$email = trim($email);
$password = trim($password);

include("bd.php"); 
$SQLstring = "SELECT * FROM user WHERE email='$email'";
$result = mysqli_query($dblink, $SQLstring); 
$myrow = mysqli_fetch_array($result);
if (empty($myrow['email'])) {
    //если пользователя с введенным логином не существует
    echo '<script>alert("Извините, введённый вами email или пароль неверный.");</script>';
    echo '<script>window.location.href = "index.php";</script>';
    exit();
} else {
    
    if ($myrow['password'] == $password)
    {
        
        $_SESSION['email'] = $myrow['email'];
        $_SESSION['idUser'] = $myrow['idUser']; //эти данные очень часто используются, вот их и будет "носить с собой" вошедший пользователь
        header("Location: account.php");
        exit();
    }
    else
    {
        echo '<script>alert("Извините, введённый вами email или пароль неверный.");</script>';
        echo '<script>window.location.href = "index.php";</script>';
        exit();
    }
}
?>