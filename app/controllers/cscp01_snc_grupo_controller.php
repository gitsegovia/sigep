<?php
/*
 * Creado el  17/12/2007 a las 12:46:21 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 * Descripcion:
 */
 class cscp01SncGrupoController extends AppController{
 	var $name = 'cscp01_snc_grupo';
 	var $uses = array ('cscd01_snc_tipo', 'cscd01_snc_grupo','cugd05_restriccion_clave');
 	var $helpers = array ('Html','Ajax','Javascript','Sisap');



 function checkSession()
    {
        // If the session info hasn't been set...
        if (!$this->Session->check('Usuario'))
        {
            // Force the user to login
            $this->redirect('/salir');
            exit();
        }
    }

 function beforeFilter(){
 	$this->checkSession();

 }//fin function


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



 function index(){

$this->verifica_entrada('95');

 	$this->layout="ajax";
    $tipo_c = $this->cscd01_snc_grupo->generateList(null, 'cod_grupo ASC', null, '{n}.cscd01_snc_grupo.cod_grupo', '{n}.cscd01_snc_grupo.denominacion');
   	$this->concatena_sin_cero($tipo_c, 'lista_numero');


    $this->data=null;



 }// fin del index





function selecion($var1=null){

   $this->layout="ajax";

   $value   = "";
   $readoly = "";



         if($var1==null){

        $readoly = "readoly";

                      echo "<script>";
					  	echo "document.getElementById('cod_grupo').value='';";
					  	echo "document.getElementById('denominacion').value='';";
					  	echo "document.getElementById('cod_grupo').readOnly=true;";
					  	echo "document.getElementById('denominacion').readOnly=true;";

					  	echo "document.getElementById('guardar').disabled=true;";
					  	echo "document.getElementById('eliminar').disabled=true;";
					  	echo "document.getElementById('modificar').disabled=true;";
					  echo "</script>";



   }else if($var1=="otros"){


                     echo "<script>";
					  	echo "document.getElementById('cod_grupo').value='';";
					  	echo "document.getElementById('denominacion').value='';";
					  	echo "document.getElementById('cod_grupo').readOnly=false;";
					  	echo "document.getElementById('denominacion').readOnly=false;";

					  	echo "document.getElementById('guardar').disabled=false;";
					  	echo "document.getElementById('eliminar').disabled=true;";
					  	echo "document.getElementById('modificar').disabled=true;";
					  echo "</script>";


   }else{



                     $a =  $this->cscd01_snc_grupo->findAll("cod_grupo='".$var1."'");

                     echo "<script>";
					  	echo "document.getElementById('cod_grupo').readOnly=true;";
					  	echo "document.getElementById('denominacion').readOnly=true;";

					  	echo "document.getElementById('cod_grupo').value   ='".$a[0]['cscd01_snc_grupo']['cod_grupo']."';";
					  	echo "document.getElementById('denominacion').value='".$a[0]['cscd01_snc_grupo']['denominacion']."';";

					  	echo "document.getElementById('guardar').disabled=true;";
					  	echo "document.getElementById('eliminar').disabled=false;";
					  	echo "document.getElementById('modificar').disabled=false;";
					  echo "</script>";


         $readoly = "readoly";
         $value   = $a[0]['cscd01_snc_grupo']['descripcion'];

   }//fin function



$this->set("readoly", $readoly);
$this->set("value", $value);


}//fin function






function modificar(){
	$this->layout="ajax";
         echo "<script>";
			  	echo "document.getElementById('denominacion').readOnly=false;";
			  	echo "document.getElementById('descripcion').readOnly=false;";

			  	echo "document.getElementById('guardar').disabled=false;";
			  	echo "document.getElementById('eliminar').disabled=true;";
			  	echo "document.getElementById('modificar').disabled=true;";
		  echo "</script>";
	$this->funcion();
	$this->render("funcion");
}//fin function






function cancelar(){
	$this->layout="ajax";
         echo "<script>";
			  	echo "document.getElementById('denominacion').readOnly=true;";
			  	echo "document.getElementById('descripcion').readOnly=true;";

			  	echo "document.getElementById('guardar').disabled=false;";
			  	echo "document.getElementById('eliminar').disabled=true;";
			  	echo "document.getElementById('modificar').disabled=true;";
		  echo "</script>";
	$this->funcion();
	$this->render("funcion");
}//fin function







function guardar($var1=null){
   	$this->layout="ajax";
			if(!empty($this->data['cscp01_snc_grupo'])){
			  		$cod_grupo      = $this->data['cscp01_snc_grupo']['cod_grupo'];   // Código tipo de la cuenta;
			  		$denominacion   = $this->data['cscp01_snc_grupo']['denominacion_grupo'];    // Código de la cuenta;
			  		$descripcion    = $this->data['cscp01_snc_grupo']['descripcion']; // Denominación;
			        $a  =  $this->cscd01_snc_grupo->findCount("cod_grupo='".$cod_grupo."'");
			           if($a==0){
					                $sql= "INSERT INTO cscd01_snc_grupo values ('".$cod_grupo."','".$denominacion."','".$descripcion."')";
						  		 if($this->cscd01_snc_grupo->execute($sql)>1){
						  			$this->set('Message_existe','Los datos fueron guardados');


						  		}else{
						  			$this->set('errorMessage','Los datos no pudier&oacute;n ser guardados');

						  		}
			        }else{
					                $sql= "update cscd01_snc_grupo set  denominacion = '".$denominacion."', descripcion = '".$descripcion."'  where cod_grupo='".$cod_grupo."'  ";
						  		 if($this->cscd01_snc_grupo->execute($sql)>1){
						  			$this->set('Message_existe','Los datos fueron actualizados');


						  		}else{
						  			$this->set('errorMessage','Los datos no pudier&oacute;n ser actualizados');

						  		}
			        }//fin else



			}else{
				            $this->set('errorMessage','Los datos no pudier&oacute;n ser guardados');

			}//fin else

      if($var1=="editar"){

			echo "<script>";

			    echo "document.getElementById('denominacion').readOnly=true;";
			  	echo "document.getElementById('descripcion').readOnly=true;";

			  	echo "document.getElementById('guardar').disabled=true;";
			  	echo "document.getElementById('eliminar').disabled=false;";
			  	echo "document.getElementById('modificar').disabled=false;";
		  echo "</script>";


      }else{
		  $this->index();
		  $this->render("index");
      }//fin else


}//finf function









function eliminar($var1=null){
	$this->layout ="ajax";
	    $cod_grupo = $this->data['cscp01_snc_grupo']['cod_grupo'];
  		$sql = "DELETE FROM cscd01_snc_grupo WHERE  cod_grupo='".$cod_grupo."'  ";
			if($this->cscd01_snc_grupo->execute($sql)>1){
				$this->set('Message_existe', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
					$this->index();
					$this->render("index");
			}else{
				$this->set('errorMessage', 'EL REGISTRO NO PUDO SER ELIMINADO');
				$this->index();
				$this->render("index");
			}
}//fin eliminar











function consultar($pagina=null){
 		$this->layout = "ajax";
 		$cod_presi      =  $this->Session->read('SScodpresi');
		$cod_entidad    =  $this->Session->read('SScodentidad');
		$cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
		$cod_inst       =  $this->Session->read('SScodinst');
		$cod_dep        =  $this->Session->read('SScoddep');
if($pagina!=null){
    $pagina=$pagina;
          	 if($pagina<=0){$pagina=1;}
          	  $Tfilas=$this->cscd01_snc_grupo->findCount(null);
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }

          	 if($Tfilas!=0){
	          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
	          	 $datos=$this->cscd01_snc_grupo->findAll(null,null,'cod_grupo ASC',1,$pagina,null);
	          	 $this->set('datos',$datos);
	          	 $this->set('siguiente',$pagina+1);
	          	 $this->set('anterior',$pagina-1);
	             $this->bt_nav($Tfilas,$pagina);
             }
 }else{
 	 $pagina=1;
          	 $Tfilas=$this->cscd01_snc_grupo->findCount(null);
          	 if($Tfilas==0){
          	 	$this->index();
          		$this->render("index");
          	 }
          	 if($Tfilas!=0){
          	 $this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 $datos=$this->cscd01_snc_grupo->findAll(null,null,'cod_grupo ASC',1,$pagina,null);
          	 $this->set('datos',$datos);
          	 $this->set('siguiente',$pagina+1);
          	 $this->set('anterior',$pagina-1);
             $this->bt_nav($Tfilas,$pagina);

             }
      }//fin else


       $this->set('ultimo',$Tfilas);

}//fin function consultar




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


function funcion($var=null){ $this->layout="ajax";}


function salir_clave(){
	$this->layout="ajax";
	$this->Session->delete('autor_valido');
}


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cscp01_snc_grupo']['login']) && isset($this->data['cscp01_snc_grupo']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cscp01_snc_grupo']['login']);
		$paswd=addslashes($this->data['cscp01_snc_grupo']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=95 and clave='".$paswd."'";
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



 }// fin de la clase
?>
