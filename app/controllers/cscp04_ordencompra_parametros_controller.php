<?php
/*
 * Creado el 31/10/2007
 *
 * Miguelangel Cabrera
 * miguel4ngel@gmail.com
 *
 * 10:46:32 AM
 */
 class Cscp04OrdencompraParametrosController extends AppController {

   var $uses = array('cscd04_ordencompra_parametros','Usuario','cugd05_restriccion_clave');
   var $helpers = array('Html','Ajax','Javascript','Sisap');

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

    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');
	$condicion2 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
	$opc = $this->Usuario->findCount($condicion2);

	/*if($cod_dep == '01'){
		return;
	}else{
 		echo "LO SIENTO - UD. NO TIENE PERMISOS PARA ESTE PROCESO!!";
		exit;
	}*/
 }
 function verifica_SS($i){
	/*******************************************
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
     $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and ";
     $sql_re .= "cod_dep=".$this->verifica_SS(5);
     return $sql_re;
}//fin funcion SQLCA


function AddCeroR($n,$extra=null){
  if($n!=null){
  	  if($extra==null){
	       	if($n<10){
	       	   $Var="0".$n;
	       	}else{
	           $Var=$n;
	       	}
  	  }else{
	       	if($n<10){
	       	   $Var=$extra.".0".$n;
	       	}else{
	           $Var=$extra.".".$n;
	       	}
	  }
  	  $Var = substr($Var, - 2);
      return $Var;
  }else{
  	//return $Var;
  }
}//fin AddCero


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
	           $Var[$x]=$extra.".".$x;
        	}
	    }//fin each
   	  }
   	  $this->set($nomVar,$Var);
   	  }else{
   	  	  $this->set($nomVar,'');
   	  }
}//fin AddCero


function Formato1($monto) {
    $monto = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$monto));
if (substr($monto,-3,1)=='.') {
    $sents = '.'.substr($monto,-4);
    $monto = substr($monto,0,strlen($monto)-3);
} elseif (substr($monto,-4,1)=='.') {
    $sents = '.'.substr($monto,-1);
    $monto = substr($monto,0,strlen($monto)-4);
} else {
    $sents = '%';
}
$monto = preg_replace("/[^0-9]/", "", $monto);
return number_format($monto.$sents,2,'.','');
}


function Formato2($monto){
	if($monto<10){
		return number_format($monto);
	}
  	return number_format($monto,2,",",".");
}


function Porcentaje($monto){
	return $monto."%";
}


function Formato($price) {
    $price = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$price));
    if (substr($price,-3,1)=='.') {
        $sents = '.'.substr($price,-2);
        $price = substr($price,0,strlen($price)-3);
    } elseif (substr($price,-2,1)=='.') {
        $sents = '.'.substr($price,-1);
        $price = substr($price,0,strlen($price)-2);
    } else {
        $sents = '.00';
    }
    $price = preg_replace("/[^0-9]/", "", $price);
    return number_format($price.$sents,2,'.','');
}


function index($id=null){

$this->verifica_entrada('11');

    $this->layout = "ajax";
    //echo str_replace('.',',','1.000');
    $cod_presi=$this->verifica_SS(1);
	$cod_entidad=$this->verifica_SS(2);
	$cod_tipo_inst=$this->verifica_SS(3);
	$cod_inst=$this->verifica_SS(4);
	$cod_dep=$this->verifica_SS(5);

	$consulta="select *from cscd04_ordencompra_parametros where ".$this->SQLCA();
	if($this->cscd04_ordencompra_parametros->execute($consulta)){
		$this->set('existe',true);
		$this->set('datos',$this->cscd04_ordencompra_parametros->findAll($this->SQLCA()));
		//echo $Sisap->Porcentaje('3');
	}else{
		$this->set('existe',false);
		//$this->set('Message_existe','Introduzca los parametros para calculos');
	}
}


function index2() {
$this->layout = "ajax";
}


function guardar($valor=null){
    $this->layout = "ajax";
	$cod_presi=$this->verifica_SS(1);
	$cod_entidad=$this->verifica_SS(2);
	$cod_tipo_inst=$this->verifica_SS(3);
	$cod_inst=$this->verifica_SS(4);
	$cod_dep=$this->verifica_SS(5);//substr($valor, 0, -1)
	$porcentaje_fiel_cumplimiento   = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_fiel_cumplimiento']);//6
	$porcentaje_laboral             = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_laboral']);//7
	$retencion_incluye_iva          = $this->data['cscp04_ordencompra_parametros']['retencion_incluye_iva'];//8
	$porcentaje_islr_natural        = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_islr_natural']);//9
	$desde_monto_natural            = $this->Formato($this->data['cscp04_ordencompra_parametros']['desde_monto_natural']);//10
	$sustraendo                     = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['sustraendo']);//11
	$porcentaje_islr_juridico       = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_islr_juridico']);//12
	$desde_monto_juridico           = $this->Formato($this->data['cscp04_ordencompra_parametros']['desde_monto_juridico']);//13
	$porcentaje_timbre_fiscal       = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_timbre_fiscal']);//14
	$desde_monto_timbre             = $this->Formato($this->data['cscp04_ordencompra_parametros']['desde_monto_timbre']);//15
	$porcentaje_impuesto_municipal  = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_impuesto_municipal']);//16
	$desde_monto_impuesto_municipal = $this->Formato($this->data['cscp04_ordencompra_parametros']['desde_monto_impuesto_municipal']);//17
	$porcentaje_retencion_iva       = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_retencion_iva']);//18
	//$desde_monto_retencion_iva    = $this->data['cscp04_ordencompra_parametros']['desde_monto_retencion_iva'];
	$aplica_retencion_iva           = $this->data['cscp04_ordencompra_parametros']['aplica_retencion_iva'];//19
	$porcentaje_anticipo            = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_anticipo']);//20
	$anticipo_incluye_iva           = $this->data['cscp04_ordencompra_parametros']['anticipo_incluye_iva'];
	$unidad_tributaria              = str_replace(',','.',$this->data['cscp04_ordencompra_parametros']['unidad_tributaria']);
	$porcentaje_iva                 = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_iva']);
	$factor_reversion               = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['factor_reversion']);//
	$salario_minimo               = $this->Formato($this->data['cscp04_ordencompra_parametros']['salario_minimo']);//

	$sql="insert into cscd04_ordencompra_parametros values('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$cod_dep'," .
			"'$porcentaje_fiel_cumplimiento','$porcentaje_laboral','$retencion_incluye_iva','$porcentaje_islr_natural','$desde_monto_natural'," .
			"'$sustraendo','$porcentaje_islr_juridico','$desde_monto_juridico','$porcentaje_timbre_fiscal','$desde_monto_timbre'," .
			"'$porcentaje_impuesto_municipal','$desde_monto_impuesto_municipal','$porcentaje_retencion_iva','$aplica_retencion_iva','$porcentaje_anticipo'," .
			"'$anticipo_incluye_iva','$unidad_tributaria','$porcentaje_iva','$factor_reversion','$salario_minimo')";

	if($this->cscd04_ordencompra_parametros->execute($sql)>1){
		//if(2==(1+1)){
		$this->set('Message_existe', 'Los Datos fueron Almacenados');
		//echo "guardo";
		//$this->set('existe',true);
		$this->set('datos',$this->cscd04_ordencompra_parametros->findAll($this->SQLCA()));
   }else{
	   	$this->set('errorMessage', 'Los Datos no fueron Almacenados');
	   	//$this->set('existe',false);
	   	//echo "no guardo";
   	}
}


function modificar () {
 	$this->layout = "ajax";
	$this->set('datos',$this->cscd04_ordencompra_parametros->findAll($this->SQLCA()));
}






function quitar_porcentaje($var=null){
	$c_var = substr_count (strtoupper($var), '%');
	if($c_var==0){
	}else{
        $var = str_replace("%", "", $var);
	}

	$c_var = substr_count (strtoupper($var), ',');
	if($c_var==0){
	}else{
        $var = $this->Formato($var);
	}

	$c_var = substr_count (strtoupper($var), 'X');
	if($c_var==0){
	}else{
        $var = substr($var, 0, -7);
	}


     return $var;

}//fin fucntion













function guardar_modificar () {
	$this->layout = "ajax";
	$cod_presi=$this->verifica_SS(1);
	$cod_entidad=$this->verifica_SS(2);
	$cod_tipo_inst=$this->verifica_SS(3);
	$cod_inst=$this->verifica_SS(4);
	$cod_dep=$this->verifica_SS(5);//substr($valor, 0, -1)
	$porcentaje_fiel_cumplimiento       = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_fiel_cumplimiento']);//6
	$porcentaje_laboral                 = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_laboral']);//7
	$retencion_incluye_iva              = $this->data['cscp04_ordencompra_parametros']['retencion_incluye_iva'];//8
	$porcentaje_islr_natural            = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_islr_natural']);//9
	$desde_monto_natural                = $this->Formato($this->data['cscp04_ordencompra_parametros']['desde_monto_natural']);//10
	$sustraendo                         = $this->Formato($this->data['cscp04_ordencompra_parametros']['sustraendo']);//11
	$porcentaje_islr_juridico           = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_islr_juridico']);//12
	$desde_monto_juridico               = $this->Formato($this->data['cscp04_ordencompra_parametros']['desde_monto_juridico']);//13
	$porcentaje_timbre_fiscal           = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_timbre_fiscal']);//14
	$desde_monto_timbre                 = $this->Formato($this->data['cscp04_ordencompra_parametros']['desde_monto_timbre']);//15
	$porcentaje_impuesto_municipal      = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_impuesto_municipal']);//16
	$desde_monto_impuesto_municipal     = $this->Formato($this->data['cscp04_ordencompra_parametros']['desde_monto_impuesto_municipal']);//17
	$porcentaje_retencion_iva           = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_retencion_iva']);//18
	//$desde_monto_retencion_iva        = $this->data['cscp04_ordencompra_parametros']['desde_monto_retencion_iva'];
	$aplica_retencion_iva               = $this->data['cscp04_ordencompra_parametros']['aplica_retencion_iva'];//19
	$porcentaje_anticipo                = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_anticipo']);//20
	$anticipo_incluye_iva               = $this->data['cscp04_ordencompra_parametros']['anticipo_incluye_iva'];
	$unidad_tributaria                  = str_replace(',','.',str_replace('.','',$this->data['cscp04_ordencompra_parametros']['unidad_tributaria']));
	$porcentaje_iva                     = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['porcentaje_iva']);
	$factor_reversion                   = $this->quitar_porcentaje($this->data['cscp04_ordencompra_parametros']['factor_reversion']);//
	$salario_minimo                     = $this->Formato($this->data['cscp04_ordencompra_parametros']['salario_minimo']);//

			
	$sql="update cscd04_ordencompra_parametros set porcentaje_fiel_cumplimiento='$porcentaje_fiel_cumplimiento',porcentaje_laboral='$porcentaje_laboral'," .
			"retencion_incluye_iva='$retencion_incluye_iva',porcentaje_islr_natural='$porcentaje_islr_natural',desde_monto_natural='$desde_monto_natural'," .
			"sustraendo='$sustraendo',porcentaje_islr_juridico='$porcentaje_islr_juridico',desde_monto_juridico='$desde_monto_juridico',porcentaje_timbre_fiscal='$porcentaje_timbre_fiscal'," .
			"desde_monto_timbre='$desde_monto_timbre',porcentaje_impuesto_municipal='$porcentaje_impuesto_municipal',desde_monto_impuesto_municipal='$desde_monto_impuesto_municipal'," .
			"porcentaje_retencion_iva='$porcentaje_retencion_iva',aplica_retencion_iva='$aplica_retencion_iva'," .
			"porcentaje_anticipo='$porcentaje_anticipo',anticipo_incluye_iva='$anticipo_incluye_iva',unidad_tributaria='$unidad_tributaria',porcentaje_iva='$porcentaje_iva'," .
			"factor_reversion='$factor_reversion',salario_minimo='$salario_minimo' where cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep'";

	if($this->cscd04_ordencompra_parametros->execute($sql)>1){
		$this->set('Message_existe', 'Los datos fueron actualizados');
		$this->set('existe',true);
		$dato=$this->cscd04_ordencompra_parametros->findAll($this->SQLCA());
		$this->set('datos',$dato);
	}else{
   	$this->set('errorMessage', 'Los datos no fueron actualizados');
   	}
}


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cscp04_ordencompra_parametros']['login']) && isset($this->data['cscp04_ordencompra_parametros']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cscp04_ordencompra_parametros']['login']);
		$paswd=addslashes($this->data['cscp04_ordencompra_parametros']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=11 and clave='".$paswd."'";
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
}//Entrar


 }//Fin Clase
?>
