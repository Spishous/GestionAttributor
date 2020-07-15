<?php
//showTable----------
//(IdResultShow) tu peux choisir les elements sql a afficher par leur id et ajouter 'n' pour rendre non editable
// ajouter 'h' pour cacher
//Exemple: "1,3,4n,5h"
//(TitleColumn) choix des titres des colonnes dans l'ordre, par default, le titre definie des les tables
//Exemple: "Col1,Col2,Col3"
function showTable($result_sql = "", $FileName, $TitleColumn = "", $IdResultShow = "", $editDelete = true, $selector = false, $showDATA = true,$showTITLE = true)
{

    $listTitle = explode(",", $TitleColumn);
    $listId = explode(",", $IdResultShow);
    $numcol = sizeof($listId);
    $row = mysqli_fetch_all($result_sql, MYSQLI_BOTH);
    if (count($row) != 0) {
        if ($IdResultShow == "") {
            $numcol = sizeof($row[0]) / 2;
            for ($a = 0; $a < $numcol; $a++) {
                ($IdResultShow != "") ? $IdResultShow .= "," : "";
                $IdResultShow .= $a;
            }
            $listId = explode(",", $IdResultShow);
        }
        $seedParam = "";
        $seedParam .= $IdResultShow."/";
        ($editDelete) ? $seedParam .= "1/" : $seedParam .= "0/";
        ($selector) ? $seedParam .= "1/" : $seedParam .= "0/";
        ($showDATA) ? $seedParam .= "1" : $seedParam .= "0";
        //if(is_array($result_sql)) echo "aze";
        //(sizeof($listTitle)>sizeof($listId)) ? $numcol=sizeof($listId) : $numcol=sizeof($listTitle);
        echo '<table class="fn table table-striped table-hover" fn="' . $FileName . '" seed="' . $seedParam . '" ' . ((!$showDATA) ? 'style="display:none"' : "") . '>
            <thead '.((!$showTITLE) ? "hidden=true" : "").'>
            <tr>';
        if ($selector) echo '<th class="tbl-select">
                    <span class="custom-checkbox">
                        <input type="checkbox" id="selectAll">
                        <label for="selectAll"></label>
                    </span></th>';
        for ($j = 0; $j < $numcol; $j++) {
            $title = (!array_key_exists($j, $listTitle) || $listTitle[$j] == "") ? array_keys($row[0])[intval($listId[$j]) * 2 + 1] : $listTitle[$j];
            echo '<th data-name=' . array_keys($row[0])[intval($listId[$j]) * 2 + 1] . ' ' . ((strpos($listId[$j], 'n')) ? 'no-edit=true ' : '') . ((strpos($listId[$j], 'h')) ? 'hidden=true ' : '') . ((strpos($listId[$j], 'd')) ? 'no-set=true ' : '') . '>' . $title . '</th>';
        }

        if ($editDelete) echo '<th class="tbl-action">action</th>';
        echo '</thead>';
        if ($showDATA) {
            echo '<tbody>';
            echo showOnlyList($row, $FileName, $IdResultShow, $editDelete, $selector);
            echo '</tbody>';
        }
        echo "</table>";
    }
}
function showOnlyList($result_sql = "", $FileName, $IdResultShow = "1,0,3", $editDelete = true, $selector = true)
{
    $listId = explode(",", $IdResultShow);
    $numcol = sizeof($listId);
    $row = $result_sql;
    if (!is_array($result_sql)) $row = mysqli_fetch_all($result_sql, MYSQLI_BOTH);
    if (count($row) != 0) {
        for ($i = 0; $i < sizeof($row); $i++) {
            echo '<tr data-id="' . $row[$i][0] . '">';
            if ($selector) echo '<td class="tbl-select">
        <span class="custom-checkbox">
            <input type="checkbox" id="selectAll">
            <label for="selectAll"></label>
        </span></td>';
            for ($j = 0; $j < $numcol; $j++) {
                if (isDate($row[$i][intval($listId[$j])])) {
                    echo "<td isdate='true' data-name=" . array_keys($row[$i])[intval($listId[$j]) * 2 + 1] . " " . ((strpos($listId[$j], 'n')) ? 'no-edit=true ' : '') . ((strpos($listId[$j], 'h')) ? 'hidden=true ' : '') . ((strpos($listId[$j], 'd')) ? 'no-set=true ' : '') . ">" . $row[$i][intval($listId[$j])] . "</td>";
                } else {
                    echo "<td data-name=" . array_keys($row[0])[intval($listId[$j]) * 2 + 1] . " " . ((strpos($listId[$j], 'n')) ? 'no-edit=true ' : '') . ((strpos($listId[$j], 'h')) ? 'hidden=true ' : '') . ((strpos($listId[$j], 'd')) ? 'no-set=true ' : '') . ">" . $row[$i][intval($listId[$j])] . "</td>";
                }
            }
            if ($editDelete) {
                echo '<td class="tbl-action"><a href="javascript:(0)" id="data-edit" class="edit fn" fn="' . $FileName . '"><i class="far fa-edit"></i></a>
            <a href="javascript:(0)" id="data-delete-popup" class="delete fn" fn="' . $FileName . '"><i class="fas fa-trash"></i></a></td>';
            };
            echo '</tr>';
        }
    }
}
function showTableTools($FileName, $SurTitle = "", $Search = true, $SearchBtn = true, $Filter = true, $AddBtn = true)
{
    echo '<div fn="' . $FileName . '"' . (($SurTitle != "") ? (' surtitle="' . $SurTitle) . '"' : "") . ' class="tools fn">';
    if ($Search) {
        echo '<span class="tool-search"><input type="text" name="search" placeholder="Recherche..">';
        if ($SearchBtn) echo '<i id="data-search" class="fas fa-search"></i>';
        echo '</span>';
    }
    if ($AddBtn) echo '<span class="tool-add"><i id="modal-add" class="fas fa-plus add-btn"></i></span>';
    echo '</div>';
}
function showNumRow($result_sql = "")
{
    $row = $result_sql;
    if (!is_array($result_sql)) $row = mysqli_fetch_all($result_sql, MYSQLI_NUM);
    echo sizeof($row);
}
function isDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
