<?php
error_reporting(-1);
@include_once('../config.php');
$obj = new USER();

class USER
{
	public $TABLE = "users";
	public $IdDefaultValuesShow = "3,4,5,6n";
	public $FileName;

	public function __construct()
	{
		$this->FileName = substr(basename(__FILE__), 0, -4);
	}
	// récupere tous les users
	function getAll($where = "Role=0", $order = "ID DESC")
	{
		$ByOrder = "";
		($order == "") ? $ByOrder = " ORDER BY " . $order : "";
		global $bdd;
		$requete = 'SELECT * from ' . $this->TABLE . ' WHERE ' . $where . $ByOrder;
		$rows = $bdd->query($requete);
		return $rows;
	}
	// récupere tous les users
	function getSelect($select = "*", $where = "Role=0", $order = "ID DESC")
	{
		$ByOrder = "";
		($order == "") ? $ByOrder = " ORDER BY " . $order : "";
		global $bdd;
		$requete = 'SELECT ' . $select . ' from ' . $this->TABLE . ' WHERE ' . $where . $ByOrder;
		$rows = $bdd->query($requete);
		return $rows;
	}

	//récupere un user
	function getByID($id)
	{
		global $bdd;
		$requete = "SELECT * from " . $this->TABLE . " where id = '$id' ";
		$row = $bdd->query($requete);
		return $row;
	}

	function search($search)
	{
		global $bdd;
		$requete = 'SELECT * from ' . $this->TABLE . ' WHERE ((Nom LIKE "%' . $search . '%") OR (Prenom LIKE "%' . $search . '%") OR (Email LIKE "%' . $search . '%")) AND Role=0';
		$rows = $bdd->query($requete);
		return $rows;
	}

	// creer un user
	function create($queryStringSql = "Nom=aze&Prenom=qsd")
	{
		$datalist = explode("&", $queryStringSql);
		$colums = "";
		for ($i = 0; $i < count($datalist); $i++) {
			if (!$colums == "") $colums .= ",";
			$colums .= explode("=", $datalist[$i])[0];
		}
		$values = "";
		for ($i = 0; $i < count($datalist); $i++) {
			if (!$values == "") $values .= ",";
			$values .= "'" . explode("=", $datalist[$i])[1] . "'";
		}
		try {
			global $bdd;
			$sql = "INSERT INTO " . $this->TABLE . " ($colums) VALUES ($values)";
			if ($bdd->query($sql)) {
				$id = $bdd->query("SELECT ID FROM " . $this->TABLE . " ORDER BY ID DESC LIMIT 1");
				$id = mysqli_fetch_all($id, MYSQLI_NUM)[0][0];
				$sqlresult = $this->getByID($id);
				return $sqlresult;
			} else {
				echo 0;
			}
		} catch (PDOException $e) {
			echo 0;
		}
	}

	//mettre à jour
	function update($id, $sqlReq)
	{
		try {
			global $bdd;
			$requete = "UPDATE " . $this->TABLE . " SET $sqlReq where ID = $id ";
			$bdd->execute($requete);
		} catch (PDOException $e) {
			echo $requete . "<br>" . $e->getMessage();
		}
	}

	//suprime
	function delete($id)
	{
		try {
			global $bdd;
			$requete = "DELETE FROM " . $this->TABLE . " WHERE ID = $id";
			echo $requete;
			$stmt = $bdd->query($requete);
		} catch (PDOException $e) {
			echo $requete . "<br>" . $e->getMessage();
		}
	}
}
include_once('functions/model.php');
// Ajouter des Modeles Specifique ici
function showadd($FileName, $SurTitle = "",$reload=false)
{
	echo '<span fn="' . $FileName . '"' . (($SurTitle != "") ? (' surtitle="' . $SurTitle) . '"' : "") . (($reload) ? ' reload="true"' : "") . ' class="tools fn">';
	echo '<span><i id="modal-add" class="fas fa-user-plus add-btn"></i></span>';
	echo '</span>';
}

//--------------
//********************************REQUETE POST********************************
if (isset($_GET['read']) && $_GET['read'] == '1') {
	if (isset($_GET['id'])) {
		$obj->getByID($_GET['id']);
	}
}
if (isset($_GET['readall']) && $_GET['readall'] == '1') {
	if (isset($_GET['seedT'])) {
		$param = explode("/", $_GET['seedT']);
		if ($param[3] == "1") {
			showOnlyList($obj->getAll(), $obj->FileName,  $param[0], $param[1], $param[2]);
		}
	}else{
		showOnlyList($obj->getAll(), $obj->FileName,  $obj->IdDefaultValuesShow);
	}

}
if (isset($_GET['add']) && $_GET['add'] == '1') {
	if (isset($_GET['seedT'])) {
		$param = explode("/", $_GET['seedT']);
		var_dump($param);
		$queryStringSql = urldecode(preg_replace("/add=1(&)?/", "", preg_replace("/seedT=(\/|,|\w)+(&)?/", "", $_SERVER['QUERY_STRING'])));
		$result = $obj->create($queryStringSql);
		if ($result != "") {
			if ($param[3] == "0") {
				showOnlyList($obj->getAll(), $obj->FileName,  $param[0], $param[1], $param[2]);
			} else {
				showOnlyList($obj->getAll(), $obj->FileName,  $obj->IdDefaultValuesShow);
			}
		}
	}
}
if (isset($_GET['update']) && $_GET['update'] == '1') {
	if (isset($_GET['id'])) {
		$sqlReq = preg_replace("/id=\d*(&)?/", "", preg_replace("/update=1(&)?/", "", $_SERVER['QUERY_STRING']));
		$sqlReq = urldecode(str_replace("&", "',", str_replace("=", "='", $sqlReq)) . "'");
		$obj->update($_GET['id'], $sqlReq);
	}
}
if (isset($_POST['delete']) && $_POST['delete'] == '1') {
	if (isset($_POST['id'])) $obj->delete($_POST['id']);
}
if (isset($_POST['search']) && $_POST['search'] == '1') {
	if (isset($_POST['word']) && isset($_POST['seedT'])) {
		$param = explode("/", $_POST['seedT']);
		if ($param[3] == "1") {
			showOnlyList($obj->search($_POST['word']), $obj->FileName,  $param[0], $param[1], $param[2]);
		}
	}
}
if(isset($_POST['getselect']) && $_POST['getselect'] == '1'){
	$result = $obj->getSelect("ID,Nom,Prenom");
	$row = mysqli_fetch_all($result, MYSQLI_NUM);
	echo json_encode($row);
}