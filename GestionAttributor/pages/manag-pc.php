<?php @include('./crud/pc.php');
$pc = new PC();?>
<div class="container">
    <h1>Manager PC</h1>
    <div class="data-list">
        <?php showTableTools($pc->FileName);?>
        <?php pcshowTable($pc->getAll(),$pc->FileName,"Nom du post","1,2,3d");?>
    </div>
</div>