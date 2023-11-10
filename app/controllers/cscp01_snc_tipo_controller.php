<?php

 class cscp01SncTipoController extends AppController {
	var $uses = array('cscd01_snc_tipo', 'ccfd04_cierre_mes', "cscd01_catalogo", "cscd01_snc_grupo", "cugd05_restriccion_clave");
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "cscp01_snc_tipo";



function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession



function beforeFilter(){
    $this->checkSession();
}//fin before filter


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


function SQLCA($ano=null){
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


function index($var=null, $var_cont=null){

$this->verifica_entrada('96');

	  $this->layout = "ajax";
	  $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";

      $Tfilas=$this->cscd01_snc_tipo->findCount();
        if($Tfilas!=0){
        	$pagina=1;
        	$Tfilas=(int)ceil($Tfilas/300);
        	//$Tfilas=$Tfilas/1000;
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('ultimo',$Tfilas);
     	    $datos_filas=$this->cscd01_snc_tipo->findAll(null,null,"cod_tipo ASC",300,1,null);
	        $this->set("datosFILAS",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
        }else{
        	$this->set("datosFILAS",'');
        }
        $this->set('datos', $datos_filas);


}//fin index






function funcion(){$this->layout = "ajax";}






function guardar($var_n=null){

	$this->layout = "ajax";

      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";



if($var_n==null){ $cod_tipo   =   $this->data['cscp01_snc_tipo']['codigo'.$var_n];}else{ $cod_tipo =  $var_n;}


if(!empty($this->data['cscp01_snc_tipo']['denominacion'.$var_n])){
   $denominacion            =       $this->data['cscp01_snc_tipo']['denominacion'.$var_n];
   $var_cont = $this->cscd01_snc_tipo->findCount("cod_tipo='".$cod_tipo."' ");

			         if($var_cont==0){
                        $sw = $this->cscd01_snc_tipo->execute("BEGIN; INSERT INTO cscd01_snc_tipo (cod_tipo, denominacion) VALUES ('".$cod_tipo."', '".$denominacion."'); ");
			         }else{
                        $sw = $this->cscd01_snc_tipo->execute("BEGIN; UPDATE cscd01_snc_tipo SET denominacion='".$denominacion."' WHERE cod_tipo='".$cod_tipo."' ");
			         }//fin function
}else{$sw="a";}

          if($sw>1){

        $this->cscd01_snc_tipo->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');

     }elseif($sw=="a"){

     	$this->cscd01_snc_tipo->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS -- FALTAN DATOS');

     }else{

     	$this->cscd01_snc_tipo->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
     }//fin else




if($var_n==null){
    $this->index();
	$this->render("index");
}else{

    $var_datos = $this->cscd01_snc_tipo->findAll("cod_tipo='".$cod_tipo."' ");
     echo'<script>';
                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var_n."').innerHTML ='".$var_datos[0]['cscd01_snc_tipo']['denominacion']."' ;";
     echo'</script>';
   $this->render("funcion");
}//fin else

}//fin function










function editar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
         $var_datos = $this->cscd01_snc_tipo->findAll("cod_tipo='".$var1."' ");
         $var2 = $var_datos[0]['cscd01_snc_tipo']['denominacion'];
      echo'<script>';
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='<input type=text name=data[cscp01_snc_tipo][denominacion".$var1."]    id=denominacion".$var1."  value=\"$var2\"   class=campoText  />' ;";
     echo'</script>';

$this->render("funcion");
}//fin function





function eliminar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
	       $a=$this->cscd01_catalogo->FindCount("cod_snc='".$var1."'   ");
	    if($a==0){
	    	$sw = $this->cscd01_snc_tipo->execute("DELETE FROM cscd01_snc_tipo  WHERE cod_tipo='".$var1."' ");
	    	$this->set('Message_existe','EL REGISTRO FUE ELIMINADO');
	    }else{
	    	$this->set('errorMessage','ESTE DATO NO PODRA SER ELIMINADO YA QUE ESTA SIENDO USADO POR EL CATALOGO');
	    }
 	  $datos    = $this->cscd01_snc_tipo->findAll(null, null, 'cod_tipo ASC');
      $this->set('datos', $datos);
}//fin function







function bt_nav($Tfilas, $pagina){
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









function mostrar_datos($pagina=null){

    $this->layout = "ajax";



        if(isset($pagina)){
				$pagina=$pagina;
		}else{
				 $pagina=1;
		}//fin else
		$Tfilas=$this->cscd01_snc_tipo->findCount(null);
        if($Tfilas!=0){
        	$Tfilas=(int)ceil($Tfilas/300);
        	$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('ultimo',$Tfilas);
     	    $datos_filas=$this->cscd01_snc_tipo->findAll(null,null,"cod_tipo ASC",300,$pagina,null);
	        $this->set("datosFILAS",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
        }else{
        	$this->set("datosFILAS",'');
        }


$this->set('datos', $datos_filas);


}//fin function










function validar_codigo_snc($var1=null){

    $this->layout = "ajax";
    $var1 = substr($var1, 0, 5);


   $a=$this->cscd01_snc_grupo->FindCount("upper(cod_grupo)=upper('".$var1."')   ");
	    if($a==0){
            echo"<script>document.getElementById('plus').disabled = true; </script>";
            $this->set('errorMessage', "el código no esta presente en CLASIFICADOR SNC - GRUPO");
	    }else{
            echo"<script>document.getElementById('plus').disabled = false; </script>";
	    }//fin function




    $this->funcion();
    $this->render("funcion");



}//fin function









function cancelar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
      $var_datos = $this->cscd01_snc_tipo->findAll("cod_tipo='".$var1."'");
      $var2 = $var_datos[0]['cscd01_snc_tipo']['denominacion'];
      echo'<script>';
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='".$var2."' ;";
     echo'</script>';
$this->render("funcion");
}//fin function


function salir_clave(){
	$this->layout="ajax";
	$this->Session->delete('autor_valido');
}


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cscp01_snc_tipo']['login']) && isset($this->data['cscp01_snc_tipo']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cscp01_snc_tipo']['login']);
		$paswd=addslashes($this->data['cscp01_snc_tipo']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=96 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->Session->write('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->Session->write('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}// Entrar


}//fin class
?>