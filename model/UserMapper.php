<?php
// file: model/UserMapper.php

require_once(__DIR__."/../core/PDOConnection.php");

/**
* Class UserMapper
*
* Database interface for User entities
*
* @author lipido <lipido@gmail.com>
*/
class UserMapper {

	/**
	* Reference to the PDO connection
	* @var PDO
	*/
	private $db;

	public function __construct() {
		$this->db = PDOConnection::getInstance();
	}

	/**
	* Saves a User into the database
	*
	* @param User $user The user to be saved
	* @throws PDOException if a database error occurs
	* @return void
	*/
	public function save($user) {
		$stmt = $this->db->prepare("INSERT INTO users values (?,?,?)");
		$stmt->execute(array($user->getUsername(), $user->getPasswd(), $user->getEmail()));
	}

	public function editUser($user, $oldname) {

		$stmt = $this->db->prepare("INSERT INTO users values (?,?,?)");
		$stmt->execute(array($user->getUsername(), $user->getPasswd(), $user->getEmail()));

		// Actualizar las referencias a ese usuario en la tabla "expenses" para apuntar a la nueva fila
		$stmt = $this->db->prepare("UPDATE expenses SET ownerDB = ? WHERE ownerDB = ?");
		$stmt->execute(array($user->getUsername(), $oldname));

		$stmt = $this->db->prepare("DELETE from users WHERE username=?");
		$stmt->execute(array($oldname));

	}

	/**
	* Checks if a given username is already in the database
	*
	* @param string $username the username to check
	* @return boolean true if the username exists, false otherwise
	*/
	public function usernameExists($username) {
		$stmt = $this->db->prepare("SELECT count(username) FROM users where username=?");
		$stmt->execute(array($username));

		if ($stmt->fetchColumn() > 0) {
			return true;
		}
	}

	/**
	* Checks if a given pair of username/password exists in the database
	*
	* @param string $username the username
	* @param string $passwd the password
	* @return boolean true the username/passwrod exists, false otherwise.
	*/
	public function isValidUser($username, $passwd) {
		$stmt = $this->db->prepare("SELECT count(username) FROM users where username=? and passwd=?");
		$stmt->execute(array($username, $passwd));

		if ($stmt->fetchColumn() > 0) {
			return true;
		}
	}

	public function createSession($user){
		$stmt = $this->db->prepare("INSERT INTO user_tokens(token, expiry, username_fk ) values (?,?,?)");
		$token = utf8_encode(random_bytes(8));
		$date = date('Y-m-d');
		$expireDate = date('Y-m-d', strtotime($date. ' + 10 days')); 
		$stmt->execute(array($token, $expireDate, $user));
	}

	public function isInSession(){
		
	}

	/**
	* Loads a expense from the database given its id
	*
	* Note: Comments are not added to the expense
	*
	* @throws PDOException if a database error occurs
	* @return User The expense instances (without comments). NULL
	* if the expense is not found
	*/
	public function findByUsername($user_name){
		$stmt = $this->db->prepare("SELECT * FROM users WHERE username=?");
		$stmt->execute(array($user_name));
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		if($user != null) {
			return new User(
			$user["username"],
			$user["passwd"],
			$user["email"]);
		} else {
			return NULL;
		}
	}

	/**
		* Deletes a user into the database
		*
		* @param User $user The user to be deleted
		* @throws PDOException if a database error occurs
		* @return void
		*/
		public function delete(User $user) {
			$stmt = $this->db->prepare("DELETE from users WHERE username=?");
			$stmt->execute(array($user->getUsername()));
		}
}
