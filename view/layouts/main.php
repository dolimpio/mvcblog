<?php

$view = ViewManager::getInstance();

$currentuser = $view->getVariable("currentusername");

?><!DOCTYPE html>
<html>
<head>
	<title><?= $view->getVariable("title", "no title") ?></title>
	<meta charset="utf-8">
	<!-- enable ji18n() javascript function to translate inside your scripts -->
	<script src="index.php?controller=language&amp;action=i18njs">
	</script>

<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

		<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <script src="js/docready.js"></script>


	<?= $view->getFragment("css") ?>
	<?= $view->getFragment("javascript") ?>
</head>
<body>

<div class="grid-item-sidebar">
		<div class="siderbar-middle">
            <a href="index.php?controller=expenses&amp;action=analysis_panel"><img class="icon-option" img src="css/icons/chart-histogram.svg" alt=""></a>
            <a href="index.php?controller=expenses&amp;action=index"><img class="icon-option" img src="css/icons/votacion.svg" alt=""></a>
        </div>			
       

    </div>
    <div class="grid-item-navbar">
        <div class="logo">SaveYourMoney</div>
        
        <div class="dropdown" id="language">
            <a href="index.php?controller=language&amp;action=change&amp;lang=es"><img class="icon-option" img src="css/icons/Spain_clip_art.svg" alt="">
                <a href="index.php?controller=language&amp;action=change&amp;lang=en"><img class="icon-option" img src="css/icons/Flags_Of_The_United_States_And_The_United_Kingdom_clip_art.svg" alt="">
        </div>

        <div class="dropdown user" id="user">
            <button class="dropbtn "><i class="fa fa-user"></i><?= i18n("User") ?></button>
            <div class="dropdown-content" id="userdropdown">
                <a href="#"> <?= i18n("User Preferences") ?></a>
                <a href="index.php?controller=users&amp;action=logout"><?= i18n("Close Session") ?></a>
            </div>
        </div>
    </div>

   
    <!-- añadir lo de default -->
    <?= $view->getFragment(ViewManager::DEFAULT_FRAGMENT) ?>


    
</body>

</html>
