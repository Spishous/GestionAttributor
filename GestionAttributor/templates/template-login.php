<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ?></title>
	<link rel="stylesheet" href="<?= DOMAINE ?>/assets/css/all.min.css">
	<link rel="stylesheet" href="<?= DOMAINE ?>/assets/css/login.css">
	<link rel="icon" type="image/png" href="<?= DOMAINE ?>/assets/imgs/icone.png">
	<script src="<?= DOMAINE ?>/assets/lib/jquery.js"></script>
	<script src="<?= DOMAINE ?>/assets/lib/popper.min.js"></script>
	<script src="<?= DOMAINE ?>/assets/lib/bootstrap.min.js"></script>
	<script src="<?= DOMAINE ?>/assets/js/script.js"></script>
</head>
<body>
	<header>

	</header>
	<div class="contenu">
        <?php @include('./pages/'.$page.'.php')?>
	</div>
	<footer>

	</footer>
</body>
</html>