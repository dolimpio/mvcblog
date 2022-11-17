<?php
//file: view/users/login.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$view->setVariable("title", "Save Your Money Login");
$errors = $view->getVariable("errors");

?>
        <div class="grid-item-Titulo">Save Your Money</div>
        <div class="grid-item-Login">
            <div id="LognTitulo">
                <h2><?= i18n("Welcome!")?></h2>
            </div>

            <form action="index.php?controller=users&amp;action=login" class="loginForm" name="loginForm" id="loginForm" method="POST">
                <input type="text" id="txtUsuario" name="username" placeholder=<?= i18n("User")?>>
                <input type="password" id="txtPassword" name="passwd" placeholder=<?= i18n("Password")?>>
							<input type="checkbox" value="rememberMe"name="rememberCheck"><?= i18n("Remember me?")?>
                <input type="submit" class=boton value="Acceder">
            </form>

            <div id="LoginMsg">
                <p><?= i18n("Have you forgetten your password?")?></p>
            </div>
						<div class="dropdown" id="language">
            		<a href="index.php?controller=language&amp;action=change&amp;lang=es"><img class="icon-option" img src="css/icons/Spain_clip_art.svg" alt="">
                <a href="index.php?controller=language&amp;action=change&amp;lang=en"><img class="icon-option" img src="css/icons/Flags_Of_The_United_States_And_The_United_Kingdom_clip_art.svg" alt="">
        		</div>

        </div>
        <!-- Mandar a la pagina de registro -->
        <div class="grid-item-Registro">
            <p><?= i18n("Dont have an account?")?></p>
            <a href="index.php?controller=users&amp;action=register"><input class=boton type="button" value=<?= i18n("Register now")?>></a>
        </div>

<?php $view->moveToFragment("css");?>
<link rel="stylesheet" href="css/login.css" type="text/css">
<?php $view->moveToDefaultFragment(); ?>
