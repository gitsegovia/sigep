<?php
class Catp01TipoConstruccionController extends AppController{
    var $uses = array('cscd04_ordencompra_parametros','catd01_ano_ordenanza','cugd90_municipio_defecto','cugd01_republica','cugd01_estados','cugd01_municipios','ccfd04_cierre_mes','catd01_valor_construccion');
    var $helpers = array('Html', 'Javascript', 'Ajax','Sisap');

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

}

function verifica_SS($i){
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

function SQLCA_S($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
				 $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
				 $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
				 $sql_re .= "cod_inst=".$this->verifica_SS(4)."   ";
				 return $sql_re;
}//fin funcion SQLCA

function SQLCAIN($ano=null){//sql para busqueda de codigos de arranque con y sin año
		 $sql_re = $this->verifica_SS(1).",";
		 $sql_re .= $this->verifica_SS(2).",";
		 $sql_re .=  $this->verifica_SS(3).",";
		 $sql_re .= $this->verifica_SS(4).",";
		 if($ano!=null){
			 $sql_re .= $this->verifica_SS(5).",";
				$sql_re .= $ano."";
		 }else{
			 $sql_re .=  $this->verifica_SS(5)."";
		 }
		 return $sql_re;
}//fin funcion SQLCAIN

function index(){
	$this->layout  = "ajax";
	$ano_actual = $this->catd01_ano_ordenanza->ano_actual($this->SQLCA());
	$this->set('ano_actual',$ano_actual);

    $this->Session->write('ano_ordenanza',$ano_actual);
    $lista=  $this->catd01_valor_construccion->generateList($this->SQLCA()." and ano_ordenanza=".$ano_actual, 'cod_tipo_construccion ASC', null, '{n}.catd01_valor_construccion.cod_tipo_construccion', '{n}.catd01_valor_construccion.denominacion_tipo');
	$this->concatena_sin_cero($lista,'vector');
    $this->set("modelo","catd01_valor_construccion");

    $rs=$this->catd01_valor_construccion->findAll($this->SQLCA()." and ano_ordenanza=".$ano_actual,null,'cod_tipo_construccion,cod_tipo_caracteristica ASC');
    $this->set("data_tipo",$rs);


}//index

function select_cod_tipo ($var=null) {
   $this->layout="ajax";
   if(isset($var) && $var!=null){
       if($var=='AGREGAR'){
       	  $this->set('tipo','input');
          $rs=$this->cscd04_ordencompra_parametros->findAll($this->SQLCA(),null,'unidad_tributaria');
          $this->set("unidad_t",$rs[0]['cscd04_ordencompra_parametros']['unidad_tributaria']);
       }else{
       	  $this->set('tipo','select');
       	  $this->set('seleccion',$var);
       	  $ano_actual = $this->Session->read('ano_ordenanza');
       	  $rs=$this->catd01_valor_construccion->findAll($this->SQLCA()." and ano_ordenanza=".$ano_actual." and cod_tipo_construccion='$var'",null,'cod_tipo_construccion ASC');
    	  $this->set("denominacion_tipo",$rs[0]['catd01_valor_construccion']['denominacion_tipo']);
    	  $lista=  $this->catd01_valor_construccion->generateList($this->SQLCA()." and ano_ordenanza=".$ano_actual, 'cod_tipo_construccion ASC', null, '{n}.catd01_valor_construccion.cod_tipo_construccion', '{n}.catd01_valor_construccion.denominacion_tipo');
		  $this->concatena_sin_cero($lista,'vector');
		  $rs_u=$this->catd01_valor_construccion->findAll($this->SQLCA()." and ano_ordenanza=".$ano_actual." and cod_tipo_construccion='$var'",null,'cod_tipo_caracteristica DESC');
          $this->set('cod_tipo_caracteristica',$rs_u[0]['catd01_valor_construccion']['cod_tipo_caracteristica']+1);

       }
   }

}//fin funcion select_cod_tipo

function guardar () {
   $this->layout="ajax";
   $modelo_form="catp01_tipo_construccion";
   if(isset($this->data[$modelo_form])){
      if(!empty($this->data[$modelo_form]["ano_ordenanza"]) && !empty($this->data[$modelo_form]["cod_tipo"])  && !empty($this->data[$modelo_form]["deno_tipo"])){
           $ano_ordenanza=$this->data[$modelo_form]["ano_ordenanza"];
            $cod[0]=$this->data[$modelo_form]["cod_tipo"];
			$cod[1]=$this->data[$modelo_form]["cod_caracteristicas"]==''?0:$this->data[$modelo_form]["cod_caracteristicas"];
			$cod[2]=$this->data[$modelo_form]["deno_tipo"];
			$cod[3]=$this->data[$modelo_form]["cara_tipo"];
			$this->data[$modelo_form]["valor_tipo"] = $this->data[$modelo_form]["valor_tipo"]!=''?$this->data[$modelo_form]["valor_tipo"]:0;
			$this->data[$modelo_form]["valor_utm"] = $this->data[$modelo_form]["valor_utm"]!=''?$this->data[$modelo_form]["valor_utm"]:0;
			$cod[4]=$this->Formato1($this->data[$modelo_form]["valor_tipo"]);
			$cod[5]=$this->Formato1($this->data[$modelo_form]["valor_ut"]);
			$cod[6]=$this->Formato1($this->data[$modelo_form]["valor_utm"]);

	        if($this->catd01_valor_construccion->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_construccion='".$cod[0]."' and cod_tipo_caracteristica='".$cod[1]."'")==0){
	            $rs=$this->catd01_valor_construccion->execute("INSERT INTO catd01_valor_construccion VALUES (".$this->SQLCAIN().",".$ano_ordenanza.",'".$cod[0]."',".$cod[1].",'".$cod[2]."','".$cod[3]."','".$cod[4]."','".$cod[5]."','".$cod[6]."');");
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fuerón Guardados Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fuerón Guardados");
                }
	        }else{
	        	 $this->set("errorMessage","El Código Tipo ya se encuentra registrado");
	        }//coun
	        //$rs=$this->catd01_valor_construccion->findAll($this->SQLCA()." and ano_ordenanza=".$ano_actual,null,'cod_tipo_construccion,cod_tipo_caracteristica ASC');
	        $rs=$this->catd01_valor_construccion->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza,null,'cod_tipo_construccion,cod_tipo_caracteristica ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_valor_construccion");
	        $ale=rand();
	        $lista=  $this->catd01_valor_construccion->generateList($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and $ale=$ale", 'cod_tipo_construccion ASC', null, '{n}.catd01_valor_construccion.cod_tipo_construccion', '{n}.catd01_valor_construccion.denominacion_tipo');
	        $this->concatena_sin_cero($lista,'vector');


          	$ct = $this->data[$modelo_form]["cara_tipo"];
          	$vt = $this->data[$modelo_form]["valor_tipo"];
          	if($ct=='' && $vt!=''){
          		$this->set('si_sel',true);
          		$this->set('n_cod_tipo_caracteristica','');
          	}else{
          		$this->set('si_sel',false);
          		$varxx=$this->data[$modelo_form]["cod_tipo"];
	        	$ano_actual = $this->Session->read('ano_ordenanza');
	        	$rs_u=$this->catd01_valor_construccion->findAll($this->SQLCA()." and ano_ordenanza=".$ano_actual." and cod_tipo_construccion='$varxx'",null,'cod_tipo_caracteristica DESC');
          		$this->set('n_cod_tipo_caracteristica',mascara($rs_u[0]['catd01_valor_construccion']['cod_tipo_caracteristica']+1,2));
          	}



      }//fin if empty
   }//if isset
}//fin guardar

function mostrar_tipos ($ano_ordenanza=null) {
   $this->layout="ajax";
            if(!isset($ano_ordenanza) || empty($ano_ordenanza)){
                 $ano_ordenanza=date("Y");
            }
	        $rs=$this->catd01_valor_construccion->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza,null,'cod_tipo_construccion ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_valor_construccion");

}//fin mostrar tipos

function agregar_detalle_tipo($var=null) {
	$this->layout="ajax";
	if(isset($_SESSION["contador_tipo"])){
        $_SESSION["contador_tipo"]=$_SESSION["contador_tipo"]+1;
	}else{
		$_SESSION["contador_tipo"]=1;
	}
	$modelo_form="catp01_tipo_construccion";
	//pr($this->data[$modelo_form]);
	if(isset($this->data[$modelo_form]) && !empty($this->data[$modelo_form]["cod_tipo"]) && !empty($this->data[$modelo_form]["deno_tipo"]) && !empty($this->data[$modelo_form]["cara_tipo"]) && !empty($this->data[$modelo_form]["valor_tipo"])){
            $cod[0]=$this->data[$modelo_form]["cod_tipo"];
			$cod[1]=$this->data[$modelo_form]["deno_tipo"];
			$cod[2]=$this->data[$modelo_form]["cara_tipo"];
			$cod[3]=$this->data[$modelo_form]["valor_tipo"];


		    if(isset($_SESSION["i_tipo"])){
				$i=$this->Session->read("i_tipo")+1;
				$this->Session->write("i_tipo",$i);
	    	}else{
		   		$this->Session->write("i_tipo",0);
				$i=0;
			}

			 $vec[$i][0]=$cod[0];
			 $vec[$i][1]=$cod[1];
			 $vec[$i][2]=$cod[2];
			 $vec[$i][3]=$cod[3];
			 $vec[$i]["id"]=$i;
			 if(isset($_SESSION["items_tipo"])){
						foreach($_SESSION["items_tipo"] as $codi){
            	           if($codi[0]==$cod[0]){
                              $est=true;
                              break;
            	           }else{
            	          	 $est=false;
            	           }
                        }//fin foreach
                        if($est==true){
            	          	$i=$this->Session->read("i_tipo")-1;
				            $this->Session->write("i_tipo",$i);
				            $_SESSION["contador_tipo"]=$_SESSION["contador_tipo"]-1;
				            $this->set('errorMessage', 'El código tipo ya existe en la lista');
                        }else{
                        	$_SESSION["items_tipo"]=$_SESSION["items_tipo"]+$vec;
                        }
			 }else{
				 $_SESSION["items_tipo"]=$vec;
			 }


		}//

		//pr($cod);

}//fin funcu¡ions

function eliminar_items ($cod_tipo,$ano_ordenanza,$cod_cara) {
	$this->layout = "ajax";
    $rs=$this->catd01_valor_construccion->execute("DELETE FROM catd01_valor_construccion WHERE ".$this->SQLCA()." and  cod_tipo_construccion='".$cod_tipo."' and ano_ordenanza=".$ano_ordenanza." and cod_tipo_caracteristica=$cod_cara");
    if($rs>1){
           	        $this->set("Message_existe","El Dato Fu&eacute; Eliminado Exitosamente");
    }else{
           	        $this->set("errorMessage","El Dato No Fu&eacute; Eliminado");
    }
}//fin eliminar_items

function editar_tipo ($cod_tipo,$ano_ordenanza,$cod_tipo_cara,$id_up,$id_fila,$tipo) {
	$this->layout = "ajax";
    $rs=$this->catd01_valor_construccion->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_construccion='".$cod_tipo."' and cod_tipo_caracteristica=$cod_tipo_cara");
    $this->set("cod_tipo",$rs[0]["catd01_valor_construccion"]["cod_tipo_construccion"]);
    $this->set("deno_tipo",$rs[0]["catd01_valor_construccion"]["denominacion_tipo"]);
    $this->set("cod_cara",$rs[0]["catd01_valor_construccion"]["cod_tipo_caracteristica"]);
    $this->set("cara_tipo",$rs[0]["catd01_valor_construccion"]["caracteristicas_basicas"]);
    $this->set("valor_ut",$rs[0]["catd01_valor_construccion"]["valor_ut"]);
    $this->set("valor_utm",$rs[0]["catd01_valor_construccion"]["valor_ut_m2"]);
    $this->set("valor_tipo",$rs[0]["catd01_valor_construccion"]["valor_m2"]);
    $this->set("ano",$ano_ordenanza);
    $this->set("i",$id_up);
    $this->set("id_fila",$id_fila);
    $this->set("tipo",$tipo);
}

function guardar_editar ($ano,$cod_tipo,$cod_cara,$id_up,$id_fila,$tipo) {
   $this->layout="ajax";
   $modelo_form="catp01_tipo_construccion";
   if(isset($this->data[$modelo_form])){
   	if($tipo==1){
      if(!empty($this->data[$modelo_form]["cod_caracteristicas"])  && !empty($this->data[$modelo_form]["cara_tipo_edt"])){
            $ano_ordenanza=$ano;
            $cod[0]=$this->data[$modelo_form]["cod_caracteristicas"];
			$cod[1]=$this->data[$modelo_form]["cara_tipo_edt"];
            //echo "hola";
            $xc=$this->catd01_valor_construccion->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_construccion='".$cod_tipo."' and cod_tipo_caracteristica=$cod_cara");
	        //echo $this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_construccion='".$cod[0]."' and cod_tipo_caracteristica=$cod_cara";
	        if($xc!=0){
	        	//echo " JGHA";
	            $rs=$this->catd01_valor_construccion->execute("UPDATE catd01_valor_construccion SET caracteristicas_basicas='".$cod[1]."' WHERE ".$this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_construccion='".$cod_tipo."' and cod_tipo_caracteristica=$cod_cara");
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fueron actualizado Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fueron actualizado");
                }
	        }else{
	        	 $this->set("errorMessage","El Codigo Tipo ya se encuentra registrado");

	        }//coun
	        $rando = rand();
	        $rs=$this->catd01_valor_construccion->findAll($this->SQLCA()." and $rando = $rando  and ano_ordenanza=".$ano_ordenanza." and cod_tipo_construccion='".$cod_tipo."' and cod_tipo_caracteristica=$cod_cara",null,'cod_tipo_construccion ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_valor_construccion");
      }//fin if empty
   	}else{
      if(!empty($this->data[$modelo_form]["cod_tipo_edt"])  && !empty($this->data[$modelo_form]["deno_tipo_edt"]) && !empty($this->data[$modelo_form]["valor_tipo_edt"])){
            $ano_ordenanza=$ano;
            $cod[0]=$this->data[$modelo_form]["cod_tipo_edt"];
			$cod[1]=$this->data[$modelo_form]["deno_tipo_edt"];
			$cod[2]=$this->Formato1($this->data[$modelo_form]["valor_tipo_edt"]);
			$cod[3]=$this->Formato1($this->data[$modelo_form]["valor_ut_edt"]);
			$cod[4]=$this->Formato1($this->data[$modelo_form]["valor_utm_edt"]);

	        if($this->catd01_valor_construccion->findCount($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_construccion='".$cod_tipo."' and cod_tipo_caracteristica=$cod_cara")!=0){
	            $rs=$this->catd01_valor_construccion->execute("UPDATE catd01_valor_construccion SET denominacion_tipo='".$cod[1]."' , valor_m2=".$cod[2].",valor_ut=".$cod[3].",valor_ut_m2=".$cod[4]." WHERE ".$this->SQLCA()." and ano_ordenanza=".$ano_ordenanza." and cod_tipo_construccion='".$cod_tipo."' and cod_tipo_caracteristica=$cod_cara");
                if($rs>1){
           	        $this->set("Message_existe","Los Datos Fuerón actualizado Exitosamente");
                }else{
           	        $this->set("errorMessage","Los Datos No Fuerón actualizado");
                }
	        }else{
	        	 $this->set("errorMessage","El Código Tipo ya se encuentra registrado");

	        }//coun
	        $rando = rand();
	        $rs=$this->catd01_valor_construccion->findAll($this->SQLCA()." and $rando = $rando and ano_ordenanza=".$ano_ordenanza." and cod_tipo_construccion='".$cod_tipo."' and cod_tipo_caracteristica=$cod_cara",null,'cod_tipo_construccion ASC');
	        $this->set("data_tipo",$rs);
	        $this->set("modelo","catd01_valor_construccion");
      }//fin if empty
   	}
   }//if isset
   $this->set("i",$id_up);
   $this->set("id_fila",$id_fila);
   $this->set("tipo",$tipo);

}//fin guardar editar

function cancelar_editar ($ano,$cod_tipo,$cod_cara,$id_up,$id_fila,$tipo) {
   $this->layout="ajax";
    $rs=$this->catd01_valor_construccion->findAll($this->SQLCA()." and ano_ordenanza=".$ano." and cod_tipo_construccion='".$cod_tipo."' and cod_tipo_caracteristica=$cod_cara");
    if($tipo==1){
    	$this->set("cod_tipo",'');
	    $this->set("deno_tipo",'');
	    $this->set("cod_cara",mascara($rs[0]["catd01_valor_construccion"]["cod_tipo_caracteristica"],2));
	    $this->set("cara_tipo",$rs[0]["catd01_valor_construccion"]["caracteristicas_basicas"]);
	    $this->set("valor_tipo",'');
	    $this->set("valor_ut",'');
	    $this->set("valor_utm",'');
    }else{
    	$this->set("cod_tipo",$rs[0]["catd01_valor_construccion"]["cod_tipo_construccion"]);
	    $this->set("deno_tipo",$rs[0]["catd01_valor_construccion"]["denominacion_tipo"]);
	    $this->set("cod_cara",'');
	    $this->set("cara_tipo",'');
	    $this->set("valor_ut",$this->Formato2($rs[0]["catd01_valor_construccion"]["valor_ut"]));
	    $this->set("valor_utm",$this->Formato2($rs[0]["catd01_valor_construccion"]["valor_ut_m2"]));
	    $this->set("valor_tipo",$this->Formato2($rs[0]["catd01_valor_construccion"]["valor_m2"]));
    }
    $this->set("cod_tipo1",$rs[0]["catd01_valor_construccion"]["cod_tipo_construccion"]);
    $this->set("cod_cara1",$rs[0]["catd01_valor_construccion"]["cod_tipo_caracteristica"]);




    $this->set("ano",$ano);
    $this->set("i",$id_up);
    $this->set("id_fila",$id_fila);
    $this->set("tipo",$tipo);
    $this->set("modelo","catd01_valor_construccion");

}//fin cancelar

function limpiar_lista () {
	$this->layout = "ajax";
	$this->Session->delete("items_tipo");
	$this->Session->delete("i_tipo");
	$this->Session->delete("contador_tipo");
}

function prueba () {
	$this->layout="ajax";
}

function reporte_tipo_valor_construccion($var=null){

	if($var!=null){
		if($var=='si'){// Se muestra la vista del formulario.
			$this->layout="ajax";
			$this->set('var',$var);
			$ano_ejec = $this->catd01_ano_ordenanza->ano_actual($this->SQLCA());
			if($ano_ejec==null){
				$ano_ejec = $this->ano_ejecucion();
			}
			$this->set('anio_ej',$ano_ejec);

		}elseif($var=='no'){// Se muestra la vista del reporte.
			$this->layout = "pdf";
			$ano_ordenanza = $this->data['catp01_tipo_construccion']['ano_ordenanza'];
			$ano_ordenanza == '' ? $ano_ordenanza = date('Y') : $ano_ordenanza;
			$datos=$this->catd01_valor_construccion->findAll($this->SQLCA()." and ano_ordenanza=".$ano_ordenanza,null,'cod_tipo_construccion,cod_tipo_caracteristica ASC');
			$_SESSION['ano_ordenanza_report']= $ano_ordenanza;
			$this->set('datos',$datos);
			$this->set('var',$var);
		}
	}
}//reporte_tipo_valor_construccion


function actualizar_ut(){
	$modelo="catd01_valor_construccion";

    $rs=$this->cscd04_ordencompra_parametros->findAll($this->SQLCA(),null,'unidad_tributaria');

    $datos=$this->$modelo->execute("UPDATE ".$modelo." SET valor_m2= valor_ut_m2 * ".$rs[0]['cscd04_ordencompra_parametros']['unidad_tributaria'].", valor_ut=".$rs[0]['cscd04_ordencompra_parametros']['unidad_tributaria']." WHERE ".$this->SQLCA()." and cod_tipo_caracteristica=0 and valor_ut_m2<>0 or valor_ut_m2=0 ");
    $datos=$this->$modelo->execute("UPDATE ".$modelo." SET  valor_ut=".$rs[0]['cscd04_ordencompra_parametros']['unidad_tributaria']." WHERE ".$this->SQLCA()." and cod_tipo_caracteristica=0 ");

    $this->set("Message_existe","La unidad tributaria fue actualizada Exitosamente");
    $this->index();
    $this->render('index');
}

}//fin class
?>
