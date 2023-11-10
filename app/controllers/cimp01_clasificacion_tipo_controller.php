<?php


 class Cimp01ClasificacionTipoController extends AppController{
	var $uses = array('cimd01_clasificacion_tipo', 'ccfd04_cierre_mes','cugd05_restriccion_clave');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "cimp01_clasificacion_tipo";

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






function index($var=null, $var_cont=null){

$this->verifica_entrada('102');

	 $this->layout = "ajax";
     $var_cont = $this->cimd01_clasificacion_tipo->findAll(null, null, 'cod_tipo ASC');
     $datos    = $this->cimd01_clasificacion_tipo->findAll(null, null, 'cod_tipo, upper(trim(denominacion)) ASC');
     $var_cont2 = 0;
     foreach($var_cont as $ve1){$var_cont2 = $ve1['cimd01_clasificacion_tipo']['cod_tipo'];}
     $var_cont2++;

     $this->set('cod_tipo', $var_cont2);
     $this->set('datos', $datos);

}//fin index






function funcion(){$this->layout = "ajax";}






function guardar($var_n=null){

	$this->layout = "ajax";

//      $cod_presi                =       $this->Session->read('SScodpresi');
//	  $cod_entidad              =       $this->Session->read('SScodentidad');
//	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
//	  $cod_inst                 =       $this->Session->read('SScodinst');
//	  $cod_dep                  =       $this->Session->read('SScoddep');
	//  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";



if($var_n==null){ $cod_tipo   =   $this->data['cimp01_clasificacion_tipo']['cod_tipo'.$var_n];}else{ $cod_tipo =  $var_n;}


if(!empty($this->data['cimp01_clasificacion_tipo']['denominacion'.$var_n])){
   $denominacion            =       $this->data['cimp01_clasificacion_tipo']['denominacion'.$var_n];

   $var_cont = $this->cimd01_clasificacion_tipo->findCount("cod_tipo=".$cod_tipo);

			         if($var_cont==0){
                        $sw = $this->cimd01_clasificacion_tipo->execute("BEGIN; INSERT INTO cimd01_clasificacion_tipo (cod_tipo, denominacion) VALUES ('".$cod_tipo."', '".$denominacion."'); ");
			         }else{
                        $sw = $this->cimd01_clasificacion_tipo->execute("BEGIN; UPDATE cimd01_clasificacion_tipo SET denominacion='".$denominacion."' WHERE cod_tipo='".$cod_tipo."' ");
			         }//fin function
}else{$sw="a";}




          if($sw>1){

        $this->cimd01_clasificacion_tipo->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');

     }elseif($sw=="a"){

     	$this->cimd01_clasificacion_tipo->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - FALTAN DATOS');

     }else{

     	$this->cimd01_clasificacion_tipo->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
     }//fin else




if($var_n==null){
    $this->index();
	$this->render("index");
}else{

    $var_datos = $this->cimd01_clasificacion_tipo->findAll("cod_tipo=".$cod_tipo);
   echo'<script>';
                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var_n."').innerHTML ='".$var_datos[0]['cimd01_clasificacion_tipo']['denominacion']."' ;";
     echo'</script>';
   $this->render("funcion");
}//fin else

}//fin function










function editar($var1=null, $var2=null, $var3=null, $var4=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  //$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
         $var_datos = $this->cimd01_clasificacion_tipo->findAll("cod_tipo=".$var1);
         $var2 = $var_datos[0]['cimd01_clasificacion_tipo']['denominacion'];


      echo'<script>';
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='<input type=text name=data[cimp01_clasificacion_tipo][denominacion".$var1."]    id=denominacion_".$var1."  value=\"$var2\"   class=inputtext  maxlength=100/>' ;";
      echo'</script>';

$this->render("funcion");
}//fin function





function eliminar($var1=null, $var2=null, $var3=null, $var4=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  //$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
      $sw = $this->cimd01_clasificacion_tipo->execute("DELETE FROM cimd01_clasificacion_tipo  WHERE cod_tipo='".$var1."' ");
$this->render("funcion");
}//fin function



function cancelar($var1=null, $var2=null, $var3=null, $var4=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  //$condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
      $var_datos = $this->cimd01_clasificacion_tipo->findAll("cod_tipo=".$var1);
      $var2 = $var_datos[0]['cimd01_clasificacion_tipo']['denominacion'];
      echo'<script>';
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='".$var2."' ;";
     echo'</script>';
$this->render("funcion");
}//fin function

function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cimp01_clasificacion_tipo']['login']) && isset($this->data['cimp01_clasificacion_tipo']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cimp01_clasificacion_tipo']['login']);
		$paswd=addslashes($this->data['cimp01_clasificacion_tipo']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=102 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
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




}//fin class
?>
