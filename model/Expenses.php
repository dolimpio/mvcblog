<?php
// file: model/expense.php

require_once(__DIR__."/../core/ValidationException.php");

/**
* Class expense
*
* Represents a expense in the blog. A expense was written by an
* specific User (author) and contains a list of Comments
*
* @author lipido <lipido@gmail.com>
*/
class expenses {

	/**
	* The id of this expense
	* @var string
	*/
	private $id;

	/**
	* The type of this expense
	* @var string
	*/
	private $expense_type;

	/**
	* The date of this expense
	* @var date
	*/
	private $expense_date;
		/**
	* The quantity of this expense
	* @var string
	*/
	private $expense_quantity;
	/** 
	* The description of this expense
	* @var string
	*/
	private $expense_description;
	/** 
	* The file of this expense
	* @var string
	*/
	private $expense_file;

	/**
	* The author of this expense
	* @var User
	*/
	private $owner;

	/**
	* The constructor
	*
	* @param string $id The id of the expense
	* @param string $expense_type The type of the expense
	* @param date $expense_date The date of the expense
	* @param string $expense_quantity The quantity of the expense
	* @param string $expense_description The description of the expense
	* @param string $expense_file The file of the expense
	* @param User $owner The owner of the expense
	*/
	public function __construct($id=NULL, $expense_type=NULL, $expense_date=NULL, $expense_quantity=NULL,  $expense_description=NULL, $expense_file=NULL,User $owner=NULL) {
		$this->id = $id;
		$this->expense_type = $expense_type;
		$this->expense_date = $expense_date;
		$this->expense_quantity = $expense_quantity;
		$this->expense_description = $expense_description;
		$this->expense_file = $expense_file;
		$this->owner = $owner;

	}

	/**
	* Gets the id of this expense
	*
	* @return string The id of this expense
	*/
	public function getId() {
		return $this->id;
	}

	/**
	* Gets the title of this expense
	*
	* @return string The title of this expense
	*/
	public function getExpense_type() {

		return $this->expense_type;
	}

	/**
	* Sets the title of this expense
	*
	* @param string $title the title of this expense
	* @return void
	*/
	public function setExpense_type($expense_type) {
	
		$this->expense_type = $expense_type;
	}

	/**
	* Gets the content of this expense
	*
	* @return string The content of this expense
	*/
	public function getExpense_date() {
		if($this->expense_date!=null)
		return $this->expense_date;
		else
		return date('Y-m-d');
	}

	/**
	* Sets the content of this expense
	*
	* @param date $content the content of this expense
	* @return void
	*/
	public function setExpense_date($expense_date) {
		$this->expense_date = $expense_date;
	}
	/**
	* Gets the content of this expense
	*
	* @return string The content of this expense
	*/
	public function getExpense_quantity() {
		return $this->expense_quantity;
	}

	/**
	* Sets the content of this expense
	*
	* @param string $content the content of this expense
	* @return void
	*/
	public function setExpense_quantity($expense_quantity) {
		$this->expense_quantity = $expense_quantity;
	}
	/**
	* Gets the content of this expense
	*
	* @return string The content of this expense
	*/
	public function getExpense_description() {
		return $this->expense_description;
	}

	/**
	* Sets the content of this expense
	*
	* @param string $content the content of this expense
	* @return void
	*/
	public function setExpense_description($expense_description) {
		$this->expense_description = $expense_description;
	}
		/**
	* Gets the content of this expense
	*
	* @return string The content of this expense
	*/
	public function getExpense_file() {
		return $this->expense_file;
	}

	/**
	* Sets the content of this expense
	*
	* @param string $content the content of this expense
	* @return void
	*/
	public function setExpense_file($expense_file) {
		$this->expense_file = $expense_file;
	}
	
	/**
	* Gets the author of this expense
	*
	* @return User The author of this expense
	*/
	public function getOwner() {
		return $this->owner;
	}

	/**
	* Sets the author of this expense
	*
	* @param User $author the author of this expense
	* @return void
	*/
	public function setOwner(User $owner) {
		$this->owner = $owner;
	}

	/**
	* Checks if the current instance is valid
	* for being updated in the database.
	*
	* @throws ValidationException if the instance is
	* not valid
	*
	* @return void
	*/

	public function checkIsValidForCreate() {
		$errors = array();
		if ($this->expense_type == NULL ) {
			$errors["expense_type"] = "type is mandatory";
		}
			//combustible, alimentacion, comunicaciones, suministros, ocio
		if ($this->expense_type != "combustible" && $this->expense_type != "alimentacion" && $this->expense_type != "comunicaciones" &&
		$this->expense_type != "suministros" && $this->expense_type != "ocio" && $this->expense_type != NULL) {
			$errors["expense_type"] = "type must be combustible, alimentacion, comunicaciones, suministros or ocio";
		}
		if ($this->expense_date == NULL ) {
			$errors["expense_date"] = "date is mandatory";
		}
		if (strlen(trim($this->expense_quantity)) == 0 ) {
			$errors["expense_quantity"] = "quantity is mandatory";
		}
		if ($this->expense_quantity <= 0 ) {
			$errors["expense_quantity"] = "quantity is mandatory and cannot be a negative number";
		}
		if ($this->owner == NULL ) {
			$errors["expense_owner"] = "owner is mandatory";
		}
		if (sizeof($errors) > 0){
			throw new ValidationException($errors, "expense is not valid");
		}
	}

	/**
	* Checks if the current instance is valid
	* for being updated in the database.
	*
	* @throws ValidationException if the instance is
	* not valid
	*
	* @return void
	*/
	public function checkIsValidForUpdate() {
		$errors = array();

		if (!isset($this->id)) {
			$errors["id"] = "id is mandatory";
		}

		try{
			$this->checkIsValidForCreate();
		}catch(ValidationException $ex) {
			foreach ($ex->getErrors() as $key=>$error) {
				$errors[$key] = $error;
			}
		}
		if (sizeof($errors) > 0) {
			throw new ValidationException($errors, "expense is not valid");
		}
	}
}
