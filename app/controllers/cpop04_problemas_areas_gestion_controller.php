<?php
 class Cpop04ProblemasAreasGestionController extends AppController {
    var $name = 'cpop04_problemas_areas_gestion';
    var $uses = array('cpod04_objetivos', 'cpod04_problemas_areas_gestion', 'ccfd04_cierre_mes', 'cfpd01_formulacion');
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap', 'Fpdf','Form','Fck');


    function checkSession(){
        if (!$this->Session->check('Usuario')){
            $this->redirect('/salir/');
            exit();
        }else{
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

        return;
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
            default: return "NULO";
        }
    }

    function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
        $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
        $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
        $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
        $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
        $sql_re .= "cod_dep=".$this->verifica_SS(5). " ";

        if($ano!=null){
            $sql_re .= "and ano=" . $ano ." ";
        }

        return $sql_re;
    }

    function SQLCA_Institucion($ano=null){//sql para busqueda de codigos de arranque con y sin año
        $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
        $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
        $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
        $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";

        if($ano!=null){
            $sql_re .= " and ano=".$ano."  ";
        }
        return $sql_re;
    }


/**
 *  Informacion de la tabla cpod04_objetivos
 *
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  ano integer NOT NULL,
  numero_objetivo integer,
  tipo_problema_area_gestion character varying(100) NOT NULL, -- Tipos: -Problemas. -Gestion (Areas de Gestion).
  numero_problema_area_gestion integer NOT NULL,
  problema_area_gestion text,
 */

    /*****
     **  ACCIONES
     **/


    function index($ano=null) {
        $this->layout = "ajax";

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
        $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);

        if($ano==null){
            $ano_formular=$year[0]['cfpd01_formulacion']['ano_formular'];
            $this->set('consulta',false);
        }elseif($ano==$year[0]['cfpd01_formulacion']['ano_formular']){
            $ano_formular=$ano;
            $this->set('consulta',false);
        }else{
            $ano_formular=$ano;
            $this->set('consulta',true);
        }

        $condicion_sql = $this->SQLCA($ano_formular);

        // SELECT DE OBJETIVOS
        $selectObj=array();
        $lista = $this->cpod04_objetivos->findAll($this->SQLCA($ano_formular), "numero_objetivo, objetivo","numero_objetivo ASC");
        foreach($lista as $lista1){
            $selectObj[$lista1['cpod04_objetivos']['numero_objetivo']]=$lista1['cpod04_objetivos']['objetivo'];
        }
        $this->set('selectObj',$selectObj);
    // FIN SELECT DE OBJETIVOS

        $sql="SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, 
                   a.numero_objetivo, b.objetivo, a.tipo_problema_area_gestion, a.numero_problema_area_gestion, 
                   a.problema_area_gestion
              FROM cpod04_problemas_areas_gestion as a
              inner join cpod04_objetivos as b on a.numero_objetivo=b.numero_objetivo
              where
              a.cod_presi=b.cod_presi and 
              a.cod_entidad=b.cod_entidad and 
              a.cod_tipo_inst=b.cod_tipo_inst and 
              a.cod_inst=b.cod_inst and 
              a.cod_dep=b.cod_dep and 
              a.ano=b.ano";
        $order="order by a.numero_problema_area_gestion ASC";
        $condicion_sql =  "a.cod_presi=".$this->verifica_SS(1)." and a.cod_entidad=".$this->verifica_SS(2)." and a.cod_tipo_inst=".$this->verifica_SS(3)." and a.cod_inst=".$this->verifica_SS(4)." and a.cod_dep=".$this->verifica_SS(5)." and a.ano=".$ano_formular;

        $datos = $this->cpod04_problemas_areas_gestion->execute($sql." and ".$condicion_sql." ".$order);
        $this->set('datos', $datos);
        $this->set('transferir', false);
        $this->set('ano', $ano_formular);
    }

    function guardar() {

        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');


        $ano = $this->data['cpod04_problemas_areas_gestion']['ano'];
        $numero_objetivo = $this->data['cpod04_problemas_areas_gestion']['numero_objetivo'];
        $tipo_problema_area_gestion = $this->data['cpod04_problemas_areas_gestion']['tipo_problema_area_gestion'];
        $numero_problema_area_gestion = $this->data['cpod04_problemas_areas_gestion']['numero_problema_area_gestion'];
        $problema_area_gestion = $this->data['cpod04_problemas_areas_gestion']['problema_area_gestion'];

        $activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
        if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){
        
            if($numero_problema_area_gestion == 0){
                /* INSERT */
                $condicion_sql = $this->SQLCA($ano);
                if($this->cpod04_problemas_areas_gestion->findCount($condicion_sql) == 0){ // si no existen registros para ese año - primer cargo registrado
                    $sql_insert = "INSERT INTO cpod04_problemas_areas_gestion VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$numero_objetivo', '$tipo_problema_area_gestion', 1, '$problema_area_gestion')";
                    if ($this->cpod04_problemas_areas_gestion->execute($sql_insert)>0) {
                        $this->set('Message_existe','Información registrada correctamente');
                    }else{
                        $this->set('errorMessage','La información no pudo ser registrada1');
                    }

                }else{ // si ya existen cargos registrado se consige el numero del cod_cargo para su insercion

                    /*$sql_cod_cargo = "SELECT (MAX(cod_cargo)+1) as numero FROM cpod04_objetivos WHERE " . $this->SQLCA($ano);
                    $datos_cod_cargo = $this->cpod04_objetivos->execute($sql_cod_cargo);
                    $cod_cargo = $datos_cod_cargo['0']['0']['numero'];*/

                    $datos_numero_problemas = $this->cpod04_problemas_areas_gestion->find($this->SQLCA($ano),'(MAX(numero_problema_area_gestion)+1) as numero');
                    $numero_problema_area_gestion = $datos_numero_problemas[0]['numero'];

                    $sql_insert = "INSERT INTO cpod04_problemas_areas_gestion VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', $numero_objetivo, '$tipo_problema_area_gestion', $numero_problema_area_gestion,'$problema_area_gestion')";

                    if ($this->cpod04_problemas_areas_gestion->execute($sql_insert)>0) {
                        $this->set('Message_existe','Información registrada correctamente');
                    }else{
                        $this->set('errorMessage','La información no pudo ser registrada2');
                    }
                }
            }else{
                /* UPDATE */
                $sql_update = "UPDATE cpod04_problemas_areas_gestion SET numero_objetivo=$numero_objetivo, tipo_problema_area_gestion='$tipo_problema_area_gestion', problema_area_gestion='$problema_area_gestion' WHERE " . $this->SQLCA($ano) . " and numero_problema_area_gestion = " . $numero_problema_area_gestion;

                if ($this->cpod04_objetivos->execute($sql_update)>0) {
                    $this->set('Message_existe','Información registrada correctamente');
                }else{
                    $this->set('errorMessage','La información no pudo ser registrada3');
                }
            }

        }else{
            $this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
        }

        $this->index($ano);
        $this->render('index');
    }


    function eliminar($numero_problema_area_gestion, $ano){

        $this->layout="ajax";
        $activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
        if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){

            $condicion_sql= $this->SQLCA($ano)." and numero_problema_area_gestion = $numero_problema_area_gestion";

            if($this->cpod04_problemas_areas_gestion->execute("DELETE FROM cpod04_problemas_areas_gestion WHERE " . $condicion_sql)>1){
                $this->set('Message_existe','Registro Eliminado con Exito');
            }else{
                $this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
            }

        }else{
            $this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
        }

        $this->index();
        $this->render('index');
    }

    function editar($numero_problema_area_gestion, $ano) {

        $this->layout = "ajax";

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        $condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
        $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
        $activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
        if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){
        
            if($ano==$year[0]['cfpd01_formulacion']['ano_formular']){

                $ano_formular=$ano;

                $condicion_sql_problemas= $this->SQLCA($ano_formular). " and numero_problema_area_gestion =" . $numero_problema_area_gestion;

                $condicion_sql_all= $this->SQLCA($ano_formular);

                if($this->cpod04_problemas_areas_gestion->findCount($condicion_sql_problemas) != 0){

                    $datos_problemas = $this->cpod04_problemas_areas_gestion->find($condicion_sql_problemas);

                    $sql="SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, 
                               a.numero_objetivo, b.objetivo, a.tipo_problema_area_gestion, a.numero_problema_area_gestion, 
                               a.problema_area_gestion
                          FROM cpod04_problemas_areas_gestion as a
                          inner join cpod04_objetivos as b on a.numero_objetivo=b.numero_objetivo
                          where
                          a.cod_presi=b.cod_presi and 
                          a.cod_entidad=b.cod_entidad and 
                          a.cod_tipo_inst=b.cod_tipo_inst and 
                          a.cod_inst=b.cod_inst and 
                          a.cod_dep=b.cod_dep and 
                          a.ano=b.ano";
                    $order="order by a.numero_problema_area_gestion ASC";
                    $condicion_sql =  "a.cod_presi=".$this->verifica_SS(1)." and a.cod_entidad=".$this->verifica_SS(2)." and a.cod_tipo_inst=".$this->verifica_SS(3)." and a.cod_inst=".$this->verifica_SS(4)." and a.cod_dep=".$this->verifica_SS(5)." and a.ano=".$ano_formular;

                    $datos = $this->cpod04_problemas_areas_gestion->execute($sql." and ".$condicion_sql." ".$order);

                    // SELECT DE OBJETIVOS
                    $selectObj=array();
                    $lista = $this->cpod04_objetivos->findAll($this->SQLCA($ano), "numero_objetivo, objetivo","numero_objetivo ASC");
                    foreach($lista as $lista1){
                        $selectObj[$lista1['cpod04_objetivos']['numero_objetivo']]=$lista1['cpod04_objetivos']['objetivo'];
                    }
                    $this->set('selectObj',$selectObj);
                    // FIN SELECT DE OBJETIVOS

                    $this->set('consulta',false);
                    $this->set('datos_problemas', $datos_problemas);
                    $this->set('datos', $datos);
                    $this->set('ano', $ano_formular);
                }else{ // no existen proyectos registrados para ese año
                    $this->set('Message_existe','Registro no Encontrado');
                    $this->set('ano', $ano_formular);
                    $this->index();
                    $this->render("index");
                }

            }else{
                $this->set('Message_existe','Registro no Pertenece a este año de Formulación');
                $this->index();
                $this->render("index");
            }

        }else{
            $this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
            $this->index();
            $this->render('index');
        }
    }

}
?>
