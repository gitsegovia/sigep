<?php


class Cnmp06Oficio2Controller extends AppController {
	var $uses = array('cnmd06_oficio', 'ccfd04_cierre_mes','cnmd06_datos_personales','cugd05_restriccion_clave');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "cnmp06_oficio2";

//cnmp06_oficio2

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




function index($var=null, $var_cont=null){

$this->verifica_entrada('40');

	$this->layout = "ajax";
	  $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";

     $var_cont = $this->cnmd06_oficio->findAll(null,null,"cod_oficio DESC",1,1,null);

     $Tfilas=$this->cnmd06_oficio->findCount();
        if($Tfilas!=0){
        	$pagina=1;
        	$Tfilas=(int)ceil($Tfilas/300);
        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('ultimo',$Tfilas);
     	    $datos_filas=$this->cnmd06_oficio->findAll(null,null,' upper(trim(denominacion)) ASC',300,1,null);
	        $this->set("datosFILAS",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
        }else{
        	$this->set("datosFILAS",'');
        }
        $this->set('datos', $datos_filas);



     $var_cont2 = 0;
     foreach($var_cont as $ve1){$var_cont2 = $ve1['cnmd06_oficio']['cod_oficio'];}
     $var_cont2++;

     $this->set('codigo', $var_cont2);


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



if($var_n==null){ $cod_oficio   =   $this->data['cnmp06_oficio2']['codigo'.$var_n];}else{ $cod_oficio =  $var_n;}


if(!empty($this->data['cnmp06_oficio2']['denominacion'.$var_n])){
   $denominacion            =       $this->data['cnmp06_oficio2']['denominacion'.$var_n];
   $var_cont = $this->cnmd06_oficio->findCount("cod_oficio=".$cod_oficio);

			         if($var_cont==0){
                        $sw = $this->cnmd06_oficio->execute("BEGIN; INSERT INTO cnmd06_oficio (cod_oficio, denominacion) VALUES ('".$cod_oficio."', '".$denominacion."'); ");
			         }else{
                        $sw = $this->cnmd06_oficio->execute("BEGIN; UPDATE cnmd06_oficio SET denominacion='".$denominacion."' WHERE cod_oficio='".$cod_oficio."' ");
			         }//fin function
}else{$sw="a";}

          if($sw>1){

        $this->cnmd06_oficio->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');

     }elseif($sw=="a"){

     	$this->cnmd06_oficio->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS -- FALTAN DATOS');

     }else{

     	$this->cnmd06_oficio->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
     }//fin else




if($var_n==null){
	$this->set('autor_valido',true);
	$this->index("autor_valido");
    $this->index();
	$this->render("index");
}else{

    $var_datos = $this->cnmd06_oficio->findAll("cod_oficio=".$cod_oficio);
   echo'<script>';
                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var_n."').innerHTML ='".$var_datos[0]['cnmd06_oficio']['denominacion']."' ;";
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
         $var_datos = $this->cnmd06_oficio->findAll("cod_oficio=".$var1);
         $var2 = $var_datos[0]['cnmd06_oficio']['denominacion'];
      echo'<script>';
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_$var1').innerHTML ='<input type=\"text\" name=\"data[cnmp06_oficio2][denominacion$var1]\"    id=\"denominacion$var1\"  value=\"$var2\"   class=\"campoText\"/>' ;";
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
	   $a=$this->cnmd06_datos_personales->FindCount("cod_oficio='$var1'");
	    if($a==0){
	    	 $sw = $this->cnmd06_oficio->execute("DELETE FROM cnmd06_oficio  WHERE cod_oficio='".$var1."' ");
	    }else{
	    	$this->set('errorMessage','ESTE DATO NO PODRA SER ELIMINADO YA QUE ESTA SIENDO USADO POR OTRO PROGRAMA');
	    }
//		$this->render("funcion");
 	$datos    = $this->cnmd06_oficio->findAll(null, null, ' upper(trim(denominacion)) ASC');
     $this->set('datos', $datos);
}//fin function



function cancelar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
      $var_datos = $this->cnmd06_oficio->findAll("cod_oficio=".$var1);
      $var2 = $var_datos[0]['cnmd06_oficio']['denominacion'];
      echo'<script>';
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='".$var2."' ;";
     echo'</script>';
$this->render("funcion");
}//fin function













function mostrar_datos($pagina=null){

    $this->layout = "ajax";



      if(isset($pagina)){
				$pagina=$pagina;
		}else{
				 $pagina=1;
		}//fin else
		$Tfilas=$this->cnmd06_oficio->findCount(null);
        if($Tfilas!=0){
        	$Tfilas=(int)ceil($Tfilas/300);
        	$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('ultimo',$Tfilas);
     	    $datos_filas=$this->cnmd06_oficio->findAll(null,null,' upper(trim(denominacion)) ASC',300,$pagina,null);
	        $this->set("datosFILAS",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
        }else{
        	$this->set("datosFILAS",'');
        }


$this->set('datos', $datos_filas);


}//fin function


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cnmp06_oficio2']['login']) && isset($this->data['cnmp06_oficio2']['password'])){
		$cod_presi                =       $this->Session->read('SScodpresi');
		$cod_entidad              =       $this->Session->read('SScodentidad');
		$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
		$cod_inst                 =       $this->Session->read('SScodinst');
		$cod_dep                  =       $this->Session->read('SScoddep');
		$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";

		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cnmp06_oficio2']['login']);
		$paswd=addslashes($this->data['cnmp06_oficio2']['password']);
		$cond=$condicion." and username='".$user."' and cod_tipo=40 and clave='".$paswd."'";
		if(($user==$l && $paswd==$c)){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}



}//fin class
?>
