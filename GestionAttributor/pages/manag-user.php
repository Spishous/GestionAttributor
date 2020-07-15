<?php @include('./crud/user.php');
$user = new USER();?>
<div class="container">
    <h1>Manager Utilisateur</h1>
    <div class="data-list">
        <?php showTableTools($user->FileName);?>
        <?php showTable($user->getAll("Role=0"),$user->FileName,",Prénom,,Inscription", "3,4,5,6n",true,true);?>
    </div>
</div>