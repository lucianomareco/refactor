<?php
include_once 'util/includes.php';
$requiereLogueo = true;
$nivel = Usuario::N_ADMIN . Usuario::N_USUARIO . Usuario::N_CONSULTA . Usuario::N_ADMINGENERAL; 
$urlVolver = 'Vecinos';
include_once 'util/util.php';
/**
 * Controlador modelo.
 * 
 * @author Agustin Arias <aarias@adoxweb.com.ar>
 */


$extrasJs = array("js/VecinosActuales.js", "js/jquery.dataTables.min.js", "js/DT_bootstrap.js");
$extrasCss = array("css/DT_bootstrap.css", "css/vecinos.css");
$incluirUI = true;


$personas = Persona::obtTodosVecinos($BD, 2021 , 2040);
$personasValidadas= Persona::obtVecinosValidados($BD , 2021 , 2040);


$titulo = "Listado de personas";
$menu = "VECINOS";
include 'vistas/VecinosActuales.php';
?>