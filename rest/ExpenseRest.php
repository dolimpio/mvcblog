<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");

require_once(__DIR__."/../model/Expenses.php");
require_once(__DIR__."/../model/ExpensesMapper.php");

require_once(__DIR__."/BaseRest.php");

/**
* Class ExpenseRest
*
* It contains operations for creating, retrieving, updating, deleting and
* listing expenses.
*
* Methods gives responses following Restful standards. Methods of this class
* are intended to be mapped as callbacks using the URIDispatcher class.
*
*/
class ExpenseRest extends BaseRest {
	private $expensesMapper;

	public function __construct() {
		parent::__construct();

		$this->expensesMapper = new ExpensesMapper();
	}

	public function readExpense($expenseId) {
		// find the Expense object in the database
		$expense = $this->expensesMapper->findById($expenseId);
		if ($expense == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Expense with id ".$expenseId." not found");
			return;
		}

		$expense_array = array(
			"id" => $expense->getId(),
			"expense_type" => $expense->getExpense_type(),
			"expense_date" => $expense->getExpense_date(),
			"expense_quantity" => $expense->getExpense_quantity(),
			// por ahora queda asi, pero igual deberiamos comprobar que esta seteados??
			"expense_description" => $expense->getExpense_description(),
			"expense_file" => $expense->getExpense_file(),
			"expense_owner" => $expense->getOwner()->getUsername()
		);
	
		
		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($expense_array));
	}

	public function getExpenses() {
		$currentUser = parent::authenticateUser();
		$username = $currentUser->getUsername();
		$expenses = $this->expensesMapper->findByUsername($username);

		// json_encode Expenses objects.
		// since Expenses objects have private fields, the PHP json_encode will not
		// encode them, so we will create an intermediate array using getters and
		// encode it finally
		$expenses_array = array();
		foreach($expenses as $expense) {
			array_push($expenses_array, array(
				"id" => $expense->getId(),
				"expense_type" => $expense->getExpense_type(),
				"expense_date" => $expense->getExpense_date(),
				"expense_quantity" => $expense->getExpense_quantity(),
				"expense_description" => $expense->getExpense_description(),
				"expense_file" => $expense->getExpense_file(),
				"expense_owner" => $expense->getOwner()->getUsername()
			));
		}

		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($expenses_array));
	}

	public function getPieChart($date) {
		$currentUser = parent::authenticateUser();
		$username = $currentUser->getUsername();
		$filter_data = explode(',', $date);
		$start_date = $filter_data[0];
		$end_date = $filter_data[1];
		$expenses = $this->expensesMapper->findByUsernameDate($username, $start_date, $end_date);
		$pie_data_array = array();

		$totalCombustible = 0;
		$totalAlimentacion = 0;
		$totalComunicaciones = 0;
		$totalSuministros = 0;
		$totalOcio = 0;

		foreach($expenses as $expense) {
			switch ($expense->getExpense_type()) {
				case "combustible":
					$totalCombustible += $expense->getExpense_quantity();
					break;
				case "alimentacion":
					$totalAlimentacion += $expense->getExpense_quantity();
					break;
				case "comunicaciones":
					$totalComunicaciones += $expense->getExpense_quantity();
					break;
				case "suministros":
					$totalSuministros += $expense->getExpense_quantity();
					break;
				case "ocio":
					$totalOcio += $expense->getExpense_quantity();
					break;
			}

		}

		array_push($pie_data_array, array(
			"Combustible" => $totalCombustible,
			"Alimentacion" => $totalAlimentacion,
			"Comunicaciones" => $totalComunicaciones,
			"Suministros" => $totalSuministros,
			"Ocio" => $totalOcio
		));
		
		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($pie_data_array));
	}

	public function getLineChart($date) {
		$currentUser = parent::authenticateUser();
		$username = $currentUser->getUsername();
		$filter_data = explode(',', $date);
		$start_date = $filter_data[0];
		$end_date = $filter_data[1];
		$expenses = $this->expensesMapper->findByUsernameDate($username, $start_date, $end_date);

		$pie_data_array = array();

		$monthly_totals = array();
		foreach ($expenses as $expense) {
			$month = date('F', strtotime($expense->getExpense_date()));
			$expense_type = $expense->getExpense_type();
			if (!isset($monthly_totals[$month])) {
				$monthly_totals[$month] = array();
			}
			if (!isset($monthly_totals[$month][$expense_type])) {
				$monthly_totals[$month][$expense_type] = 0;
			}
			$monthly_totals[$month][$expense_type] += $expense->getExpense_quantity();
		}
		
		// $totalCombustible = array();
		// $totalAlimentacion = array();
		// $totalComunicaciones = array();
		// $totalSuministros = array();
		// $totalOcio = array();

		// foreach($expenses as $expense) {
		// 	switch ($expense->getExpense_type()) {
		// 		case "combustible":
		// 			array_push($totalCombustible, $expense->getExpense_quantity());
		// 			break;
		// 		case "alimentacion":
		// 			array_push($totalAlimentacion, $expense->getExpense_quantity());
		// 			break;
		// 		case "comunicaciones":
		// 			array_push($totalComunicaciones, $expense->getExpense_quantity());
		// 			break;
		// 		case "suministros":
		// 			array_push($totalSuministros, $expense->getExpense_quantity());
		// 			break;
		// 		case "ocio":
		// 			array_push($totalOcio, $expense->getExpense_quantity());
		// 			break;
		// 	}
		// }
		// array_push($pie_data_array, array(
		// 	"Combustible" => $totalCombustible,
		// 	"Alimentacion" => $totalAlimentacion,
		// 	"Comunicaciones" => $totalComunicaciones,
		// 	"Suministros" => $totalSuministros,
		// 	"Ocio" => $totalOcio
		// ));
		
		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($monthly_totals));
	}

	public function createExpense($data) {
		$currentUser = parent::authenticateUser();
		$expense = new Expenses();
		if (isset($data->expense_type) && isset($data->expense_date)
		&& isset($data->expense_quantity) && isset($data->expense_owner)) {
			
			$expense->setExpense_type($data->expense_type);
			$expense->setExpense_date($data->expense_date);
			$expense->setExpense_quantity($data->expense_quantity);
			$expense->setOwner($currentUser);

			// if the optional fields are filled, we set them

			if(isset($data->expense_description)){
				$expense->setExpense_description($data->expense_description);
			}

			if(isset($data->expense_file)){
				$uuid_file = uniqid();
				$expense->setExpense_file($data->expense_file);

			}

		}

		try {
			// validate Expenses object
			$expense->checkIsValidForCreate(); // if it fails, ValidationException

			// save the Expenses object into the database
			$expenseId = $this->expensesMapper->save($expense);
			$this->expensesMapper->saveFile($expense);
			// response OK. Also send expense in content
			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
			header('Location: '.$_SERVER['REQUEST_URI']."/".$expenseId);
			header('Content-Type: application/json');
			echo(json_encode(array(

				"id" => $expenseId,
				"expense_type" => $expense->getExpense_type(),
				"expense_date" => $expense->getExpense_date(),
				"expense_quantity" => $expense->getExpense_quantity(),
				// por ahora queda asi, pero igual deberiamos comprobar que esta seteados??
				"expense_description" => $expense->getExpense_description(),
				"expense_file" => $expense->getExpense_file(),
				"expense_owner" => $expense->getOwner()->getUsername()
			)));

		} catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}

	public function updateExpense($expenseId, $data) {
		$currentUser = parent::authenticateUser();

		$expense = $this->expensesMapper->findById($expenseId);
		if ($expense == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Expense with id ".$expenseId." not found");
			return;
		}

		// Check if the Expense owner is the currentUser (in Session)
		if ($expense->getOwner() != $currentUser) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("you are not the owner of this expense");
			return;
		}

		$expense->setExpense_type($data->expense_type);
		$expense->setExpense_date($data->expense_date);
		$expense->setExpense_quantity($data->expense_quantity);
		$expense->setOwner($currentUser);

		// if the optional fields are filled, we set them
		if(isset($data->expense_description)){
			$expense->setExpense_description($data->expense_description);
		}

		if(isset($data->expense_file)){
			$expense->setExpense_file($data->expense_file);
		}

		try {
			// validate Expenses object
			$expense->checkIsValidForUpdate(); // if it fails, ValidationException
			$this->expensesMapper->update($expense);
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		}catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}

	public function deleteExpense($expenseId) {
		$currentUser = parent::authenticateUser();
		$expense = $this->expensesMapper->findById($expenseId);

		if ($expense == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Expense with id ".$expenseId." not found");
			return;
		}
		// Check if the Expenses owner is the currentUser (in Session)
		if ($expense->getOwner() != $currentUser) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("you are not the owner of this expense");
			return;
		}

		$this->expensesMapper->delete($expense);

		header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
	}

	public function debug_to_console($data) {
		$output = $data;
		if (is_array($output))
			$output = implode(',', $output);
	
		echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
	}

}

// URI-MAPPING for this Rest endpoint
$expenseRest = new ExpenseRest();
URIDispatcher::getInstance()
->map("GET",	"/expense", array($expenseRest,"getExpenses"))
->map("GET",	"/expense/piechart/$1", array($expenseRest,"getPieChart"))
->map("GET",	"/expense/linechart/$1", array($expenseRest,"getLineChart"))
->map("GET",	"/expense/$1", array($expenseRest,"readExpense"))
->map("POST", "/expense", array($expenseRest,"createExpense"))
->map("PUT",	"/expense/$1", array($expenseRest,"updateExpense"))
->map("DELETE", "/expense/$1", array($expenseRest,"deleteExpense"));

