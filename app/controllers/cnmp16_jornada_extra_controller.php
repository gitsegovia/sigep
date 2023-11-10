<?php

 class Cnmp16JornadaExtraController extends AppController{

	var $name = 'cnmp16_jornada_extra';
	var $uses = array('cnmd01', 'cnmd03_transacciones', 'cnmd16_jornada_extra', 'cnmd09_tqcs', 'Cnmd01');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');

function checkSession()
    {
        // Verificar la session del Usuario...
        if (!$this->Session->check('Usuario'))
        {
            // Redireccionar a la salida
            $this->redirect('/salir/');
						exit();
        }
    } // fin funcion checkSession



	function beforeFilter(){

		$this->checkSession();

} // fin funcion beforeFilter


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


function index(){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
    $aux ="";

	$lista = $this->Cnmd01->generateList($condicion, 'cod_tipo_nomina ASC', null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
   	$this->concatenaN($lista, 'nomina');

   	$cnmd03_transacciones = $this->cnmd03_transacciones->generateList2("cod_tipo_transaccion='1'  ", 'cod_transaccion ASC', null, '{n}.cnmd03_transacciones.cod_transaccion', '{n}.cnmd03_transacciones.denominacion');
   	$this->concatenaN($cnmd03_transacciones, 'nomina2');

}//fin function



function cod_nomina($var=null){
    $this->layout = "ajax";
    $this->Session->delete('tipo_nomina_jornada');
	$this->Session->write('tipo_nomina_jornada',$var);
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

     $resultado= $this->Cnmd01->execute("SELECT
			  a.cod_presi,
			  a.cod_entidad,
			  a.cod_tipo_inst,
			  a.cod_inst,
			  a.cod_dep,
			  a.cod_tipo_nomina,
			  a.denominacion

			  from  Cnmd01 a where

			    a.cod_presi              =   ".$cod_presi." and
			    a.cod_entidad            =   ".$cod_entidad." and
			    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
			    a.cod_inst               =   ".$cod_inst." and
			    a.cod_dep                =   ".$cod_dep." and
			    a.cod_tipo_nomina        =   ".$var."
			    ");


echo "<script>";
        echo "document.getElementById('cod_nomina').value='".mascara_tres($resultado[0][0]['cod_tipo_nomina'])."';";
		echo "document.getElementById('deno_nomina').value='".$resultado[0][0]['denominacion']."';";
echo "</script>";


}//fin function




function cod_nomina2($var=null){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;



   $resultado= $this->Cnmd01->execute("SELECT
			  a.cod_tipo_transaccion,
              a.cod_transaccion,
              a.denominacion

			  from  cnmd03_transacciones a where

			    a.cod_tipo_transaccion     =   1 and
			    a.cod_transaccion          =   ".$var.";
			    ");


echo "<script>";
        echo "document.getElementById('cod_transaccion').value='".mascara_tres($resultado[0][0]['cod_transaccion'])."';";
		echo "document.getElementById('deno_transaccion').value='".$resultado[0][0]['denominacion']."';";
echo "</script>";


}//fin function




function guardar(){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

	$cod_tipo_transaccion = "1";

  $cod_tipo_nomina     =  $this->data['cnmp09_tqcs']['cod_nomina'];
  $cod_transaccion     =  $this->data['cnmp09_tqcs']['cod_transaccion'];
  $dias_nom            =  $this->Formato1($this->data['cnmp09_tqcs']['dias_nomina']);
  $dias_bus            =  $this->Formato1($this->data['cnmp09_tqcs']['dias_busqueda']);
  $cont = 0;
  $sql = " INSERT INTO cnmd16_jornada_extra (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_tipo_transaccion, cod_transaccion, dias_mensual_nomina, dias_buscar_historia) ";
  $sql.= "VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_tipo_nomina."', '".$cod_tipo_transaccion."', '".$cod_transaccion."', '".$dias_nom."', '".$dias_bus."'); ";

$cont = $this->cnmd16_jornada_extra->findCount($this->SQLCA()." and cod_tipo_nomina= '".$cod_tipo_nomina."' and cod_tipo_transaccion=1 and cod_transaccion= '".$cod_transaccion."' ");

if($cont==0){
$sw2  = $this->Cnmd01->execute('BEGIN; ');
$sw2  = $this->Cnmd01->execute($sql);

                  if($sw2>1){
		                $this->Cnmd01->execute("COMMIT;");
		                $this->set('Message_existe', 'Los Datos Fueron Guardados Exitosamente');


					}else{
						$this->Cnmd01->execute("ROLLBACK;");
                        $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');

					}//fin else
}else{
	$this->Cnmd01->execute("ROLLBACK;");
    $this->set('errorMessage', 'ESTA TRANSACCI&Oacute;N YA SE ENCUENTRA REGISTRADA PARA ESTE TIPO DE N&Oacute;MINA');
}//fin else


  $resultado= $this->Cnmd01->execute("SELECT
											  a.cod_tipo_nomina,
											  a.cod_tipo_transaccion,
								              a.cod_transaccion,
											  a.dias_mensual_nomina,
											  a.dias_buscar_historia,
								              b.cod_tipo_transaccion as cod_tipo_transaccion_cnmd03,
								              b.cod_transaccion      as cod_transaccion_cnmd03,
								              b.denominacion         as denominacion_cnmd03

											      from  cnmd16_jornada_extra a, cnmd03_transacciones b where

													    a.cod_presi              =   ".$cod_presi." and
													    a.cod_entidad            =   ".$cod_entidad." and
													    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
													    a.cod_inst               =   ".$cod_inst." and
													    a.cod_dep                =   ".$cod_dep." and
													    a.cod_tipo_nomina        =   ".$cod_tipo_nomina." and
													    b.cod_tipo_transaccion   =   1 and
								                        b.cod_transaccion        =   a.cod_transaccion
														order by a.cod_transaccion asc;");

$this->set('accion', $resultado);


} //fin function guardar




function consulta($var=null){
    $this->layout = "ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;


  $resultado= $this->Cnmd01->execute("SELECT
											  a.cod_tipo_nomina,
											  a.cod_tipo_transaccion,
								              a.cod_transaccion,
											  a.dias_mensual_nomina,
											  a.dias_buscar_historia,
								              b.cod_tipo_transaccion as cod_tipo_transaccion_cnmd03,
								              b.cod_transaccion      as cod_transaccion_cnmd03,
								              b.denominacion         as denominacion_cnmd03

											      from  cnmd16_jornada_extra a, cnmd03_transacciones b where

													    a.cod_presi              =   ".$cod_presi." and
													    a.cod_entidad            =   ".$cod_entidad." and
													    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
													    a.cod_inst               =   ".$cod_inst." and
													    a.cod_dep                =   ".$cod_dep." and
													    a.cod_tipo_nomina        =   ".$var." and
													    b.cod_tipo_transaccion   =   1 and
								                        b.cod_transaccion        =   a.cod_transaccion
														order by a.cod_transaccion asc;");

$this->set('accion', $resultado);


} //fin function consulta



function modificar($nomina=null,$codigo_transac=null,$dias_no=null,$dias_bu=null,$i=null){
	$this->layout="ajax";

	$sq_datos = $this->cnmd03_transacciones->execute("SELECT denominacion FROM cnmd03_transacciones WHERE cod_tipo_transaccion = 1 AND cod_transaccion="."'$codigo_transac'"." LIMIT 1;");
	$dato_deno = $sq_datos[0][0]['denominacion'];

	$this->set('nomina',$nomina);
	$this->set('codi_transaccion',$codigo_transac);
	$this->set('denom_tran',$dato_deno);
	$this->set('dias_nomi',$dias_no);
	$this->set('dias_buscar',$dias_bu);
	$this->set('k',$i);

} //fin function modificar




function guardar_modificar($nomina=null,$codigo_transac=null){
	$this->layout="ajax";
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');

	$codigo_tran = $this->data["cnmd16_jornada_extra"]["cod_transaccion"];
	$dias_men_nomi = $this->Formato1($this->data["cnmd16_jornada_extra"]["dias_mensual_nomina"]);
	$dias_buscar_hist = $this->Formato1($this->data["cnmd16_jornada_extra"]["dias_buscar_historia"]);

	if($nomina!=null && $codigo_transac!=null && $dias_men_nomi!="" && $dias_buscar_hist!=""){
		$sw=$this->cnmd16_jornada_extra->execute("update cnmd16_jornada_extra set dias_mensual_nomina=".$dias_men_nomi." , dias_buscar_historia=".$dias_buscar_hist." where ".$this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_tipo_transaccion = 1 and cod_transaccion=".$codigo_transac);
		if($sw>1){
			$this->set('Message_existe','se ha modificado exitosamente');
		}else{
			$this->set('errorMessage','No se pudo modificar, intente nuevamente');
		}
	}else{
		$this->set('errorMessage','Debe ingresar datos en todos los campos');
	}

  $resultado= $this->Cnmd01->execute("SELECT
											  a.cod_tipo_nomina,
											  a.cod_tipo_transaccion,
								              a.cod_transaccion,
											  a.dias_mensual_nomina,
											  a.dias_buscar_historia,
								              b.cod_tipo_transaccion as cod_tipo_transaccion_cnmd03,
								              b.cod_transaccion      as cod_transaccion_cnmd03,
								              b.denominacion         as denominacion_cnmd03

											      from  cnmd16_jornada_extra a, cnmd03_transacciones b where

													    a.cod_presi              =   ".$cod_presi." and
													    a.cod_entidad            =   ".$cod_entidad." and
													    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
													    a.cod_inst               =   ".$cod_inst." and
													    a.cod_dep                =   ".$cod_dep." and
													    a.cod_tipo_nomina        =   ".$nomina." and
													    b.cod_tipo_transaccion   =   1 and
								                        b.cod_transaccion        =   a.cod_transaccion
														order by a.cod_transaccion asc;");

$this->set('accion', $resultado);

} //fin function guardar_modificar



function eliminar($var=null, $var2=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

  $sql="BEGIN;  DELETE FROM cnmd16_jornada_extra WHERE cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina= '".$var."' and cod_tipo_transaccion=1 and cod_transaccion= '".$var2."' ";

  $sw2 = $this->Cnmd01->execute($sql);
			if($sw2>1){
                $this->Cnmd01->execute("COMMIT;");
		        $this->set('Message_existe', 'LOS DATOS FUERON ELIMINADOS CORRECTAMENTE');
			}else{
				$this->Cnmd01->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINADOS - POR FAVOR INTENTE DE NUEVO');
			}//fin else

  $resultado= $this->Cnmd01->execute("SELECT
											  a.cod_tipo_nomina,
											  a.cod_tipo_transaccion,
								              a.cod_transaccion,
											  a.dias_mensual_nomina,
											  a.dias_buscar_historia,
								              b.cod_tipo_transaccion as cod_tipo_transaccion_cnmd03,
								              b.cod_transaccion      as cod_transaccion_cnmd03,
								              b.denominacion         as denominacion_cnmd03

											      from  cnmd16_jornada_extra a, cnmd03_transacciones b where

													    a.cod_presi              =   ".$cod_presi." and
													    a.cod_entidad            =   ".$cod_entidad." and
													    a.cod_tipo_inst          =   ".$cod_tipo_inst." and
													    a.cod_inst               =   ".$cod_inst." and
													    a.cod_dep                =   ".$cod_dep." and
													    a.cod_tipo_nomina        =   ".$var." and
													    b.cod_tipo_transaccion   =   1 and
								                        b.cod_transaccion        =   a.cod_transaccion
														order by a.cod_transaccion asc;");

$this->set('accion', $resultado);

} //fin funtion eliminar item




function eliminar_completo(){
	$this->layout="ajax";
	$nomina=$this->Session->read('tipo_nomina_jornada');
	if($nomina!=""){
		if($this->cnmd16_jornada_extra->FindAll($this->SQLCA()." and cod_tipo_nomina=".$nomina)){
			$sw1=$this->cnmd16_jornada_extra->execute("delete from cnmd16_jornada_extra where ".$this->SQLCA()." and cod_tipo_nomina=".$nomina." and cod_tipo_transaccion=1");
			if($sw1>1){
				$this->set('Message_existe','se elimino exitosamente');

			}else{
				$this->set('errorMessage','no se pudo eliminar');
			}
		}else{
			$this->set('errorMessage','El codigo que intenta eliminar no existe registrado!');
		}
	}else{
		$this->set('errorMessage','debe seleccionar el codigo a eliminar');
	}
	echo "<script>";
		echo "document.getElementById('cod_nomina').value='';";
		echo "document.getElementById('deno_nomina').value='';";
		echo "document.getElementById('cod_transaccion').value='';";
		echo "document.getElementById('deno_transaccion').value='';";
		echo "document.getElementById('select_1').value='';";
		echo "document.getElementById('select2_1').value='';";
		echo "document.getElementById('consulta').innerHTML='';";
	echo "</script>";


} //fin function eliminar_completo

 }

?>
