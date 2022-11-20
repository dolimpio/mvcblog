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

	public function getExpenses() {
		$expenses = $this->expensesMapper->findAll();

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
				"owner" => $expense->getOwner()->getUsername()
			));
		}

		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($expenses_array));
	}

	public function createExpense($data) {
		$currentUser = parent::authenticateUser();
		$expense = new Expenses();

		if (isset($data->expense_type) && isset($data->expense_date)
		&& isset($data->expense_quantity) && isset($data->owner)) {
			
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

		}

		try {
			// validate Expenses object
			$expense->checkIsValidForCreate(); // if it fails, ValidationException

			// save the Expenses object into the database
			$expenseId = $this->expensesMapper->save($expense);

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
				"owner" => $expense->getOwner()->getUsername()
			)));

		} catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
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
			"owner" => $expense->getOwner()->getUsername()

		);

		//No tenemos comentarios
		//add comments
		// $post_array["comments"] = array();
		// foreach ($post->getComments() as $comment) {
		// 	array_push($post_array["comments"], array(
		// 		"id" => $comment->getId(),
		// 		"content" => $comment->getContent(),
		// 		"author" => $comment->getAuthor()->getusername()
		// 	));
		//}

		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($expense_array));
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

		$expense->setExpense_description($data->expense_description);
		$expense->setExpense_file($data->expense_file);

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

	//There's no comments
	// public function createComment($postId, $data) {
	// 	$currentUser = parent::authenticateUser();

	// 	$post = $this->postMapper->findById($postId);
	// 	if ($post == NULL) {
	// 		header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
	// 		echo("Post with id ".$postId." not found");
	// 		return;
	// 	}

	// 	$comment = new Comment();
	// 	$comment->setContent($data->content);
	// 	$comment->setAuthor($currentUser);
	// 	$comment->setPost($post);

	// 	try {
	// 		$comment->checkIsValidForCreate(); // if it fails, ValidationException

	// 		$this->commentMapper->save($comment);

	// 		header($_SERVER['SERVER_PROTOCOL'].' 201 Created');

	// 	}catch(ValidationException $e) {
	// 		header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
	// 		header('Content-Type: application/json');
	// 		echo(json_encode($e->getErrors()));
	// 	}
	// }
}

// URI-MAPPING for this Rest endpoint
$expenseRest = new ExpenseRest();
URIDispatcher::getInstance()
->map("GET",	"/expense", array($expenseRest,"getExpenses"))
->map("GET",	"/expense/$1", array($expenseRest,"readExpense"))
->map("POST", "/expense", array($expenseRest,"createExpense"))
// ->map("POST", "/post/$1/comment", array($expenseRest,"createComment"))
->map("PUT",	"/expense/$1", array($expenseRest,"updateExpense"))
->map("DELETE", "/expense/$1", array($expenseRest,"deleteExpense"));
