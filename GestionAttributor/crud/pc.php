<?php
error_reporting(-1);
@include_once('../config.php');
$obj = new PC();

class PC
{
	public $TABLE = "pc";
	public $IdDefaultValuesShow = "1,2,3d";
	public $FileName;

	public function __construct()
	{
		$this->FileName = substr(basename(__FILE__), 0, -4);
	}
	// récupere tous les users
	function getAll($where = "1",$order="ID DESC")
	{
		$ByOrder="";
		($order=="")? $ByOrder=" ORDER BY ".$order : "";
		global $bdd;
		$requete = 'SELECT * from ' . $this->TABLE . ' WHERE ' . $where . $ByOrder;
		$rows = $bdd->query($requete);
		return $rows;
	}
	// récupere tous les users
	function getSelect($select="*",$where="1",$order="ID DESC")
	{
		$ByOrder="";
		($order=="")? $ByOrder=" ORDER BY ".$order : "";
		global $bdd;
		$requete = 'SELECT '.$select.' from ' . $this->TABLE . ' WHERE ' . $where . $ByOrder;
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
		$requete = 'SELECT * from ' . $this->TABLE . ' WHERE (Nom LIKE "%' . $search . '%")';
		$rows = $bdd->query($requete);
		return $rows;
	}

	// creer un user
	function create($queryStringSql = "Nom=aze&Prenom=qsd")
	{
		$datalist = explode("&", urldecode($queryStringSql));
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
function pcshowTable($result_sql = "", $FileName, $TitleColumn = "", $listIdResultShow = "", $editDelete = true,$selector=true,$showDATA=true)
{
    $listTitle = explode(",", $TitleColumn);
    $listId = explode(",", $listIdResultShow);
    $numcol = sizeof($listId);
    $row = mysqli_fetch_all($result_sql, MYSQLI_BOTH);

    if($listIdResultShow == ""){
        $numcol=sizeof($row[0])/2;
        for($a=0;$a<$numcol;$a++){
            ($listIdResultShow!="")?$listIdResultShow.=",":"";
            $listIdResultShow.=$a;
        }
        $listId = explode(",", $listIdResultShow);
    }
    $seedParam="";
    ($editDelete) ? $seedParam.="1/" : $seedParam.="0/";
    ($selector) ? $seedParam.="1/" : $seedParam.="0/";
    ($showDATA) ? $seedParam.="1/" : $seedParam.="0/";
    $seedParam.=$listIdResultShow;
    //if(is_array($result_sql)) echo "aze";
    //(sizeof($listTitle)>sizeof($listId)) ? $numcol=sizeof($listId) : $numcol=sizeof($listTitle);
    echo '<table class="fn table table-striped table-hover" fn="' . $FileName . '" seed='.$seedParam.'>
            <thead>
            <tr>';
    if($selector) echo '<th class="tbl-select">
                    <span class="custom-checkbox">
                        <input type="checkbox" id="selectAll">
                        <label for="selectAll"></label>
                    </span></th>';
    for ($j = 0; $j < $numcol; $j++) {
        $title=(!array_key_exists($j,$listTitle)||$listTitle[$j]=="")? array_keys($row[0])[intval($listId[$j])*2+1]: $listTitle[$j];
        echo '<th data-name='.$title.' '. ((strpos($listId[$j], 'n')) ? 'no-edit=true ' : '') . ((strpos($listId[$j], 'h')) ? 'hidden=true ' : '') .((strpos($listId[$j], 'd')) ? 'no-set=true ' : '') .'>' . $title . '</th>';
    }

    if ($editDelete) echo '<th class="tbl-action">action</th>';
    echo '</thead>';
    if($showDATA){
		echo '<tbody>';
		echo pcshowOnlyList($row, $FileName, $listIdResultShow, $editDelete,$selector);
		echo '</tbody>';

    } 
    echo "</table>";
}
function pcshowOnlyList($result_sql = "", $FileName, $listIdResultShow = "1,0,3", $editDelete = true,$selector=true)
{
    $listId = explode(",", $listIdResultShow);
    $numcol = sizeof($listId);
    $row = $result_sql;
    if(!is_array($result_sql)) $row = mysqli_fetch_all($result_sql, MYSQLI_BOTH);
    for ($i = 0; $i < sizeof($row); $i++) {
        echo '<tr data-id="' . $row[$i][0] . '" class="etat'.$row[$i][2].'">';
        if($selector) echo '<td class="tbl-select">
        <span class="custom-checkbox">
            <input type="checkbox" id="selectAll">
            <label for="selectAll"></label>
        </span></td>';
        for ($j = 0; $j < $numcol; $j++) {
            $isdate="";
            if(isDate($row[$i][intval($listId[$j])])){
                echo "<td isdate='true' data-name=".array_keys($row[$i])[intval($listId[$j])*2+1]." " . ((strpos($listId[$j], 'n')) ? 'no-edit=true ' : '') . ((strpos($listId[$j], 'h')) ? 'hidden=true ' : '') .((strpos($listId[$j], 'd')) ? 'no-set=true ' : '') .">" . $row[$i][intval($listId[$j])] . "</td>";
            }else{
                echo "<td data-name=".array_keys($row[$i])[intval($listId[$j])*2+1]." " . ((strpos($listId[$j], 'n')) ? 'no-edit=true ' : '') . ((strpos($listId[$j], 'h')) ? 'hidden=true ' : '') .((strpos($listId[$j], 'd')) ? 'no-set=true ' : '') .">" . $row[$i][intval($listId[$j])] . "</td>";
            }
         }
        if ($editDelete) {
            echo '<td class="tbl-action"><a href="javascript:(0)" id="data-edit" class="edit fn" fn="' . $FileName . '"><i class="far fa-edit"></i></a>
            <a href="javascript:(0)" id="data-delete-popup" class="delete fn" fn="' . $FileName . '"><i class="fas fa-trash"></i></a></td>';
        };
        echo '</tr>';
    }
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
if (isset($_POST['getselect']) && $_POST['getselect'] == '1') {
	$select="ID,Nom";
	$where="Etat=1";
	$result=$obj->getSelect($select,$where);
	$row = mysqli_fetch_all($result, MYSQLI_NUM);
	echo json_encode($row);
	/*for($i=0;$i<count($row);$i++){
		echo '<option value="'.$row[$i][0].'">'.$row[$i][1].'</option>';
	}*/
}
if (isset($_GET['add']) && $_GET['add'] == '1') {
	if (isset($_GET['seedT'])) {
		$param = explode("/", $_GET['seedT']);
		var_dump($param);
		$queryStringSql = urldecode(preg_replace("/add=1(&)?/", "", preg_replace("/seedT=(\/|,|\w)+(&)?/", "", $_SERVER['QUERY_STRING'])));
		$result = $obj->create($queryStringSql);
		if ($result != "") {
			if ($param[3] == "0") {
				pcshowOnlyList($obj->getAll(), $obj->FileName,  $param[0], $param[1], $param[2]);
			} else {
				pcshowOnlyList($obj->getAll(), $obj->FileName,  $obj->IdDefaultValuesShow);
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