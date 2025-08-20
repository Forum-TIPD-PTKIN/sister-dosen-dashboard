<?php
include "config.php";
session_start();
$id_user = $_GET['id'];
$user = $db->fetchSingleRow("sys_users","id",$id_user);
$_SESSION['group_level']= $user->group_level;
$_SESSION['id_user']= $user->id;
$_SESSION['username']=$user->username;
$_SESSION['login']=1;

$url = $_SESSION["back_uri"];
unset ($_SESSION["admin_id"]);
unset ($_SESSION["login_as"]);
unset ($_SESSION["url"]);
unset ($_SESSION["back_uri"]);
header("location:".base_admin().$url);