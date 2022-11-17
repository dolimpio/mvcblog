<?php
//file: view/expenses/index.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$expenses = $view->getVariable("expenses");
$currentuser = $view->getVariable("currentusername");
//getExpense_type, getExpense_date, getExpense_quantity, getExpense_description, getExpense_file, getOwner
$view->setVariable("title", "Expenses");

?><h1><?=i18n("Expenses")?></h1>


		<div class="grid-item-cajainfo1">
        <div class="boxcontent">
            <span class="tituloSpan"><?= i18n("Total Expenses") ?></span>
            <br>
            <span class="interiorelem">12,345.02</span>
        </div>
    </div>

    <div class="grid-item-cajainfo2">
        <div class="boxcontent">
            <span class="tituloSpan"><?= i18n("Number of Operations") ?></span>
            <br>
            <span class="interiorelem">3,000</span>
        </div>
    </div>

    <div class="grid-item-cajainfo3">
        <div class="boxcontent">
            <span class="tituloSpan"><?= i18n("Types of Expenses") ?></span>
            <br>
            <span class="interiorelem">5</span>
            
        </div> 
    </div>

    <div class="grid-item-table" class="cajaTemp">
    <table border="1">
        <thead>
            <caption>Tabla de Gastos</caption>
            <tr class="table-header">
								<th><?= i18n("Type")?></th>
								<th><?= i18n("Date")?></th>
								<th><?= i18n("Quantity")?></th>
								</th><th><?= i18n("Description")?></th>
								<th><?= i18n("File")?></th>
								<th><?= i18n("Options")?></th>
            </tr>
        </thead>
        <tbody>
				<?php foreach ($expenses as $expense): ?>
		<?php if (isset($currentuser) && $currentuser == $expense->getOwner()->getUsername()): ?>
		<tr>
			<td>
				<a href="index.php?controller=expenses&amp;action=view&amp;id=<?= $expense->getId() ?>"><?= htmlentities($expense->getExpense_type()) ?></a>
			</td>
			<td>
				<?= $expense->getExpense_date() ?>
			</td>
			<td>
				<?= $expense->getExpense_quantity() ?>
			</td>
			<td>
				<?= $expense->getExpense_description() ?>
			</td>
			<td>
				<?= $expense->getExpense_file() ?>
			</td>
			<td>
				<?php
				//show actions ONLY for the author of the post (if logged)
				if (isset($currentuser) && $currentuser == $expense->getOwner()->getUsername()): ?>
				<?php
				// 'Delete Button': show it as a link, but do POST in order to preserve
				// the good semantic of HTTP
				?>
				
				<form
				method="POST"
				action="index.php?controller=expenses&amp;action=delete"
				id="delete_expense_<?= $expense->getId(); ?>"
				style="display: inline"
				>

				<input type="hidden" name="id" value="<?= $expense->getId() ?>">

				<a href="#" onclick="if (confirm('<?= i18n("are you sure?") ?>') ) {
					document.getElementById('delete_expense_<?= $expense->getId() ?>').submit()}">
				<?= i18n("Delete") ?></a>

				</form>

			&nbsp;

			<?php
			// 'Edit Button'
			?>
			<a href="index.php?controller=expenses&amp;action=edit&amp;id=<?= $expense->getId() ?>"><?= i18n("Edit") ?></a>

			<?php endif; ?>
			<?php endif; ?>

			</td>
			</tr>
			<?php endforeach; ?>
			</table>
          
    </div>
    <div class="grid-item-date">
        <button class="botton"><i class="fa fa-calendar"></i> <a href="index.php?controller=expenses&amp;action=csv"><?= i18n("Download in csv") ?></a></button>
    </div>
    <div class="grid-item-type">
        <button class="botton" ><i class="fa fa-list"></i><?= i18n("Filter by date") ?></button>
    </div>  
    <div class="grid-item-addExpenses">
				<?php if (isset($currentuser)): ?>
        <button class="botton" ><a href="index.php?controller=expenses&amp;action=add"><?= i18n("Create post") ?></a></button>
				<?php endif; ?>	
		</div> 

<?php $view->moveToFragment("css");?>
<link rel="stylesheet" href="css/crud.css" type="text/css">
<link rel="stylesheet" href="css/estilos.css"> 
<?php $view->moveToDefaultFragment(); ?>