<?php
//file: view/layouts/default.php

$view = ViewManager::getInstance();

$currentuser = $view->getVariable("currentusername");

?><!DOCTYPE html>
<html>
<head>
	<title><?= $view->getVariable("title", "no title") ?></title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<!-- enable ji18n() javascript function to translate inside your scripts -->
	<script src="index.php?controller=language&amp;action=i18njs">
	</script>
	<?= $view->getFragment("css") ?>
	<?= $view->getFragment("javascript") ?>
</head>
<body>
	<!-- header -->
	<header>
		<h1><?= sprintf(i18n("Expense account")) ?></h1>
		<nav id="menu" style="background-color:grey">
			<ul>
				<li><a href="index.php?controller=expenses&amp;action=index"><?= sprintf(i18n("Expenses")) ?></a></li>
				

				<?php if (isset($currentuser)): ?>
				
				<li><?= sprintf(i18n("Hello %s"), $currentuser) ?>
						<a 	href="index.php?controller=users&amp;action=logout">(<?= sprintf(i18n("Log out")) ?>)</a>
				</li>

				<li>
				<form method="POST" action="index.php?controller=users&amp;action=delete"
				id="delete_user_<?= $currentuser?>"
				style="display: inline"
				>

					<input type="hidden" name="username" value="<?= $currentuser?>">
					
					<a href="#" onclick="if (confirm('<?= i18n("are you sure?")?>')) {
					document.getElementById('delete_user_<?= $currentuser?>').submit()
				}"> <?= i18n("Click here to delete your user") ?></a>

				</form>
				</li>


				<?php else: ?>
					<li><a href="index.php?controller=users&amp;action=login"><?= i18n("Login") ?></a></li>
				<?php endif ?>
			</ul>
		</nav>
	</header>

	<main>
		<div id="flash">
			<?= $view->popFlash() ?>
		</div>

		<?= $view->getFragment(ViewManager::DEFAULT_FRAGMENT) ?>
	</main>

	<footer>
		<?php
		include(__DIR__."/language_select_element.php");
		?>
	</footer>

</body>
</html>
