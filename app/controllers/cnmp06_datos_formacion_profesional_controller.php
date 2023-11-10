<?php
 class Cnmp06DatosFormacionProfesionalController extends AppController {
   var $name = 'cnmp06_datos_formacion_profesional';
   var $uses = array('v_cnmd06_datos_formacion_profesional','cnmd06_cursos','cnmd06_datos_formacion_profesional','cnmd06_instituto_educativo','cnmd06_datos_personales');
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
 }

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

function SQLCAX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_republica=".$this->verifica_SS(1)."  and    ";
         return $sql_re;
    }//fin funcion SQLCA

function concatena($vector1=null, $nomVar=null){

	if($vector1 != null){

		foreach($vector1 as $x => $y){

			$cod[$x] = $this->zero($x).' - '.$y;
		}
		//print_r($cod);

		$this->set($nomVar, $cod);

	}
}//fin concatena

 function index($id=null){
 	$this->layout ="ajax";
 	$this->data = null;
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
    $listacurso=$this->cnmd06_cursos->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_cursos.cod_curso', '{n}.cnmd06_cursos.denominacion');
    $this->concatena_cuatro_digitos("", 'cod_curso');
    $listainstitucion=$this->cnmd06_instituto_educativo->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_instituto_educativo.cod_institucion', '{n}.cnmd06_instituto_educativo.denominacion');
    $this->concatena_cuatro_digitos("", 'cod_institucion');

     if($this->Session->read('cedula_pestana_expediente')==""){
         	$id=0;
    }else{
    	    $id=$this->Session->read('cedula_pestana_expediente');
    	 }//fin else

    	$this->set('cedula', "");
    	$Tfilas=$this->cnmd06_datos_personales->findCount("cedula_identidad=".$id);
       if($Tfilas!=0){
          $this->cedula($this->Session->read('cedula_pestana_expediente'));
          $this->render("cedula");
       }else{
       	    $this->set('ci',"");
		    $this->set('pa',"");
		    $this->set('sa',"");
		    $this->set('pn',"");
		    $this->set('sn',"");
      }//fin else
}//fin function



function cedula($ci=null){
	$this->layout = "ajax";
	$cond ="cedula_identidad=".$ci;
	$cond2="cedula=".$ci;
	$resul = 0;
	if($resul==0){
			$a = $this->cnmd06_datos_personales->findAll($cond);
		    $pa=$a[0]['cnmd06_datos_personales']['primer_apellido'];
		    $sa=$a[0]['cnmd06_datos_personales']['segundo_apellido'];
		    $pn=$a[0]['cnmd06_datos_personales']['primer_nombre'];
		    $sn=$a[0]['cnmd06_datos_personales']['segundo_nombre'];
		    $this->set('ci',$ci);
		    $this->set('pa',$pa);
		    $this->set('sa',$sa);
		    $this->set('pn',$pn);
		    $this->set('sn',$sn);
		    //$this->set('errorMessage', 'No se encontro informacion para esa cedula');
	}else{
            $pagina=1;
          	 $Tfilas=$this->v_cnmd06_datos_formacion_profesional->findCount($cond2);
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_cnmd06_datos_formacion_profesional->findAll($cond2,null,'cedula ASC',1,$pagina,null);
          	 }
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);


	}//fin else
}//fin function


function consultar($pagina=null){
 		$this->layout = "ajax";

  if($this->Session->read('cedula_pestana_expediente')==""){
     	$id=0;
}else{
	    $id=$this->Session->read('cedula_pestana_expediente');
}//fin else
      $Tfilas=$this->cnmd06_datos_personales->findCount("cedula_identidad=".$id);
   if($Tfilas!=0){
       $cond2="cedula=".$id;
   }else{
   	    $cond2="";
   }//fin else


         if($pagina!=null){
          	 $pagina=$pagina;
          	  $Tfilas=$this->v_cnmd06_datos_formacion_profesional->findCount($cond2);
          	 if($Tfilas==0){
          	 	$this->index();
          		//$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_cnmd06_datos_formacion_profesional->findAll($cond2,null,'cedula ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
             }
 }else{
 	       $pagina=1;
          	 $Tfilas=$this->v_cnmd06_datos_formacion_profesional->findCount($cond2);
          	 if($Tfilas==0){
          	 	$this->index();
          		//$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->v_cnmd06_datos_formacion_profesional->findAll($cond2,null,'cedula ASC',1,$pagina,null);
          	 }
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);
		}
}//fin function




function codi_curso($codigo=null){
	$this->layout = "ajax";
	if($codigo!=null){
		$a = $this->cnmd06_cursos->findAll("cod_curso=".$codigo,array('cod_curso','denominacion'));
   		$this->set("a",$a[0]['cnmd06_cursos']['cod_curso']);
	}else{
		$this->set("a",'');
	}

}

function deno_curso($codigo=null){
	$this->layout = "ajax";
	if($codigo!=null){
		$b = $this->cnmd06_cursos->findAll("cod_curso=".$codigo,array('cod_curso','denominacion'));
		$this->set("b",$b[0]['cnmd06_cursos']['denominacion']);
	}else{
		$this->set("b",'');
	}

}

function codi_institucion($codigo=null){
	$this->layout = "ajax";
	if($codigo!=null){
		$a = $this->cnmd06_instituto_educativo->findAll("cod_institucion=".$codigo,array('cod_institucion','denominacion'));
   		$this->set("a",$a[0]['cnmd06_instituto_educativo']['cod_institucion']);
	}else{
		$this->set("a",'');
	}

}

function deno_institucion($codigo=null){
	$this->layout = "ajax";
	if($codigo!=null){
		$b = $this->cnmd06_instituto_educativo->findAll("cod_institucion=".$codigo,array('cod_institucion','denominacion'));
		$this->set("b",$b[0]['cnmd06_instituto_educativo']['denominacion']);
	}else{
		$this->set("b",'');
	}
}


function guardar(){
	$this->layout = "ajax";
	//pr($this->data);
	if(!empty($this->data)){
	 $cedula=$this->data['cnmp06_datos_formacion_profesional']['cedula'];
	 $cod_curso=$this->data['cnmp06_datos_formacion_profesional']['cod_curso'];
	 $cod_institucion=$this->data['cnmp06_datos_formacion_profesional']['cod_institucion'];
	 $duracion=$this->data['cnmp06_datos_formacion_profesional']['duracion'];
	 $desde=$this->data['cnmp06_datos_formacion_profesional']['desde'];
	 $hasta=$this->data['cnmp06_datos_formacion_profesional']['hasta'];
	 $observaciones=$this->data['cnmp06_datos_formacion_profesional']['observaciones'];
	 $ss=$this->cnmd06_datos_formacion_profesional->findAll(null,array('consecutivo'),'consecutivo DESC',1,1,null);
 		 if($ss==null){
     	$consecutivo=1;
     }else{
     	$consecutivo=$ss[0]["cnmd06_datos_formacion_profesional"]["consecutivo"]+1;
     }
	}

	$SQL_INSERT ="INSERT INTO cnmd06_datos_formacion_profesional (cedula,cod_curso,consecutivo,cod_institucion,duracion,desde,hasta,observaciones)";
	 $SQL_INSERT .=" VALUES ($cedula, $cod_curso,$consecutivo,$cod_institucion,'".$duracion."','".$desde."','".$hasta."', '".$observaciones."')";
	 $resp=$this->cnmd06_datos_formacion_profesional->execute($SQL_INSERT);
	    if($resp>1){
		 $this->set('Message_existe', 'Registro Agregado con exito.');
		 $this->index();
		 //$this->render("index");//echo "si entro";
  }else if ($resp <= 1){
		  $this->set('errorMessage', 'Disculpe, El Registro no fue creado.');
		  $this->index();
		  //$this->render("index");//echo "no entro";
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



function modificar($cedula=null,$consecutivo=null,$cod_curso=null){
	  		$this->layout = "ajax";
          	$cond ="cedula=".$cedula." and consecutivo=".$consecutivo." and cod_curso=".$cod_curso;
          	$datos=$this->v_cnmd06_datos_formacion_profesional->findAll($cond);


	       	$listacurso=$this->cnmd06_cursos->generateList("cod_curso=".$datos[0]["v_cnmd06_datos_formacion_profesional"]["cod_curso"], 'denominacion ASC', null, '{n}.cnmd06_cursos.cod_curso', '{n}.cnmd06_cursos.denominacion');
		    $this->concatena_cuatro_digitos($listacurso, 'cod_cursos');


		    $listainstitucion=$this->cnmd06_instituto_educativo->generateList("cod_institucion=".$datos[0]["v_cnmd06_datos_formacion_profesional"]["cod_institucion"], 'denominacion ASC', null, '{n}.cnmd06_instituto_educativo.cod_institucion', '{n}.cnmd06_instituto_educativo.denominacion');
		    $this->concatena_cuatro_digitos($listainstitucion, 'cod_instituciones');
          	$this->set('datos',$datos);
}

function guardar_modificar($cedula=null,$consecutivo=null,$cod_cursos=null){
	$this->layout = "ajax";
	if(!empty($this->data)){
	 $cod_curso=$this->data['cnmp06_datos_formacion_profesional']['cod_curso'];
	 $cod_institucion=$this->data['cnmp06_datos_formacion_profesional']['cod_institucion'];
	 $duracion=$this->data['cnmp06_datos_formacion_profesional']['duracion'];
	 $desde=$this->data['cnmp06_datos_formacion_profesional']['desde'];
	 $hasta=$this->data['cnmp06_datos_formacion_profesional']['hasta'];
	 $observaciones=$this->data['cnmp06_datos_formacion_profesional']['observaciones'];
	 $cond ="cedula=".$cedula." and consecutivo=".$consecutivo." and cod_curso=".$cod_cursos;
	 $sql="update cnmd06_datos_formacion_profesional set cod_curso=$cod_curso, cod_institucion=$cod_institucion,duracion='".$duracion."', desde='".$desde."', hasta='".$hasta."', observaciones='".$observaciones."' where ". $cond;
     $vvv=$this->cnmd06_datos_formacion_profesional->execute($sql);
     $this->data=null;
     $this->set('Message_existe', 'Registro Modificado con exito.');
	 $this->consultar();
     $this->render("consultar");


	}
}









function expediente(){

		if($this->Session->read('cedula_pestana_expediente')==""){
		         	$id=0;
		}else{
		    	    $id=$this->Session->read('cedula_pestana_expediente');
		}//fin else

		    $y=$this->cnmd06_datos_formacion_profesional->findCount("cedula=".$id);

				    if($y==0){
				            $this->index();
				    }else{
				            $this->consultar();
				            $this->render("consultar");
				    }//fin else
}//fin function












function infomacion_faltante($var1=null, $var2=null){

$this->layout = "ajax";

$var3 = "";

		switch($var1){
                case "curso":{                $this->set('userTable', $this->requestAction('/cnmp06_cursos/', array('return')));  }break;
                case "instituto_educativo":{  $this->set('userTable', $this->requestAction('/cnmp06_instituto_educativo/', array('return')));  }break;
		 }//fin

$this->set('opcion',     $var1);
$this->set('capa',       $var2);
$this->set('controlador',$var3);

}//fin function



function select_cambio($var1=null, $var2=null, $var3=null){

$this->layout = "ajax";





	switch($var1){
                case "curso":{
                	$listacurso=$this->cnmd06_cursos->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_cursos.cod_curso', '{n}.cnmd06_cursos.denominacion');
	                $this->concatena_cuatro_digitos("", 'lista');
	                $this->set("name", "cod_curso");
                }break;

                case "instituto_educativo":{
                	$listainstitucion=$this->cnmd06_instituto_educativo->generateList(null, 'denominacion ASC', null, '{n}.cnmd06_instituto_educativo.cod_institucion', '{n}.cnmd06_instituto_educativo.denominacion');
	                $this->concatena_cuatro_digitos("", 'lista');
	                $this->set("name", "cod_institucion");
                }break;

		 }//fin






}//fin function










function concatena_tres_digitos($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){
						      if($x<99 && $x>9){
						$cod[$x] = $extra.'0'.$x.' - '.$y;
				    }else if($x<=9){
						$cod[$x] = $extra.'00'.$x.' - '.$y;
					}else{
						$cod[$x] = $extra.''.$x.' - '.$y;
					}

			}else{
				      if($x<99 && $x>9){
					$cod[$x] = '0'.$x.' - '.$y;
			    }else if($x<=9){
					$cod[$x] = '00'.$x.' - '.$y;
				}else{
					$cod[$x] = ''.$x.' - '.$y;
				}
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function






function concatena_cuatro_digitos($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){
						  if($x<=999 && $x>99){
					    $cod[$x] = $extra.'0'.$x.' - '.$y;
			     	}else if($x<=99 && $x>9){
						$cod[$x] = $extra.'00'.$x.' - '.$y;
				    }else if($x<=9){
						$cod[$x] = $extra.'000'.$x.' - '.$y;
					}else{
						$cod[$x] = $extra.''.$x.' - '.$y;
					}

			}else{
				      if($x<=999 && $x>99){
					$cod[$x] = '0'.$x.' - '.$y;
				}else if($x<=99 && $x>9){
					$cod[$x] = '00'.$x.' - '.$y;
			    }else if($x<=9){
					$cod[$x] = '000'.$x.' - '.$y;
				}else{
					$cod[$x] = ''.$x.' - '.$y;
				}
			}
		}

	}

	$this->set($nomVar, $cod);
}//fin function








function buscar_pista($var1=null, $var2=null){
$this->layout = "ajax";
        switch($var1){
                case "curso":{
                	$listacurso=$this->cnmd06_cursos->generateList("upper(quitar_acentos(denominacion)) LIKE upper(quitar_acentos('%$var2%'))", 'denominacion ASC', null, '{n}.cnmd06_cursos.cod_curso', '{n}.cnmd06_cursos.denominacion');
	                $this->concatena_cuatro_digitos($listacurso, 'lista');
	                $this->set("name", "cod_curso");
                }break;

                case "instituto_educativo":{
                	$listainstitucion=$this->cnmd06_instituto_educativo->generateList("upper(quitar_acentos(denominacion)) LIKE upper(quitar_acentos('%$var2%'))", 'denominacion ASC', null, '{n}.cnmd06_instituto_educativo.cod_institucion', '{n}.cnmd06_instituto_educativo.denominacion');
	                $this->concatena_cuatro_digitos($listainstitucion, 'lista');
	                $this->set("name", "cod_institucion");
                }break;

		 }//fin
 $this->render("select_cambio");
}//fin function





function eliminar($cedula=null,$consecutivo=null,$cod_curso=null){
	$this->layout = "ajax";
	$cond ="cedula=".$cedula." and consecutivo=".$consecutivo." and cod_curso=".$cod_curso;
		$sql1 ="DELETE  FROM  cnmd06_datos_formacion_profesional where ".$cond;
		$this->cnmd06_datos_formacion_profesional->execute($sql1);
		$this->set('Message_existe', 'Dato Eliminado con exito.');
	  $y=$this->cnmd06_datos_formacion_profesional->findCount();
	  if($y!=0){
	  $this->consultar();
      $this->render("consultar");
		}else if($y==0){
			$this->index();
      		//$this->render("index");
		}//fin if
}
//fin eliminar
}