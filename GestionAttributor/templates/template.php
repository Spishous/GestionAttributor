<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ?></title>
	<link rel="stylesheet" href="<?= DOMAINE ?>/assets/css/all.min.css">
	<link rel="stylesheet" href="<?= DOMAINE ?>/assets/css/style.css">
	<link rel="stylesheet" href="<?= DOMAINE ?>/assets/css/select2.min.css">
	<link rel="icon" type="image/png" href="<?= DOMAINE ?>/assets/imgs/icone.png">
	<script src="<?= DOMAINE ?>/assets/lib/jquery.js"></script>
	<script src="<?= DOMAINE ?>/assets/lib/popper.min.js"></script>
	<script src="<?= DOMAINE ?>/assets/lib/bootstrap.min.js"></script>
	<script src="<?= DOMAINE ?>/assets/js/script.js"></script>
	<script src="<?= DOMAINE ?>/assets/lib/select2.min.js"></script>
	<script src="<?= DOMAINE ?>/crud/functions/ajax-crud.js"></script>
	
</head>

<body>
	<header class="px-4">
		<a class="title" href="<?= DOMAINE ?>/">
		<img src="<?= DOMAINE ?>/assets/imgs/icone2.png" height="35" width="35">
			<h2>GestionAttributor</h2>
		</a>
		<div class="ml-auto"><a class="parametre" href="#">Paramètre</a><a class="disconnect" href="?logout=true">Deconnexion</a></div>
	</header>
	<div class="contenu">
		<nav class="menu-side">
			<a href="<?= DOMAINE ?>/manage-user" <?= ($currenturl == "/manage-user") ? 'class="active"' : '' ?>>
				<i class="fas fa-users"></i>
			</a>
			<a href="<?= DOMAINE ?>/manage-pc" <?= ($currenturl == "/manage-pc") ? 'class="active"' : '' ?>>
				<i class="fas fa-desktop"></i>
			</a>
			<a href="<?= DOMAINE ?>/planning" <?= ($currenturl == "/planning") ? 'class="active"' : '' ?>>
				<i class="far fa-calendar-alt"></i>
			</a>
			<a href="<?= DOMAINE ?>/help" <?= ($currenturl == "/help") ? 'class="active"' : '' ?>>
				<i class="far fa-question-circle"></i>
			</a>
		</nav>
		<div class="main-container">
			<?php include_once('./crud/functions/popup.php');@include('./pages/' . $page . '.php'); ?>
		</div>
	</div>
	<footer>

	</footer>
</body>

</html>