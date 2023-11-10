<?php



class cscd01CatalogoInflacionController extends AppController {
   var $name = 'cscd01_catalogo_inflacion';
   var $uses = array('cscd01_catalogo_inflacion','cscd01_catalogo_inflacion','ccfd03_instalacion', 'ccfd04_cierre_mes', 'v_cnmd05');
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

  $accion =  $this->cscd01_catalogo_inflacion->findAll(null, null, 'ano DESC');
  $this->set('accion', $accion);
  //$this->set('ano', $ano);
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


  $ano          =  $this->data['cscd01_catalogo_inflacion']['ano'];
  $tasa_ene     =  $this->Formato1($this->data['cscd01_catalogo_inflacion']['ene']);
  $tasa_feb     =  $this->Formato1($this->data['cscd01_catalogo_inflacion']['feb']);
  $tasa_mar     =  $this->Formato1($this->data['cscd01_catalogo_inflacion']['mar']);
  $tasa_abr     =  $this->Formato1($this->data['cscd01_catalogo_inflacion']['abr']);
  $tasa_may     =  $this->Formato1($this->data['cscd01_catalogo_inflacion']['may']);
  $tasa_jun     =  $this->Formato1($this->data['cscd01_catalogo_inflacion']['jun']);
  $tasa_jul     =  $this->Formato1($this->data['cscd01_catalogo_inflacion']['jul']);
  $tasa_ago     =  $this->Formato1($this->data['cscd01_catalogo_inflacion']['ago']);
  $tasa_sep     =  $this->Formato1($this->data['cscd01_catalogo_inflacion']['sep']);
  $tasa_oct     =  $this->Formato1($this->data['cscd01_catalogo_inflacion']['oct']);
  $tasa_nov     =  $this->Formato1($this->data['cscd01_catalogo_inflacion']['nov']);
  $tasa_dic     =  $this->Formato1($this->data['cscd01_catalogo_inflacion']['dic']);
  $tasa_acum     =  $this->Formato1($this->data['cscd01_catalogo_inflacion']['inflacion_acumulada']);

  


$sw2  = $this->cscd01_catalogo_inflacion->execute('BEGIN; ');
$cont = $this->cscd01_catalogo_inflacion->findCount('ano='.$ano);
$opcion = 'si';

if($cont==0){
		$sql =" INSERT INTO cscd01_catalogo_inflacion ";
		$sql.="VALUES ('".$ano."', '".$tasa_ene."', '".$tasa_feb."', '".$tasa_mar."', '".$tasa_abr."', '".$tasa_may."', '".$tasa_jun."', '".$tasa_jul."', '".$tasa_ago."', '".$tasa_sep."', '".$tasa_oct."', '".$tasa_nov."', '".$tasa_dic."','".$tasa_acum."');";
}else{
        $sql = " UPDATE cscd01_catalogo_inflacion SET ene='".$tasa_ene."', feb='".$tasa_feb."', mar='".$tasa_mar."', abr='".$tasa_abr."', may='".$tasa_may."', jun='".$tasa_jun."', jul='".$tasa_jul."', ago='".$tasa_ago."', sep='".$tasa_sep."', oct='".$tasa_oct."', nov='".$tasa_nov."', dic='".$tasa_dic."',inflacion_acumulada='".$tasa_acum."'  where ano=".$ano;
}//fin else

if($opcion=='si'){
		$sw2 = $this->cscd01_catalogo_inflacion->execute($sql);

					if($sw2>1){
		                $this->cscd01_catalogo_inflacion->execute("COMMIT;");
				        $this->set('Message_existe', 'Los datos fueron guardados correctamente');
					}else{
						$this->cscd01_catalogo_inflacion->execute("ROLLBACK;");
						$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
					}//fin else
}else{

						$this->cscd01_catalogo_inflacion->execute("ROLLBACK;");
						$this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');

}//fin else

  $accion =  $this->cscd01_catalogo_inflacion->findAll(null, null, 'ano DESC');
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
		      echo" document.getElementById('inflacion_acumulada').value = '0,00';";
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
  $accion =  $this->cscd01_catalogo_inflacion->findAll('ano='.$var1, null, 'ano DESC');

           echo "<script>";
                      echo" document.getElementById('ano').value = '".$accion[0]['cscd01_catalogo_inflacion']['ano']."';";
		      echo" document.getElementById('ene').value = '".$this->Formato2($accion[0]['cscd01_catalogo_inflacion']['ene'])."';";
		      echo" document.getElementById('feb').value = '".$this->Formato2($accion[0]['cscd01_catalogo_inflacion']['feb'])."';";
		      echo" document.getElementById('mar').value = '".$this->Formato2($accion[0]['cscd01_catalogo_inflacion']['mar'])."';";
		      echo" document.getElementById('abr').value = '".$this->Formato2($accion[0]['cscd01_catalogo_inflacion']['abr'])."';";
		      echo" document.getElementById('may').value = '".$this->Formato2($accion[0]['cscd01_catalogo_inflacion']['may'])."';";
		      echo" document.getElementById('jun').value = '".$this->Formato2($accion[0]['cscd01_catalogo_inflacion']['jun'])."';";
		      echo" document.getElementById('jul').value = '".$this->Formato2($accion[0]['cscd01_catalogo_inflacion']['jul'])."';";
		      echo" document.getElementById('ago').value = '".$this->Formato2($accion[0]['cscd01_catalogo_inflacion']['ago'])."';";
		      echo" document.getElementById('sep').value = '".$this->Formato2($accion[0]['cscd01_catalogo_inflacion']['sep'])."';";
		      echo" document.getElementById('oct').value = '".$this->Formato2($accion[0]['cscd01_catalogo_inflacion']['oct'])."';";
		      echo" document.getElementById('nov').value = '".$this->Formato2($accion[0]['cscd01_catalogo_inflacion']['nov'])."';";
		      echo" document.getElementById('dic').value = '".$this->Formato2($accion[0]['cscd01_catalogo_inflacion']['dic'])."';";
		      echo" document.getElementById('inflacion_acumulada').value = '".$this->Formato2($accion[0]['cscd01_catalogo_inflacion']['inflacion_acumulada'])."';";
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




$sql="BEGIN;  DELETE FROM cscd01_catalogo_inflacion  WHERE ano='".$var1."'  ";
$sw2 = $this->cscd01_catalogo_inflacion->execute($sql);

			if($sw2>1){
                $this->cscd01_catalogo_inflacion->execute("COMMIT;");
		        $this->set('Message_existe', 'LOS DATOS FUERON ELIMINDOS CORRECTAMENTE');
			}else{
				$this->cscd01_catalogo_inflacion->execute("ROLLBACK;");
				$this->set('errorMessage', 'LOS DATOS NO FUERON ELIMINDOS - POR FAVOR INTENTE DE NUEVO');
			}//fin else



}//fin funtion


}//fin