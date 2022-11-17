<?php
// file: view/layouts/welcome.php

$view = ViewManager::getInstance();

?>
<!DOCTYPE html>
<html>
<head>
	<title><?= $view->getVariable("title", "no title") ?></title>
	<meta charset="utf-8">
	<?= $view->getFragment("css") ?>
	<?= $view->getFragment("javascript") ?>
</head>
<body>
		<!-- flash message -->
		<div id="flash">
			<?= $view->popFlash() ?>
		</div>
	
		<?= $view->getFragment(ViewManager::DEFAULT_FRAGMENT) ?>


	<!-- Anadir lo de languagle select element en el login -->

</body>
</html>
