<?php $idListSelector;?>

<?php function boxselector($value = "", $text = "Yogesh singh,QSDFdqsf,Rztsfg")
{
    global $idListSelector;
    ($idListSelector >= 0) ? $idListSelector += 1 : $idListSelector = 0; $listtext=explode(",",$text);?>
    <select id='Selector<?= $idListSelector ?>' class="selector-search" style='width: 200px'>
        <?php
        if ($value == "") {
            for ($i = 0; $i < count($listtext); $i++) {
                echo '<option value="' . $i . '">'.$listtext[$i].'</option>';
            }
        } else {
            $listvalue=explode(",",$value);
            for ($i = 0; $i < count($listvalue); $i++) {
                echo '<option value="' . $listvalue[$i] . '">'.$listtext[$i].'</option>';
            }
        }
        ?>
    </select>
<?php } ?>
<script>
    $(document).ready(function() {
    $('#Selector1').select2();
    $('#Selector2').select2();
});
</script>