<?php
error_reporting(0);
include_once('../config.php');
define('TABLE', 'planning');
define('IdDefaultValuesShow', "");


function getSelectCatId($where, $select = "*")
{
	global $bdd;
	$requete = 'SELECT ' . $select . ' from ' . TABLE . ' WHERE ' . $where . " ORDER BY DateDebut";
	$rows = $bdd->query($requete);
	return $rows;
}
function getSelect($select = "*", $datedebut, $datefin)
{
	global $bdd;
	//echo $datedebut." ".$datefin;
	$requete = 'SELECT ' . $select . ' from ' . TABLE . ' WHERE 
	((TIMEDIFF(DateDebut, "' . $datedebut . '") > 0) AND (TIMEDIFF(DateDebut, "' . $datefin . '") < 0))
	 OR ((TIMEDIFF(DateFin, "' . $datedebut . '") > 0) AND (TIMEDIFF(DateFin, "' . $datefin . '") < 0))
	 OR ((TIMEDIFF(DateDebut, "' . $datedebut . '") < 0) AND (TIMEDIFF(DateFin, "' . $datedebut . '") > 0))
	 OR ((TIMEDIFF(DateDebut, "' . $datefin . '") < 0) AND (TIMEDIFF(DateFin, "' . $datefin . '") > 0))';
	//echo $requete;
	$rows = $bdd->query($requete);
	return $rows;
}
function nbrCurrentUser()
{
    global $bdd;
    //echo $datedebut." ".$datefin;
    $requete = 'SELECT `id_User` FROM `planning` WHERE ((TIMEDIFF(DateDebut, CURRENT_TIMESTAMP) < 0) AND (TIMEDIFF(DateFin, CURRENT_TIMESTAMP) > 0))';
    $result = $bdd->query($requete);
    $row = mysqli_fetch_all($result, MYSQLI_NUM);
    return count($row);
}
function nbrCurrentPc()
{
    global $bdd;
    //echo $datedebut." ".$datefin;
    $requete = 'SELECT `id_Pc` FROM `planning` WHERE ((TIMEDIFF(DateDebut, CURRENT_TIMESTAMP) < 0) AND (TIMEDIFF(DateFin, CURRENT_TIMESTAMP) > 0))';
    $result = $bdd->query($requete);
    $row = mysqli_fetch_all($result, MYSQLI_NUM);
    return count($row);
}
function create($values)
{
	global $bdd;
	$requete = 'INSERT INTO `planning`(`id_User`, `id_Pc`, `DateDebut`, `DateFin`) VALUES ("' . $values[0] . '","' . $values[1] . '","' . $values[2] . '","' . $values[3] . '")';
	$rows = $bdd->query($requete);
	return $rows;
}
function update($id,$datedebut,$datefin){
	global $bdd;
	$requete = "UPDATE " . TABLE . " SET `DateDebut`='".$datedebut."',`DateFin`='".$datefin."' where ID = $id ";
    $result = $bdd->query($requete);
    $row = mysqli_fetch_all($result, MYSQLI_NUM);
    return count($row);
}
function delete($id)
{
	try {
		global $bdd;
		$requete = 'DELETE FROM ' . TABLE . ' WHERE ID = "'.$id.'"';
		echo $requete;
		$bdd->query($requete);
	} catch (PDOException $e) {
		echo $requete . "<br>" . $e->getMessage();
	}
}
function deletePc($id)
{
	try {
		global $bdd;
		$requete = 'DELETE FROM ' . TABLE . ' WHERE id_Pc = "'.$id.'"';
		echo $requete;
		$bdd->query($requete);
	} catch (PDOException $e) {
		echo $requete . "<br>" . $e->getMessage();
	}
}

function deleteUser($id)
{
	try {
		global $bdd;
		$requete = 'DELETE FROM ' . TABLE . ' WHERE id_User = "'.$id.'"';
		echo $requete;
		$bdd->query($requete);
	} catch (PDOException $e) {
		echo $requete . "<br>" . $e->getMessage();
	}
}


//--------------
//********************************REQUETE POST********************************
if (isset($_GET['getselect']) && $_GET['getselect'] == '1' && isset($_GET['date1']) && isset($_GET['date2'])) {
	$select = "id_Pc,id_User";
	$datedebut = $_GET['date1'];
	$datefin = $_GET['date2'];
	$result = getSelect($select, $datedebut, $datefin);
	$row = mysqli_fetch_all($result, MYSQLI_NUM);
	echo json_encode($row);
}
if (isset($_GET['create']) && $_GET['create'] == '1') {
	if (isset($_GET['values'])) {
		$values = explode("/", $_GET['values']);
		$result = create($values);
		echo $result;
	}
}
if (isset($_POST['planning']) && $_POST['planning'] == '1' && isset($_POST['categ'])) {
	$row = "";
	if ($_POST['categ'] == 'user') {
		$where = 'id_User="' . $_POST['id'] . '"';
	} else {
		$where = 'id_Pc="' . $_POST['id'] . '"';
	}
	$result = getSelectCatId($where);
	$row = mysqli_fetch_all($result, MYSQLI_NUM);
	echo json_encode($row);
}
if (isset($_GET['update']) && $_GET['update'] == '1') {
	echo(update($_GET['id'],$_GET['DateDebut'],$_GET['DateFin']));
}
if (isset($_POST['delete']) && $_POST['delete'] == '1') {
	delete($_POST['id']);
}
if (isset($_POST['deletePc']) && $_POST['deletePc'] == '1') {
	deletePc($_POST['id']);
}
if (isset($_POST['deleteUser']) && $_POST['deleteUser'] == '1') {
	deleteUser($_POST['id']);
}
if (isset($_POST['nbrcurrentuser']) && $_POST['nbrcurrentuser'] == '1') {
    echo nbrCurrentUser();
}
if (isset($_POST['nbrcurrentpc']) && $_POST['nbrcurrentpc'] == '1') {
    echo nbrCurrentPc();
}