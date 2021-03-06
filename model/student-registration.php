<?php

require_once __DIR__ . '/functions/getMysqli.php';
require_once __DIR__ . '/config.php';

$mysqli = getMysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$login = htmlentities($mysqli->real_escape_string($_POST['login']));
$passwordHash = password_hash(
    htmlentities($mysqli->real_escape_string($_POST['password'])),
    PASSWORD_DEFAULT
);
$fio = htmlentities($mysqli->real_escape_string($_POST['fio']));
$group = htmlentities($mysqli->real_escape_string($_POST['group']));
$idRole = 2; // student

$isUserExists = $mysqli->query("SELECT COUNT(*) as `count` FROM `users` WHERE `login` = '$login'")->fetch_assoc()['count'] > 0;

if ($isUserExists) {
    header('Location: /student-registration.php?error=Студент+с+таким+логином+уже+существует');
    die();
}

$mysqli->query("INSERT INTO `users` VALUES (null, '$login', '$passwordHash', '$fio', $idRole)");

$idUser = $mysqli->query("SELECT `id` FROM `users` ORDER BY `id` DESC")->fetch_assoc()['id'];
$mysqli->query("INSERT INTO `id_group_id_user` VALUES ($idUser, '$group')");

header('Location: /accounts-management.php');
