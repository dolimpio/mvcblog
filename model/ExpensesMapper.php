<?php
// file: model/expenseMapper.php
require_once(__DIR__."/../core/PDOConnection.php");

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/Expenses.php");

/**
* Class expenseMapper
*
* Database interface for expense entities
*
* @author lipido <lipido@gmail.com>
*/
class ExpensesMapper {

	/**
	* Reference to the PDO connection
	* @var PDO
	*/
	private $db;

	public function __construct() {
		$this->db = PDOConnection::getInstance();
	}

	/**
	* Retrieves all expenses
	*
	* Note: Comments are not added to the expense instances
	*
	* @throws PDOException if a database error occurs
	* @return mixed Array of expense instances (without comments)
	*/

	public function findByDate($begin_date, $finish_date) {
		$stmt = $this->db->prepare("SELECT * FROM expenses, users WHERE users.username = expenses.ownerDB and expenses.dateDB >= ? and expenses.dateDB <= ? ORDER BY expenses.dateDB asc");
		$stmt->execute(array($begin_date, $finish_date));
		$expenses_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$expenses = array();

		foreach ($expenses_db as $expense) {
			$owner = new User($expense["ownerDB"]);
			array_push($expenses, new Expenses($expense["id"], $expense["typeDB"], $expense["dateDB"], $expense["quantityDB"], $expense["descriptionDB"],$expense["fileDB"],$owner));
		}
		return $expenses;
	}

	public function findAll() {
		$stmt = $this->db->query("SELECT * FROM expenses, users WHERE users.username = expenses.ownerDB ORDER BY expenses.dateDB asc" );
		$expenses_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$expenses = array();

		foreach ($expenses_db as $expense) {
			$owner = new User($expense["ownerDB"]);
			array_push($expenses, new Expenses($expense["id"], $expense["typeDB"], $expense["dateDB"], $expense["quantityDB"], $expense["descriptionDB"],$expense["fileDB"],$owner));
		}
		
		return $expenses;
	}

	

	/**
	* Loads a expense from the database given its id
	*
	* Note: Comments are not added to the expense
	*
	* @throws PDOException if a database error occurs
	* @return Expenses The expense instances (without comments). NULL
	* if the expense is not found
	*/
	public function findById($expenseid){
		$stmt = $this->db->prepare("SELECT * FROM expenses WHERE id=?");
		$stmt->execute(array($expenseid));
		$expense = $stmt->fetch(PDO::FETCH_ASSOC);

		if($expense != null) {
			return new Expenses(
			$expense["id"],
			$expense["typeDB"],
			$expense["dateDB"],
			$expense["quantityDB"],
			$expense["descriptionDB"],
			$expense["fileDB"],
			new User($expense["ownerDB"]));
		} else {
			return NULL;
		}
	}

	public function findByUsernameDate($ownerDB, $start_date, $end_date){
		$fromDate = $start_date;
		$toDate = $end_date;

		$dateCompare = new DateTime($start_date);
		$yearCompare = (int) $dateCompare->format('Y');

		if($yearCompare == 1920){
			$stmt = $this->db->prepare("SELECT * FROM expenses WHERE ownerDB=?");
			$stmt->execute(array($ownerDB));
			$expenses_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$expenses = array();
		}else{
			$stmt = $this->db->prepare("SELECT * FROM expenses WHERE ownerDB=? AND dateDB BETWEEN ? AND ? GROUP BY dateDB");
			$stmt->execute(array($ownerDB,$fromDate,$toDate));
			$expenses_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$expenses = array();
		}

		foreach ($expenses_db as $expense) {
			$owner = new User($expense["ownerDB"]);
			array_push($expenses, new Expenses($expense["id"], $expense["typeDB"], $expense["dateDB"], $expense["quantityDB"], $expense["descriptionDB"],$expense["fileDB"],$owner));
		}
		return $expenses;
	}

	public function findByUsername($ownerDB){
		$stmt = $this->db->prepare("SELECT * FROM expenses WHERE ownerDB=?");
		$stmt->execute(array($ownerDB));
		$expenses_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$expenses = array();

		foreach ($expenses_db as $expense) {
			$owner = new User($expense["ownerDB"]);
			array_push($expenses, new Expenses($expense["id"], $expense["typeDB"], $expense["dateDB"], $expense["quantityDB"], $expense["descriptionDB"],$expense["fileDB"],$owner));
		}
		return $expenses;
	}
	/**
	* Loads a Expense from the database given its id
	*
	* It includes all the comments
	*
	* @throws PDOException if a database error occurs
	* @return Expenses The expense instances (without comments). NULL
	* if the expense is not found
	*/

		/**
		* Saves a expense into the database
		*
		* @param Expenses $expense The expense to be saved
		* @throws PDOException if a database error occurs
		* @return int The mew expense id
		*/
		public function save(Expenses $expense) {
			$stmt = $this->db->prepare("INSERT INTO expenses(typeDB, dateDB, quantityDB, descriptionDB, fileDB, ownerDB ) values (?,?,?,?,?,?)");
			//$id=NULL, $expense_type=NULL, $expense_date=NULL, User $expense_quantity=NULL,  $expense_description=NULL, $expense_file=NULL, $owner=NULL
			$stmt->execute(array($expense->getExpense_type(), $expense->getExpense_date(), $expense->getExpense_quantity(), $expense->getExpense_description(), $expense->getExpense_file(), $expense->getOwner()->getUsername()));
			return $this->db->lastInsertId();
		}

		public function saveFile(Expenses $expense) {
			$stmt = $this->db->prepare("INSERT INTO files(uuid, filename) values (?,?)");
			$uuid_file = uniqid();
			$stmt->execute(array($uuid_file, $expense->getExpense_file()));
			return $this->db->lastInsertId();
		}

		/**
		* Updates a expense in the database
		*
		* @param Expenses $expense The expense to be updated
		* @throws PDOException if a database error occurs
		* @return void
		*/
		public function update(Expenses $expense) {
			$stmt = $this->db->prepare("UPDATE expenses set typeDB=?, dateDB=?, quantityDB=?, descriptionDB=?, fileDB=? where id=?");
			$stmt->execute(array($expense->getExpense_type(), $expense->getExpense_date(), $expense->getExpense_quantity(), $expense->getExpense_description(), $expense->getExpense_file(), $expense->getId()));
		}
		/**
		* Deletes a expense into the database
		*
		* @param Expenses $expense The expense to be deleted
		* @throws PDOException if a database error occurs
		* @return void
		*/
		public function delete(Expenses $expense) {
			$stmt = $this->db->prepare("DELETE from expenses WHERE id=?");
			$stmt->execute(array($expense->getId()));
		}

	}
