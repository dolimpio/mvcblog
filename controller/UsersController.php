<?php

require_once(__DIR__."/../core/ViewManager.php");
require_once(__DIR__."/../core/I18n.php");

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");

require_once(__DIR__."/../controller/BaseController.php");

/**
* Class UsersController
*
* Controller to login, logout and user registration
*
* @author lipido <lipido@gmail.com>
*/
class UsersController extends BaseController {

	/**
	* Reference to the UserMapper to interact
	* with the database
	*
	* @var UserMapper
	*/
	private $userMapper;
	public function __construct() {
		parent::__construct();

		$this->userMapper = new UserMapper();

		// Users controller operates in a "welcome" layout
		// different to the "default" layout where the internal
		// menu is displayed
		$this->view->setLayout("welcome");
	}

	public function index() {
	
		$this->view->render("users", "index");
	}


	/**
	* Action to login
	*
	* Logins a user checking its creedentials agains
	* the database
	*
	* When called via GET, it shows the login form
	* When called via POST, it tries to login
	*
	* The expected HTTP parameters are:
	* <ul>
	* <li>login: The username (via HTTP POST)</li>
	* <li>passwd: The password (via HTTP POST)</li>
	* </ul>
	*
	* The views are:
	* <ul>
	* <li>POSTs/login: If this action is reached via HTTP GET (via include)</li>
	* <li>POSTs/index: If login succeds (via redirect)</li>
	* <li>users/login: If validation fails (via include). Includes these view variables:</li>
	* <ul>
	*	<li>errors: Array including validation errors</li>
	* </ul>
	* </ul>
	*
	* @return void
	*/
	public function login() {
		if (isset($_POST["username"])){ // reaching via HTTP POST...
			//process login form
			if ($this->userMapper->isValidUser($_POST["username"], $_POST["passwd"])) {
					$_SESSION["currentuser"]=$_POST["username"];
					$userName = $_POST["username"];
			// if user check remember me option, create a session for 30 days
					if(!empty($_POST['rememberCheck'])){
						$var = $_POST['rememberCheck'];
						if(isset($var)){
							setcookie('session',$_POST["username"], time() + 30 * 24 * 60 * 60);
						}
					}else{
						setcookie('session','', time()- 4200000000000);
					}
				//$this->view->setFlash("Your session for user ".$_POST["username"]." will last 30 days.");
				// send user to the restricted area (HTTP 302 code)

				$this->view->redirect("expenses", "analysis_panel");

			}else{
				$errors = array();
				$errors["general"] = "Username is not valid";
				$this->view->setVariable("errors", $errors);
			}


		}
		if(isset($_COOKIE['session'])){
			$this->view->redirect("expenses", "analysis_panel");
		}

		// render the view (/view/users/login.php)

		$this->view->render("users", "login");

	}

	/**
	* Action to register
	*
	* When called via GET, it shows the register form.
	* When called via POST, it tries to add the user
	* to the database.
	*
	* The expected HTTP parameters are:
	* <ul>
	* <li>login: The username (via HTTP POST)</li>
	* <li>passwd: The password (via HTTP POST)</li>
	* </ul>
	*
	* The views are:
	* <ul>
	* <li>users/register: If this action is reached via HTTP GET (via include)</li>
	* <li>users/login: If login succeds (via redirect)</li>
	* <li>users/register: If validation fails (via include). Includes these view variables:</li>
	* <ul>
	*	<li>user: The current User instance, empty or being added
	*	(but not validated)</li>
	*	<li>errors: Array including validation errors</li>
	* </ul>
	* </ul>
	*
	* @return void
	*/
	public function register() {

		$user = new User();

		if (isset($_POST["username"])){ // reaching via HTTP POST...

			// populate the User object with data form the form
			$user->setUsername($_POST["username"]);
			$user->setPassword($_POST["passwd"]);
			$user-> setEmail($_POST["email"]);

			try{
				$user->checkIsValidForRegister(); // if it fails, ValidationException

				// check if user exists in the database
				if (!$this->userMapper->usernameExists($_POST["username"])){

					// save the User object into the database
					$this->userMapper->save($user);

					// POST-REDIRECT-GET
					// Everything OK, we will redirect the user to the list of POSTs
					// We want to see a message after redirection, so we establish
					// a "flash" message (which is simply a Session variable) to be
					// get in the view after redirection.
					$this->view->setFlash("Username ".$user->getUsername()." successfully added. Please login now");

					// perform the redirection. More or less:
					// header("Location: index.php?controller=users&action=login")
					// die();
					$this->view->redirect("users", "login");
				} else {
					$errors = array();
					$errors["username"] = "Username already exists";
					$this->view->setVariable("errors", $errors);
				}
			}catch(ValidationException $ex) {
				// Get the errors array inside the exepction...
				$errors = $ex->getErrors();
				// And put it to the view as "errors" variable
				$this->view->setVariable("errors", $errors);
			}
		}

		// Put the User object visible to the view
		$this->view->setVariable("user", $user);

		// render the view (/view/users/register.php)
		$this->view->render("users", "register");

	}

	/**
	* Action to logout
	*
	* This action should be called via GET
	*
	* No HTTP parameters are needed.
	*
	* The views are:
	* <ul>
	* <li>users/login (via redirect)</li>
	* </ul>
	*
	* @return void
	*/
	public function logout() {
		if(isset($_COOKIE['session'])) {
			setcookie('session','', time() - 4200000000000);
		}
		$this->view->redirect("users", "login");

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
	* @throws Exception if no username was provided
	* @throws Exception if no user is in session
	* @throws Exception if there is not any expense with the provided id
	* @return void
	*/
	public function delete() {
		if (!isset($_POST["username"])) {
			throw new Exception("username is mandatory");
		}
		if (!isset($this->currentUser)) {
			throw new Exception("Not in session. Editing expenses requires login");
		}
		
		// Get the Expenses object from the database
		$user_name = $_REQUEST["username"];
		$user = $this->userMapper->findByUsername($user_name);

		// Does the expense exist?
		if ($user == NULL) {
			throw new Exception("no such expense with id: ".$user_name);
		}

		// Delete the Expenses object from the database
		$this->userMapper->delete($user);

		// POST-REDIRECT-GET
		// Everything OK, we will redirect the user to the list of expenses
		// We want to see a message after redirection, so we establish
		// a "flash" message (which is simply a Session variable) to be
		// get in the view after redirection.
		$this->view->setFlash(sprintf(i18n("User \"%s\" successfully deleted."),$user ->getUsername()));

		session_destroy();
		// perform the redirection. More or less:
		// header("Location: index.php?controller=posts&action=index")
		// die();
		$this->view->redirect("users", "login");

	}

}
