var orden = [[5, "asc"]];

$(document).ready(function () {
  $(".btn").tooltip();
  $("button").click(function () {
    $(".tooltip:visible").remove();
  });
  $("button").mouseleave(function () {
    $(".tooltip:visible").remove();
  });
});
var cantidad = 10;
var pagina = 0;
function guardarPersona(id) {
  var dni = $("#parDNI:visible").val();
  var nombre = $("#parNombre:visible").val();
  var apellido = $("#parApellido:visible").val();
  var email = $("#parEMail:visible").val();
  var nacimiento = $("#parNacimiento:visible").val();
  var partida = $("#parPartida:visible").val();
  var ruc = $("#parRUC:visible").val();
  var calle = $("#parCalle:visible").val();
  var altura = $("#parAltura:visible").val();
  var msjInfo = $(".mensajes:visible").find("#msjInfo");
  var msjLoad = $("");
  var msjError = $(".mensajes:visible").find("#msjError");
  var msjOk = $(".mensajes:visible").find("#msjOk");
  var boton = $("#btnGuardar:visible");
  var incompletos = "";
  var erroneos = "";

  if (dni === "") incompletos += " <BR/> - DNI";
  if (nombre === "") incompletos += " <BR/> - Nombre";
  if (apellido === "") incompletos += " <BR/> - Apellido";
  //    if (email === "")
  //        incompletos += " <BR/> - E-Mail";
  if (nacimiento === "") incompletos += " <BR/> - Nacimiento";
  if (partida === "") incompletos += " <BR/> - Partida";
  if (ruc === "") incompletos += " <BR/> - Cuil/Cuit";
  if (calle === "") incompletos += " <BR/> - Calle";
  if (altura === "") incompletos += " <BR/> - Altura";
  if (incompletos !== "") {
    msjOk.slideUp();
    msjError.slideUp();
    msjInfo.html("<b>Por favor complete:</b>" + incompletos);
    msjInfo.slideDown();
    return;
  }

  if (email !== "" && !reMail.test(email.toUpperCase()))
    erroneos += " <BR/> - Email";
  if (erroneos !== "") {
    msjOk.slideUp();
    msjError.slideUp();
    msjInfo.html("<b>Por favor corrija:</b>" + erroneos);
    msjInfo.slideDown();
    return;
  }

  msjInfo.slideUp();
  msjError.slideUp();
  boton.button("loading");
  $.ajax({
    url: "acciones/GuardarPersona.php",
    data: $("#formPersona").serialize() + (id ? "&id=" + id : ""),
    type: "POST",
    timeout: 10000,
    success: function (data) {
      var vecino = JSON.parse(data);

      if (vecino !== null && !vecino.error) {
        var id = vecino.id;
        if ($("#fila" + id).size() === 0) {
          mostrarTelefonosPersona(id, null, true);

          // Creo array con columnas en orden:
          var vecinoarr = new Array(
            vecino.dni,
            vecino.nombre,
            vecino.apellido,
            vecino.email,
            vecino.direccion,
            vecino.estadostr,
            vecino.creador,
            vecino.alta,
            armarAcciones(id, vecino.estado === "V")
          );

          $("#tabVecinosActuales").dataTable().fnAddData(vecinoarr);
          // Agrego el tooltip a los botones nuevos.
          $(".btn").tooltip();
          // cambiar
          $("tr.odd:not('[id*=fila]'), tr.even:not('[id*=fila]')").attr(
            "id",
            "fila" + id
          );
          $("#fila" + id)
            .find("td:nth-child(1)")
            .attr("name", "dni");
          $("#fila" + id)
            .find("td:nth-child(2)")
            .attr("name", "nombre");
          $("#fila" + id)
            .find("td:nth-child(3)")
            .attr("name", "apellido");
          $("#fila" + id)
            .find("td:nth-child(4)")
            .attr("name", "email");
          $("#fila" + id)
            .find("td:nth-child(5)")
            .attr("name", "direccion");
          $("#fila" + id)
            .find("td:nth-child(6)")
            .attr("name", "estado");
          $("#fila" + id)
            .find("td:nth-child(7)")
            .attr("name", "creador");
          $("#fila" + id)
            .find("td:nth-child(8)")
            .attr("name", "alta");
          $("#fila" + id)
            .find("td:nth-child(9)")
            .attr("name", "acciones");
        } else {
          // cambiar
          $("#fila" + id)
            .find("[name=dni]")
            .html(vecino.dni);
          $("#fila" + id)
            .find("[name=nombre]")
            .html(vecino.nombre);
          $("#fila" + id)
            .find("[name=apellido]")
            .html(vecino.apellido);
          $("#fila" + id)
            .find("[name=email]")
            .html(vecino.email);
          $("#fila" + id)
            .find("[name=direccion]")
            .html(vecino.direccion);
          $("#fila" + id)
            .find("[name=creador]")
            .html(vecino.creador);
          $("#fila" + id)
            .find("[name=alta]")
            .html(vecino.alta);
          msjsSuccess(msjError, msjInfo, msjLoad, msjOk);
        }
        $(".modal").modal("hide");
      } else {
        msjsError(msjInfo, msjLoad, msjError, msjOk, vecino.error);
      }
      boton.button("reset");
    },
    statusCode: {
      405: function () {
        redirectHome();
      },
    },
    error: function () {
      msjsFalla(msjInfo, msjLoad, msjError);
      boton.button("reset");
      $(".modal").modal("hide");
    },
  });
}

function armarAcciones(id, confirmado) {
  var html =
    '<div class="btn-group">' +
    '<button class="btn" data-loading-text="<i class=\'icon-spinner icon-spin\'></i>" ' +
    'data-toggle="tooltip" data-animation="true" data-placement="top" title="Mas datos"' +
    "onclick=\"$(this).button('loading');" +
    "mostrarDatosPersona('" +
    id +
    "', $(this));\">" +
    '<i class="icon-user"></i>' +
    "</button>" +
    '<button class="btn" data-loading-text="<i class=\'icon-spinner icon-spin\'></i>" ' +
    "onclick=\"mostrarEditarPersona('" +
    id +
    "', $(this));\"> " +
    '<i class="icon-edit"></i> ' +
    "</button> " +
    '<button class="btn" data-loading-text="<i class=\'icon-spinner icon-spin\'></i>" ' +
    'data-toggle="tooltip" data-animation="true" data-placement="top" title="Telefonos"' +
    " onclick=\"$(this).button('loading'); " +
    "mostrarTelefonosPersona('" +
    id +
    "', $(this));\">" +
    '<i class="icon-phone"></i>' +
    "</button> " +
    '<button class="btn" data-loading-text="<i class=\'icon-spinner icon-spin\'></i>" ' +
    'data-toggle="tooltip" data-animation="true" data-placement="top" title="Contactos"' +
    " onclick=\"$(this).button('loading'); " +
    "mostrarContactosPersona('" +
    id +
    "', $(this));\">" +
    '<i class="icon-phone-sign"></i>' +
    '<button class="btn btn-danger" data-loading-text="<i class=\'icon-spinner icon-spin\'></i>" ' +
    'data-toggle="tooltip" data-animation="true" data-placement="top" title="Borrar"' +
    " onclick=\"borrarPersona('" +
    id +
    "', $(this));\">" +
    '<i class="icon-remove"></i>' +
    "</button> " +
    (!confirmado
      ? '<button class="btn btn-success" data-loading-text="<i class=\'icon-spinner icon-spin\'></i>" ' +
        'data-toggle="tooltip" data-animation="true" data-placement="top" title="Validar"' +
        " onclick=\"$(this).button('loading'); " +
        "validarPersona('" +
        id +
        "', $(this));\">" +
        '<i class="icon-ok icon-white"></i>' +
        "</button> "
      : "") +
    "</div>";
  return html;
}

function guardarTelefonos(id, seguir) {
  var numeros = "";

  $("[name=parNumero]:visible").each(function (i, o) {
    if ($(o).val() !== "" || numeros.indexOf("ERROR") !== -1)
      var numero = $(o).val();
    else return;
    numero = replaceAll(numero, " ", "");
    if (validarNumero(numero))
      if (validarTelefono(numero)) numeros += (i !== 0 ? ";" : "") + numero;
      else {
        $(".mensajes")
          .find("#msjError")
          .show()
          .html(
            "El formato del n&uacute;mero '" + $(o).val() + "' es incorecto."
          );
        $(o).focus();
        numeros = "ERROR";
        return;
      }
    else {
      $(".mensajes")
        .find("#msjError")
        .show()
        .html("Por favor, ingrese solo n&uacute;meros.");
      $(o).focus();
      numeros = "ERROR";
      return;
    }
  });

  if ($("[name=parNumero]:visible").size() === 0 || numeros === "") {
    $(".mensajes")
      .find("#msjError")
      .show()
      .html("Por favor, ingrese al menos un n&uacute;mero de tel&eacute;fono");
    return;
  }

  if (numeros.indexOf("ERROR") !== -1) {
    return;
  }

  numeros = "numeros=" + numeros + "&id=" + id + (seguir ? "&nuevo=S" : "");

  if ($("[id=btnGuardar]:visible").size() !== 0)
    $("[id=btnGuardar]:visible").button("loading");
  $.ajax({
    url: "acciones/GuardarTelefonos.php",
    data: numeros,
    type: "POST",
    timeout: 30000,
    success: function (data) {
      if (data !== null && data.substr(0, 5) !== "ERROR") {
        if (seguir) mostrarContactosPersona(id);
        else $("#divTelefonosPersona").modal("hide");
      } else {
        $(".mensajes").find("#msjError").show().html(data.substr(6));
      }
      if ($("[id=btnGuardar]:visible").size() !== 0)
        $("[id=btnGuardar]:visible").button("reset");
    },
    statusCode: {
      405: function () {
        redirectHome();
      },
    },
    error: function () {
      if ($("[id=btnGuardar]:visible").size() !== 0)
        $("[id=btnGuardar]:visible").button("reset");
      $("#divTelefonosPersona").modal("hide");
    },
  });
}

function guardarContactos(id) {
  var contactos = "";
  $(".mensajes").find("#msjError").hide();

  $("[name=parNumero]:visible").each(function (i, o) {
    if ($(o).val() !== "" || contactos.indexOf("ERROR") !== -1)
      var numero = $(o).val();
    else return;
    numero = replaceAll(numero, " ", "");
    if (validarNumero(numero))
      if (validarTelefono(numero)) contactos += (i !== 0 ? ";" : "") + numero;
      else {
        $(".mensajes")
          .find("#msjError")
          .show()
          .html(
            "El formato del n&uacute;mero '" + $(o).val() + "' es incorecto."
          );
        $(o).focus();
        contactos = "ERROR";
        return;
      }
    else {
      $(".mensajes")
        .find("#msjError")
        .show()
        .html("Por favor, ingrese solo n&uacute;meros.");
      $(o).focus();
      contactos = "ERROR";
      return;
    }
  });

  if (contactos.indexOf("ERROR") !== -1) {
    return;
  }

  contactos = "numeros=" + contactos + "&id=" + id;

  if ($("[id=btnGuardar]:visible").size() !== 0)
    $("[id=btnGuardar]:visible").button("loading");
  $.ajax({
    url: "acciones/GuardarContactos.php",
    data: contactos,
    type: "POST",
    timeout: 30000,
    success: function () {
      if ($("[id=btnGuardar]:visible").size() !== 0)
        $("[id=btnGuardar]:visible").button("reset");
      $("#divContactosPersona").modal("hide");
    },
    statusCode: {
      405: function () {
        redirectHome();
      },
    },
    error: function () {
      if ($("[id=btnGuardar]:visible").size() !== 0)
        $("[id=btnGuardar]:visible").button("reset");
      $("#divContactosPersona").modal("hide");
    },
  });
}

var lineasTelefono;
function agregarLineaNumero(max) {
  var fieldSet = $("#fsLineasNumero");

  if ($("[name=parNumero]:visible").size() >= max) {
    $(".mensajes")
      .find("#msjError")
      .show()
      .html("Por favor, ingrese " + max + " n&uacute;meros como m&aacute;ximo");
    return;
  }

  lineasTelefono++;
  var input =
    "<input id='inpTel" +
    lineasTelefono +
    "' type='text' class='span11' name='parNumero' " +
    '  onkeydown="noEnter(event)" placeholder="11 15 55667788"/>';
  var button =
    " <button id='btnTel" +
    lineasTelefono +
    "' class='btn btn-fix add btn-danger' title='Quitar numero'" +
    " type='button' onclick='borrarLineaNumero(" +
    lineasTelefono +
    ")'><i class='icon-remove'></i></button>";
  fieldSet.append(input);
  fieldSet.append(button);
}

function borrarLineaNumero(id) {
  $("#inpTel" + id).remove();
  $("#btnTel" + id).remove();
}

function mostrarAgregarPersona(button) {
  button.button("loading");
  $.ajax({
    url: "ajax/AgregarPersona.php",
    success: function (data) {
      $("#divAgregarPersona").html(data);
      $("#divAgregarPersona").modal("show");
      button.button("reset");
      $(".fecha").datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "1900:2013",
        format: "dd/mm/yyyy",
      });
      $("#divEditarPersona").html("");
    },
    statusCode: {
      405: function () {
        redirectHome();
      },
    },
  });
}
function mostrarEnviarEmail(button, id) {
  var url = "ajax/EnviarEmailPersonas.php";
  var datos = "";

  if (id !== undefined) {
    url = "ajax/EnviarEmailPersona.php";
    datos = "id=" + id;
  }

  button.button("loading");
  $.ajax({
    url: url,
    data: datos,
    success: function (data) {
      $("#divEnviarEmail").html(data);
      $("#divEnviarEmail").modal("show");
      button.button("reset");
    },
    statusCode: {
      405: function () {
        redirectHome();
      },
    },
  });
}

function mostrarDatosPersona(id, button) {
  button.button("loading");
  $.ajax({
    url: "ajax/VerDatosPersona.php",
    data: "id=" + id,
    success: function (data) {
      $("#divDatosPersona").html(data);
      $("#divDatosPersona").modal("show");
      button.button("reset");
    },
    statusCode: {
      405: function () {
        redirectHome();
      },
    },
  });
}
function mostrarEditarPersona(id, button) {
  button.button("loading");
  $.ajax({
    url: "ajax/EditarPersona.php",
    data: "id=" + id,
    success: function (data) {
      $("#divAgregarPersona").html("");
      $("#divEditarPersona").html(data);
      $("#divEditarPersona").modal("show");

      button.button("reset");
      $(".fecha").datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "1900:2013",
        format: "dd/mm/yyyy",
      });
    },
    statusCode: {
      405: function () {
        redirectHome();
      },
    },
  });
}

function mostrarTelefonosPersona(id, button, esAlta) {
  if (button) button.button("loading");
  $.ajax({
    url: "ajax/TelefonosPersona.php",
    data: "id=" + id + "&alta=" + (esAlta ? "true" : "false"),
    success: function (data) {
      $("#divTelefonosPersona").html(data);
      $("#divTelefonosPersona").modal("show");
      $("#divAgregarPersona").modal("hide");

      if (button) button.button("reset");
    },
    statusCode: {
      405: function () {
        redirectHome();
      },
    },
  });
}

function mostrarContactosPersona(id, button) {
  if (button) button.button("loading");
  $.ajax({
    url: "ajax/ContactosPersona.php",
    data: "id=" + id,
    success: function (data) {
      $("#divContactosPersona").html(data);
      $("#divTelefonosPersona").modal("hide");
      $("#divTelefonosPersona").html(" ");
      $("#divContactosPersona").modal("show");

      if (button) button.button("reset");
    },
    statusCode: {
      405: function () {
        redirectHome();
      },
    },
  });
}

function borrarPersona(id, button) {
  if (
    confirm(
      "Est\u00E1 seguro que desea eliminar a este vecino? \u00C9sta acci\u00F3n no puede deshacerse.\n\
Se perderan los datos generados por el mismo (alertas, direcci\u00F3n, tel\u00E9fonos y contactos)"
    )
  ) {
    button.button("loading");
    $.ajax({
      url: "acciones/BorrarPersona.php",
      data: "id=" + id,
      type: "POST",
      timeout: 30000,
      success: function () {
        $("#fila" + id).slideUp();
      },
      statusCode: {
        405: function () {
          redirectHome();
        },
      },
      error: function () {
        location.reload();
      },
    });
  }
}

function validarPersona(id, button) {
  button.button("loading");
  $.ajax({
    url: "acciones/ValidarPersona.php",
    data: "id=" + id,
    type: "POST",
    timeout: 30000,
    success: function (data) {
      if (button) button.fadeOut();
      $("#tdEstado" + id).html(data);
    },
    statusCode: {
      405: function () {
        redirectHome();
      },
    },
    error: function () {
      location.reload();
    },
  });
}
function enviarEmail(button, id) {
  var data = $("#formEmail").serialize();
  var url = "acciones/EnviarEmailPersonas.php";
  var textoConfirmar =
    "Est\u00E1 seguro que desea enviar este email a todos los vecinos? \u00C9sta acci\u00F3n puede demorar unos minutos.";

  if (id !== undefined) {
    data += "&id=" + id;
    url = "acciones/EnviarEmailPersona.php";
    textoConfirmar = "Est\u00E1 seguro que desea enviar este email?";
  }

  if (confirm(textoConfirmar)) {
    button.button("loading");
    $.ajax({
      url: url,
      data: data,
      type: "POST",
      timeout: 30000,
      success: function (data) {
        if (data !== null && data.substr(0, 5) !== "ERROR") {
          $(".mensajes")
            .find("#msjOk")
            .html("<b>Email enviado con &eacute;xito</b>");
          $("#divEnviarEmail").modal("hide");
        } else {
          $(".mensajes").find("#msjError").show().html(data.substr(6));
        }
        if (button) button.button("reset");
      },
      statusCode: {
        405: function () {
          redirectHome();
        },
      },
      error: function () {
        location.reload();
      },
    });
  }
}

function resetPass(id, button) {
  if (
    confirm(
      "Est\u00E1 seguro que desea reiniciar la contrase\u00F1a de esta persona? La misma volvera a ser su nombre de usuario."
    )
  ) {
    button.button("loading");
    $.ajax({
      url: "acciones/ResetearPass.php",
      data: "idPersona=" + id,
      type: "POST",
      timeout: 30000,
      success: function () {
        button.button("reset");
      },
      error: function () {
        location.reload();
      },
    });
  }
}

function generarExcel(button) {
  if (button) button.button("loading");

  var tipo = $("#parTipoAlerta").val();
  var desde = $("#parDesde").val();
  var hasta = $("#parHasta").val();
  var hdesde = $("#parHDesde").val();
  var hhasta = $("#parHHasta").val();
  var informante = $("#parIdInformante").val();

  //console.log(tipo2);
  window.open(
    "ajax/GenerarExcelVecinos.php?tipo=" +
      tipo +
      "&desde=" +
      desde +
      "&hasta=" +
      hasta +
      "&hhasta=" +
      hhasta +
      "&hdesde=" +
      hdesde +
      "&idInformante=" +
      informante,
    "Descarga"
  );

  if (button) button.button("reset");
  /*
     $.ajax({
     url: "ajax/GenerarExcel.php",
     data: "tipo=" + tipo + "&desde=" + desde + "&hasta=" + hasta + "&hhasta=" + hhasta + "&hdesde=" + hdesde + "&idInformante=" + informante,
     type: "POST",
     dataType: "json",
     timeout: 30000,
     success: function(data) {
     console.log("11111");
     if (button)
     button.button("reset");
     },
     error: function() {
     console.log("2222");
     if (button)
     button.button("reset");
     }
     });*/
}
