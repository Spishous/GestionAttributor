<?php include_once('search-list.php');
include_once('./crud/user.php');
$user = new USER(); ?>
<div class="attribution box-style">
    <h2 class="text-center"><u>Attribution d'un PC</u></h2>
    <br>
    <h3 class="text-left">Créneau horaire</h3>
    <div class="d-flex">
        <?php require('./assets/functions/date-intervale.php'); ?>
    </div>
    <hr>
    <div class="selector-zone">
        <div>
            <h3 class="text-left">Attribution</h3>
        </div>
        <div class="d-flex justify-content-around flex-column flex-md-row align-items-center" style="max-width: 700px;margin: auto;">
            <span class="d-flex p-20 flex-column text-center" id="choose-user">

                <?php
                $row = mysqli_fetch_all($user->getSelect("ID,Nom,Prenom"), MYSQLI_NUM);
                $a = "";
                $b = "";
                for ($i = 0; $i < count($row); $i++) {
                    ($a != "") ? $a .= "," : "";
                    ($b != "") ? $b .= "," : "";
                    $a .= $row[$i][0];
                    $b .= $row[$i][1] . " " . $row[$i][2];
                } ?>
                <span class="d-flex">
                    <div class="d-flex flex-column">
                        <h4>Utilisateur</h4>
                        <span><?= boxselector($a, $b); ?>
                            <?= showadd($user->FileName, "un utilisateur", true);
                            showTable($user->getSelect("*", "Role=0 LIMIT 1"), $user->FileName, "", "3h,4h,5h", false, false, false); ?></span>
                        <span class="min-text" id="count-cu"><?= count($row) ?> utilisateur</span>
                    </div>

                </span>

            </span>
            <span class="d-flex p-20 flex-column text-center" id="choose-pc">
                <span class="d-flex">
                    <div class="d-flex flex-column">
                        <h4>PC</h4>
                        <span><?= boxselector("", ""); ?>
                        </span>
                        <span class="min-text" id="count-cp">.. PC</span>
                    </div>

                </span>

            </span>
        </div>
    </div>
    <span class="already-use" hidden>Un ordinateur est déjà utiliser par l'utilisateur dans cette période</span>
    <span class="btn btn-success">Valider</span>
</div>