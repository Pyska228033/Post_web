<?php
include("bd.php");

$name = $_POST['name'];
$translit = array(
    'а' => 'a',
    'б' => 'b',
    'в' => 'v',
    'г' => 'g',
    'д' => 'd',
    'е' => 'e',
    'ё' => 'e',
    'ж' => 'zh',
    'з' => 'z',
    'и' => 'i',
    'й' => 'th',
    'к' => 'k',
    'л' => 'l',
    'м' => 'm',
    'н' => 'n',
    'о' => 'o',
    'п' => 'p',
    'р' => 'r',
    'с' => 's',
    'т' => 't',
    'у' => 'u',
    'ф' => 'f',
    'х' => 'x',
    'ц' => 'ts',
    'ч' => 'ch',
    'ш' => 'sh',
    'щ' => 'sch',
    'ъ' => '',
    'ы' => 'y',
    'ь' => '',
    'э' => 'uh',
    'ю' => 'y',
    'я' => 'ya',
    'А' => 'a',
    'Б' => 'b',
    'В' => 'v',
    'Г' => 'g',
    'Д' => 'd',
    'Е' => 'e',
    'Ё' => 'e',
    'Ж' => 'zh',
    'З' => 'z',
    'И' => 'i',
    'Й' => 'th',
    'К' => 'k',
    'Л' => 'l',
    'М' => 'm',
    'Н' => 'n',
    'О' => 'o',
    'П' => 'p',
    'Р' => 'r',
    'С' => 's',
    'Т' => 't',
    'У' => 'u',
    'Ф' => 'f',
    'Х' => 'x',
    'Ц' => 'ts',
    'Ч' => 'ch',
    'Ш' => 'sh',
    'Щ' => 'sch',
    'Ъ' => '',
    'Ы' => 'y',
    'Ь' => '',
    'Э' => 'uh',
    'Ю' => 'y',
    'Я' => 'ya',
    ' ' => '',
    // Здесь нужно продолжить для остальных букв
);

$login = strtolower(strtr($name, $translit));


$query = "SELECT MAX(SUBSTRING_INDEX(login, '_', -1)) as max_index FROM worker WHERE login LIKE '$login%'";
$result = mysqli_query($dblink, $query);

$row = $result->fetch_assoc();
$maxIndex = $row['max_index'];

if ($maxIndex === NULL) {
    $index = 1;
} else {
    $index = $maxIndex + 1;
}

$generatedLogin = $login . '_' . $index;

echo $generatedLogin;
?>
