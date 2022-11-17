<?php
//file: controller/ExpenseController.php

require_once(__DIR__."/../model/Expenses.php");
require_once(__DIR__."/../model/ExpensesMapper.php");
require_once(__DIR__."/../model/User.php");

require_once(__DIR__."/../core/ViewManager.php");
require_once(__DIR__."/../controller/BaseController.php");

/**
* Class ExpensesController
*
* Controller to make a CRUDL of Expenses entities
*
* @author lipido <lipido@gmail.com>
*/
class ExpensesController extends BaseController {

	/**
	* Reference to the ExpensesMapper to interact
	* with the database
	*
	* @var ExpensesMapper
	*/
	private $expenseMapper;

	public function __construct() {
		parent::__construct();

		$this->expenseMapper = new ExpensesMapper();
		$this->view->setLayout("main");

	}

	/**
	* Action to list posts
	*
	* Loads all the posts from the database.
	* No HTTP parameters are needed.
	*
	* The views are:
	* <ul>
	* <li>posts/index (via include)</li>
	* </ul>
	*/
	public function index() {

		// obtain the data from the database
		$expenses = $this->expenseMapper->findAll();
		if(sizeof($expenses)>0){
				$beginDate = $expenses[0]->getExpense_date();
				$finishDate = end($expenses)->getExpense_date();
		}


		$expenses = $this->expenseMapper->findByDate($beginDate, $finishDate);

		// put the array containing Expenses object to the view
		$this->view->setVariable("expenses", $expenses);

		// render the view (/view/expenses/index.php)
		$this->view->render("expenses", "index");
}

	public function analysis_panel(){
		$begin_string_date = "2022-05-01";
		$finish_string_date = "2022-10-30";

		$beginDate = date_format(date_create($begin_string_date), 'Y-m-d');
		$finishDate = date_format(date_create($finish_string_date), 'Y-m-d');;


		//$this->pieChart($beginDate,$finishDate);
		$this->view->render("expenses", "analysis_panel");

	}


	/**
	* Action to view a given expense
	*
	* This action should only be called via GET
	*
	* The expected HTTP parameters are:
	* <ul>
	* <li>id: Id of the expense (via HTTP GET)</li>
	* </ul>
	*
	* The views are:
	* <ul>
	* <li>expenses/view: If expense is successfully loaded (via include).	Includes these view variables:</li>
	* <ul>
	*	<li>expense: The current Expenses retrieved</li>

	*	<li>comment: The current Comment instance, empty or
	*	being added (but not validated)</li>

	* </ul>
	* </ul>
	*
	* @throws Exception If no such expense of the given id is found
	* @return void
	*
	*/
	public function view(){
		if (!isset($_GET["id"])) {
			throw new Exception("id is mandatory");
		}

		$expenseid = $_GET["id"];

		// find the Expenses object in the database
		$expense = $this->expenseMapper->findById($expenseid);

		if ($expense == NULL) {
			throw new Exception("no such expense with id: ".$expenseid);
		}

		// put the Expenses object to the view
		$this->view->setVariable("expense", $expense);

		// check if comment is already on the view (for example as flash variable)
		// if not, put an empty Comment for the view
		// $comment = $this->view->getVariable("comment");
		// $this->view->setVariable("comment", ($comment==NULL)?new Comment():$comment);

		// render the view (/view/posts/view.php)
		$this->view->render("expenses", "view");

	}

	/**
	 * 
	 * ESTOS COMENTARIOS NO ESTAN BIEN, CORREGIR
	 * 
	* Action to add a new expense
	*
	* When called via GET, it shows the add form
	* When called via POST, it adds the post to the
	* database
	*
	* The expected HTTP parameters are:
	* <ul>
	* <li>title: Title of the post (via HTTP POST)</li>
	* <li>content: Content of the post (via HTTP POST)</li>
	* </ul>
	*
	* The views are:
	* <ul>
	* <li>posts/add: If this action is reached via HTTP GET (via include)</li>
	* <li>posts/index: If post was successfully added (via redirect)</li>
	* <li>posts/add: If validation fails (via include). Includes these view variables:</li>
	* <ul>
	*	<li>post: The current Post instance, empty or
	*	being added (but not validated)</li>
	*	<li>errors: Array including per-field validation errors</li>
	* </ul>
	* </ul>
	* @throws Exception if no user is in session
	* @return void
	*/
	public function add() {
		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Adding posts requires login");
		}

		$expense = new Expenses();

		if (isset($_POST["submit"])) { // reaching via HTTP Post...

			//public function __construct($id=NULL, $expense_type=NULL, $expense_date=NULL, User $expense_quantity=NULL,  $expense_description=NULL, $expense_file=NULL, $owner=NULL) {

			// populate the Expense object with data form the form
			//combustible, alimentacion, comunicaciones, suministros, ocio
			echo $_POST["expense_type"];
			$expense->setExpense_type($_POST["expense_type"]);
			echo $_POST["expense_type"];
			$expense->setExpense_date($_POST["expense_date"]);
			$expense->setExpense_quantity($_POST["expense_quantity"]);
			$expense->setExpense_description($_POST["expense_description"]);

			// //check if file is going to be uploaded to create uuid
			// $uuid_file = uniqid();
			// //echo $_POST["uuid_file"];

			$expense->setExpense_file($_POST["expense_file"]);

			// uploads file to the server
			// $target_dir = "uploads/";
			// $target_file = $target_dir.basename($_FILES["expense_file"]["name"]);
			// $uploadOK = 1;
			// $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			// if(isset($_POST["submit"])) {
			// 	$check = getimagesize($_FILES["expense_file"]["name"]);
			// 	if($check !== false) {
			// 	  echo "File is an image - " . $check["mime"] . ".";
			// 	  $uploadOk = 1;
			// 	} else {
			// 	  echo "File is not an image.";
			// 	  $uploadOk = 0;
			// 	}
			// }
			//   // Check if file already exists
			//   //añadir en el edit
			// if (file_exists($target_file)) {
			// 	echo "Sorry, file already exists.";
			// 	$uploadOk = 0;
  			// }
			//getExpense_type, getExpense_date, getExpense_quantity, getExpense_description, getExpense_file, getOwner

			// $expense->setOwner($_POST["ownerDB"]); Mirar lo siguiente

			// The user of the Expenses is the currentUser (user in session)
			$expense->setOwner($this->currentUser);

			try {
				// validate Expenses object
				$expense->checkIsValidForCreate(); // if it fails, ValidationException

				// save the Expenses object into the database
				$this->expenseMapper->save($expense);

				// POST-REDIRECT-GET
				// Everything OK, we will redirect the user to the list of expenses
				// We want to see a message after redirection, so we establish
				// a "flash" message (which is simply a Session variable) to be
				// get in the view after redirection.
				$this->view->setFlash(sprintf(i18n("Expense \"%s\" successfully added."),$expense ->getId()));

				// perform the redirection. More or less:
				// header("Location: index.php?controller=posts&action=index")
				// die();
				$this->view->redirect("expenses", "index");

			}catch(ValidationException $ex) {
				// Get the errors array inside the exepction...
				$errors = $ex->getErrors();
				// And put it to the view as "errors" variable
				$this->view->setVariable("errors", $errors);
			}
		}

		// Put the Expenses object visible to the view
		$this->view->setVariable("expenses", $expense);

		// render the view (/view/expenses/add.php)
		$this->view->render("expenses", "add");

	}

	/**
	 * 
	 * MIRAR ESTOS COMENTARIOS PORQUE TAMPOCO ESTA BIEN
	 * 
	* Action to edit a expense
	*
	* When called via GET, it shows an edit form
	* including the current data of the Post.
	* When called via POST, it modifies the post in the
	* database.
	*
	* The expected HTTP parameters are:
	* <ul>
	* <li>id: Id of the post (via HTTP POST and GET)</li>
	* <li>title: Title of the post (via HTTP POST)</li>
	* <li>content: Content of the post (via HTTP POST)</li>
	* </ul>
	*
	* The views are:
	* <ul>
	* <li>posts/edit: If this action is reached via HTTP GET (via include)</li>
	* <li>posts/index: If post was successfully edited (via redirect)</li>
	* <li>posts/edit: If validation fails (via include). Includes these view variables:</li>
	* <ul>
	*	<li>post: The current Post instance, empty or being added (but not validated)</li>
	*	<li>errors: Array including per-field validation errors</li>
	* </ul>
	* </ul>
	* @throws Exception if no id was provided
	* @throws Exception if no user is in session
	* @throws Exception if there is not any expense with the provided id
	* @throws Exception if the current logged user is not the owner of the expense
	* @return void
	*/
	public function edit() {
		if (!isset($_REQUEST["id"])) {
			throw new Exception("A expense id is mandatory");
		}

		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Editing expenses requires login");
		}


		// Get the Expenses object from the database
		$expenseid = $_REQUEST["id"];
		$expense = $this->expenseMapper->findById($expenseid);

		// Does the expense exist?
		if ($expense == NULL) {
			throw new Exception("no such expense with id: ".$expenseid);
		}

		// Check if the Expenses owner is the currentUser (in Session)
		if ($expense->getOwner() != $this->currentUser) {
			throw new Exception("logged user is not the author of the expense id ".$expenseid);
		}

		if (isset($_POST["submit"])) { // reaching via HTTP Post...

			// populate the Expenses object with data form the form
			$expense->setExpense_type($_POST["expense_type"]);
			$expense->setExpense_date($_POST["expense_date"]);
			$expense->setExpense_quantity($_POST["expense_quantity"]);
			$expense->setExpense_description($_POST["expense_description"]);
			$expense->setExpense_file($_POST["expense_file"]);
			//$expense->setOwner($_POST["ownerDB"]);			

			try {
				// validate Expenses object
				$expense->checkIsValidForUpdate(); // if it fails, ValidationException

				// update the Expenses object in the database
				$this->expenseMapper->update($expense);

				// POST-REDIRECT-GET
				// Everything OK, we will redirect the user to the list of expenses
				// We want to see a message after redirection, so we establish
				// a "flash" message (which is simply a Session variable) to be
				// get in the view after redirection.

				//Uso ID porque los gastos no tienen title
				$this->view->setFlash(sprintf(i18n("Expenses \"%s\" successfully updated."),$expense ->getId()));

				// perform the redirection. More or less:
				// header("Location: index.php?controller=posts&action=index")
				// die();
				$this->view->redirect("expenses", "index");

			}catch(ValidationException $ex) {
				// Get the errors array inside the exepction...
				$errors = $ex->getErrors();
				// And put it to the view as "errors" variable
				$this->view->setVariable("errors", $errors);
			}
		}

		// Put the Post object visible to the view
		$this->view->setVariable("expense", $expense);

		// render the view (/view/expenses/add.php)
		$this->view->render("expenses", "edit");
	}

	/**
	 * 
	 * // CAMBIAR ESTE COMENTARIO QUE ESTA MAL
	 * 
	* Action to delete a expense
	*
	* This action should only be called via HTTP POST
	*
	* The expected HTTP parameters are:
	* <ul>
	* <li>id: Id of the post (via HTTP POST)</li>
	* </ul>
	*
	* The views are:
	* <ul>
	* <li>posts/index: If post was successfully deleted (via redirect)</li>
	* </ul>
	* @throws Exception if no id was provided
	* @throws Exception if no user is in session
	* @throws Exception if there is not any expense with the provided id
	* @throws Exception if the author of the expense to be deleted is not the current user
	* @return void
	*/
	public function delete() {
		if (!isset($_POST["id"])) {
			throw new Exception("id is mandatory");
		}
		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Editing expenses requires login");
		}
		
		// Get the Expenses object from the database
		$expenseid = $_REQUEST["id"];
		$expense = $this->expenseMapper->findById($expenseid);

		// Does the expense exist?
		if ($expense == NULL) {
			throw new Exception("no such expense with id: ".$expenseid);
		}

		// Check if the Expenses author is the currentUser (in Session)
		if ($expense->getOwner() != $this->currentUser) {
			throw new Exception("Expense owner is not the logged user");
		}

		// Delete the Expenses object from the database
		$this->expenseMapper->delete($expense);

		// POST-REDIRECT-GET
		// Everything OK, we will redirect the user to the list of expenses
		// We want to see a message after redirection, so we establish
		// a "flash" message (which is simply a Session variable) to be
		// get in the view after redirection.
		$this->view->setFlash(sprintf(i18n("Expenses \"%s\" successfully deleted."),$expense ->getId()));

		// perform the redirection. More or less:
		// header("Location: index.php?controller=posts&action=index")
		// die();
		$this->view->redirect("expenses", "index");

	}
	// public function showCharts(){
	// 	$expenses = $this->expenseMapper->findAll();
	// 	foreach ($expenses as $expense){
	// 		if (datetime)

	// 	}
	// }
	// $begin_string_date = "2022-05-01";
	// $finish_string_date = "2022-09-29";
	//beginDate == 05
	//finishDate == 09
	//lenght = 9-5 = 4;
	/*
	public function pieChart($beginDate, $finishDate){

		$expenses = $this->expenseMapper->findByDate($beginDate, $finishDate);

		$data_combustible = 0;
		$data_alimentacion = 0;
		$data_comunicaciones = 0;
		$data_suministros = 0;
		$data_ocio = 0;

		foreach ($expenses as $expense){
				if($expense->getExpense_type() == "combustible"){
						$data_combustible += $expense->getExpense_quantity();
				}
				if($expense->getExpense_type() == "alimentacion"){
						$data_alimentacion += $expense->getExpense_quantity();
				}
				if($expense->getExpense_type() == "comunicaciones"){
						$data_comunicaciones += $expense->getExpense_quantity();
				}
				if($expense->getExpense_type() == "suministros"){
						$data_suministros += $expense->getExpense_quantity();
				}
				if($expense->getExpense_type() == "ocio"){
						$data_ocio += $expense->getExpense_quantity();
				}
		}

		$data_formatted = array();

		array_push($data_formatted, "Combustible");
		array_push($data_formatted, "'".$data_combustible."'");

		array_push($data_formatted, "Alimentacion");
		array_push($data_formatted, "'".$data_alimentacion."'");

		array_push($data_formatted, "Comunicaciones");
		array_push($data_formatted, "'".$data_comunicaciones."'");

		array_push($data_formatted, "Suministros");
		array_push($data_formatted, "'".$data_suministros."'");

		array_push($data_formatted, "Ocio");
		array_push($data_formatted, "'".$data_ocio."'");


		$this->view->setVariable("pieChart", $data_formatted);
}int_r($data_formatted);
	}*/

	public function csv(){
			$username = $this->currentUser->getUsername();
			$delimiter = ",";
			//create a file pointer
			$f = fopen('php://memory','w');

			//set column headers
			$fields = array('Type', 'Date', 'Quantity', 'Description', 'File');
			fputcsv($f, $fields, $delimiter);
			$expenses = $this->expenseMapper->findAll();
			
			foreach ($expenses as $expense) {
							$lineData = array( $expense->getExpense_type(), $expense->getExpense_date(), $expense->getExpense_quantity(), $expense->getExpense_description(),$expense->getExpense_file());
							fputcsv($f, $lineData, $delimiter);
			}
			//move back to beginning of file
			fseek($f, 0);

			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="tablaGastos.csv";');
			//output all remaining data on a file pointer
			fpassthru($f);
	}
	
}
