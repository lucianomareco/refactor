Si aparece error TipoAlerta:

en el archivo TipoAlerta.php agregar el parametro $query a la funcion obt

antes:

 public static function obt($id, $BD){
        return parent::obt("obtTipoAlerta", $id, $BD);
    }
    
    
 despues:
 
     public static function obt($query, $id, $BD){
        return parent::obt("obtTipoAlerta", $id, $BD);
    }
