<?php




class cnmp15TasaInteresController extends AppController {
   var $name = 'cnmp15_tasa_interes';
   var $uses = array('cnmd15_tasa_interes','ccfd03_instalacion', 'ccfd04_cierre_mes', 'v_cnmd05');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');




function checkSession(){
        if (!$this->Session->check('Usuario')){
            // Force the user to login
            $this->redirect('/salir');
            exit();
        }
}//fin function





 function beforeFilter(){
 	$this->checkSession();
 }//fin function













function index(){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $ano = $this->ano_ejecucion();
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

  $accion =  $this->cnmd15_tasa_interes->findAll(null, null, 'ano DESC');
  $this->set('accion', $accion);
  $this->set('ano', $ano);
}//fin funtion





function funcion(){
  $this->layout = "ajax";

}//fin funtion





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


  $ano          =  $this->data['cnmp15_tasa_interes']['ano'];
  $tasa_ene     =  $this->Formato1($this->data['cnmp15_tasa_interes']['ene']);
  $tasa_feb     =  $this->Formato1($this->data['cnmp15_tasa_interes']['feb']);
  $tasa_mar     =  $this->Formato1($this->data['cnmp15_tasa_interes']['mar']);
  $tasa_abr     =  $this->Formato1($this->data['cnmp15_tasa_interes']['abr']);
  $tasa_may     =  $this->Formato1($this->data['cnmp15_tasa_interes']['may']);
  $tasa_jun     =  $this->Formato1($this->data['cnmp15_tasa_interes']['jun']);
  $tasa_jul     =  $this->Formato1($this->data['cnmp15_tasa_interes']['jul']);
  $tasa_ago     =  $this->Formato1($this->data['cnmp15_tasa_interes']['ago']);
  $tasa_sep     =  $this->Formato1($this->data['cnmp15_tasa_interes']['sep']);
  $tasa_oct     =  $this->Formato1($this->data['cnmp15_tasa_interes']['oct']);
  $tasa_nov     =  $this->Formato1($this->data['cnmp15_tasa_interes']['nov']);
  $tasa_dic     =  $this->Formato1($this->data['cnmp15_tasa_interes']['dic']);



$sw2  = $this->cnmd15_tasa_interes->execute('BEGIN; ');
$cont = $this->cnmd15_tasa_interes->findCount('ano='.$ano);
$opcion = 'si';

if($cont==0){
		$sql =" INSERT INTO cnmd15_tasa_interes (ano, tasa_ene, tasa_feb, tasa_mar, tasa_abr, tasa_may, tasa_jun, tasa_jul, tasa_ago, tasa_sep, tasa_oct, tasa_nov, tasa_dic)";
		$sql.="VALUES ('".$ano."', '".$tasa_ene."', '".$tasa_feb."', '".$tasa_mar."', '".$tasa_abr."', '".$tasa_may."', '".$tasa_jun."', '".$tasa_jul."', '".$tasa_ago."', '".$tasa_sep."', '".$tasa_oct."', '".$tasa_nov."', '".$tasa_dic."'); ";
}else{
        $sql = " UPDATE cnmd15_tasa_interes SET tasa_ene='".$tasa_ene."', tasa_feb='".$tasa_feb."', tasa_mar='".$tasa_mar."', tasa_abr='".$tasa_abr."', tasa_may='".$tasa_may."', tasa_jun='".$tasa_jun."', tasa_jul='".$tasa_jul."', tasa_ago='".$tasa_ago."', tasa_sep='".$tasa_sep."', tasa_oct='".$tasa_oct."', tasa_nov='".$tasa_nov."', tasa_dic='".$tasa_dic."'  where ano=".$ano;
}//fin else
if($opcion=='si'){
		$sw2 = $this->cnmd15_tasa_interes->execute($sql);

					if($sw2>1){
		                $this->cnmd15_tasa_interes->execute("COMMIT;");
				        $this->set('Message_existe', 'Los datos fueron guardados correctamente');
					}else{
						$this->cnmd15_tasa_interes->execute("ROLLBACK;");
						$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
					}//fin else
}else{

						$this->cnmd15_tasa_interes->execute("ROLLBACK;");
						$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');

}//fin else

  $accion =  $this->cnmd15_tasa_interes->findAll(null, null, 'ano DESC');
  $this->set('accion', $accion);
  $this->set('cedula', $var1);


            echo "<script>";
              echo" document.getElementById('ano').value = '';";
		      echo" document.getElementById('ene').value = '0,00';";
		      echo" document.getElementById('feb').value = '0,00';";
		      echo" document.getElementById('mar').value = '0,00';";
		      echo" document.getElementById('abr').value = '0,00';";
		      echo" document.getElementById('may').value = '0,00';";
		      echo" document.getElementById('jun').value = '0,00';";
		      echo" document.getElementById('jul').value = '0,00';";
		      echo" document.getElementById('ago').value = '0,00';";
		      echo" document.getElementById('sep').value = '0,00';";
		      echo" document.getElementById('oct').value = '0,00';";
		      echo" document.getElementById('nov').value = '0,00';";
		      echo" document.getElementById('dic').value = '0,00';";
			echo "</script>";
}//fin funtion








function editar($var1=null){
 $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
  $accion =  $this->cnmd15_tasa_interes->findAll('ano='.$var1, null, 'ano DESC');




           echo "<script>";
              echo" document.getElementById('ano').value = '".$accion[0]['cnmd15_tasa_interes']['ano']."';";
		      echo" document.getElementById('ene').value = '".$this->Formato2($accion[0]['cnmd15_tasa_interes']['tasa_ene'])."';";
		      echo" document.getElementById('feb').value = '".$this->Formato2($accion[0]['cnmd15_tasa_interes']['tasa_feb'])."';";
		      echo" document.getElementById('mar').value = '".$this->Formato2($accion[0]['cnmd15_tasa_interes']['tasa_mar'])."';";
		      echo" document.getElementById('abr').value = '".$this->Formato2($accion[0]['cnmd15_tasa_interes']['tasa_abr'])."';";
		      echo" document.getElementById('may').value = '".$this->Formato2($accion[0]['cnmd15_tasa_interes']['tasa_may'])."';";
		      echo" document.getElementById('jun').value = '".$this->Formato2($accion[0]['cnmd15_tasa_interes']['tasa_jun'])."';";
		      echo" document.getElementById('jul').value = '".$this->Formato2($accion[0]['cnmd15_tasa_interes']['tasa_jul'])."';";
		      echo" document.getElementById('ago').value = '".$this->Formato2($accion[0]['cnmd15_tasa_interes']['tasa_ago'])."';";
		      echo" document.getElementById('sep').value = '".$this->Formato2($accion[0]['cnmd15_tasa_interes']['tasa_sep'])."';";
		      echo" document.getElementById('oct').value = '".$this->Formato2($accion[0]['cnmd15_tasa_interes']['tasa_oct'])."';";
		      echo" document.getElementById('nov').value = '".$this->Formato2($accion[0]['cnmd15_tasa_interes']['tasa_nov'])."';";
		      echo" document.getElementById('dic').value = '".$this->Formato2($accion[0]['cnmd15_tasa_interes']['tasa_dic'])."';";
			echo "</script>";


}//fin function








function eliminar($var1=null, $var2=null){
  $this->layout = "ajax";
  $cod_presi = $this->Session->read('SScodpresi');
  $cod_entidad = $this->Session->read('SScodentidad');
  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
  $cod_inst = $this->Session->read('SScodinst');
  $cod_dep = $this->Session->read('SScoddep');
  $modulo = $this->Session->read('Modulo');
  $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;




$sql="BEGIN;  DELETE FROM cnmd15_tasa_interes  WHERE ano='".$var1."'  ";
$sw2 = $this->cnmd15_tasa_interes->execute($sql);

			if($sw2>1){
                $this->cnmd15_tasa_interes->execute("COMMIT;");
		        $this->set('Message_existe', 'LOS DATOS FUERON ELIMINDOS CORRECTAMENTE');
			}else{
				$this->cnmd15_tasa_interes->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINDOS - POR FAVOR INTENTE DE NUEVO');
			}//fin else



}//fin funtion







}//fin