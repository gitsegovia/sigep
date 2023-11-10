<?php
 class Cpop04ObjetivosController extends AppController {
    var $name = 'cpop04_objetivos';
    var $uses = array('cpod04_objetivos', 'cpod04_problemas_areas_gestion','cpod01_proyectos', 'ccfd04_cierre_mes', 'cfpd01_formulacion');
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
  cod_dep integer,
  ano integer NOT NULL,
  tipo_proyecto text, -- Tipos de Proyectos: -Estrategicos, -Gestion
  numero_proyecto integer,
  numero_objetivo integer NOT NULL,
  objetivo text,
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

        $sql="SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, 
                a.tipo_proyecto, a.numero_proyecto, b.proyectos, a.numero_objetivo, a.objetivo
                FROM cpod04_objetivos as a 
                inner join cpod01_proyectos as b on a.numero_proyecto=b.numero_proyecto and a.tipo_proyecto=b.tipo_proyecto
              where
              a.cod_presi=b.cod_presi and 
              a.cod_entidad=b.cod_entidad and 
              a.cod_tipo_inst=b.cod_tipo_inst and 
              a.cod_inst=b.cod_inst and 
              a.cod_dep=b.cod_dep and 
              a.ano=b.ano
              ";
        $order="order by a.tipo_proyecto ASC, numero_objetivo ASC";
        $condicion_sql =  "a.cod_presi=".$this->verifica_SS(1)." and a.cod_entidad=".$this->verifica_SS(2)." and a.cod_tipo_inst=".$this->verifica_SS(3)." and a.cod_inst=".$this->verifica_SS(4)." and a.cod_dep=".$this->verifica_SS(5)." and a.ano=".$ano_formular;

        $datos = $this->cpod04_objetivos->execute($sql." and ".$condicion_sql." ".$order);
        $this->set('datos', $datos);
        $this->set('transferir', false);
        $this->set('ano', $ano_formular);

    }

    function proyectos($ano, $tipo_proyecto){

            $vector=array();
            $lista = $this->cpod01_proyectos->findAll($this->SQLCA($ano)." and tipo_proyecto='".$tipo_proyecto."' and numero_proyecto NOT IN (SELECT numero_proyecto FROM cpod04_objetivos WHERE ".$this->SQLCA($ano)." and tipo_proyecto='".$tipo_proyecto."')", " numero_proyecto, proyectos","numero_proyecto ASC");
            foreach($lista as $lista1){
                $vector[$lista1['cpod01_proyectos']['numero_proyecto']]=$lista1['cpod01_proyectos']['proyectos'];
            }
            $this->set('vector',$vector);
    }



    function guardar() {

        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');


        $ano = $this->data['cpod04_objetivos']['ano'];
        //$tipo_proyecto = $this->data['cpod04_objetivos']['tipo_proyecto'];
        $tipo_proyecto='GESTION';
        $numero_proyecto = $this->data['cpod04_objetivos']['numero_proyecto'];
        $objetivo = $this->data['cpod04_objetivos']['objetivo'];
        $numero_objetivo = $this->data['cpod04_objetivos']['numero_objetivo'];

        $activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
        if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){

            if($numero_objetivo == 0){
                /* INSERT */
                $condicion_sql = $this->SQLCA($ano);
                if($this->cpod04_objetivos->findCount($condicion_sql) == 0){ // si no existen registros para ese año - primer cargo registrado
                    $sql_insert = "INSERT INTO cpod04_objetivos VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$tipo_proyecto', $numero_proyecto, 1, '$objetivo')";
                    if ($this->cpod04_objetivos->execute($sql_insert)>0) {
                        $this->set('Message_existe','Información registrada correctamente');
                    }else{
                        $this->set('errorMessage','La información no pudo ser registrada1');
                    }

                }else{ // si ya existen cargos registrado se consige el numero del cod_cargo para su insercion

                    /*$sql_cod_cargo = "SELECT (MAX(cod_cargo)+1) as numero FROM cpod04_objetivos WHERE " . $this->SQLCA($ano);
                    $datos_cod_cargo = $this->cpod04_objetivos->execute($sql_cod_cargo);
                    $cod_cargo = $datos_cod_cargo['0']['0']['numero'];*/

                    $datos_numero_objetivo = $this->cpod04_objetivos->find($this->SQLCA($ano),'(MAX(numero_objetivo)+1) as numero');
                    $numero_objetivo = $datos_numero_objetivo[0]['numero'];

                    $sql_insert = "INSERT INTO cpod04_objetivos VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$tipo_proyecto', $numero_proyecto, $numero_objetivo, '$objetivo')";

                    if ($this->cpod04_objetivos->execute($sql_insert)>0) {
                        $this->set('Message_existe','Información registrada correctamente');
                    }else{
                        $this->set('errorMessage','La información no pudo ser registrada2');
                    }
                }
            }else{
                /* UPDATE */
                $sql_update = "UPDATE cpod04_objetivos SET tipo_proyecto='$tipo_proyecto', numero_proyecto=$numero_proyecto, objetivo='$objetivo' WHERE " . $this->SQLCA($ano) . " and numero_objetivo = " . $numero_objetivo;

                if ($this->cpod04_objetivos->execute($sql_update)>0) {
                    $this->set('Message_existe','Información registrada correctamente');
                }else{
                    $this->set('errorMessage','La información no pudo ser registrada3');
                }
            }

        }else{
            $this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
        }

        $this->index();
        $this->render('index');
    }


    function eliminar($numero_objetivo, $ano){

        $this->layout="ajax";

        $activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
        if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){

            $condicion_sql= $this->SQLCA($ano)." and numero_objetivo = $numero_objetivo";

            if($this->cpod04_problemas_areas_gestion->find($condicion_sql)>0){
                $this->set('errorMessage', 'EL DATO NO PUEDE SER ELIMINADO, POSEE UN REGISTRO DE PROBLEMA O GESTIÓN ASOCIADO');
            }else{
                if($this->cpod04_objetivos->execute("DELETE FROM cpod04_objetivos WHERE " . $condicion_sql)>1){
                    $this->set('Message_existe','Registro Eliminado con Exito');
                }else{
                    $this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
                }
            }

        }else{
            $this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
        }

        $this->index();
        $this->render('index');
    }

    function editar($numero_objetivo, $ano) {

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

                $condicion_sql_objetivos= $this->SQLCA($ano_formular). " and numero_objetivo =" . $numero_objetivo;

                $condicion_sql_all= $this->SQLCA($ano_formular);

                if($this->cpod04_objetivos->findCount($condicion_sql_objetivos) != 0){
                    $datos_objetivo = $this->cpod04_objetivos->find($condicion_sql_objetivos);
                    $sql="SELECT a.cod_presi, a.cod_entidad, a.cod_tipo_inst, a.cod_inst, a.cod_dep, a.ano, 
                        a.tipo_proyecto, a.numero_proyecto, b.proyectos, a.numero_objetivo, a.objetivo
                        FROM cpod04_objetivos as a 
                        inner join cpod01_proyectos as b on a.numero_proyecto=b.numero_proyecto and a.tipo_proyecto=b.tipo_proyecto
                      where
                      a.cod_presi=b.cod_presi and 
                      a.cod_entidad=b.cod_entidad and 
                      a.cod_tipo_inst=b.cod_tipo_inst and 
                      a.cod_inst=b.cod_inst and 
                      a.cod_dep=b.cod_dep and 
                      a.ano=b.ano";
                    $order="order by a.tipo_proyecto ASC, numero_objetivo ASC";
                    $condicion_sql =  "a.cod_presi=".$this->verifica_SS(1)." and a.cod_entidad=".$this->verifica_SS(2)." and a.cod_tipo_inst=".$this->verifica_SS(3)." and a.cod_inst=".$this->verifica_SS(4)." and a.cod_dep=".$this->verifica_SS(5)." and a.ano=".$ano_formular;

                    $datos = $this->cpod04_objetivos->execute($sql." and ".$condicion_sql." ".$order);

                    // datos select proyectos
                    $vector=array();
                    $tipo_proyecto=$datos_objetivo['cpod04_objetivos']['tipo_proyecto'];
                    $lista = $this->cpod01_proyectos->findAll($this->SQLCA($ano)." and tipo_proyecto='".$tipo_proyecto."'", " numero_proyecto, proyectos","numero_proyecto ASC");
                    foreach($lista as $lista1){
                        $vector[$lista1['cpod01_proyectos']['numero_proyecto']]=$lista1['cpod01_proyectos']['proyectos'];
                    }
                    // fin datos select proyectos
                    $this->set('vector',$vector);
                    $this->set('consulta',false);
                    $this->set('datos', $datos);
                    $this->set('ano', $ano_formular);
                    $this->set('datos_objetivo', $datos_objetivo);
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
