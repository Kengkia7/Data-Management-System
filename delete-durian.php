<?php include 'setting/system.php'; ?>
<?php

if(!$_GET['id'] OR empty($_GET['id']))
{
	header('location: manage-durian.php');
}else
{
	$id = (int)$_GET['id'];
	$query = $db->query("DELETE FROM durian WHERE id = $id ");
	if($query){
		header('location: manage-durian.php');
	}
}

