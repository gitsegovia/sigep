<?php
 class cepp01CodigosRetencionIslrController extends AppController{
	var $uses = array('cepd01_codigos_retencion_islr');
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
	$datos = $this->cepd01_codigos_retencion_islr->findAll(null,null,'codigo_retencion, cod_escala ASC');
    $this->set("datos",$datos);
}//fin index






function guardar(){
	$this->layout = "ajax";


	$codigo_retencion          = $this->data['cepp01_codigos_retencion_islr']['codigo_retencion'];
	$cod_escala                = $this->data['cepp01_codigos_retencion_islr']['cod_escala'];
	$denominacion_actividad    = $this->data['cepp01_codigos_retencion_islr']['denominacion_actividad'];
	$denominacion_escala       = $this->data['cepp01_codigos_retencion_islr']['denominacion_escala'];
	$porcentaje= $this->Formato1($this->data['cepp01_codigos_retencion_islr']['porcentaje']);
	if($porcentaje==null){
		$porcentaje=0;
	}

   if($cod_escala==""){$cod_escala=0;}

   if($cod_escala==0){
   	 $denominacion_escala = "";
   }else{
//   	 $denominacion_actividad = "";
   }



	$ver=$this->cepd01_codigos_retencion_islr->FindCount(" codigo_retencion='".$codigo_retencion."'  and cod_escala='".$cod_escala."'    ");
	if($ver==0){
		$SQL_INSERT ="INSERT INTO cepd01_codigos_retencion_islr (codigo_retencion, cod_escala, denominacion_actividad, porcentaje, denominacion_escala)";
		$SQL_INSERT .=" VALUES ('".$codigo_retencion."','".$cod_escala."','".$denominacion_actividad."','".$porcentaje."','".$denominacion_escala."')";
	    $resp=$this->cepd01_codigos_retencion_islr->execute($SQL_INSERT);
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







function editar($var1=null, $var2=null, $i=null){
	$this->layout = "ajax";


	   $var_datos = $this->cepd01_codigos_retencion_islr->findAll("  codigo_retencion='".$var1."'  and  cod_escala='".$var2."'     ", null, "codigo_retencion, cod_escala ASC");

       $var3 = $var_datos[0]['cepd01_codigos_retencion_islr']['cod_escala'];
    if($var3==0){
       $var_datos[0]['cepd01_codigos_retencion_islr']['cod_escala']='';
    }

    $var3 = $var_datos[0]['cepd01_codigos_retencion_islr']['denominacion_escala'];
    if($var3=="0"){
       $var_datos[0]['cepd01_codigos_retencion_islr']['denominacion_escala']='';
    }

	$this->set('datos',$var_datos);
	$this->set('k',$i);

}//fin function













function guardar_editar($var1=null, $var2=null,  $k=null){
	$this->layout = "ajax";

  	$denominacion_actividad   =  $this->data['cepp01_codigos_retencion_islr']['denominacion_actividad'.$k];
  	$denominacion_escala      =  $this->data['cepp01_codigos_retencion_islr']['denominacion_escala'.$k];
    if($this->data['cepp01_codigos_retencion_islr']['porcentaje'.$k]==null){
  		$porcentaje=0;
  	}else{
  		$porcentaje			 =	$this->Formato1($this->data['cepp01_codigos_retencion_islr']['porcentaje'.$k]);
  	}

  	if($var2==null || $var2==0){
         $denominacion_escala = "";
  	}


    $sql = " UPDATE cepd01_codigos_retencion_islr SET denominacion_actividad='".$denominacion_actividad."', denominacion_escala='".$denominacion_escala."', porcentaje='".$porcentaje."' where codigo_retencion ='".$var1."' and cod_escala='".$var2."' ";
	$this->cepd01_codigos_retencion_islr->execute($sql);
	$this->set('Message_existe', 'Datos Actualizados Correctamente');
	$this->index();
	$this->render("index");

}//fin funtion













function eliminar($codigo_retencion=null, $cod_escala=null){
	$this->layout="ajax";
			$ver = $this->cepd01_codigos_retencion_islr->FindCount("  codigo_retencion='".$codigo_retencion."'  and cod_escala='".$cod_escala."'  ");
			if($ver==0){
			    if($codigo_retencion!=null){
					$sql="DELETE FROM cepd01_codigos_retencion_islr WHERE codigo_retencion='".$codigo_retencion."'  and cod_escala='".$cod_escala."' ";
					if($this->cepd01_codigos_retencion_islr->execute($sql)>1){
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
}//fin function










function cancelar($var=null){
    $this->layout="ajax";
	$this->index();
	$this->render("index");

}






}//fin class
?>