<?
class UtilidadesRootController extends AppController{
	var $name = "utilidades_root";
	var $uses = array('cnmd06_cursos','cnmd06_instituto_educativo');
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap', 'Fpdf');


	function checkSession(){
					if (!$this->Session->check('Root_session')){
							$this->redirect('/root/salir/');
							exit();
					}else{
						if($this->Session->read('Root_session')!="VISION_INTEGRAL"){
							$this->redirect('/root/salir/');
							 exit();
						}
					}
	}//fin checksession

	function beforeFilter(){
	    $this->checkSession();

	}


function index () {
   $this->layout="ajax";

}


function eliminar_repetidos($opcion=null){
    $this->layout = "ajax";
    if(isset($opcion) && up($opcion)=='CURSOS'){
       $sqlx="SELECT x.denominacion, (SELECT c.cod_curso FROM cnmd06_cursos c WHERE 2=2 and c.denominacion=x.denominacion order by c.cod_curso ASC limit 1) as codigos FROM cnmd06_cursos x where 1=1 GROUP BY  x.denominacion ORDER BY x.denominacion ASC";
       $res=$this->cnmd06_cursos->execute($sqlx);
       $i=1;
       $updates ="";
       $delete="";
       $var ="PROCESO REALIZADO:<br/>";
	   foreach($res as $r){
	   	   $sql2 = "SELECT c.cod_curso FROM cnmd06_cursos c WHERE c.denominacion=(select x.denominacion from cnmd06_cursos x where x.cod_curso=".$r[0]['codigos'].") and c.cod_curso<>".$r[0]['codigos'];
	   	   $sql_c = "SELECT count(*) as cantidad FROM cnmd06_cursos c WHERE c.denominacion=(select x.denominacion from cnmd06_cursos x where x.cod_curso=".$r[0]['codigos'].") and c.cod_curso<>".$r[0]['codigos'];
	   	   $co = $this->cnmd06_cursos->execute($sql_c);
	   	   if($co[0][0]['cantidad']!=0){
	            $res2=$this->cnmd06_cursos->execute($sql2);
	            $cod = array();
	            foreach($res2 as $r2){
	            	$cod[] = $r2[0]['cod_curso'];
	            }
	            $updates .="UPDATE cnmd06_datos_formacion_profesional SET cod_curso=".$r[0]['codigos']."   WHERE cod_curso in (".implode(',',$cod).");";
	            $delete .= "DELETE FROM cnmd06_cursos WHERE cod_curso in (".implode(',',$cod).");";
	   	        $var .=  "<br/>".$r[0]['denominacion']." =>".$r[0]['codigos'];$i++;
	   	   }
	   }
	   $this->set('var',$var);
	   $this->cnmd06_cursos->exete($updates);
	   $this->cnmd06_cursos->execute($delete);
    }else if(isset($opcion) && up($opcion)=='INSTITUTOS_EDUCATIVOS'){
       $sqlx="SELECT x.denominacion,(SELECT c.cod_institucion FROM cnmd06_instituto_educativo c WHERE 2=2 and c.denominacion=x.denominacion order by c.cod_institucion ASC limit 1) as codigos FROM cnmd06_instituto_educativo x where 1=1 GROUP BY  x.denominacion ORDER BY x.denominacion ASC";
	   $res=$this->cnmd06_instituto_educativo->execute($sqlx);
	   $i=1;
	   $updates ="";
	   $delete="";
	   $var ="PROCESO REALIZADO:<br/>";
	   foreach($res as $r){
	   	   $sql2 = "SELECT c.cod_institucion FROM cnmd06_instituto_educativo c WHERE c.denominacion=(select x.denominacion from cnmd06_instituto_educativo x where x.cod_institucion=".$r[0]['codigos'].") and c.cod_institucion<>".$r[0]['codigos'];
	   	   $sql_c = "SELECT count(*) as cantidad FROM cnmd06_instituto_educativo c WHERE c.denominacion=(select x.denominacion from cnmd06_instituto_educativo x where x.cod_institucion=".$r[0]['codigos'].") and c.cod_institucion<>".$r[0]['codigos'];
	   	   $co = $this->cnmd06_instituto_educativo->execute($sql_c);
	   	   if($co[0][0]['cantidad']!=0){
	            $res2=$this->cnmd06_instituto_educativo->execute($sql2);
	            $cod = array();
	            foreach($res2 as $r2){
	            	$cod[] = $r2[0]['cod_institucion'];
	            }
	            $updates .="UPDATE cnmd06_datos_educativos SET cod_institucion=".$r[0]['codigos']."   WHERE cod_institucion in (".implode(',',$cod).");";
	            $updates .="UPDATE cnmd06_datos_formacion_profesional SET cod_institucion=".$r[0]['codigos']."   WHERE cod_institucion in (".implode(',',$cod).");";
	            $delete .= "DELETE FROM cnmd06_instituto_educativo WHERE cod_institucion in (".implode(',',$cod).");";
	   	        $var .="<br/>".$r[0]['denominacion']." =>".$r[0]['codigos'];$i++;
	   	   }
	   }
	   $this->set('var',$var);
       $this->cnmd06_instituto_educativo->execute($updates);
       $this->cnmd06_instituto_educativo->execute($delete);
    }
    $this->render('index');

}//fin eliminar_repetidos

function especialidades () {
   $this->layout="ajax";
   $sql = "INSERT INTO cnmd06_especialidades (cod_profesion, cod_especialidad, denominacion)
           SELECT cod_profesion, 1, 'NINGUNO' FROM cnmd06_profesiones where cod_profesion not in ((select cod_profesion   from cnmd06_especialidades where cod_especialidad = 1 order by cod_profesion asc));";

   $this->cnmd06_instituto_educativo->execute($sql);

}//fin especialidades





}
?>
