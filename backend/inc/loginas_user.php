<?php
session_start();
include "config.php";
$email = $_GET['email'];
$user = $db->fetchSingleRow("user_google","email",$email);
$mhs = $db->fetchSingleRow("tb_user_daftar_step","email_user",$email);
$_SESSION['email']= $user->email;
$_SESSION['nomor_pendaftaran']= $mhs->nomor_pendaftaran;
$_SESSION['user']= array(
	'name' => $user->name,
	'email' => $user->email,
	'picture' => $user->picture
);
header("location:".base_url());