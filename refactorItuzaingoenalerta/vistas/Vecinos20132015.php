<?php include 'top.php'; ?>

<div class="row-fluid">
    <div class="span10">
        <h1>Vecinos</h1>
    </div>
    <div class="span2">
        <h5> <U>Vecinos validados:</u> <?= count($personasValidadas); ?> </h5>
        <h5> <U>Vecinos sin validar: </U> <?= count($personas) - count($personasValidadas); ?></h5>
    </div>
</div>

<div class="row-fluid row-fix mb">
    <div class="span8">
        <button class="btn btn-success" data-loading-text="<i class='icon-spinner icon-spin'></i> Cargando"
            onclick="mostrarAgregarPersona($(this));">
            <i class="icon-plus icon-white"></i> Agregar persona
        </button>
        <!-- <button class="btn btn-warning" data-loading-text="<i class='icon-spinner icon-spin'></i> Cargando"
            onclick="mostrarEnviarEmail($(this));">
            <i class="icon-envelope icon-white"></i> Enviar email a vecinos
        </button> -->
    </div>
    <div id="divEnviarEmail" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="titEnviarEmail"
        aria-hidden="true">
    </div>
    <div id="divAgregarPersona" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="titAgregarPersona"
        aria-hidden="true">
    </div>
    <div id="divEditarPersona" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="titEditarPersona"
        aria-hidden="true">
    </div>
    <div id="divTelefonosPersona" class="modal hide fade" tabindex="-1" role="dialog"
        aria-labelledby="titTelefonosPersona" aria-hidden="true">
    </div>
    <div id="divContactosPersona" class="modal hide fade" tabindex="-1" role="dialog"
        aria-labelledby="titContactosPersona" aria-hidden="true">
    </div>
    <div id="divDatosPersona" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="titDatosPersona"
        aria-hidden="true">
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <? if (count($personas) == 0) { ?>
        <h3 class="text-info text-center">
            No hay personas cargadas.
        </h3>
        <?
        } else {
            ?>
        <table id="tabVecinos20132015" class="table table-hover table-bordered dataTable">
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>E-Mail</th>
                    <th>Direcci&oacute;n</th>
                    <th>Estado</th>
                    <th>Creado por</th>
                    <th>F. de Alta</th>
                    <th>Acci&oacute;nes</th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($personas as $persona) { ?>
                <tr id="fila<? echo $persona->getId(); ?>">
                    <td name="dni">
                        <? echo $persona->getDni(); ?>
                    </td>
                    <td name="nombre">
                        <? echo htmlchars($persona->getNombre(), false); ?>
                    </td>
                    <td name="apellido">
                        <? echo htmlchars($persona->getApellido(), false); ?>
                    </td>
                    <td name="email">
                        <? echo htmlchars($persona->getEmail(), false); ?>
                    </td>
                    <td name="direccion">
                        <? echo htmlchars($persona->getDireccion()->getStr(), false); ?>
                    </td>
                    <td name="estado" id="tdEstado<? echo $persona->getId(); ?>">
                        <? echo $persona->getEstadoStr(); ?>
                    </td>
                    <td name="creador">
                        <? echo htmlchars($persona->getCreador(), false); ?>
                    </td>
                    <td name="alta">
                        <? echo $persona->getAlta(); ?>
                    </td>
                    <td name="acciones">
                        <div class="btn-group">

                            <button class="btn" data-loading-text="<i class='icon-spinner icon-spin'></i>"
                                data-toggle="tooltip" data-animation="true" data-placement="top" title="Mas datos"
                                onclick="mostrarDatosPersona('<? echo $persona->getId(); ?>', $(this));">
                                <i class="icon-user"></i>
                            </button>
                            <button class="btn" data-loading-text="<i class='icon-spinner icon-spin'></i>"
                                onclick="mostrarEditarPersona('<? echo $persona->getId(); ?>', $(this));">
                                <i class="icon-edit"></i>
                            </button>
                            <button class="btn" data-loading-text="<i class='icon-spinner icon-spin'></i>"
                                data-toggle="tooltip" data-animation="true" data-placement="top" title="Telefonos"
                                onclick=" mostrarTelefonosPersona('<? echo $persona->getId(); ?>', $(this));">
                                <i class="icon-phone"></i>
                            </button>
                            <button class="btn" data-loading-text="<i class='icon-spinner icon-spin'></i>"
                                data-toggle="tooltip" data-animation="true" data-placement="top" title="Contactos"
                                onclick=" mostrarContactosPersona('<? echo $persona->getId(); ?>', $(this));">
                                <i class="icon-phone-sign"></i>
                            </button>
                            <button class="btn btn-warning" data-loading-text="<i class='icon-spinner icon-spin'></i>"
                                onclick="mostrarEnviarEmail($(this), '<? echo $persona->getId(); ?>');">
                                <i class="icon-envelope icon-white"></i>
                            </button>
                            <!-- <button class="btn btn-danger" data-loading-text="<i class='icon-spinner icon-spin'></i>"
                                data-toggle="tooltip" data-animation="true" data-placement="top" title="Borrar"
                                onclick=" borrarPersona('<? echo $persona->getId(); ?>', $(this));">
                                <i class="icon-remove"></i>
                            </button> -->
                            <button class="btn btn-info" data-loading-text="<i class=\'icon-spinner icon-spin\'></i>"
                                data-toggle="tooltip" data-animation="true" data-placement="top"
                                title="Resetear contrase&ntilde;a"
                                onclick=" resetPass('<? echo $persona->getId(); ?>', $(this));">
                                <i class="icon-key"></i>
                            </button>'
                            <? if ($persona->getEstado() == "P") { ?>
                            <button class="btn btn-success" data-loading-text="<i class='icon-spinner icon-spin'></i>"
                                data-toggle="tooltip" data-animation="true" data-placement="top" title="Validar"
                                onclick="validarPersona('<? echo $persona->getId(); ?>', $(this));">
                                <i class="icon-ok icon-white"></i>
                            </button>
                            <? } ?>
                        </div>
                    </td>
                </tr>

                <? } ?>
            </tbody>
        </table>
        <? } ?>
    </div>
</div>


<?php include 'bottom.php'; ?>