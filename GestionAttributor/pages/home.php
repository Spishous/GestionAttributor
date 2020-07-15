<?php include('./crud/user.php');
$user = new USER();
include('./crud/pc.php');
$pc = new PC();
include('./crud/planning.php');?>
<div class="container main">
    <h1 id="currenttime">10:40</h1>
    <div class="main-info">
        <section>
        <a href="<?=DOMAINE?>/manage-user"><h1>Utilisateur</h1></a>
            <div class="zone-info">
                <div>
                    <span>Total: <?=showNumRow($user->getSelect("ID"))?> <i class="fas fa-user"></i></span>
                    <span class="actif">Actif: <?= nbrCurrentUser()?> <i class="fas fa-user"></i></span>
                </div>
            </div>
            <?php
            showadd($user->FileName,"un utilisateur");
            showTable($user->getAll(),$user->FileName,"", "3h,4h,5h",false,false,false);
            ?>
        </section>
        <section>
            <a href="<?=DOMAINE?>/manage-pc"><h1>PC</h1></a>
            <div class="zone-info">
                <div>
                    <span>Total: <?=showNumRow($pc->getAll())?> <i class="fas fa-desktop"></i></span>
                    <span class="actif">Actif: <?= nbrCurrentPc()?> <i class="fas fa-desktop"></i></span>
                </div>
                <div><span class="warn">Hors Service: <?=showNumRow($pc->getSelect("ID","Etat=0"))?> <i class="fas fa-desktop"></i></span></div>
            </div>
            <?php
                showTableTools($pc->FileName,"un PC",false,false,false);
                showTable($pc->getAll(),$pc->FileName,",,Date d'ajout", "1h,2h,3dh",false,false,false);
            ?>
        </section>
    </div>
    <div class="main-tools">
        <span>
        </span>
    </div>
</div>