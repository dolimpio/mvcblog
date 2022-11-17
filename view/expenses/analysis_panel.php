<?php
//file: view/users/login.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$view->setVariable("title", "Save Your Money Analysis Panel");
$errors = $view->getVariable("errors");

?>
    <div class="grid-item-cajainfo1">
        <div class="boxcontent">
            <span class="tituloSpan"><?= i18n("Total Expenses") ?></span>
            <br>
            <span class="interiorelem">12,345.02</span>
        </div>
    </div>

    <div class="grid-item-cajainfo2">
        <div class="boxcontent">
            <span class="tituloSpan"><?= i18n("Number of Operations") ?></span>
            <br>
            <span class="interiorelem">3,000</span>
        </div>
    </div>

    <div class="grid-item-cajainfo3">
        <div class="boxcontent">
            <span class="tituloSpan"><?= i18n("Types of Expenses") ?></span>
            <br>
            <span class="interiorelem">5</span>
            
        </div> 
    </div>
    
    <div class="grid-item-graphics" class="cajaTemp">
        
            <div id="linechart" class="chart">
                <span class="textChart"><?= i18n("Types of mensual Expense") ?></span>
                <figure class="highcharts-figure">
                    <div id="container"></div>
                    <p class="highcharts-description">
												<?= i18n("Expenses in the last 12 months, clasificated by type of expense") ?>
                    </p>
                </figure>
            </div>
    
            <div id="piechart" class="chart">
                <span class="textChart"><?= i18n("Types of Expense") ?></span>
                <figure class="highcharts-figure">
                    <div id="container2"></div>
                    <p class="highcharts-description">
                        <?= i18n("Expenses clasificated by types") ?>
                    </p>
                </figure>
            </div>
        
    </div>

    <div class="grid-item-date">
        <button class="botton"><i class="fa fa-calendar"></i><?= i18n("Filter by date") ?></button>
    </div>

    <div class="grid-item-type">
        <button class="botton" ><i class="fa fa-list"></i><?= i18n("Filter by type of expenses") ?></button>
    </div>

<?php $view->moveToFragment("javascript");?>
<script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="js/docready.js"></script>
    <script src="js/charts.js"></script>
<?php $view->moveToFragment("css");?>
<link rel="stylesheet" href="css/estilos.css" type="text/css">
<link rel="stylesheet" href="css/stylechart.css" type="text/css">
<?php $view->moveToDefaultFragment(); ?>