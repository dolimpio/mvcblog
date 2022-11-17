<?php
//file: view/posts/edit.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$expense = $view->getVariable("expense");
$errors = $view->getVariable("errors");

$view->setVariable("title", "Edit Post");

?>

    <div class="grid-item-titulo">
		<h1><?= i18n("Modify expense") ?></h1>
    </div>
    <div class="grid-item-addExpenses" class="cajaTemp">
    <form action="index.php?controller=expenses&amp;action=edit" method="POST">
            <label for="expense_type"><?= i18n("Type") ?>: </label>
            <select name="expense_type">
								<option value="combustible"><?= i18n("Fuel") ?></option>
                <option value="alimentacion"><?= i18n("Food") ?></option>
                <option value="comunicaciones"><?= i18n("Comunications") ?></option>
                <option value="suministros"><?= i18n("Supplies") ?></option>
                <option value="ocio"><?= i18n("Fun") ?></option>
            </select><br>

            <label for="expense_date"><?= i18n("Date") ?>: </label>
            <input type="date" name="expense_date"
						value="<?= isset($_POST["expense_date"])?$_POST["expense_date"]:$expense->getExpense_date() ?>">
						<?= isset($errors["expense_date"])?i18n($errors["expense_date"]):"" ?><br>

            <label for="expense_quantity"><?= i18n("Quantity") ?>: </label>
						<input type="number" name="expense_quantity"
						value="<?= isset($_POST["expense_quantity"])?$_POST["expense_quantity"]:$expense->getExpense_quantity() ?>">
						<?= isset($errors["expense_quantity"])?i18n($errors["expense_quantity"]):"" ?><br>

            <label for="expense_description"><?= i18n("Description") ?>: </label>
            <textarea name="expense_description" rows="4" cols="50"><?=
						isset($_POST["expense_description"])?
						htmlentities($_POST["expense_description"]):
						htmlentities($expense->getExpense_description())
						?></textarea>
						<?= isset($errors["expense_description"])?i18n($errors["expense_description"]):"" ?><br>

            <label for="expense_file"><?= i18n("File") ?>: </label>
						<input type="file" name="expense_file"
						value="<?= isset($_POST["expense_file"])?$_POST["expense_file"]:$expense->getExpense_file() ?>">
						<?= isset($errors["expense_file"])?i18n($errors["expense_file"]):"" ?><br>
						<input type="hidden" name="id" value="<?= $expense->getId() ?>">
						<div class="grid-item-addbttn">
            <input class="addbttn" type="submit" name="submit" value="<?= i18n("Modify post") ?>" ></input>
            </div>
        </form>

    </div>

<?php $view->moveToFragment("css");?>
<link rel="stylesheet" href="css/add.css" type="text/css">
<link rel="stylesheet" href="css/estilos.css"> 
<?php $view->moveToDefaultFragment(); ?>
