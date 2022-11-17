<?php
//file: view/posts/add.php

//getExpense_type, getExpense_date, getExpense_quantity, getExpense_description, getExpense_file, getOwner

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$expense = $view->getVariable("expenses");
$errors = $view->getVariable("errors");

$view->setVariable("title", "Añadir Gasto");

?>

    <div class="grid-item-titulo">
        <h1><?= i18n("Create expense")?></h1>
    </div>
    <div class="grid-item-addExpenses" class="cajaTemp">
    <form action="index.php?controller=expenses&amp;action=add" method="POST">
            
            <label for="expense_type"><?= i18n("Type") ?>:</label>
            <select name="expense_type">
                <option value="combustible"><?= i18n("Fuel") ?></option>
                <option value="alimentacion"><?= i18n("Food") ?></option>
                <option value="comunicaciones"><?= i18n("Comunications") ?></option>
                <option value="suministros"><?= i18n("Supplies") ?></option>
                <option value="ocio"><?= i18n("Fun") ?></option>
            </select><br>

            <label for="expense_date"><?= i18n("Date") ?>: </label>
            <input type="date" name="expense_date"
	        value="<?= $expense->getExpense_date() ?>"><br>
            <?= isset($errors["expense_date"])?i18n($errors["expense_date"]):"" ?><br>

            <label for="expense_quantity"><?= i18n("Quantity") ?>: </label>
            <input type="number" name="expense_quantity"
	        value="<?= $expense->getExpense_quantity() ?>"><br>
	        <?= isset($errors["expense_quantity"])?i18n($errors["expense_quantity"]):"" ?><br>

            <label for="expense_description"><?= i18n("Description") ?>: </label>
            <textarea name="expense_description" placeholder="describe brevemente en menos de 300 carácteres" maxlength="300" rows="4" cols="50">
            <?=htmlentities($expense->getExpense_description()) ?>
            </textarea>
            <?= isset($errors["expense_description"])?i18n($errors["expense_description"]):"" ?><br>

            <label for="expense_file"><?= i18n("File") ?>: </label>
            <input type="file" name="expense_file" value="<?= $expense->getExpense_file() ?>"><br> 
            <?= isset($errors["expense_file"])?i18n($errors["expense_file"]):"" ?><br>
            <div class="grid-item-addbttn">
            <input class="addbttn" type="submit" name="submit" value="Añadir Gasto" ></input>

            </div>
        </form>

    </div>

<?php $view->moveToFragment("css");?>
<link rel="stylesheet" href="css/add.css" type="text/css">
<link rel="stylesheet" href="css/estilos.css"> 
<?php $view->moveToDefaultFragment(); ?>
