<?php
/*
 * Fecha: 15/06/2007
 *
 * Por Luis Alfredo Diaz Jaramillo
 *
 * Herramienta utilizada: easyEclipse
 * cake
 */

 class Cfpp00Controller extends AppController{


 	var $uses = array('cfpd01_grupo', 'cfpd01_partida', 'cfpd01_generica', 'cfpd01_especifica', 'cfpd01_sub_espec', 'cfpd01_auxiliar','clasificador');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');


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


function AddCero($nomVar,$vector=object,$extra=null){
   	  if($vector!=null){
   	  	  if($extra==null){
   	  	foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]="0".$x;
        	}else{
	           $Var[$x]=$x;
        	}
	    }//fin each
   	  }else{
          foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]=$extra.".0".$x;
        	}else{
	           $Var[$x]=$extra."".$x;
        	}
	    }//fin each
   	  }
   	  $this->set($nomVar,$Var);
   	  }else{
   	  	  $this->set($nomVar,'');
   	  }



}//fin AddCero


function add_c_c($var){
		if($var<=9 && strlen($var)==1){
				$codigo = '0'.$var;
			}else{$codigo = ''.$var;}
		return $codigo;
}//fin AddCero


function index(){
    $this->layout = "ajax";
	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
   	$this->concatena_sin_cero( $this->cfpd01_grupo->generateList(null, 'cod_grupo ASC', null, '{n}.cfpd01_grupo.cod_grupo', '{n}.cfpd01_grupo.descripcion'), 'grupo');
}



function selec_grupo($var=null){

   $this->layout = "ajax";

   if($this->data['cfpp00']['codigo']){$var = $this->data['cfpp00']['codigo'];   $this->set('opcion', $var);


   }else{ $this->set('opcion', $var);  }


   $lista =  $this->cfpd01_grupo->generateList(null, 'cod_grupo ASC', null, '{n}.cfpd01_grupo.cod_grupo', '{n}.cfpd01_grupo.descripcion');

 $this->concatena_sin_cero($lista, 'grupo');




}








function selec_partida($var=null, $aux=null){
	$this->layout = "ajax";

if($this->data['cfpp00']['codigo'] &&  $var!=null){ $this->set('selecion', $this->data['cfpp00']['codigo']); }
if($var==null){ $var = $this->data['cfpp00']['codigo']; }
if($aux!=null){  $this->set('selecion', $aux);}


$this->set('opcion1', $var);

if($var!=null && $var!='otros'){

	$this->concatena($this->cfpd01_partida->generateList('where cod_grupo =  '.$var.' ', ' cod_partida ASC', null, '{n}.cfpd01_partida.cod_partida', '{n}.cfpd01_partida.descripcion'), "partida");

	}else{   $this->AddCero('partida', '');}

}






function selec_generica($var1=null, $var2=null , $aux=null){
    $this->layout = "ajax";

			if($this->data['cfpp00']['codigo']  &&  $var2!=null){ $this->set('selecion', $this->data['cfpp00']['codigo']); }
            if($var2==null){ $var2 = $this->data['cfpp00']['codigo'];}
			if($aux!=null){  $this->set('selecion', $aux);}

	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);

	if($var2!=null && $var2!='otros'){

	$this->concatena($this->cfpd01_generica->generateList('where cod_grupo =  '.$var1.'  and cod_partida = '.$var2.'', ' cod_generica ASC', null, '{n}.cfpd01_generica.cod_generica', '{n}.cfpd01_generica.descripcion'), 'generica');

	}else{   $this->AddCero('generica', ''); }

}









function selec_especifica($var1=null, $var2=null, $var3=null , $aux=null){
	$this->layout = "ajax";

if($this->data['cfpp00']['codigo']  &&  $var3!=null){ $this->set('selecion', $this->data['cfpp00']['codigo']); }
if($var3==null){ $var3 = $this->data['cfpp00']['codigo'];}
if($aux!=null){  $this->set('selecion', $aux);}


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);

	if($var3!=null && $var3!='otros'){

    $this->concatena($this->cfpd01_especifica->generateList('where cod_grupo =  '.$var1.'  and cod_partida = '.$var2.' and cod_generica = '.$var3.'', ' cod_especifica ASC', null, '{n}.cfpd01_especifica.cod_especifica', '{n}.cfpd01_especifica.descripcion'), 'especifica');

	}else{   $this->AddCero('especifica', ''); }

}










function selec_sub_especifica($var1=null, $var2=null, $var3=null, $var4=null, $aux=null) {
	$this->layout = "ajax";

if($this->data['cfpp00']['codigo']  &&  $var4!=null){ $this->set('selecion', $this->data['cfpp00']['codigo']); }
if($var4==null){ $var4 = $this->data['cfpp00']['codigo'];}
if($aux!=null){  $this->set('selecion', $aux);}


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);

	if($var4!=null && $var4!='otros'){

	$this->concatena($this->cfpd01_sub_espec->generateList('where cod_grupo =  '.$var1.'  and cod_partida = '.$var2.' and cod_generica = '.$var3.' and cod_especifica = '.$var4.'', ' cod_sub_espec ASC', null, '{n}.cfpd01_sub_espec.cod_sub_espec', '{n}.cfpd01_sub_espec.descripcion'), 'subespecifica');

	}else{   $this->AddCero('subespecifica', ''); }
}









function selec_auxiliar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $aux=null){
	$this->layout = "ajax";

if($this->data['cfpp00']['codigo']  &&  $var5!=null){ $this->set('selecion', $this->data['cfpp00']['codigo']); }
if($var5==null){ $var5 = $this->data['cfpp00']['codigo'];}
if($aux!=null){  $this->set('selecion', $aux);}

	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);

	if($var5!=null && $var5!='otros'){

	$this->concatena_cuatro_digitos( $this->cfpd01_auxiliar->generateList('where cod_grupo =  '.$var1.'  and cod_partida = '.$var2.' and cod_generica = '.$var3.' and cod_especifica = '.$var4.' and cod_sub_espec = '.$var5.'', ' cod_auxiliar ASC', null, '{n}.cfpd01_auxiliar.cod_auxiliar', '{n}.cfpd01_auxiliar.descripcion'), 'auxiliar');

	}else{   $this->AddCero('auxiliar', '');}

}








function principal($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

   	$this->layout = "ajax";
	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);

	$action='';
	$tabla = '';
	$sql_3 = '';

	if($var1=='otros'){$action=$var1; }
	if($var2=='otros'){$action=$var2; }
	if($var3=='otros'){$action=$var3; }
	if($var4=='otros'){$action=$var4; }
	if($var5=='otros'){$action=$var5; }
	if($var6=='otros'){$action=$var6; }


	if($var1!=null){ $sql_2 =  ' cod_grupo =  '.$var1.'  ';                       $tabla='cfpd01_grupo';   }
	if($var2!=null){ $sql_2 .= 'and cod_partida = '.$var2.'  ';              $tabla='cfpd01_partida';                             $sql_3 =  ' cod_grupo =  '.$var1.'  ';}
	if($var3!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';           $tabla='cfpd01_generica';                         $sql_3 .= 'and cod_partida = '.$var2.'  ';}
	if($var4!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';        $tabla='cfpd01_especifica';                       $sql_3 .= 'and cod_generica = '.$var3.'  ';}
	if($var5!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';          $tabla='cfpd01_sub_espec';               $sql_3 .= 'and cod_especifica = '.$var4.'  ';}
	if($var6!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_auxiliar';                              $sql_3.= 'and cod_sub_espec = '.$var5.' '; }

	$this->set('tabla', $tabla);


if($var1!=null && $action!='otros'){

       $sql_re = $sql_2;
	  $data = $this->$tabla->findAll($sql_re, null, null, null);

	  $this->set('datos_cod_cfpp00', $data);

}else if($var1!=null){

	  $sql_re = $sql_3;
	  $data = $this->$tabla->findAll($sql_re, null, null, null);

	  $this->set('datos_cod_cfpp00', $data);

}//fin else

 }//FIN FUNCTION







 function guardar($tabla=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

 	$this->layout = "ajax";



    if($var1==null){
    	$this->data['cfpp00']['cod_grupo'] = $this->data['cfpp00']['codigo'];
    	$var1 = $this->data['cfpp00']['codigo'];
	}else if($var2==null){
		$this->data['cfpp00']['cod_partida'] =$this->data['cfpp00']['codigo'];
		$var2 = $this->data['cfpp00']['codigo'];
	} else if($var3==null){
		$this->data['cfpp00']['cod_generica'] = $this->data['cfpp00']['codigo'];
		$var3 = $this->data['cfpp00']['codigo'];
	}else if($var4==null){
		$this->data['cfpp00']['cod_especifica'] = $this-> data['cfpp00']['codigo'];
		$var4 = $this->data['cfpp00']['codigo'];
	}else if($var5==null){
		$this->data['cfpp00']['cod_sub_espec'] = $this->data['cfpp00']['codigo'];
		$var5 = $this->data['cfpp00']['codigo'];
	}else if($var6==null){
		$this->data['cfpp00']['cod_auxiliar'] = $this->data['cfpp00']['codigo'];
		$var6 = $this->data['cfpp00']['codigo'];
	}


	 $descripcion = $this->data['cfpp00']['descripcion'];
	 $concepto = $this->data['cfpp00']['concepto'];

    $codigos = "";
	$values = "";



	if($var1!=null){
		        $codigos .= "cod_grupo, ";
				$values .=  " '".$var1."',  " ;
				$tabla='cfpd01_grupo';
	}

	if($var2!=null){
                 $codigos .= "cod_partida, ";
				$values .=  " '".$var2."',  ";
		        $tabla='cfpd01_partida';
	}

	if($var3!=null){    $codigos .= "cod_generica, ";   $values .=  " '".$var3."',  ";  $tabla='cfpd01_generica';                       }
	if($var4!=null){    $codigos .= "cod_especifica, ";   $values .=  " '".$var4."',  ";  $tabla='cfpd01_especifica';                    }
	if($var5!=null){    $codigos .= "cod_sub_espec, ";   $values .=  " '".$var5."',  ";   $tabla='cfpd01_sub_espec';            }
	if($var6!=null){     $codigos .= "cod_auxiliar, ";   $values .=  " '".$var6."',  ";    $tabla='cfpd01_auxiliar';                          }


	$sql_1 = "INSERT INTO  ".$tabla."   ( ".$codigos."  concepto, descripcion)   VALUES  ( ".$values."   '$concepto', '$descripcion' )  ";


	$sql = $sql_1;





	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);


	$tabla = '';
	$sql_2 = '';



	if($var1!=null){ $sql_2 =  ' cod_grupo =  '.$var1.'  ';                       $tabla='cfpd01_grupo';   }
	if($var2!=null){ $sql_2 .= 'and cod_partida = '.$var2.'  ';              $tabla='cfpd01_partida';                            }
	if($var3!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';           $tabla='cfpd01_generica';                        }
	if($var4!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';        $tabla='cfpd01_especifica';                      }
	if($var5!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';  $tabla='cfpd01_sub_espec';              }
	if($var6!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_auxiliar';                            }




if($tabla!=''){

  if ($this->$tabla->validates($this->data['cfpp00'])){

	  if($this->$tabla->findCount($sql_2) == 0){

	   		$this->$tabla->execute($sql);

			  if($var4!=null  && $var5==null){

			  	$sql_1 = "INSERT INTO  cfpd01_sub_espec   ( ".$codigos." cod_sub_espec,  concepto, descripcion)   VALUES  ( ".$values." '0' ,  '$concepto', '$descripcion' )  ";

			    $this->cfpd01_sub_espec->execute($sql_1);
			  }

			$this->set('errorMessage', 'Los Datos Fueron Guardados ');

	   }else{ $this->set('Message_existe', 'Este registro no fue almacenado porque ya existe');}

   }else{}


    $datos = $this->$tabla->findAll($sql_2, null, null, null);

	  $this->set('datos_cod_cfpp00', $datos);

	  $this->set('tabla', $tabla);



 }//fin if tabla






 }//FIN FUNCTION











 function editar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

 	$this->layout = "ajax";
	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);

	$action='';
	$tabla = '';
	$sql_2 = '';


	if($var1!=null){ $sql_2 =  ' cod_grupo =  '.$var1.'  ';                      $tabla='cfpd01_grupo';                             }
	if($var2!=null){ $sql_2 .= 'and cod_partida = '.$var2.'  ';              $tabla='cfpd01_partida';                           }
	if($var3!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';           $tabla='cfpd01_generica';                        }
	if($var4!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';         $tabla='cfpd01_especifica';                     }
	if($var5!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';        $tabla='cfpd01_sub_espec';                   }
	if($var6!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_auxiliar';                          }

	$this->set('tabla', $tabla);

	  $data = $this->$tabla->findAll($sql_2, null, null, null);

	  $this->set('datos_cod_cfpp00', $data);



 }




 function editar2($pagina=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

 	$this->layout = "ajax";
	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);

	$action='';
	$tabla = '';
	$sql_2 = '';


	if($var1!=null){ $sql_2 =  ' cod_grupo =  '.$var1.'  ';                      $tabla='cfpd01_grupo';                             }
	if($var2!=null){ $sql_2 .= 'and cod_partida = '.$var2.'  ';              $tabla='cfpd01_partida';                           }
	if($var3!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';           $tabla='cfpd01_generica';                        }
	if($var4!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';         $tabla='cfpd01_especifica';                     }
	if($var5!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';        $tabla='cfpd01_sub_espec';                   }
	if($var6!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_auxiliar';                          }

	$this->set('tabla', $tabla);
	$this->set('pagina_actual', $pagina);


	  $data = $this->$tabla->findAll($sql_2, null, null, null);

	  $this->set('datos_cod_cfpp00', $data);



 }








 function  guardar_editar($tabla=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null, $aux=null){

 	$this->layout = "ajax";

	 $descripcion = $this->data['cfpp00']['descripcion'];
	 $concepto = $this->data['cfpp00']['concepto'];


	$sql_1 = 'UPDATE '.$tabla.'   SET  concepto = \''.$concepto.'\', descripcion = \''.$descripcion.'\' WHERE ';

	if($var1!=null){
		$sql_2 =  ' cod_grupo =  '.$var1.'  ';
                $tabla='cfpd01_grupo';
	}
	if($var2!=null){
		$sql_2 .= 'and cod_partida = '.$var2.'  ';
		$tabla='cfpd01_partida';
	}
	if($var3!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';           $tabla='cfpd01_generica';                       }
	if($var4!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';        $tabla='cfpd01_especifica';                    }
	if($var5!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';  $tabla='cfpd01_sub_espec';            }
	if($var6!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_auxiliar';                          }


	$sql = $sql_1.$sql_2;

    $this->$tabla->execute($sql);

	$this->set('errorMessage', 'Los Datos Fueron Modificados');


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);


	$tabla = '';
	$sql_2 = '';



	if($var1!=null){ $sql_2 =  ' cod_grupo =  '.$var1.'  ';                       $tabla='cfpd01_grupo';   }
	if($var2!=null){ $sql_2 .= 'and cod_partida = '.$var2.'  ';              $tabla='cfpd01_partida';                            }
	if($var3!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';           $tabla='cfpd01_generica';                        }
	if($var4!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';        $tabla='cfpd01_especifica';                      }
	if($var5!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';  $tabla='cfpd01_sub_espec';              }
	if($var6!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_auxiliar';                            }


	  $data = $this->$tabla->findAll($sql_2, null, null, null);

	  $this->set('datos_cod_cfpp00', $data);

	  $this->set('tabla', $tabla);





 }//FIN FUNCTION







 function  guardar_editar2($pagina=null, $tabla=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null, $aux=null){

 	$this->layout = "ajax";

	 $descripcion = $this->data['cfpp00']['descripcion'];
	 $concepto = $this->data['cfpp00']['concepto'];


	$sql_1 = 'UPDATE '.$tabla.'   SET  concepto = \''.$concepto.'\', descripcion = \''.$descripcion.'\' WHERE ';

	if($var1!=null){
		$sql_2 =  ' cod_grupo =  '.$var1.'  ';
                $tabla='cfpd01_grupo';
	}
	if($var2!=null){
		$sql_2 .= 'and cod_partida = '.$var2.'  ';
		$tabla='cfpd01_partida';
	}
	if($var3!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';           $tabla='cfpd01_generica';                       }
	if($var4!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';        $tabla='cfpd01_especifica';                    }
	if($var5!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';  $tabla='cfpd01_sub_espec';            }
	if($var6!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_auxiliar';                          }


	$sql = $sql_1.$sql_2;

    $this->$tabla->execute($sql);

	$this->set('errorMessage', 'Los Datos Fueron Modificados');


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);


	$tabla = '';
	$sql_2 = '';



	if($var1!=null){ $sql_2 =  ' cod_grupo =  '.$var1.'  ';                       $tabla='cfpd01_grupo';   }
	if($var2!=null){ $sql_2 .= 'and cod_partida = '.$var2.'  ';              $tabla='cfpd01_partida';                            }
	if($var3!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';           $tabla='cfpd01_generica';                        }
	if($var4!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';        $tabla='cfpd01_especifica';                      }
	if($var5!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';  $tabla='cfpd01_sub_espec';              }
	if($var6!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_auxiliar';                            }


	  $data = $this->$tabla->findAll($sql_2, null, null, null);

	  $this->set('datos_cod_cfpp00', $data);

	  $this->set('tabla', $tabla);



$this->consulta_clasificador($pagina);
$this->render("consulta_clasificador");

 }//FIN FUNCTION






 function eliminar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

 	$this->layout = "ajax";



	 $tabla[1]='cfpd01_grupo';
	 $tabla[2]='cfpd01_partida';
	 $tabla[3]='cfpd01_generica';
	 $tabla[4]='cfpd01_especifica';
	 $tabla[5]='cfpd01_sub_espec';
	 $tabla[6]='cfpd01_auxiliar';

   $n_tabla = 0;
   $sql_2 = '';

	if($var1!=null){ $sql_2 .=  ' cod_grupo =  '.$var1.'  ';      $n_tabla++;}
	if($var2!=null){ $sql_2 .= ' and cod_partida = '.$var2.'  ';     $n_tabla++;}
	if($var3!=null){ $sql_2 .= ' and cod_generica = '.$var3.'  ';   $n_tabla++;}
	if($var4!=null){ $sql_2 .= ' and cod_especifica = '.$var4.'  ';      $n_tabla++;}
	if($var5!=null){ $sql_2  .= ' and cod_sub_espec = '.$var5.'  ';    $n_tabla++;}
	if($var6!=null){ $sql_2 .=' and cod_auxiliar = '.$var6.'  ';                $n_tabla++; }



	//for($i=$n_tabla; $i<=6; $i++){

   					$sql_1 = 'DELETE  FROM '.$tabla[$n_tabla].'   WHERE ';
					$sql = $sql_1.$sql_2.' ;';

					$this->$tabla[$n_tabla]->execute($sql);

	//	}//fin if

	//}//fin for


	$this->set('errorMessage', 'Los Datos Fueron Eliminados ');


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);


	$tabla = '';
	$sql_2 = '';



	if($var2!=null){ $sql_2 =  ' cod_grupo =  '.$var1.'  ';                       $tabla='cfpd01_grupo';   }
	if($var3!=null){ $sql_2 .= 'and cod_partida = '.$var2.'  ';              $tabla='cfpd01_partida';                            }
	if($var4!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';           $tabla='cfpd01_generica';                        }
	if($var5!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';        $tabla='cfpd01_especifica';                      }
	if($var6!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';  $tabla='cfpd01_sub_espec';              }
	//if($var7!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_auxiliar';                            }

	  if($sql_2 != ''){
	  	$data = $this->$tabla->findAll($sql_2, null, null, null);
	  	$this->set('datos_cod_cfpp00', $data);
	  	$this->set('tabla', $tabla);
	  }




 }//FIN FUNCTION















function eliminar2($pagina=null, $var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

 	$this->layout = "ajax";



	 $tabla[1]='cfpd01_grupo';
	 $tabla[2]='cfpd01_partida';
	 $tabla[3]='cfpd01_generica';
	 $tabla[4]='cfpd01_especifica';
	 $tabla[5]='cfpd01_sub_espec';
	 $tabla[6]='cfpd01_auxiliar';

   $n_tabla = 0;
   $sql_2 = '';

	if($var1!=null){ $sql_2 .=  ' cod_grupo =  '.$var1.'  ';      $n_tabla++;}
	if($var2!=null){ $sql_2 .= ' and cod_partida = '.$var2.'  ';     $n_tabla++;}
	if($var3!=null){ $sql_2 .= ' and cod_generica = '.$var3.'  ';   $n_tabla++;}
	if($var4!=null){ $sql_2 .= ' and cod_especifica = '.$var4.'  ';      $n_tabla++;}
	if($var5!=null){ $sql_2  .= ' and cod_sub_espec = '.$var5.'  ';    $n_tabla++;}
	if($var6!=null){ $sql_2 .=' and cod_auxiliar = '.$var6.'  ';                $n_tabla++; }



	//for($i=$n_tabla; $i<=6; $i++){

   					$sql_1 = 'DELETE  FROM '.$tabla[$n_tabla].'   WHERE ';
					$sql = $sql_1.$sql_2.' ;';

					$this->$tabla[$n_tabla]->execute($sql);

	//	}//fin if

	//}//fin for


	$this->set('errorMessage', 'Los Datos Fueron Eliminados ');


	$this->set('opcion1', $var1);
	$this->set('opcion2', $var2);
	$this->set('opcion3', $var3);
	$this->set('opcion4', $var4);
	$this->set('opcion5', $var5);
    $this->set('opcion6', $var6);


	$tabla = '';
	$sql_2 = '';



	if($var2!=null){ $sql_2 =  ' cod_grupo =  '.$var1.'  ';                       $tabla='cfpd01_grupo';   }
	if($var3!=null){ $sql_2 .= 'and cod_partida = '.$var2.'  ';              $tabla='cfpd01_partida';                            }
	if($var4!=null){ $sql_2 .= 'and cod_generica = '.$var3.'  ';           $tabla='cfpd01_generica';                        }
	if($var5!=null){ $sql_2 .= 'and cod_especifica = '.$var4.'  ';        $tabla='cfpd01_especifica';                      }
	if($var6!=null){ $sql_2.= 'and cod_sub_espec = '.$var5.'  ';  $tabla='cfpd01_sub_espec';              }
	//if($var7!=null){ $sql_2 .='and cod_auxiliar = '.$var6.'  ';                $tabla='cfpd01_auxiliar';                            }

	  if($sql_2 != ''){
	  	$data = $this->$tabla->findAll($sql_2, null, null, null);
	  	$this->set('datos_cod_cfpp00', $data);
	  	$this->set('tabla', $tabla);
	  }

if($pagina<=0){$pagina=1;}

$this->consulta_clasificador($pagina);
$this->render("consulta_clasificador");


 }//FIN FUNCTION

















 function consulta ($pag_num=null) {
 		$this->layout = "ajax";





		 $grupo = $this->cfpd01_grupo->findAll('cod_grupo=3', null, 'cod_grupo ASC', null,null, null);
		 $partida = $this->cfpd01_partida->findAll('cod_grupo=3', null, 'cod_partida ASC', null, null, null);
		 $generica = $this->cfpd01_generica->findAll('cod_grupo=3', null, 'cod_generica ASC', null, null, null);
		 $especifica = $this->cfpd01_especifica->findAll('cod_grupo=3', null, 'cod_especifica ASC', null,null, null);
		 $subespecifica = $this->cfpd01_sub_espec->findAll('cod_grupo=3', null, 'cod_sub_espec ASC', null, null, null);
		 $auxiliar = $this->cfpd01_auxiliar->findAll('cod_grupo=3', null, 'cod_auxiliar ASC', null,null, null);





$grupo_ver = '';
$partida_ver = '';
$generica_ver = '';
$especifica_ver = '';
$subespecifica_ver = '';
$auxiliar_ver = '';


$grupo_ver_aux = '';
$partida_ver_aux = '';
$generica_ver_aux = '';
$especifica_ver_aux = '';
$subespecifica_ver_aux = '';
$auxiliar_ver_aux = '';




$consulta = '';
$index = 0;


$i = 0;
$j = 0;
$k = 0;
$l = 0;
$n = 0;
$o = 0;


 foreach($grupo as $row){   $i++;

$grupo_ver[$i]  = $row['cfpd01_grupo']['cod_grupo'];

$grupo_ver_aux[$i] = $row['cfpd01_grupo']['cod_grupo'];


   $grupo_descripcion[$i] = $row['cfpd01_grupo']['descripcion'];
   $grupo_concepto[$i] =  $row['cfpd01_grupo']['concepto'];

}



 foreach($partida as $row){ $j++;

$partida_ver[$j] = $row['cfpd01_partida']['cod_grupo'].".".$this->add_c_c($row['cfpd01_partida']['cod_partida']);

$partida_ver_aux[$j] = $this->add_c_c($row['cfpd01_partida']['cod_partida']);

  $partida_descripcion[$j]=$row['cfpd01_partida']['descripcion'];
   $partida_concepto[$j] =  $row['cfpd01_partida']['concepto'];

 }

 foreach($generica as $row){ $k++;

$generica_ver[$k] =  $row['cfpd01_generica']['cod_grupo'].".".$this->add_c_c($row['cfpd01_generica']['cod_partida']).".".$this->add_c_c($row['cfpd01_generica']['cod_generica']);

$generica_ver_aux[$k] = $this->add_c_c($row['cfpd01_generica']['cod_generica']);

  $generica_descripcion[$k] =$row['cfpd01_generica']['descripcion'];
  $generica_concepto[$k] =  $row['cfpd01_generica']['concepto'] ;
 }


foreach($especifica as $row){ $l++;

$especifica_ver [$l]=  $row['cfpd01_especifica']['cod_grupo'].".".$this->add_c_c($row['cfpd01_especifica']['cod_partida']).".".$this->add_c_c($row['cfpd01_especifica']['cod_generica']).".".$this->add_c_c($row['cfpd01_especifica']['cod_especifica']);

 $especifica_ver_aux[$l] = $this->add_c_c($row['cfpd01_especifica']['cod_especifica']);

 $especifica_descripcion[$l] = $row['cfpd01_especifica']['descripcion'];
 $especifica_concepto[$l] = $row['cfpd01_especifica']['concepto'];

 }


 foreach($subespecifica as $row){ $n++;


$subespecifica_ver[$n] =  $row['cfpd01_sub_espec']['cod_grupo'].".".$this->add_c_c($row['cfpd01_sub_espec']['cod_partida']).".".$this->add_c_c($row['cfpd01_sub_espec']['cod_generica']).".".$this->add_c_c($row['cfpd01_sub_espec']['cod_especifica']).".".$this->add_c_c($row['cfpd01_sub_espec']['cod_sub_espec']);

 $subespecifica_ver_aux[$n] = $this->add_c_c($row['cfpd01_sub_espec']['cod_sub_espec']);

$subespecifica_descripcion[$n] =$row['cfpd01_sub_espec']['descripcion'];
$subespecifica_concepto[$n] =$row['cfpd01_sub_espec']['concepto'];

 }



 foreach($auxiliar as $row){ $o++;

$auxiliar_ver[$o] =  $row['cfpd01_auxiliar']['cod_grupo'].".".$this->add_c_c($row['cfpd01_auxiliar']['cod_partida']).".".$this->add_c_c($row['cfpd01_auxiliar']['cod_generica']).".".$this->add_c_c($row['cfpd01_auxiliar']['cod_especifica']).".".$this->add_c_c($row['cfpd01_auxiliar']['cod_sub_espec']).".".$this->add_c_c($row['cfpd01_auxiliar']['cod_auxiliar']);

$auxiliar_ver_aux[$o] = $this->add_c_c($row['cfpd01_auxiliar']['cod_auxiliar']);

$auxiliar_descripcion[$o] = $row['cfpd01_auxiliar']['descripcion'];
$auxiliar_concepto[$o] = $row['cfpd01_auxiliar']['concepto'];



 }




for($a=1; $a<=$i; $a++){

  						if($grupo_ver[$a]!=''){

						         $index++;
								 $consulta[$index]['codigo'] =  $grupo_ver[$a].'.00.00.00.00.00';
								 $consulta[$index]['descripcion'] =  $grupo_descripcion[$a];
                                 $consulta[$index]['concepto'] =   $grupo_concepto[$a];

								 }//fin if

   							$grupo_ver[$a]='';





	for($b=1; $b<=$j; $b++){

		  $aux  = $grupo_ver_aux[$a].'.'.$partida_ver_aux[$b];

	         if($aux == $partida_ver[$b] ){

						if($partida_ver[$b]!=''){

						     $index++;
						     $consulta[$index]['codigo'] =   $partida_ver[$b].'.00.00.00.00' ;
							 $consulta[$index]['descripcion'] =  $partida_descripcion[$b];
                             $consulta[$index]['concepto'] =   $partida_concepto[$b];

								  }//fin if


						$partida_ver[$b] = '';

						}


		for($c=1; $c<=$k; $c++){


			$aux  = $grupo_ver_aux[$a].'.'.$partida_ver_aux[$b].'.'.$generica_ver_aux[$c];

		if($aux == $generica_ver[$c] ){

						if($generica_ver[$c]!=''){

						      $index++;
							  $consulta[$index]['codigo'] =   $generica_ver[$c].'.00.00.00' ;
							  $consulta[$index]['descripcion'] =  $generica_descripcion[$c];
                              $consulta[$index]['concepto'] =   $generica_concepto[$c];

							}//fin if

						$generica_ver[$c] = '';


						}


			for($d=1; $d<=$l; $d++){

			$aux  = $grupo_ver_aux[$a].'.'.$partida_ver_aux[$b].'.'.$generica_ver_aux[$c].'.'.$especifica_ver_aux[$d];

			if($aux == $especifica_ver[$d] ){

					 if($especifica_ver[$d]!=''){

					      $index++;
						  $consulta[$index]['codigo'] =   $especifica_ver[$d].'.00.00';
						  $consulta[$index]['descripcion'] =  $especifica_descripcion[$d];
                          $consulta[$index]['concepto'] =   $especifica_concepto[$d];

						   }//fin if

  						 $especifica_ver[$d] = '';


						 }





				for($e=1; $e<=$n; $e++){

				$aux  = $grupo_ver_aux[$a].'.'.$partida_ver_aux[$b].'.'.$generica_ver_aux[$c].'.'.$especifica_ver_aux[$d].'.'.$subespecifica_ver_aux[$e];

				if($aux == $subespecifica_ver[$e] ){

						if($subespecifica_ver[$e]!= '') {

							   $index++;
							   $consulta[$index]['codigo'] =   $subespecifica_ver[$e].'.00' ;
							   $consulta[$index]['descripcion'] =  $subespecifica_descripcion[$e];
                               $consulta[$index]['concepto'] =   $subespecifica_concepto[$e];

							    }//fin if

						$subespecifica_ver[$e] = '';


						}





					for($f=1; $f<=$o; $f++){

					$aux  = $grupo_ver_aux[$a].'.'.$partida_ver_aux[$b].'.'.$generica_ver_aux[$c].'.'.$especifica_ver_aux[$d].'.'.$subespecifica_ver_aux[$e].'.'.$auxiliar_ver_aux[$f];

					if($aux == $auxiliar_ver[$f] ){

						if($auxiliar_ver[$f]!= '') {

						        $index++;
								$consulta[$index]['codigo'] =  $auxiliar_ver[$f];
								$consulta[$index]['descripcion'] =  $auxiliar_descripcion[$f];
                                $consulta[$index]['concepto'] =   $auxiliar_concepto[$f];

								 }//fin if

   							$auxiliar_ver[$f] = '';


							}
					}
				}
			}
		}
	}
}


$this->set('consulta', $consulta);
if($pag_num!=null){$this->set('pagina_actual', $pag_num);  }



 }//fin function consultar

function consulta_clasificador ($pagina=null) {
	$this->layout="ajax";
	if(isset($pagina)){
		$Tfilas=$this->clasificador->findCount();
        if($Tfilas!=0){
        	$data=$this->clasificador->findAll(null,null,"cod_grupo, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, tabla ASC",1,$pagina,null);

            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->set('ultimo',$Tfilas);
          	$this->set('pagina_actual',$pagina);
            $this->bt_nav($Tfilas,$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->index();
	 	       $this->render("index");
        }

	}else{
		$pagina=1;
		$Tfilas=$this->clasificador->findCount();
        if($Tfilas!=0){
        	$data=$this->clasificador->findAll(null,null,"cod_grupo, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar, tabla ASC",1,$pagina,null);
            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->set('ultimo',$Tfilas);
          	$this->set('pagina_actual',$pagina);
          	$this->bt_nav($Tfilas,$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->index();
	 	       $this->render("index");
        }
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
}//FIN CLASS
?>
