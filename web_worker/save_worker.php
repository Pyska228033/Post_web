<html>
<head>
    <link rel="stylesheet" href="StyleSheet_save_worker.css" />
</head>
<body>

    <?php

    include("bd.php");
    $sql = "LOCK TABLES worker WRITE";
    mysqli_query($dblink, $sql);

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
    if (isset($_POST['post'])) {
        $post = $_POST['post'];
        if ($post == '') {
            unset($post);
        }
    }

    if (isset($_POST['post_office_work'])) {
        $post_office_work = $_POST['post_office_work'];
        if ($post_office_work == '') {
            unset($post_office_work);
        }
    }
    //заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
        if ($password == '') {
            unset($password);
        }
    }
    //заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
    if (isset($_POST['login'])) {
        $login = $_POST['login'];
        if ($login == '') {
            unset($login);
        }
    }
    //если логин и пароль введены, то обрабатываем их, чтобы теги и скрипты не работали, мало ли что люди могут ввести
    $name = stripslashes($name);
    $name = htmlspecialchars($name);
    $sname = stripslashes($sname);
    $sname = htmlspecialchars($sname);
    $post = stripslashes($post);
    $post = htmlspecialchars($post);
    $post_office_work = stripslashes($post_office_work);
    $post_office_work = htmlspecialchars($post_office_work);
    $password = stripslashes($password);
    $password = htmlspecialchars($password);
    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    //удаляем лишние пробелы
    $name = trim($name);
    $sname = trim($sname);
    $post = trim($post);
    $post_office_work = trim($post_office_work);
    $password = trim($password);
    $login = trim($login);
    // подключаемся к базе
    include("bd.php"); // файл bd.php должен быть в той же папке, что и все остальные, если это не так, то просто измените путь
    // проверка на существование пользователя с таким же логином
    $SQLstring = "SELECT idWorker FROM worker WHERE login='$login'";
    $result = mysqli_query($dblink, $SQLstring);
    $myrow = mysqli_fetch_array($result);
    if (!empty($myrow['idWorker'])) {
        echo '<script>alert("Извините, введённый вами логин уже зарегистрирован. Введите другой логин.");</script>';
    } else {
        $SQLstring = "SELECT idtype_post FROM type_post WHERE name='$post'";
        $result2 = mysqli_query($dblink, $SQLstring);
        if ($result2)
        {
            $row2 = mysqli_fetch_assoc($result2);
            $idtype_post = (int)$row2['idtype_post'];
        } else {
            // Обработка ошибки запроса
            echo "Ошибка выполнения запроса: " . mysqli_error($dblink);
        }

        $SQLstring = "SELECT idPost_office FROM post_office WHERE name='$post_office_work'";
        $result3 = mysqli_query($dblink, $SQLstring);
        if ($result3) {
            $row3 = mysqli_fetch_assoc($result3);
            $idPost_office = (int)$row3['idPost_office'];
        } else {
            // Обработка ошибки запроса
            echo "Ошибка выполнения запроса: " . mysqli_error($dblink);
        }
        // если такого нет, то сохраняем данные
        $SQLstring = "INSERT INTO worker (name,suname,post,post_office_work,password,login) VALUES('$name','$sname','$idtype_post','$idPost_office','$password','$login')";
        $result4 = mysqli_query($dblink, $SQLstring);
        // Проверяем, есть ли ошибки
        if ($result4 == 'TRUE') {
            echo "<div class='centered-container'>";
            echo "<div class='form-container'>";
            echo "<p>";
            echo "Вы успешно зарегистрированы! Теперь вы можете зайти на сайт. <form action='index.php' method='GET'><input type='submit' value='Главная страница'></form>";
            echo "</p>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "Ошибка! Вы не зарегистрированы.";
        }
    }
    $sql = "UNLOCK TABLES";
    mysqli_query($dblink, $sql);

    ?>
</body>
</html>
