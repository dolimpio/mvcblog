<?php
//file: view/users/register.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$user = $view->getVariable("user");
$view->setVariable("title", "Register");
$view->setVariable("grid-item-Titulo", "¡Regístrate ahora!");
$view->setVariable("style", "css/register.css");

?>
<div class="grid-container">
<div class=" grid-item grid-item-Titulo"><?= i18n("Register Now")?></div>

    <div class=" grid-item grid-item-Registro">
        <div id=" grid-item LognTitulo">
          <h2><?= i18n("Register")?></h2>
        </div>
          <form action="index.php?controller=users&amp;action=register" class="registroForm" name="registroForm" id="registroForm" method="POST">
            <input type="text" name="username" id="name" required placeholder=<?= i18n("User")?>><span class="barra"></span>
            <input type="email" name="email" id="email" required placeholder=<?= i18n("Email")?>><span class="barra"></span>
            <input type="password" name="passwd" id="password" required placeholder=<?= i18n("Password")?>><span class="barra"></span>
            <button type="submit"><?= i18n("Subscribe")?></button>
          </form>
  	</div>
		<div class="dropdown" id="language">
            		<a href="index.php?controller=language&amp;action=change&amp;lang=es"><img class="icon-option" img src="css/icons/Spain_clip_art.svg" alt="">
                <a href="index.php?controller=language&amp;action=change&amp;lang=en"><img class="icon-option" img src="css/icons/Flags_Of_The_United_States_And_The_United_Kingdom_clip_art.svg" alt="">
    </div>
</div>
<?php $view->moveToFragment("css");?>
<link rel="stylesheet" href="css/registro.css">
<?php $view->moveToDefaultFragment(); ?>
