<?
include_once 'util.php';

$menu = isset($menu) ? $menu : null;
?>

<!DOCTYPE html>
<html>
    <head>
        <title><? echo $labels["TITULO"] . ($titulo ? " - " . $titulo : "") ?></title>

        <link rel="shortcut icon" href="img/fav.ico">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta charset="utf-8">
        <meta name="description" content="<? echo $system["DESCRIPCION"]; ?>">
        <meta name="keywords" content="<? echo $system["KEYWORDS"]; ?>">
        <!--    <link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
                <link href='http://fonts.googleapis.com/css?family=Spirax' rel='stylesheet' type='text/css'>
        -->

        <!-- CSS -->
        <link href="css/common.css" rel="stylesheet" media="screen">       
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">   
        <link href="css/font-awesome.min.css" rel="stylesheet" media="screen">      
        <link href="css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
        <? if ($extrasCss) foreach ($extrasCss as $eCss) { ?>
                <link href="<? echo $eCss; ?>" rel="stylesheet" media="screen">
            <? } ?>

        <? if ($incluirUI) { ?>
            <link href="css/custom-theme/jquery-ui-1.9.2.custom.css" rel="stylesheet" media="screen">
        <? } ?>

        <!-- JS -->
        <script src="js/jquery-2.0.3.min.js" type="text/javascript"></script>
        <? if ($incluirUI) { ?>
            <script src="js/jquery-ui-1.9.2.min.js" type="text/javascript"></script>
        <? } ?>
        <script src="js/bootbox.min.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/common.js" type="text/javascript"></script>
        <? if ($extrasJs) foreach ($extrasJs as $ejs) { ?>
                <script src="<? echo $ejs; ?>" type="text/javascript"></script>
            <? } ?>
        <script type="text/javascript">

<? if ($requiereLogueo && !$noVerificarCambioPass) { ?>
                $(document).ready(function() {
                    verificarLogin();
                });
<? } ?>

            function redirectHome() {
                redirect('<? echo $system["URL_SINACCESO"] ?>');
            }
            function verificarLogin() {
                $.ajax({
                    url: "acciones/VerificarSesion.php",
                    data: 'nivel=<? echo $nivel; ?>',
                    type: "POST",
                    statusCode: {405: function() {
                            redirectHome();
                        }
                    }
                });
            }
        </script>
		<script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body>
        <script>
            (function(i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function() {
                    (i[r].q = i[r].q || []).push(arguments);
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m);
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', '<? echo $system["GA_SEGUIMIENTO"] ?>'
                    , '<? echo $system["GA_URL"] ?>');
            ga('send', 'pageview');

        </script>
        <div>
            <div class="navbar  navbar-static-top">
                <div class="navbar-inner navbar-conf">
                    <button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                 <!--   <a href="#" class="brand">
                       <img src="img/logo-s.png" alt="<? echo $labels["TITULO"] ?>">
                    </a>  -->
                    <div class="nav-collapse in collapse" style="height: auto;">
                        <ul class="nav">
                           <!-- <li <? echo $menu == "HOME" ? 'class="active"' : ''; ?>>
                                <a href="Home">
                                    <i class="icon-home"></i> Inicio</a></li>  -->
                            <li <? echo $menu == "COMO" ? 'class="active"' : ''; ?>>
                                <a href="ComoFunciona">
                                    <i class="icon-question"></i> C&oacute;mo funciona </a></li>

                            <? if ($usuario->tieneAcceso('AUIL')) { ?>
                                <li class="dropdown <? echo $menu == "MAPA" ? ' active' : ''; ?>">
                                    <a data-toggle="dropdown" class="dropdown-toggle">
                                        <i class="icon-map-marker"></i> Mapa<b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="Mapa">
                                                Mapas Google
                                            </a></li>
                                            <? if ($usuario->tieneAcceso('L')) { ?>   
                                                <li><a href="MapaFiltro">
                                                        Mapas del delito
                                                    </a></li>
                                            <? } ?>
                                            <li><a href="MapaIgn">
                                                    Mapas IGN
                                                </a></li>
                                    </ul>
                                </li>
                            <? } ?>

                            <? if ($usuario->tieneAcceso(Usuario::N_ADMIN . Usuario::N_USUARIO . Usuario::N_INFORMANTE . Usuario::N_ADMINGENERAL)) { ?>
                                <!--<li <? echo $menu == "ALERTAS" ? 'class="active"' : ''; ?>>
                                    <a href="Alertas"><i class="icon-book"></i> Historial alertas</a></li>-->
                                <li class="dropdown <? echo $menu == "ALERTAS" ? 'class="active"' : ''; ?>">
                                    <a data-toggle="dropdown" class="dropdown-toggle">
                                        <i class="icon-book"></i> Alertas<b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="Alertas">
                                                Mes actual
                                            </a></li>
                                        <li><a href="HistorialAlertas">
                                                Meses anteriores
                                            </a></li>
                                    </ul>
                                </li>
                            <? } ?>

                                
                                <? if ($usuario->tieneAcceso( Usuario::N_VENTANILLA)) { ?>                            
                                <li <? echo $menu == "Registrar" ? 'class="active"' : ''; ?>>
                                    <a href="Registrar"><i class="icon-group"></i> Registrar</a></li>
                            <? } ?>
                            <? if ($usuario->tieneAcceso(Usuario::N_ADMIN . Usuario::N_USUARIO . Usuario::N_CONSULTA . Usuario::N_ADMINGENERAL)) { ?>                            
                                <li class="dropdown <? echo $menu == "VECINOS" ? 'class="active"' : ''; ?>">
                                    <a data-toggle="dropdown" class="dropdown-toggle">
                                        <i class="icon-book"></i> Vecinos<b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="VecinosActuales">
                                                2021 - Actualidad
                                            </a></li>
                                        <li><a href="Vecinos20162020">
                                                2016 - 2020
                                            </a></li>
                                        <li><a href="Vecinos20132015">
                                                2013 - 2015
                                            </a></li>                                            
                                    </ul>
                                </li>
                            <? } ?>
                            <? if ($usuario->tieneAcceso(Usuario::N_ADMIN . Usuario::N_ADMINGENERAL)) { ?>
                                <li <? echo $menu == "USUARIOS" ? 'class="active"' : ''; ?>>
                                    <a href="Usuarios"><i class="icon-user"></i> Usuarios</a></li>
                            <? } ?>
                            <? if ($usuario->tieneAcceso(Usuario::N_ADMIN . Usuario::N_USUARIO . Usuario::N_ADMINGENERAL
                            							. Usuario::N_INFORMANTE)) { ?>
                                <li <? echo $menu == "CENTRALES" ? 'class="active"' : ''; ?>>
                                    <a href="Centrales"><i class="icon-phone"></i> Centrales</a></li>
                            <? } ?>
                            <? if ($usuario->tieneAcceso(Usuario::N_ADMIN . Usuario::N_ADMINGENERAL)) { ?>
                                <li <? echo $menu == "CONF" ? 'class="active"' : ''; ?>>
                                    <a href="Configuracion"><i class="icon-cogs"></i> Configuraci&oacute;n</a></li>
                            <? } ?>                                
                            <? if ($usuario->esVecino()) { ?>
                                <li <? echo $menu == "PERFIL" ? 'class="active"' : ''; ?>>
                                    <a href="MisDatos" class="btn-success opt-misdatos">
                                        <i class="icon-user"></i> Mis Datos</a></li>
                            <? } ?>
                            <? if ($usuario->tieneAcceso(Usuario::N_GIOMON )) { ?>
                                <li <? echo $menu == "ALERTASGIOMON" ? 'class="active"' : ''; ?>>
                                    <a href="AlertasGiomon" class="btn-info opt-misdatos">
                                        <i class="icon-bell"></i> Alertas</a></li>
                            <? } ?>
                        </ul>
                    </div>
                    <div id="divUser" class="usuario">
                        <? if ($usuario && $usuario->estaLogueado()) { ?>
                            <a class="cambio-pass" href="<? echo $system["URL_CAMBIAR"]; ?>">
                                <i class="icon-key"></i> 
                                
                          
                             
                                <? if (!$usuario->tieneAcceso('A')) { ?>
                                    Cambiar contrase&ntilde;a 
                                <? } else { ?>
                                    Password
                                <? } ?>
                            </a>
                         
                            <div class="username">
                                <?
                                if ($usuario->esVecino()) {
                                    echo $usuario->getPersona()->getStr();
                                } else {
                                    echo $usuario->getUsername();
                                }
                                ?>
                            </div>
                            <div class="logout">
                                <button class="btn btn-small btn-danger" onclick="logout();">Logout</button>
                            </div>     
                      
                        <? } else { ?>
                          
                 
                        <div class="login">
                                <a class="link" href="<? echo $system["URL_REGISTRO"]; ?>">Registrarse</a>
                                <button class="btn btn-small" onclick="location.href = '<? echo $system["URL_LOGIN"]; ?>';">Login</button>
                            </div>  
                        
                        <? } ?>
                    </div>
                    <div id="divLoading" class="loading-s hide">
                        <img src="img/load-s.gif" alt="Cargando..."/>
                    </div>

                </div>
            </div>
        </div>
        <div class="container-fluid">
