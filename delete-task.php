<?php include 'setting/system.php'; ?>
<?php

if(!$_GET['id'] OR empty($_GET['id']))
{
	header('location: manage-task.php');
}else
{
	$id = (int)$_GET['id'];
	$query = $db->query("DELETE FROM task WHERE id = $id ");
	if($query){
		header('location: manage-task.php');
	}
}

