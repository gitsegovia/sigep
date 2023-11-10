<?php
 class Shp100ActividadesController extends AppController{
	var $uses = array('shd003_codigo_ingresos','shd002_cobradores','shd100_actividades','shd100_solicitud_actividades');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');

function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
					//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession
function beforeFilter(){
    $this->checkSession();
}//fin before filter

 function verifica_SS($i){
    	/**
    	 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
    	 * para ser insertados en todas las tablas.
    	 * */
    	switch ($i){
    		case 1:return $this->Session->read('SScodpresi');break;
    		case 2:return $this->Session->read('SScodentidad');break;
    		case 3:return $this->Session->read('SScodtipoinst');break;
    		case 4:return $this->Session->read('SScodinst');break;
    		case 5:return $this->Session->read('SScoddep');break;
    		case 6:return $this->Session->read('entidad_federal');break;
    		default:
    		   return "NULO";


    	}//fin switch
    }//fin verifica_SS

    function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }
         return $sql_re;
    }//fin funcion SQLCA

        function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;


    }//fin funcion SQLX


    function SQLCAX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_republica=".$this->verifica_SS(1)."  and    ";
         return $sql_re;
    }//fin funcion SQLCA

       function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;

}//fin zero



function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}

		$this->set($nomVar, $cod);

	}
}//fin concatena

function index($var=null){
	$this->layout = "ajax";
	$datos = $this->shd100_actividades->findAll($this->SQLCA(),null,'cod_actividad ASC');
    $this->set("datos",$datos);
    /*$ss=$this->shd100_actividades->findAll($this->SQLCA(),array('cod_actividad'),'cod_actividad DESC',1,1,null);
 	if($ss==null){
    	$new_numero=1;
    }else{
    	$new_numero=$ss[0]["shd100_actividades"]["cod_actividad"]+1;
    }
    $this->set('numero',$new_numero);//$numero
    */
}//fin index



function procesar_unidades(){
	$this->layout = "ajax";
	$unidad = $this->shd100_actividades->execute("select * from cscd04_ordencompra_parametros where ".$this->SQLCA());
	$datos = $this->shd100_actividades->execute("select * from shd100_actividades where ".$this->SQLCA()." order by cod_actividad asc");
	if($datos!=null){
		for($i=0;$i<count($datos);$i++){
			$monto_minimo=($unidad[0][0]['unidad_tributaria']*$datos[$i][0]['unidades_tributarias']);
			$cod_actividad=$datos[$i][0]['cod_actividad'];
			$this->shd100_actividades->execute("UPDATE shd100_actividades SET minimo_tributable='$monto_minimo' where ".$this->SQLCA()." and cod_actividad='".$cod_actividad."'");
		}
		$this->set('Message_existe', 'Proceso exitoso');
	}else{
		$this->set('errorMessage', 'No existe información que procesar');

	}

	$this->index();
	$this->render("index");


}

function guardar(){
	$this->layout = "ajax";
	$cod_presi=$this->verifica_SS(1);
  	$cod_entidad=$this->verifica_SS(2);
  	$cod_tipo_inst=$this->verifica_SS(3);
  	$cod_inst=$this->verifica_SS(4);
  	$cod_dep=$this->verifica_SS(5);
	$codigo=$this->data['shp100_actividades']['codigo'];
	$denominacion=$this->data['shp100_actividades']['denominacion'];

	$alicuota= $this->Formato1($this->data['shp100_actividades']['alicuota']);
	if($alicuota==null){
		$alicuota=0;
	}
	$unidades=$this->Formato1($this->data['shp100_actividades']['unidades']);
	if($unidades==null){
		$unidades=0;
	}
	$minimo=$this->Formato1($this->data['shp100_actividades']['minimo']);
	if($minimo==null){
		$minimo=0;
	}

	$ver=$this->shd100_actividades->FindCount($this->SQLCA()." and cod_actividad='$codigo'");
	if($ver==0){
		$SQL_INSERT ="INSERT INTO shd100_actividades (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep,
	  				  cod_actividad, denominacion_actividad, alicuota, unidades_tributarias, minimo_tributable)";
		$SQL_INSERT .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,'".$codigo."','".$denominacion."',$alicuota,$unidades,$minimo)";
	    $resp=$this->shd100_actividades->execute($SQL_INSERT);
		if($resp>1){
	 		$this->set('Message_existe', 'Registro Agregado con exito.');
	 		$this->index();
			$this->render("index");
	  	}else if ($resp <= 1){
	  		$this->set('errorMessage', 'Disculpe, El Registro no fue creado.');
	  		$this->index();
	  		$this->render("index");
		}
	}else{
		$this->set('errorMessage', 'este registro ya existe creado');
		$this->index();
	  	$this->render("index");
	}
}

function bt_nav($Tfilas,$pagina){
	if($Tfilas==1){
    	$this->set('mostrarS',false);
    	$this->set('mostrarA',false);
    }else if($Tfilas==2){
     	if($pagina==2){
        	$this->set('mostrarS',false);
            $this->set('mostrarA',true);
        }else{
        	$this->set('mostrarS',true);
            $this->set('mostrarA',false);
        }
    }else if($Tfilas>=3){
    	if($pagina==$Tfilas){
        	$this->set('mostrarS',false);
            $this->set('mostrarA',true);
        }else if($pagina==1){
        	$this->set('mostrarS',true);
            $this->set('mostrarA',false);
        }else{
          	$this->set('mostrarS',true);
        	$this->set('mostrarA',true);
    	}
    }
}//fin navegacion

function editar($var1=null,$i=null){
	$this->layout = "ajax";
    $cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$condicion = $this->SQLCA();
	$var_datos = $this->shd100_actividades->findAll($condicion." and cod_actividad='".$var1."'", null, "denominacion_actividad ASC");
    $var2 = $var_datos[0]['shd100_actividades']['denominacion_actividad'];
    $var3 = $var_datos[0]['shd100_actividades']['alicuota'];
    if($var3==0){
    	$var_datos[0]['shd100_actividades']['alicuota']='';
    }
    $var4 = $var_datos[0]['shd100_actividades']['unidades_tributarias'];
    if($var4==0){
    	$var_datos[0]['shd100_actividades']['unidades_tributarias']='';
    }
    $var5 = $var_datos[0]['shd100_actividades']['minimo_tributable'];
    if($var5==0){
    	$$var_datos[0]['shd100_actividades']['minimo_tributable']='';
    }

	$this->set('datos',$var_datos);
	$this->set('k',$i);


	$this->set('Message_existe', 'PUEDE EDITAR LOS DATOS');
//	$this->render("funcion");
}//fin function

function guardar_editar($var1=null,$k){
	$this->layout = "ajax";
  	$denominacion      =  $this->data['shp100_actividades']['denominacion'.$k];

  	if($this->data['shp100_actividades']['alicuota'.$k]==null){
  		$alicuota=0;
  	}else{
  		$alicuota			 =	$this->Formato1($this->data['shp100_actividades']['alicuota'.$k]);
  	}

  	if($this->data['shp100_actividades']['unidades'.$k]==null){
  		$unidades=0;
  	}else{
  		$unidades			 =	$this->Formato1($this->data['shp100_actividades']['unidades'.$k]);
  	}


    if($this->data['shp100_actividades']['minimo'.$k]==null){
  		$minimo=0;
  	}else{
  		$minimo			 =	$this->Formato1($this->data['shp100_actividades']['minimo'.$k]);

  	}
    $sql = " UPDATE shd100_actividades SET denominacion_actividad='".$denominacion."', alicuota=".$alicuota.", unidades_tributarias=".$unidades.", minimo_tributable=".$minimo." where cod_actividad ='".$var1."' and ".$this->SQLCA();
	$this->shd100_actividades->execute($sql);
	$this->set('Message_existe', 'Datos Actualizados Correctamente');
	$this->index();
	$this->render("index");

}//fin funtion

 function eliminar($cod_actividad=null){
	$this->layout="ajax";

$ver=$this->shd100_solicitud_actividades->FindCount($this->SQLCA()." and cod_actividad='$cod_actividad'");
if($ver==0){
    if($cod_actividad!=null){
		$sql="DELETE FROM shd100_actividades WHERE cod_actividad='".$cod_actividad."' and ".$this->SQLCA();
		if($this->shd100_actividades->execute($sql)>1){
			$this->set('Message_existe','REGISTRO FUE ELIMINADO CORRECTAMENTE');
		}else{
		   $this->set('errorMessage','LO SIENTO, REGISTRO NO PUDO SER ELIMINADO');
		}
    }else{
    	$this->set('errorMessage','LO SIENTO, LOS DATOS NO LLEGARON CORRECTAMENTE Y NO SE PUDO PROCESAR LA ELIMINACI&Oacute;N');
    }

 }else{
 	$this->set('errorMessage','El dato que intenta eliminar esta siendo usado por otro programa');
 }

    $this->index();
	$this->render("index");


}


function cancelar($var=null){
		$this->layout="ajax";
	$this->index();
	$this->render("index");

}



function tributario($n=null,$var=null){
	$this->layout="ajax";
if($n=='no'){
	$n='';
}else{
	$n=$n;
}
$this->set('n',$n);

		///////UNIDAD TRIBUTARIA//////////
			$iva=$this->shd100_actividades->execute("select unidad_tributaria from cscd04_ordencompra_parametros where ".$this->SQLCA()." limit 1");
			$unidad=$iva[0][0]['unidad_tributaria'];
			$valor=($iva[0][0]['unidad_tributaria']*$this->Formato1($var));
			$this->set('unidad',$valor);
		//////////////////////



}




}
?>