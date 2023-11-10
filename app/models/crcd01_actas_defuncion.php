<?php
 class crcd01_actas_defuncion extends AppModel {

 	var $name = "crcd01_actas_defuncion";
 	var $useTable = "crcd01_actas_defuncion";


    public function insertar($datos = array(),$cod_acta){
    	if(count($datos)>0){
    		//pr($datos);
    		$sql="INSERT INTO crcd01_actas_defuncion(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_acta,contenido_acta, usuario, fecha_proceso, cedula_difunto, nombres_apellidos_difunto,cedula_exponente, nombres_apellidos_exponente, cedula_testigo,nombres_apellidos_testigo, ano_acta, tomo, folio)
                  VALUES (".implode(',',$datos).");";
             $c=parent::findCount($this->SQLCA()." and cod_acta='".$cod_acta."'");
             if($c==0){
             	$res = parent::execute($sql);
             }else{
             	$res = -1;
             }
             return $res;
    	}else{
    		return 0;
    	}
    }

    public function modificar($datos = array(),$cod_acta){
    	if(count($datos)>0){
    		//pr($datos);
    		 $sql="UPDATE crcd01_actas_defuncion SET ".implode(',',$datos)." WHERE ".$this->SQLCA()." and cod_acta='".$cod_acta."'";
             $res = parent::execute($sql);
             return $res;
    	}else{
    		return 0;
    	}
    }

    function SQLCA($ano=null){
				 $sql_re = "cod_presi=".$_SESSION["SScodpresi"]."  and    ";
				 $sql_re .= "cod_entidad=".$_SESSION["SScodentidad"]."  and  ";
				 $sql_re .= "cod_tipo_inst=".$_SESSION["SScodtipoinst"]."  and ";
				 $sql_re .= "cod_inst=".$_SESSION["SScodinst"]."  and  ";
				 if($ano!=null){
					 $sql_re .= "cod_dep=".$_SESSION["SScoddep"]."  and  ";
						$sql_re .= "ano=".$ano."  ";
				 }else{
					 $sql_re .= "cod_dep=".$_SESSION["SScoddep"]." ";
				 }
				 return $sql_re;
   }//fin funcion SQLCA

 }
?>