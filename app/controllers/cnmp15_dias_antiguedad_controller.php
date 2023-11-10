<?php
/*
 * Creado el 31/10/2007
 *
 * Miguelangel Cabrera
 * miguel4ngel@gmail.com
 *
 * 10:46:32 AM
 */
 class Cnmp15DiasAntiguedadController extends AppController {

   var $name = "cnmp15_dias_antiguedad";
   var $uses = array('cnmd15_dias_antiguedad', 'Cnmd01','ccfd04_cierre_mes', 'v_cnmd05');
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
    $this->layout ="ajax";
    $cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');
    $modulo = $this->Session->read('Modulo');
    $ano = $this->ano_ejecucion();

    $lista = $this->cnmd15_dias_antiguedad->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.cnmd15_dias_antiguedad.cod_tipo_nomina', '{n}.cnmd15_dias_antiguedad.cod_tipo_nomina');


    $resultado= $this->cnmd15_dias_antiguedad->execute("SELECT
  b.cod_presi,
  b.cod_entidad,
  b.cod_tipo_inst,
  b.cod_inst,
  b.cod_dep,
  b.cod_tipo_nomina,
  a.denominacion

  from  cnmd01 a, cnmd15_dias_antiguedad b where

    b.cod_presi              =   ".$cod_presi." and
    b.cod_entidad            =   ".$cod_entidad." and
    b.cod_tipo_inst          =   ".$cod_tipo_inst." and
    b.cod_inst               =   ".$cod_inst." and
    b.cod_dep                =   ".$cod_dep." and
    a.cod_presi              =   b.cod_presi and
    a.cod_entidad            =   b.cod_entidad and
    a.cod_tipo_inst          =   b.cod_tipo_inst and
    a.cod_inst               =   b.cod_inst and
    a.cod_dep                =   b.cod_dep and
    a.cod_tipo_nomina        =   b.cod_tipo_nomina   ORDER BY b.cod_tipo_nomina;");

    foreach($resultado as $ve){
          $lista[$ve[0]['cod_tipo_nomina']]=mascara_tres($ve[0]['cod_tipo_nomina']).' - '.$ve[0]['denominacion'];
    }//fin foreach

   $this->set( 'nomina', $lista);

	$lista1 = $this->Cnmd01->FindAll($this->SQLCA());
	$this->set('lista1',$lista1);
	$this->set('ano', $ano);

	$lista2 = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
	$this->concatenaN($lista2, 'nomina2');

}//fin function




function cod_nomina($opcion=null, $cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$deno_nomina = "";
		$this->set('cod_nomina', $cod_nomina);
		$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$cod_nomina'", $order ="cod_tipo_nomina ASC");
		$this->set('deno_nomina', $deno_nomina);
	}//fin if
	echo "<script>";
		echo "document.getElementById('cod_nomina_".$opcion."').value='".$this->AddCeroR2($cod_nomina)."';";
		echo "document.getElementById('deno_nomina_".$opcion."').value='".$deno_nomina."';";
		echo" document.getElementById('trasnferir').disabled = false;";
	echo "</script>";
}//fin function







function cod_nomina2($cod_nomina=null){
	$this->layout="ajax";
	if($cod_nomina!=null){
		$deno_nomina = "";
		$this->set('cod_nomina', $cod_nomina);
		$deno_nomina = $this->Cnmd01->field('denominacion', $conditions = $this->condicion()." and Cnmd01.cod_tipo_nomina='$cod_nomina'", $order ="cod_tipo_nomina ASC");
		$this->set('deno_nomina', $deno_nomina);
	}//fin if
	echo "<script>";
		echo "document.getElementById('cod_nomina').value='".$this->AddCeroR2($cod_nomina)."';";
		echo "document.getElementById('deno_nomina').value='".$deno_nomina."';";
	echo "</script>";
	$this->render('cod_nomina');
}//fin function












function guardar($var1=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $ano = $this->ano_ejecucion();
  $this->set('ano', $ano);
  $cont = 0;
  $cod_tipo_nomina     =  $this->data['cnmp15_dias_antiguedad']['cod_nomina'];
  $ano                 =  $this->data['cnmp15_dias_antiguedad']['ano'];
  $dias_ene            =  $this->Formato1($this->data['cnmp15_dias_antiguedad']['ene']);
  $dias_feb            =  $this->Formato1($this->data['cnmp15_dias_antiguedad']['feb']);
  $dias_mar            =  $this->Formato1($this->data['cnmp15_dias_antiguedad']['mar']);
  $dias_abr            =  $this->Formato1($this->data['cnmp15_dias_antiguedad']['abr']);
  $dias_may            =  $this->Formato1($this->data['cnmp15_dias_antiguedad']['may']);
  $dias_jun            =  $this->Formato1($this->data['cnmp15_dias_antiguedad']['jun']);
  $dias_jul            =  $this->Formato1($this->data['cnmp15_dias_antiguedad']['jul']);
  $dias_ago            =  $this->Formato1($this->data['cnmp15_dias_antiguedad']['ago']);
  $dias_sep            =  $this->Formato1($this->data['cnmp15_dias_antiguedad']['sep']);
  $dias_oct            =  $this->Formato1($this->data['cnmp15_dias_antiguedad']['oct']);
  $dias_nov            =  $this->Formato1($this->data['cnmp15_dias_antiguedad']['nov']);
  $dias_dic            =  $this->Formato1($this->data['cnmp15_dias_antiguedad']['dic']);
$sw2  = $this->cnmd15_dias_antiguedad->execute('BEGIN; ');
$cont = $this->cnmd15_dias_antiguedad->findCount(" cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina  = '".$cod_tipo_nomina."' and ano=".$ano);
$opcion = 'si';
if($cont==0){
		$sql =" INSERT INTO cnmd15_dias_antiguedad (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, dias_ene, dias_feb, dias_mar, dias_abr, dias_may, dias_jun, dias_jul, dias_ago, dias_sep, dias_oct, dias_nov, dias_dic)";
		$sql.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_tipo_nomina."', '".$ano."', '".$dias_ene."', '".$dias_feb."', '".$dias_mar."', '".$dias_abr."', '".$dias_may."', '".$dias_jun."', '".$dias_jul."', '".$dias_ago."', '".$dias_sep."', '".$dias_oct."', '".$dias_nov."', '".$dias_dic."'); ";
}else{
        $sql = " UPDATE cnmd15_dias_antiguedad SET dias_ene='".$dias_ene."', dias_feb='".$dias_feb."', dias_mar='".$dias_mar."', dias_abr='".$dias_abr."', dias_may='".$dias_may."', dias_jun='".$dias_jun."', dias_jul='".$dias_jul."', dias_ago='".$dias_ago."', dias_sep='".$dias_sep."', dias_oct='".$dias_oct."', dias_nov='".$dias_nov."', dias_dic='".$dias_dic."'  where cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina  = '".$cod_tipo_nomina."' and  ano=".$ano;
}//fin else
if($opcion=='si'){
		$sw2 = $this->cnmd15_dias_antiguedad->execute($sql);

					if($sw2>1){
		                $this->cnmd15_dias_antiguedad->execute("COMMIT;");
				        $this->set('Message_existe', 'Los datos fueron guardados correctamente');
					}else{
						$this->cnmd15_dias_antiguedad->execute("ROLLBACK;");
						$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
					}//fin else
}else{

						$this->cnmd15_dias_antiguedad->execute("ROLLBACK;");
						$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');

}//fin else
  $accion =  $this->cnmd15_dias_antiguedad->findAll(" cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina  = '".$cod_tipo_nomina."' ", null, 'ano DESC');
  $this->set('accion', $accion);
  $this->set('cedula', $var1);
            echo "<script>";
              echo" document.getElementById('ano').value = '';";
		      echo" document.getElementById('ene').value = '5,00';";
		      echo" document.getElementById('feb').value = '5,00';";
		      echo" document.getElementById('mar').value = '5,00';";
		      echo" document.getElementById('abr').value = '5,00';";
		      echo" document.getElementById('may').value = '5,00';";
		      echo" document.getElementById('jun').value = '5,00';";
		      echo" document.getElementById('jul').value = '5,00';";
		      echo" document.getElementById('ago').value = '5,00';";
		      echo" document.getElementById('sep').value = '5,00';";
		      echo" document.getElementById('oct').value = '5,00';";
		      echo" document.getElementById('nov').value = '5,00';";
		      echo" document.getElementById('dic').value = '5,00';";
			echo "</script>";
}//fin funtion









function transferir(){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $ano = $this->ano_ejecucion();
  $this->set('ano', $ano);
  $cont = 0;
$cod_nomina_desde     =  $this->data['cnmp15_dias_antiguedad']['cod_nomina_desde'];
$cod_nomina_hasta     =  $this->data['cnmp15_dias_antiguedad']['cod_nomina_hasta'];
  $accion2 =  $this->cnmd15_dias_antiguedad->findAll(" cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina  = '".$cod_nomina_desde."' ", null, 'ano DESC');
  $sw2  = $this->cnmd15_dias_antiguedad->execute('BEGIN; ');
  foreach($accion2 as $ve){
  	                $ano       =  $ve['cnmd15_dias_antiguedad']['ano'];
				    $dias_ene  =  $ve['cnmd15_dias_antiguedad']['dias_ene'];
				    $dias_feb  =  $ve['cnmd15_dias_antiguedad']['dias_feb'];
				    $dias_mar  =  $ve['cnmd15_dias_antiguedad']['dias_mar'];
				    $dias_abr  =  $ve['cnmd15_dias_antiguedad']['dias_abr'];
				    $dias_may  =  $ve['cnmd15_dias_antiguedad']['dias_may'];
				    $dias_jun  =  $ve['cnmd15_dias_antiguedad']['dias_jun'];
				    $dias_jul  =  $ve['cnmd15_dias_antiguedad']['dias_jul'];
				    $dias_ago  =  $ve['cnmd15_dias_antiguedad']['dias_ago'];
				    $dias_sep  =  $ve['cnmd15_dias_antiguedad']['dias_sep'];
				    $dias_oct  =  $ve['cnmd15_dias_antiguedad']['dias_oct'];
				    $dias_nov  =  $ve['cnmd15_dias_antiguedad']['dias_nov'];
				    $dias_dic  =  $ve['cnmd15_dias_antiguedad']['dias_dic'];
        $sql =" INSERT INTO cnmd15_dias_antiguedad (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, ano, dias_ene, dias_feb, dias_mar, dias_abr, dias_may, dias_jun, dias_jul, dias_ago, dias_sep, dias_oct, dias_nov, dias_dic)";
		$sql.="VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$cod_nomina_hasta."', '".$ano."', '".$dias_ene."', '".$dias_feb."', '".$dias_mar."', '".$dias_abr."', '".$dias_may."', '".$dias_jun."', '".$dias_jul."', '".$dias_ago."', '".$dias_sep."', '".$dias_oct."', '".$dias_nov."', '".$dias_dic."'); ";
        $sw2 = $this->cnmd15_dias_antiguedad->execute($sql);
     if($sw2>1){}else{break;}

  }//fin foreach

                    if($sw2>1){
		                $this->cnmd15_dias_antiguedad->execute("COMMIT;");
				        $this->set('Message_existe', 'Los datos fueron transferidos');
					}else{
						$this->cnmd15_dias_antiguedad->execute("ROLLBACK;");
						$this->set('errorMessage', 'LOS DATOS NO fueron transferidos - POR FAVOR INTENTE DE NUEVO');
					}//fin else
  $accion =  $this->cnmd15_dias_antiguedad->findAll(" cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina  = '".$cod_nomina_hasta."' ", null, 'ano DESC');
  $this->set('accion', $accion);
}//fin functin









function editar($cod_tipo_nomina=null, $ano=null){
 $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $accion =  $this->cnmd15_dias_antiguedad->findAll(" cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina  = '".$cod_tipo_nomina."' and ano=".$ano, null, 'ano DESC');
          echo "<script>";
              echo" document.getElementById('ano').value = '".$accion[0]['cnmd15_dias_antiguedad']['ano']."';";
		      echo" document.getElementById('ene').value = '".$this->Formato2($accion[0]['cnmd15_dias_antiguedad']['dias_ene'])."';";
		      echo" document.getElementById('feb').value = '".$this->Formato2($accion[0]['cnmd15_dias_antiguedad']['dias_feb'])."';";
		      echo" document.getElementById('mar').value = '".$this->Formato2($accion[0]['cnmd15_dias_antiguedad']['dias_mar'])."';";
		      echo" document.getElementById('abr').value = '".$this->Formato2($accion[0]['cnmd15_dias_antiguedad']['dias_abr'])."';";
		      echo" document.getElementById('may').value = '".$this->Formato2($accion[0]['cnmd15_dias_antiguedad']['dias_may'])."';";
		      echo" document.getElementById('jun').value = '".$this->Formato2($accion[0]['cnmd15_dias_antiguedad']['dias_jun'])."';";
		      echo" document.getElementById('jul').value = '".$this->Formato2($accion[0]['cnmd15_dias_antiguedad']['dias_jul'])."';";
		      echo" document.getElementById('ago').value = '".$this->Formato2($accion[0]['cnmd15_dias_antiguedad']['dias_ago'])."';";
		      echo" document.getElementById('sep').value = '".$this->Formato2($accion[0]['cnmd15_dias_antiguedad']['dias_sep'])."';";
		      echo" document.getElementById('oct').value = '".$this->Formato2($accion[0]['cnmd15_dias_antiguedad']['dias_oct'])."';";
		      echo" document.getElementById('nov').value = '".$this->Formato2($accion[0]['cnmd15_dias_antiguedad']['dias_nov'])."';";
		      echo" document.getElementById('dic').value = '".$this->Formato2($accion[0]['cnmd15_dias_antiguedad']['dias_dic'])."';";
			echo "</script>";
$this->set('Message_existe', 'PUEDE EDITAR LOS DATOS');
}//fin function







function eliminar($cod_tipo_nomina=null, $ano=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $sql="BEGIN;  DELETE FROM cnmd15_dias_antiguedad  WHERE cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina  = '".$cod_tipo_nomina."' and ano=".$ano;
  $sw2 = $this->cnmd15_dias_antiguedad->execute($sql);
			if($sw2>1){
                $this->cnmd15_dias_antiguedad->execute("COMMIT;");
		        $this->set('Message_existe', 'LOS DATOS FUERON ELIMINADOS CORRECTAMENTE');
			}else{
				$this->cnmd15_dias_antiguedad->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINADOS - POR FAVOR INTENTE DE NUEVO');
			}//fin else
}//fin funtion








function consulta($cod_nomina=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $ano = $this->ano_ejecucion();
  $this->set('ano', $ano);
if($cod_nomina!=null){
		$deno_nomina = "";
		$this->set('cod_nomina', $cod_nomina);
		$nomina = $this->cnmd15_dias_antiguedad->findAll(" cod_presi = '".$cod_presi."' and cod_entidad  = '".$cod_entidad."' and  cod_tipo_inst = '".$cod_tipo_inst."' and cod_inst = '".$cod_inst."'  and  cod_dep  = '".$cod_dep."' and  cod_tipo_nomina  = '".$cod_nomina."'", null, "ano DESC");
		$this->set('accion', $nomina);
	}//fin if
}//fin function








 }//fin class