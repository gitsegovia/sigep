<?php
/*
 * Created on 19/07/2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class Cdvp01DatosPersonalesController extends AppController{
     var $name = 'cdvp01_datos_personales';
     var $uses = array('v_cdvd01_visitas','cugd02_direccion','cugd02_secretaria','cugd02_coordinacion','cdvd01_datos_personales','cdvd01_visitas','cfpd15', 'cfpd02_sector', 'cfpd02_programa', 'cfpd05', 'cfpd05_auxiliar', 'cfpd02_sub_prog', 'cfpd02_proyecto', 'cfpd02_activ_obra', 'cfpd01_ano_grupo', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd01_formulacion','cugd02_direccionsuperior');
     var $helpers = array('Html','Ajax','Javascript', 'Sisap');
    // var $paginate = array('limit' => 3, 'page' => 1);
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
        $sents = '.'.substr($monto,-2);
        $monto = substr($monto,0,strlen($monto)-3);
    } elseif (substr($monto,-2,1)=='.') {
        $sents = '.'.substr($monto,-1);
        $monto = substr($monto,0,strlen($monto)-2);
    } else {
        $sents = '.00';
    }
    $monto = preg_replace("/[^0-9]/", "", $monto);
    return number_format($monto.$sents,2,'.','');
    }

function Formato2($monto){
    	return number_format($monto,2,",",".");
    }



function index(){
	$this->layout = "ajax";
	$cod_presi = $this->verifica_SS(1);
	$cod_entidad = $this->verifica_SS(2);
	$cod_tipo_inst = $this->verifica_SS(3);
	$cod_inst = $this->verifica_SS(4);
	$cod_dep = $this->verifica_SS(5);
 	$this->data=null;

	$this->Session->write('cinst',$cod_inst);
	$this->Session->write('cdepe',$cod_dep);
	$hora = array('01:00AM','02:00AM','03:00AM','04:00AM','05:00AM','06:00AM','07:00AM','08:00AM','09:00AM','10:00AM','11:00AM','12:00AM','01:00PM','02:00PM','03:00PM','04:00PM','05:00PM','06:00PM','07:00PM','08:00PM','09:00PM','10:00PM','11:00PM','12:00PM');
	$this->set('hora',$hora);
	$cond2 ="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;
	$lista2 = $this->cugd02_direccionsuperior->generateList($cond2, ' upper(trim(denominacion)) ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
    $this->set('dir_superior',$lista2);
}//fin index

function select5($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	if($var!=null){
	switch($select){
		case 'institucion':
		  $this->set('SELECT','dependencia');
		  $this->set('codigo','institucion');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $ano =  $this->Session->read('ano');
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE."";
		  $lista=  $this->Cugd02_dependencia->generateList($cond2, 'cod_institucion ASC', null, '{n}.Cugd02_dependencia.cod_institucion', '{n}.Cugd02_dependencia.denominacion');
			$this->concatena($lista,'vector');
		break;
		case 'dependencia':
			echo "<script>";
				echo "document.getElementById('c_seleccion_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','direccions');
		  $this->set('codigo','dependencia');
		  $this->set('seleccion','');
		  $this->set('n',6);
		  $this->Session->write('cinst',$var);
		  $cond2 ="cod_institucion=".$var;
		  $lista=  $this->cugd02_dependencia->generateList($cond2, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
          $this->concatena($lista,'vector');
 		break;
		case 'direccions':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','coordinacion');
		  $this->set('codigo','direccions');
		  $this->set('seleccion','');
		  $this->set('n',7);
		  $cinst		 =  $this->Session->read('cinst');
		  $cod_tipo_inst =  $this->verifica_SS(3);
		  $this->Session->write('cdepe',$var);
		  $cond2 ="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cinst." and cod_dependencia=".$var;
		  $lista = $this->cugd02_direccionsuperior->generateList($cond2, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'coordinacion':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','secretaria');
		  $this->set('codigo','coordinacion');
		  $this->set('seleccion','');
		  $this->set('n',8);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		   $cod_tipo_inst =  $this->verifica_SS(3);
		  $this->Session->write('cdirs',$var);
		  $cond2 ="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$var;
		  $lista=  $this->cugd02_coordinacion->generateList($cond2, ' upper(trim(denominacion)) ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
          $this->set('vector',$lista);
		break;
		case 'secretaria':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','direccion');
		  $this->set('codigo','secretaria');
		  $this->set('seleccion','');
		  $this->set('n',9);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		   $cod_tipo_inst =  $this->verifica_SS(3);
		  $this->Session->write('ccoor',$var);
		  $cond2 ="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$var;
		  $lista=  $this->cugd02_secretaria->generateList($cond2, ' upper(trim(denominacion)) ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
			$this->set('vector',$lista);
		break;
		case 'direccion':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','division');
		  $this->set('codigo','direccion');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $this->Session->write('csecr',$var);
		  $cod_tipo_inst =  $this->verifica_SS(3);
		  $cond2 ="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$var;
		  $lista=  $this->cugd02_direccion->generateList($cond2, ' upper(trim(denominacion)) ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
          $this->set('vector',$lista);
 		break;
 		case 'division':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','departamento');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		  $this->set('n',11);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $csecr =  $this->Session->read('csecr');
		  $this->Session->write('cdire',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$var;
		  $lista=  $this->cugd02_division->generateList($cond2, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
          if($lista!=null){
          $this->concatena($lista,'vector');
          }else{
          	echo "<script>";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text value=00 name=data[cimp03_inventario_muebles][cod_division] size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text value=00 name=data[cimp03_inventario_muebles][cod_departamento] size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text value=00 name=data[cimp03_inventario_muebles][cod_oficina] size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('sel_11').innerHTML='<select  class=select100 id=x_11><option value=00 selected>00</select>';  ";
		  		echo "document.getElementById('sel_12').innerHTML='<select  class=select100 id=x_12><option value=00 selected>00</select>';  ";
		  		echo "document.getElementById('sel_13').innerHTML='<select  class=select100 id=x_13><option value=00 selected>00</select>';  ";
        	echo "</script>";
          }


 		break;
		case 'departamento':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','oficina');
		  $this->set('codigo','departamento');
		  $this->set('seleccion','');
		  $this->set('n',12);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $csecr =  $this->Session->read('csecr');
		  $cdire =  $this->Session->read('cdire');
		  $this->Session->write('cdivi',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$cdire." and cod_division=".$var;
		  $lista = $this->cugd02_departamento->generateList($cond2, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
          if($lista!=null){
          $this->concatena($lista,'vector');
          }else{
          	echo "<script>";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text name=data[cimp03_inventario_muebles][cod_departamento] value=00  size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text name=data[cimp03_inventario_muebles][cod_oficina] value=00  size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('sel_12').innerHTML='<select  class=select100 id=x_12><option value=00 selected>00</select>';  ";
		  		echo "document.getElementById('sel_13').innerHTML='<select  class=select100 id=x_13><option value=00 selected>00</select>';  ";
        	echo "</script>";
          }
		break;
		case 'oficina':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','oficina');
		  $this->set('codigo','oficina');
		  $this->set('seleccion','');
		  $this->set('n',13);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $csecr =  $this->Session->read('csecr');
		  $cdire =  $this->Session->read('cdire');
		  $cdivi =  $this->Session->read('cdivi');
		  $this->Session->write('cdepa',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$cdire." and cod_division=".$cdivi." and cod_departamento=".$var;
		  $lista=  $this->cugd02_oficina->generateList($cond2, 'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
          if($lista!=null){
          $this->concatena($lista,'vector');
          }else{
          	echo "<script>";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text name=data[cimp03_inventario_muebles][cod_oficina] value=00  size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('sel_13').innerHTML='<select  class=select100 id=x_13><option value=00 selected>00</select>';  ";
        	echo "</script>";
          };
		break;
	}//fin wsitch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',21);
		  $this->set('no','no');
		 $this->set('vector','');
	}
}//fin select codigos presupuestarios

function select6($select=null,$var=null) { //select codigos presupuestarios
	$this->layout = "ajax";
	if($var!=null){
	switch($select){
		case 'institucion':
		  $this->set('SELECT','dependencia');
		  $this->set('codigo','institucion');
		  $this->set('seleccion','');
		  $this->set('n',5);
		  $ano =  $this->Session->read('ano');
		  $cond2 ="ejercicio=".$ano." and cod_grupo=".CE."";
		  $lista=  $this->Cugd02_dependencia->generateList($cond2, 'cod_institucion ASC', null, '{n}.Cugd02_dependencia.cod_institucion', '{n}.Cugd02_dependencia.denominacion');
			$this->concatena($lista,'vector');
		break;
		case 'dependencia':
			echo "<script>";
				echo "document.getElementById('c_seleccion_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','direccions');
		  $this->set('codigo','dependencia');
		  $this->set('seleccion','');
		  $this->set('n',6);
		  $this->Session->write('cinst',$var);
		  $cond2 ="cod_institucion=".$var;
		  $lista=  $this->cugd02_dependencia->generateList($cond2, 'cod_dependencia ASC', null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
          $this->concatena($lista,'vector');
 		break;
		case 'direccions':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_8').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_9').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','coordinacion');
		  $this->set('codigo','direccions');
		  $this->set('seleccion','');
		  $this->set('n',7);
		  $cinst		 =  $this->Session->read('cinst');
		  $cod_tipo_inst =  $this->verifica_SS(3);
		  $this->Session->write('cdepe',$var);
		  $cond2 ="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cinst." and cod_dependencia=".$var;
		  $lista = $this->cugd02_direccionsuperior->generateList($cond2, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
          $this->concatena($lista,'vector');
		break;
		case 'coordinacion':
			echo "<script>";
        		echo "document.getElementById('sel_9b').innerHTML='<select  class=select100 id=x_9b><option value=00 selected></select>';  ";
        		echo "document.getElementById('sel_10b').innerHTML='<select  class=select100 id=x_10b><option value=00 selected></select>';  ";
        	echo "</script>";
		  $this->set('SELECT','secretaria');
		  $this->set('codigo','coordinacion');
		  $this->set('seleccion','');
		  $this->set('n',8);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		   $cod_tipo_inst =  $this->verifica_SS(3);
		  $this->Session->write('cdirs',$var);
		  $cond2 ="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$var;
		  $lista=  $this->cugd02_coordinacion->generateList($cond2, ' upper(trim(denominacion)) ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
          $this->set('vector',$lista);
		break;
		case 'secretaria':
			echo "<script>";
        		echo "document.getElementById('sel_10b').innerHTML='<select  class=select100 id=x_10b><option value=00 selected></select>';  ";
        	echo "</script>";
		  $this->set('SELECT','direccion');
		  $this->set('codigo','secretaria');
		  $this->set('seleccion','');
		  $this->set('n',9);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		   $cod_tipo_inst =  $this->verifica_SS(3);
		  $this->Session->write('ccoor',$var);
		  $cond2 ="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$var;
		  $lista=  $this->cugd02_secretaria->generateList($cond2, ' upper(trim(denominacion)) ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
			$this->set('vector',$lista);
		break;
		case 'direccion':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_10').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','division');
		  $this->set('codigo','direccion');
		  $this->set('seleccion','');
		  $this->set('n',10);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $this->Session->write('csecr',$var);
		  $cod_tipo_inst =  $this->verifica_SS(3);
		  $cond2 ="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$var;
		  $lista=  $this->cugd02_direccion->generateList($cond2, ' upper(trim(denominacion)) ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
          $this->set('vector',$lista);
 		break;
 		case 'division':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','departamento');
		  $this->set('codigo','division');
		  $this->set('seleccion','');
		  $this->set('n',11);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $csecr =  $this->Session->read('csecr');
		  $this->Session->write('cdire',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$var;
		  $lista=  $this->cugd02_division->generateList($cond2, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
          if($lista!=null){
          $this->concatena($lista,'vector');
          }else{
          	echo "<script>";
		  		echo "document.getElementById('c_seleccion_11').innerHTML='<input type=text value=00 name=data[cimp03_inventario_muebles][cod_division] size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text value=00 name=data[cimp03_inventario_muebles][cod_departamento] size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text value=00 name=data[cimp03_inventario_muebles][cod_oficina] size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('d_seleccion_11').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('sel_11').innerHTML='<select  class=select100 id=x_11><option value=00 selected>00</select>';  ";
		  		echo "document.getElementById('sel_12').innerHTML='<select  class=select100 id=x_12><option value=00 selected>00</select>';  ";
		  		echo "document.getElementById('sel_13').innerHTML='<select  class=select100 id=x_13><option value=00 selected>00</select>';  ";
        	echo "</script>";
          }


 		break;
		case 'departamento':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','oficina');
		  $this->set('codigo','departamento');
		  $this->set('seleccion','');
		  $this->set('n',12);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $csecr =  $this->Session->read('csecr');
		  $cdire =  $this->Session->read('cdire');
		  $this->Session->write('cdivi',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$cdire." and cod_division=".$var;
		  $lista = $this->cugd02_departamento->generateList($cond2, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
          if($lista!=null){
          $this->concatena($lista,'vector');
          }else{
          	echo "<script>";
		  		echo "document.getElementById('c_seleccion_12').innerHTML='<input type=text name=data[cimp03_inventario_muebles][cod_departamento] value=00  size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text name=data[cimp03_inventario_muebles][cod_oficina] value=00  size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('d_seleccion_12').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('sel_12').innerHTML='<select  class=select100 id=x_12><option value=00 selected>00</select>';  ";
		  		echo "document.getElementById('sel_13').innerHTML='<select  class=select100 id=x_13><option value=00 selected>00</select>';  ";
        	echo "</script>";
          }
		break;
		case 'oficina':
		echo "<script>";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text  size=10% class=inputtext />';  ";
        	echo "</script>";
		  $this->set('SELECT','oficina');
		  $this->set('codigo','oficina');
		  $this->set('seleccion','');
		  $this->set('n',13);
		  $cinst =  $this->Session->read('cinst');
		  $cdepe =  $this->Session->read('cdepe');
		  $cdirs =  $this->Session->read('cdirs');
		  $ccoor =  $this->Session->read('ccoor');
		  $csecr =  $this->Session->read('csecr');
		  $cdire =  $this->Session->read('cdire');
		  $cdivi =  $this->Session->read('cdivi');
		  $this->Session->write('cdepa',$var);
		  $cond2 ="cod_institucion=".$cinst." and cod_dependencia=".$cdepe." and cod_dir_superior=".$cdirs." and cod_coordinacion=".$ccoor." and cod_secretaria=".$csecr." and cod_direccion=".$cdire." and cod_division=".$cdivi." and cod_departamento=".$var;
		  $lista=  $this->cugd02_oficina->generateList($cond2, 'cod_oficina ASC', null, '{n}.cugd02_oficina.cod_oficina', '{n}.cugd02_oficina.denominacion');
          if($lista!=null){
          $this->concatena($lista,'vector');
          }else{
          	echo "<script>";
		  		echo "document.getElementById('c_seleccion_13').innerHTML='<input type=text name=data[cimp03_inventario_muebles][cod_oficina] value=00  size=10% class=inputtext readonly style=text-align:center />';  ";
		  		echo "document.getElementById('d_seleccion_13').innerHTML='<input type=text value=N/A size=10% class=inputtext readonly />';  ";
		  		echo "document.getElementById('sel_13').innerHTML='<select  class=select100 id=x_13><option value=00 selected>00</select>';  ";
        	echo "</script>";
          };
		break;
	}//fin wsitch
	}else{
		  $this->set('SELECT','');
		  $this->set('codigo','');
		  $this->set('seleccion','');
		  $this->set('n',21);
		  $this->set('no','no');
		 $this->set('vector','');
	}
}//fin select codigos presupuestarios

function agregar_grilla(){
	$this->layout='ajax';
	//pr($this->data);
	$cod_presi 			= $this->verifica_SS(1);
	$cod_entidad 		= $this->verifica_SS(2);
	$cod_tipo_inst 		= $this->verifica_SS(3);
	$cod_inst 			= $this->verifica_SS(4);
	$cod_dep 			= $this->verifica_SS(5);
  	$cedula_identidad 	= $this->data['cdvp01_datos_personales']['cedula'];
  	$nombres_apellidos	= $this->data['cdvp01_datos_personales']['nombres'];
  	$sexo				= $this->data['cdvp01_datos_personales']['sexo'];
  	$direccion			= $this->data['cdvp01_datos_personales']['direccion'];
  	$telefonos			= $this->data['cdvp01_datos_personales']['telefonos'];
  	$rif				= $this->data['cdvp01_datos_personales']['rif'];
  	$razon_social		= $this->data['cdvp01_datos_personales']['razon'];
  	$username_registro	= $_SESSION['nom_usuario'];
  	//$fecha_registro		= date("d/m/Y");
	$fecha_registro		= $this->data['cdvp01_datos_personales']['fecha'];
//  numero_control
	$cod_dir_superior 	= $this->data['cdvp01_datos_personales']['cod_direccions'];
	$cod_coordinacion 	= $this->data['cdvp01_datos_personales']['cod_coordinacion'];
	$cod_secretaria 	= $this->data['cdvp01_datos_personales']['cod_secretaria'];
	$cod_direccion 		= $this->data['cdvp01_datos_personales']['cod_direccion'];
  	$observaciones		= $this->data['cdvp01_datos_personales']['observaciones'];
  	$hora				= $this->data['cdvp01_datos_personales']['hora'];

    $cont = $this->cdvd01_datos_personales->findCount('cedula_identidad='.$cedula_identidad);
	if($cont=='0'){
 		//cedula_identidad, nombres_apellidos, sexo, direccion, telefonos, rif, razon_social, username_registro, fecha_registro
		$sql ="INSERT INTO cdvd01_datos_personales (cedula_identidad, nombres_apellidos, sexo, direccion, telefonos, rif, razon_social, username_registro, fecha_registro)";
		$sql.="VALUES ($cedula_identidad, '".$nombres_apellidos."', $sexo, '".$direccion."', '".$telefonos."', '".$rif."', '".$razon_social."', '".$username_registro."', '".$fecha_registro."');";
		$sw1 = $this->cdvd01_datos_personales->execute($sql);
		$this->set('Message_existe', 'Los datos fueron guardados con exito.');
	}
	$num = $this->cdvd01_visitas->findAll($this->SQLCA().' and cedula_identidad='.$cedula_identidad,null,'numero_control DESC');
	if($num==null){
		$numero_control = 1;
	}else{
		$numero_control = $num[0]['cdvd01_visitas']['numero_control'] + 1;
	}
	//cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cedula_identidad, numero_control,
  	//cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, observaciones, username_registro, fecha_registro, hora
	$sql2 ="INSERT INTO cdvd01_visitas (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cedula_identidad, numero_control,
  	cod_dir_superior, cod_coordinacion, cod_secretaria, cod_direccion, observaciones, username_registro, fecha_registro, hora)";
	$sql2.="VALUES ($cod_presi, $cod_entidad, $cod_tipo_inst, $cod_inst, $cod_dep, $cedula_identidad, $numero_control,
  	$cod_dir_superior, $cod_coordinacion, $cod_secretaria, $cod_direccion, '".$observaciones."', '".$username_registro."', '".$fecha_registro."', '".$hora."');";
	$sw2 = $this->cdvd01_visitas->execute($sql2);

	//$datos  =$this->cdvd01_datos_personales->findAll('cedula_identidad='.$cedula_identidad);
	//$this->set('datos',$datos);
	$datos =$this->v_cdvd01_visitas->findAll($this->SQLCA().' and cedula_identidad='.$cedula_identidad,null,'fecha_registro,hora,numero_control ASC');
	$this->set('datos',$datos);
	echo'<script>';
       echo" document.getElementById('fecha').value           	= ''; ";
       echo" document.getElementById('hora').value           	= ''; ";
       echo" document.getElementById('x_7').value           	= ''; ";
       echo" document.getElementById('x_8').value           	= ''; ";
       echo" document.getElementById('x_9').value           	= ''; ";
       echo" document.getElementById('x_10').value           	= ''; ";
       echo" document.getElementById('observaciones').value    	= ''; ";
	echo'</script>';

}

function grilla($cedula_identidad=null){
	$this->layout='ajax';
	//$hora2 = array('01:00AM','02:00AM','03:00AM','04:00AM','05:00AM','06:00AM','07:00AM','08:00AM','09:00AM','10:00AM','11:00AM','12:00AM','01:00PM','02:00PM','03:00PM','04:00PM','05:00PM','06:00PM','07:00PM','08:00PM','09:00PM','10:00PM','11:00PM','12:00PM');
	//$this->set('hora2',$hora2);
	$datos2  =$this->cdvd01_datos_personales->findAll('cedula_identidad='.$cedula_identidad);
	if($datos2 != null){
	$nombres 	= $datos2[0]['cdvd01_datos_personales']['nombres_apellidos'];
	$sexo 		= $datos2[0]['cdvd01_datos_personales']['sexo'];
	if($sexo =='1'){
		$sexo_1 = true;
		$sexo_2 = false;
	}else if($sexo == '2'){
		$sexo_2 = true;
		$sexo_1 = false;
	}
	$direccion 	= $datos2[0]['cdvd01_datos_personales']['direccion'];
	$telefonos 	= $datos2[0]['cdvd01_datos_personales']['telefonos'];
	$rif 		= $datos2[0]['cdvd01_datos_personales']['rif'];
	$razon 		= $datos2[0]['cdvd01_datos_personales']['razon_social'];

	$datos =$this->v_cdvd01_visitas->findAll($this->SQLCA().' and cedula_identidad='.$cedula_identidad,null,'fecha_registro,hora,numero_control ASC');
	$this->set('datos',$datos);

	echo'<script>';
       	echo" document.getElementById('nombres').value           	= '".$nombres."'; ";
       	echo" document.getElementById('sexo_1').checked           	= '".$sexo_1."'; ";
       	echo" document.getElementById('sexo_2').checked           	= '".$sexo_2."'; ";
       	echo" document.getElementById('direccion').value           	= '".$direccion."'; ";
       	echo" document.getElementById('telefonos').value           	= '".$telefonos."'; ";
       	echo" document.getElementById('rif').value           		= '".$rif."'; ";
       	echo" document.getElementById('razon').value           		= '".$razon."'; ";
       	echo" document.getElementById('nombres').readOnly           = 'true'; ";
       	echo" document.getElementById('sexo_1').disabled           	= 'true'; ";
       	echo" document.getElementById('sexo_2').disabled           	= 'true'; ";
       	echo" document.getElementById('direccion').readOnly         = 'true'; ";
       	echo" document.getElementById('telefonos').readOnly         = 'true'; ";
       	echo" document.getElementById('rif').readOnly           	= 'true'; ";
       	echo" document.getElementById('razon').readOnly           	= 'true'; ";
	echo'</script>';

	}else{
		$sexo_1 = true;
		$sexo_2 = false;
		echo'<script>';
       	echo" document.getElementById('nombres').value           	= ''; ";
       	echo" document.getElementById('sexo_1').checked           	= '".$sexo_1."'; ";
       	echo" document.getElementById('sexo_2').checked           	= '".$sexo_2."'; ";
       	echo" document.getElementById('direccion').value           	= ''; ";
       	echo" document.getElementById('telefonos').value           	= ''; ";
       	echo" document.getElementById('rif').value           		= ''; ";
       	echo" document.getElementById('razon').value           		= ''; ";
       	echo" document.getElementById('nombres').readOnly           = ''; ";
       	echo" document.getElementById('sexo_1').disabled           	= ''; ";
       	echo" document.getElementById('sexo_2').disabled           	= ''; ";
       	echo" document.getElementById('direccion').readOnly         = ''; ";
       	echo" document.getElementById('telefonos').readOnly         = ''; ";
       	echo" document.getElementById('rif').readOnly           	= ''; ";
       	echo" document.getElementById('razon').readOnly           	= ''; ";
	echo'</script>';
	}
}

function editar($cedula_identidad=null,$numero_control=null,$i=null){
	$this->layout='ajax';
	$hora2 = array('01:00AM','02:00AM','03:00AM','04:00AM','05:00AM','06:00AM','07:00AM','08:00AM','09:00AM','10:00AM','11:00AM','12:00AM','01:00PM','02:00PM','03:00PM','04:00PM','05:00PM','06:00PM','07:00PM','08:00PM','09:00PM','10:00PM','11:00PM','12:00PM');
	$this->set('hora2',$hora2);
	$datos =$this->v_cdvd01_visitas->findAll($this->SQLCA().' and cedula_identidad='.$cedula_identidad.' and numero_control='.$numero_control);
	$fecha_registro 	= $datos[0]['v_cdvd01_visitas']['fecha_registro'];
	$hora 				= $datos[0]['v_cdvd01_visitas']['hora'];
	$cod_dir_superior 	= $datos[0]['v_cdvd01_visitas']['deno_dir_superior'];
	$cod_coordinacion 	= $datos[0]['v_cdvd01_visitas']['deno_coordinacion'];
	$cod_secretaria 	= $datos[0]['v_cdvd01_visitas']['deno_secretaria'];
	$cod_direccion 		= $datos[0]['v_cdvd01_visitas']['deno_direccion'];
	$observaciones 		= $datos[0]['v_cdvd01_visitas']['observaciones'];

	if($hora=='0'){$hora='01:00AM';}
	if($hora=='1'){$hora='02:00AM';}
	if($hora=='2'){$hora='03:00AM';}
	if($hora=='3'){$hora='04:00AM';}
	if($hora=='4'){$hora='05:00AM';}
	if($hora=='5'){$hora='06:00AM';}
	if($hora=='6'){$hora='07:00AM';}
	if($hora=='7'){$hora='08:00AM';}
	if($hora=='8'){$hora='09:00AM';}
	if($hora=='9'){$hora='10:00AM';}
	if($hora=='10'){$hora='11:00AM';}
	if($hora=='11'){$hora='12:00AM';}
	if($hora=='12'){$hora='01:00PM';}
	if($hora=='13'){$hora='02:00PM';}
	if($hora=='14'){$hora='03:00PM';}
	if($hora=='15'){$hora='04:00PM';}
	if($hora=='16'){$hora='05:00PM';}
	if($hora=='17'){$hora='06:00PM';}
	if($hora=='18'){$hora='07:00PM';}
	if($hora=='19'){$hora='08:00PM';}
	if($hora=='20'){$hora='09:00PM';}
	if($hora=='21'){$hora='10:00PM';}
	if($hora=='22'){$hora='11:00PM';}
	if($hora=='23'){$hora='12:00PM';}




    $this->set('cedula_identidad',$cedula_identidad);
    $this->set('numero_control',$numero_control);
    $this->set('i',$i);
    $this->set('deno_dir_superior',$cod_dir_superior);
    $this->set('deno_coordinacion',$cod_coordinacion);
    $this->set('deno_secretaria',$cod_secretaria);
    $this->set('deno_direccion',$cod_direccion);
    $this->set('observaciones',$observaciones);
    $this->set('hora',$hora);
    $this->set('fecha_registro',$fecha_registro);
}

function cancelar($cedula_identidad=null,$numero_control=null,$i=null){
	$this->layout='ajax';
	//$hora2 = array('01:00AM','02:00AM','03:00AM','04:00AM','05:00AM','06:00AM','07:00AM','08:00AM','09:00AM','10:00AM','11:00AM','12:00AM','01:00PM','02:00PM','03:00PM','04:00PM','05:00PM','06:00PM','07:00PM','08:00PM','09:00PM','10:00PM','11:00PM','12:00PM');
	//$this->set('hora2',$hora2);
	$datos =$this->v_cdvd01_visitas->findAll($this->SQLCA().' and cedula_identidad='.$cedula_identidad.' and numero_control='.$numero_control);
	$fecha_registro 	= $datos[0]['v_cdvd01_visitas']['fecha_registro'];
	$hora 				= $datos[0]['v_cdvd01_visitas']['hora'];
	$cod_dir_superior 	= $datos[0]['v_cdvd01_visitas']['deno_dir_superior'];
	$cod_coordinacion 	= $datos[0]['v_cdvd01_visitas']['deno_coordinacion'];
	$cod_secretaria 	= $datos[0]['v_cdvd01_visitas']['deno_secretaria'];
	$cod_direccion 		= $datos[0]['v_cdvd01_visitas']['deno_direccion'];
	$observaciones 		= $datos[0]['v_cdvd01_visitas']['observaciones'];
	if($hora=='0'){$hora='01:00AM';}
	if($hora=='1'){$hora='02:00AM';}
	if($hora=='2'){$hora='03:00AM';}
	if($hora=='3'){$hora='04:00AM';}
	if($hora=='4'){$hora='05:00AM';}
	if($hora=='5'){$hora='06:00AM';}
	if($hora=='6'){$hora='07:00AM';}
	if($hora=='7'){$hora='08:00AM';}
	if($hora=='8'){$hora='09:00AM';}
	if($hora=='9'){$hora='10:00AM';}
	if($hora=='10'){$hora='11:00AM';}
	if($hora=='11'){$hora='12:00AM';}
	if($hora=='12'){$hora='01:00PM';}
	if($hora=='13'){$hora='02:00PM';}
	if($hora=='14'){$hora='03:00PM';}
	if($hora=='15'){$hora='04:00PM';}
	if($hora=='16'){$hora='05:00PM';}
	if($hora=='17'){$hora='06:00PM';}
	if($hora=='18'){$hora='07:00PM';}
	if($hora=='19'){$hora='08:00PM';}
	if($hora=='20'){$hora='09:00PM';}
	if($hora=='21'){$hora='10:00PM';}
	if($hora=='22'){$hora='11:00PM';}
	if($hora=='23'){$hora='12:00PM';}

    $this->set('cedula_identidad',$cedula_identidad);
    $this->set('numero_control',$numero_control);
    $this->set('i',$i);
    $this->set('deno_dir_superior',$cod_dir_superior);
    $this->set('deno_coordinacion',$cod_coordinacion);
    $this->set('deno_secretaria',$cod_secretaria);
    $this->set('deno_direccion',$cod_direccion);
    $this->set('observaciones',$observaciones);
    $this->set('hora',$hora);
    $this->set('fecha_registro',$fecha_registro);
}

function guardar_editar($cedula_identidad=null,$numero_control=null,$i=null){
	$this->layout='ajax';
	//pr($this->data);
	$observaciones = $this->data['cdvp01_datos_personales']["observaciones_$i"];
	//if($observaciones != null){
	$upd = "UPDATE cdvd01_visitas set observaciones='".$observaciones."' where cedula_identidad=$cedula_identidad and numero_control=$numero_control and ".$this->SQLCA();
	$this->cdvd01_visitas->execute($upd);
		$this->set('Message_existe', 'Registro Modificado con exito.');
//	}else{
	//	$this->set('errorMessage', 'ingrese observaciones, registro no fue Modificado.');
//	}
	$datos =$this->v_cdvd01_visitas->findAll($this->SQLCA().' and cedula_identidad='.$cedula_identidad.' and numero_control='.$numero_control);
	$fecha_registro 	= $datos[0]['v_cdvd01_visitas']['fecha_registro'];
	$hora 				= $datos[0]['v_cdvd01_visitas']['hora'];
	$cod_dir_superior 	= $datos[0]['v_cdvd01_visitas']['deno_dir_superior'];
	$cod_coordinacion 	= $datos[0]['v_cdvd01_visitas']['deno_coordinacion'];
	$cod_secretaria 	= $datos[0]['v_cdvd01_visitas']['deno_secretaria'];
	$cod_direccion 		= $datos[0]['v_cdvd01_visitas']['deno_direccion'];
	$observaciones 		= $datos[0]['v_cdvd01_visitas']['observaciones'];
	if($hora=='0'){$hora='01:00AM';}
	if($hora=='1'){$hora='02:00AM';}
	if($hora=='2'){$hora='03:00AM';}
	if($hora=='3'){$hora='04:00AM';}
	if($hora=='4'){$hora='05:00AM';}
	if($hora=='5'){$hora='06:00AM';}
	if($hora=='6'){$hora='07:00AM';}
	if($hora=='7'){$hora='08:00AM';}
	if($hora=='8'){$hora='09:00AM';}
	if($hora=='9'){$hora='10:00AM';}
	if($hora=='10'){$hora='11:00AM';}
	if($hora=='11'){$hora='12:00AM';}
	if($hora=='12'){$hora='01:00PM';}
	if($hora=='13'){$hora='02:00PM';}
	if($hora=='14'){$hora='03:00PM';}
	if($hora=='15'){$hora='04:00PM';}
	if($hora=='16'){$hora='05:00PM';}
	if($hora=='17'){$hora='06:00PM';}
	if($hora=='18'){$hora='07:00PM';}
	if($hora=='19'){$hora='08:00PM';}
	if($hora=='20'){$hora='09:00PM';}
	if($hora=='21'){$hora='10:00PM';}
	if($hora=='22'){$hora='11:00PM';}
	if($hora=='23'){$hora='12:00PM';}

    $this->set('cedula_identidad',$cedula_identidad);
    $this->set('numero_control',$numero_control);
    $this->set('i',$i);
    $this->set('deno_dir_superior',$cod_dir_superior);
    $this->set('deno_coordinacion',$cod_coordinacion);
    $this->set('deno_secretaria',$cod_secretaria);
    $this->set('deno_direccion',$cod_direccion);
    $this->set('observaciones',$observaciones);
    $this->set('hora',$hora);
    $this->set('fecha_registro',$fecha_registro);

}

function eliminar_grilla($cedula_identidad=null,$numero_control=null,$i=null){
	$this->layout='ajax';
	$del = 'DELETE FROM cdvd01_visitas WHERE cedula_identidad='.$cedula_identidad.' and numero_control='.$numero_control.' and '.$this->SQLCA();
	$this->cdvd01_visitas->execute($del);
	$this->set('Message_existe', 'Registro eliminado con exito.');
	$datos =$this->v_cdvd01_visitas->findAll($this->SQLCA().' and cedula_identidad='.$cedula_identidad,null,'fecha_registro,hora,numero_control ASC');
	$this->set('datos',$datos);

}

	function consulta($pagina=null){
 		$this->layout = "ajax";
        if($pagina!=null){
        	$pagina=$pagina;
          	$this->set('pagina',$pagina);
          	$Tfilas=$this->cdvd01_datos_personales->findCount();
          	if($Tfilas==0){
          		$this->set('Message_existe', 'No se encontraron datos.');
          	 	$this->set('validado',true);
				$this->index();
				$this->render("index");
          	}
          	if($Tfilas!=0){
          		$this->set('pag_cant',$pagina.'/'.$Tfilas);
          		$datos2=$this->cdvd01_datos_personales->findAll(null,null,'cedula_identidad ASC',1,$pagina,null);
          	 	$this->set('datos2',$datos2);
          	 	$cedula_identidad = $datos2[0]['cdvd01_datos_personales']['cedula_identidad'];
          	 	$datos = $this->v_cdvd01_visitas->findAll($this->SQLCA().' and cedula_identidad='.$cedula_identidad,null,'fecha_registro,hora,numero_control ASC');
    			$this->set('datos',$datos);
          	 	$this->set('siguiente',$pagina+1);
          	 	$this->set('anterior',$pagina-1);
             	$this->bt_nav($Tfilas,$pagina);
           	}
 		}else{
 			$pagina=1;
 			$this->set('pagina',$pagina);
          	$Tfilas=$this->cdvd01_datos_personales->findCount();
          	if($Tfilas==0){
          		$this->set('Message_existe', 'No se encontraron datos.');
          	 	$this->set('validado',true);
				$this->index();
				$this->render("index");
          	}
          	if($Tfilas!=0){
          		$this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 	$datos2=$this->cdvd01_datos_personales->findAll(null,null,'cedula_identidad ASC',1,$pagina,null);
          	 	$this->set('datos2',$datos2);
          	 	$cedula_identidad = $datos2[0]['cdvd01_datos_personales']['cedula_identidad'];
          	 	$datos = $this->v_cdvd01_visitas->findAll($this->SQLCA().' and cedula_identidad='.$cedula_identidad,null,'fecha_registro,hora,numero_control ASC');
    			$this->set('datos',$datos);
          	 	$this->set('siguiente',$pagina+1);
          	 	$this->set('anterior',$pagina-1);
             	$this->bt_nav($Tfilas,$pagina);
			}
        }
}//fin function consultar2

	function modificar($cedula_identidad=null,$pagina=null){
 		$this->layout = "ajax";
 		$cod_presi = $this->verifica_SS(1);
		$cod_entidad = $this->verifica_SS(2);
		$cod_tipo_inst = $this->verifica_SS(3);
		$cod_inst = $this->verifica_SS(4);
		$cod_dep = $this->verifica_SS(5);
 		//echo 'ci='.$cedula_identidad;
        $this->set('pagina',$pagina);
		$datos2=$this->cdvd01_datos_personales->findAll('cedula_identidad='.$cedula_identidad);
        $this->set('datos2',$datos2);
        $datos = $this->v_cdvd01_visitas->findAll($this->SQLCA().' and cedula_identidad='.$cedula_identidad,null,'fecha_registro,hora,numero_control ASC');
        $this->set('datos',$datos);
        $this->set('cedula_identidad',$cedula_identidad);
        $hora = array('01:00AM','02:00AM','03:00AM','04:00AM','05:00AM','06:00AM','07:00AM','08:00AM','09:00AM','10:00AM','11:00AM','12:00AM','01:00PM','02:00PM','03:00PM','04:00PM','05:00PM','06:00PM','07:00PM','08:00PM','09:00PM','10:00PM','11:00PM','12:00PM');
		$this->set('hora',$hora);
		$cond2 ="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;
		$lista2 = $this->cugd02_direccionsuperior->generateList($cond2, ' upper(trim(denominacion)) ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
    	$this->set('dir_superior',$lista2);

	}

	function guardar_modificar($cedula_identidad=null,$pagina=null){
		$this->layout='ajax';
		$nombres_apellidos	= $this->data['cdvp01_datos_personales']['nombres'];
  		$sexo				= $this->data['cdvp01_datos_personales']['sexo'];
  		$direccion			= $this->data['cdvp01_datos_personales']['direccion'];
  		$telefonos			= $this->data['cdvp01_datos_personales']['telefonos'];
  		$rif				= $this->data['cdvp01_datos_personales']['rif'];
  		$razon_social		= $this->data['cdvp01_datos_personales']['razon'];
  		$upd = "UPDATE cdvd01_datos_personales SET nombres_apellidos='".$nombres_apellidos."', sexo=$sexo, direccion='".$direccion."', telefonos='".$telefonos."', rif='".$rif."', razon_social='".$razon_social."' WHERE cedula_identidad='".$cedula_identidad."'";
		$this->cdvd01_datos_personales->execute($upd);
		$this->set('Message_existe', 'Registro Modificado con exito.');
		$this->consulta($pagina);
  		$this->render("consulta");
	}

	function eliminar($cedula_identidad=null,$pagina=null){
	$this->layout = "ajax";
		$sql1 ="DELETE  FROM  cdvd01_datos_personales where cedula_identidad=".$cedula_identidad;
		$this->cdvd01_datos_personales->execute($sql1);
		$y=$this->cdvd01_datos_personales->findCount();
		if($pagina>$y){
 			$pagina=$pagina-1;
 		}
 		if($y!=0){
	  		$this->set('Message_existe', 'Registro Eliminado con exito.');
      	 	$this->consulta($pagina);//si es el primero solamente
      		$this->render("consulta");
		}else if($y==0){
			$this->set('Message_existe', 'Registro Eliminado con exito.');
			$this->index();
      		$this->render("index");
		}//fin if
}
//fin eliminar

function buscar($var1=null){
	$this->layout="ajax";
	$this->set("opcion",$var1);
	$this->Session->delete('pista');
}//fin function

function buscar_por_pista2($var1=null, $var2=null, $var3=null){
$this->layout="ajax";
    if($var3==null){$var2 = strtoupper($var2);
					$this->Session->write('pista', $var2);//echo "(rif_cedula LIKE '%$var2%' or razon_social_nombres LIKE '%$var2%')";
					$Tfilas=$this->cdvd01_datos_personales->findCount("(cedula_identidad::text LIKE '%$var2%' or quitar_acentos(nombres_apellidos) LIKE quitar_acentos('%$var2%'))");
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/50);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->cdvd01_datos_personales->findAll("(cedula_identidad::text LIKE '%$var2%' or quitar_acentos(nombres_apellidos) LIKE quitar_acentos('%$var2%'))",null,"nombres_apellidos ASC",50,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }
            }else{
						$var22 = $this->Session->read('pista');
						$var22 = strtoupper($var22);//echo "(rif_cedula LIKE '%$var22%' or razon_social_nombres LIKE '%$var22%')";
						$Tfilas=$this->cdvd01_datos_personales->findCount("(cedula_identidad::text LIKE '%$var2%' or quitar_acentos(nombres_apellidos) LIKE quitar_acentos('%$var2%'))");
						        if($Tfilas!=0){
						        	$pagina=$var3;
						        	$Tfilas=(int)ceil($Tfilas/50);
						        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
									$this->set('total_paginas',$Tfilas);
									$this->set('pagina_actual',$pagina);
									$this->set('ultimo',$Tfilas);
						     	    $datos_filas=$this->cdvd01_datos_personales->findAll("(cedula_identidad::text LIKE '%$var2%' or quitar_acentos(nombres_apellidos) LIKE quitar_acentos('%$var2%'))",null,"nombres_apellidos ASC",50,$pagina,null);
							        $this->set("datosFILAS",$datos_filas);
							        $this->set('siguiente',$pagina+1);
									$this->set('anterior',$pagina-1);
									$this->bt_nav($Tfilas,$pagina);
						          }else{
						        	$this->set("datosFILAS",'');
						          }
                 }//fin else
$this->set("opcion",$var1);
}//fin function

function consulta2($cedula_identidad=null){
	$this->layout = "ajax";
    $veri=$this->cdvd01_datos_personales->findCount('cedula_identidad='.$cedula_identidad);
      if($veri > 0){
		$datos2=$this->cdvd01_datos_personales->findAll('cedula_identidad='.$cedula_identidad);
        $this->set('datos2',$datos2);
        $datos = $this->v_cdvd01_visitas->findAll($this->SQLCA().' and cedula_identidad='.$cedula_identidad,null,'fecha_registro,hora,numero_control ASC');
        $this->set('datos',$datos);
      }else{
	  			$this->index();
				$this->render("index");
          	 }
}//fin function consultar2


}//fin clase cfpp15Controller
?>
