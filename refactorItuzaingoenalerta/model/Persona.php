<?php

/**
 * Persona
 *
 * @author Agustin Arias <aarias@adoxweb.com.ar>
 */
class Persona {

    private $id;
    private $nombre;
    private $apellido;
    private $dni;
    private $tipoDoc;
    private $fNacimiento;
    private $email;
    private $ruc;
    private $partida;
    private $estado;
    private $observaciones;
    private $direccion;
    private $alta;
    private $gio;
    private $creador;

//    private $id_creador;

    const PENDIENTE = 'P';

    function __construct($rs = null) {
        if ($rs) {
            $this->id = encrypt($rs["id"]);
            $this->nombre = isset($rs["nombre"]) ? $rs["nombre"] : null;
            $this->apellido = isset($rs["apellido"]) ? $rs["apellido"] : null;
            $this->dni = isset($rs["dni"]) ? $rs["dni"] : null;
            $this->tipoDoc = isset($rs["tipo_doc"]) ? $rs["tipo_doc"] : null;
            $this->fNacimiento = isset($rs["f_nacimiento"]) ? $rs["f_nacimiento"] : null;
            $this->email = isset($rs["email"]) ? $rs["email"] : null;
            $this->ruc = isset($rs["ruc"]) ? $rs["ruc"] : null;
            $this->partida = isset($rs["partida_numero"]) ? $rs["partida_numero"] : null;
            $this->creador = isset($rs["creador"]) ? $rs["creador"] : null;
            $this->estado = isset($rs["estado"]) ? $rs["estado"] : null;
            $this->observaciones = isset($rs["observaciones"]) ? $rs["observaciones"] : null;
            $this->alta = isset($rs["alta"]) ? $rs["alta"] : null;
            $this->gio = isset($rs["gio"]) ? $rs["gio"] : null;

            if (isset($rs["direccion_id"])) {
                $rs["id"] = $rs["direccion_id"];
                $this->direccion = new Direccion($rs);
            }
        }
    }

    public function guardar($BD) {

        $rowPersona = $BD->fetch($BD->call("guardarPersona(" .
                        ($this->getId() ? decrypt($this->getId()) : "-1") . ",'" .
                        $this->getNombre() . "','" .
                        $this->getApellido() . "','" .
                        $this->getDni() . "','" .
                        $this->getTipoDoc() . "','" .
                        $this->getFNacimiento() . "','" .
                        $this->getEmail() . "','" .
                        $this->getRuc() . "','" .
                        $this->getPartida() . "','" .
                        $this->getCreador() . "','" .
                        $this->getObservaciones() . "','" .
                        $this->getEstado() . "'," .
                        ($this->getDireccion()->getId() ?
                                $this->getDireccion()->getId() : "-1") . ",'" .
                        $this->getGio() . "'" .
                        ")"));

        $idPersona = encrypt($rowPersona["id"]);

        $this->setAlta($rowPersona["alta"]);

        $this->setId($idPersona);

        return $idPersona;
    }

    public function borrar($id, $BD) {
        $id = decrypt($id);
        $BD->update("CALL borrarPersona($id)");
    }

    //Solo devuelve las validadas
    // public static function obtTodas($BD) {
    //     return Persona::obtMultiples("obtPersonas", $BD);
    // }
    public static function obtTodas($BD, $desde , $hasta) {
        return self::obtMultiples("obtPersonas($desde , $hasta)", $BD);
    }

    //devuelve todas, validadas o no
    // public static function obtValidadas($BD) {
    //     return Persona::obtMultiples("obtPersonasValidadas", $BD);2015
    // }
    public static function obtValidadas($BD , $desde , $hasta) {
        return Persona::obtMultiples("obtPersonasValidadas($desde , $hasta)", $BD);
    }

    //Devuelve solo personas que hayan hecho alertas alguna vez
    public static function obtPersonasConAlertas($BD) {
        return Persona::obtMultiples("obtPersonasConAlertas", $BD);
    }

    public static function obtEmpresas($BD) {
        return Persona::obtMultiples("obtEmpresas", $BD);
    }

    public static function obt($id, $BD, $eliminados = false) {
        $id = decrypt($id);
        if (!$eliminados)
            $rs = $BD->fetch($BD->call("obtPersona($id)"));
        else
            $rs = $BD->fetch($BD->call("obtPersonaElim($id)"));

        return new Persona($rs);
    }

    public static function obtPorDNI($dni, $BD, $elim = false) {
        if ($elim)
            $rs = $BD->fetch($BD->call("obtPersonaDNIElim('$dni')"));
        else
            $rs = $BD->fetch($BD->call("obtPersonaDNI('$dni')"));

        return new Persona($rs);
    }

    public static function obtPorGio($gio, $BD) {
        $rs = $BD->fetch($BD->call("obtPersonaGio('$gio')"));

        return new Persona($rs);
    }

    public static function obtMultiples($query, $BD) {

        $rs = $BD->call($query);
        $personas = array();

        while ($res = $BD->fetch($rs)) {
            $persona = new Persona($res);
            $personas[$persona->getId()] = $persona;
        }

        return $personas;
    }

    static public function validar($id, $BD) {
        $id = decrypt($id);
        $BD->update("CALL validarPersona($id)");
    }

    // Una vez que ingresa un telefono en la registración se activa la persona.
    static public function activar($id, $BD) {
        $id = decrypt($id);
        $BD->update("CALL activarPersona($id)");
    }

    public function getStr() {
        return strdecode(ucfirst(strtolower($this->nombre)) . " "
                . ucfirst(strtolower($this->apellido)), false);
    }

    public function getId($decrypt = false) {
        if ($decrypt)
            return decrypt($this->id);
        else
            return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNombre() {
        return ucfirst(strtolower($this->nombre));
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getApellido() {
        return ucfirst(strtolower($this->apellido));
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function getDni() {
        return $this->dni;
    }

    public function setDni($dni) {
        $this->dni = $dni;
    }

    
        public static function obtComboValidados($BD, $sel = null) {
//        $todos = self::obtTodas($BD);
        $todos = array(array("Validados"), array("No validados"));
        $str = "";
        foreach ($todos as $tipo) {
            $str .= "<option " . ($tipo[0] == $sel ? "selected='selected'" : "")
                    . " value='" . $tipo[0] . "'>"
                    . $tipo[0]
                    . "</option>";
        }
        return $str;
    }
    
        public static function obtComboAlerta($BD, $sel = null) {
//        $todos = self::obtTodas($BD);
        $todos = array(array("Tiene alertas"), array("No tiene alertas"), array("Tiene alertas reales"), array("Tiene alertas falsas"));
        $str = "";
        foreach ($todos as $tipo) {
            $str .= "<option " . ($tipo[0] == $sel ? "selected='selected'" : "")
                    . " value='" . $tipo[0] . "'>"
                    . $tipo[0]
                    . "</option>";
        }
        return $str;
    }
    
    
    public static function obtComboTiposDoc($BD, $sel = null) {
//        $todos = self::obtTodas($BD);
        $todos = array(array("DNI"), array("LC"), array("LE"));
        $str = "";
        foreach ($todos as $tipo) {
            $str .= "<option " . ($tipo[0] == $sel ? "selected='selected'" : "")
                    . " value='" . $tipo[0] . "'>"
                    . $tipo[0]
                    . "</option>";
        }
        return $str;
    }

    public function getTipoDoc() {
        return $this->tipoDoc;
    }

    public function setTipoDoc($tipoDoc) {
        $this->tipoDoc = $tipoDoc;
    }

    public function getFNacimiento() {
        return $this->fNacimiento;
    }

    public function setFNacimiento($fNacimiento) {
        $this->fNacimiento = $fNacimiento;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getRuc() {
        return $this->ruc;
    }

    public function setRuc($ruc) {
        $this->ruc = $ruc;
    }

    public function getPartida() {
        return $this->partida;
    }

    public function setPartida($partida) {
        $this->partida = $partida;
    }

    public function getObservaciones() {
        return $this->observaciones;
    }

    public function setObservaciones($observaciones) {
        $this->observaciones = $observaciones;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function claseEstado() {
        $str = '';

        switch ($this->estado) {
            case 'P':
                $str = 'text-error';
                break;
            case 'V':
                $str = 'text-success';
                break;
        }

        return strdecode($str);
    }

    public function claseTrEstado() {
        $str = '';

        switch ($this->estado) {
            case 'P':
                $str = 'error';
                break;
            case 'V':
                $str = 'success';
                break;
        }

        return strdecode($str);
    }

    public static function obtEstadoStr($estado) {

        $new = new Persona();

        $new->setEstado($estado);

        return $new->getEstadoStr();
    }

    public static function obtEstadoDesc($estado) {
        $str = '';

        switch ($estado) {
            case 'V':
                $str = 'Sus datos fueron validados. Puede realizar alertas.';
                break;
            case 'P':
                $str = 'Sus datos a&uacute;n NO FUERON VALIDADOS. NO puede realizar alertas.';
                break;
        }

        return strdecode($str);
    }

    public function getEstadoStr() {
        $str = '';

        switch ($this->estado) {
            case 'V':
                $str = 'V&aacute;lido';
                break;
            case 'P':
                $str = 'Pendiente';
                break;
        }

        return strdecode($str);
    }

    public function getEstadoIcon() {
        $str = '';

        switch ($this->estado) {
            case 'V':
                $str = "<i class='icon-ok'></i> ";
                break;
            case 'P':
                $str = "<i class='icon-remove'></i> ";
                break;
        }

        return strdecode($str);
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getAlta() {
        return $this->alta;
    }

    public function setAlta($alta) {
        $this->alta = $alta;
    }

    /* PROCEDURES PARA GIOMON */

    public function getGio() {
        return $this->gio;
    }

    public function setGio($gio) {
        $this->gio = $gio;
    }

    public function getTelefono() {
        return $this->partida;
    }

    public function getRazonSocial() {
        return $this->nombre;
    }

    public function getCuil() {
        return $this->dni;
    }

    public function getCreador() {
        return $this->creador;
    }

    public function setCreador($creador) {
        $this->creador = $creador;
    }


    
}

?>