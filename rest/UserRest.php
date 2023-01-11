<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");
require_once(__DIR__."/BaseRest.php");

/**
* Class UserRest
*
* It contains operations for adding and check users credentials.
* Methods gives responses following Restful standards. Methods of this class
* are intended to be mapped as callbacks using the URIDispatcher class.
*
*/
class UserRest extends BaseRest {
	private $userMapper;

	public function __construct() {
		parent::__construct();

		$this->userMapper = new UserMapper();
	}

	public function postUser($data) {
		$user = new User($data->username, $data->password, $data->email,);
		try {
			$user->checkIsValidForRegister();

			$this->userMapper->save($user);

			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
			header("Location: ".$_SERVER['REQUEST_URI']."/".$data->username);
		}catch(ValidationException $e) {
			http_response_code(400);
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}

	public function login($username) {
		$currentLogged = parent::authenticateUser();
		if ($currentLogged->getUsername() != $username) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("You are not authorized to login as anyone but you");
		} else {
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
			echo("Hello ".$username);
		}
	}

	public function deleteUser($user) {
		
		$currentUser = parent::authenticateUser();

		$userCheck = $this->userMapper->findByUsername($user);

		if ($userCheck == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("User with username ".$user." not found");
			return;
		}

		// Check if the user is the currentUser (in Session)
		if ($userCheck->getUsername() != $currentUser->getUsername()) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("you are not the current user");
			return;
		}

		$this->userMapper->delete($userCheck);

		header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
	}

}

// URI-MAPPING for this Rest endpoint
$userRest = new UserRest();
URIDispatcher::getInstance()
->map("GET",	"/user/$1", array($userRest,"login"))
->map("POST", "/user", array($userRest,"postUser"))
->map("DELETE",	"/user/$1", array($userRest,"deleteUser"));


