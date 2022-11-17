<?php
//file: view/posts/view.php
require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$expense = $view->getVariable("expense");
$currentuser = $view->getVariable("currentusername");
$errors = $view->getVariable("errors");

$view->setVariable("title", "View Post");

?><h1><?= i18n("Expense").": ".htmlentities($expense->getId()) ?></h1>
<em><?= sprintf(i18n("by %s"),$expense->getOwner()->getUsername()) ?></em>
<p>
	<?= htmlentities($expense->getContent()) ?>
	<?php print_r($_POST); ?>

</p>
