<?php
session_start();
include "config.php";
$id_user = $_GET['id'];
$admin_id = $_GET['adm_id'];
if ($_GET['url']=='mahasiswa' or $_GET['url']=='dosen') {
	$user = $db->fetchSingleRow("sys_users","username",$id_user);
} else {
	$user=$db->fetchSingleRow('sys_users','id',$id_user);
}
$_SESSION['group_level']= $user->group_level;
$_SESSION['id_user']= $user->id;
$_SESSION['admin_id']= $admin_id;
$_SESSION['username']=$user->username;
$_SESSION['login']=1;
$_SESSION['login_as']=1;
$_SESSION['url']=$_GET['url'];
$_SESSION['back_uri']=$_GET['back_uri'];
header("location:".base_admin());
//print_r($_SESSION);
//header("location:./");