<?php
//cpod06_distribucion_ingresos_propios
 class Cpop06DistribucionIngresosPropiosController extends AppController {
    var $name = 'cpop06_distribucion_ingresos_propios';
    var $uses = array('cpod01_proyectos', 'cpod06_vinculacion_presupuesto','cpod06_distribucion_ingresos_propios', 'cpod05_control_metas', 'ccfd04_cierre_mes', 'cfpd01_formulacion', 'cfpd01_ano_partida', 'cfpd01_ano_generica', 'cfpd01_ano_especifica', 'cfpd01_ano_sub_espec', 'cfpd01_ano_auxiliar', 'cfpd02_activ_obra');
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

    function AddCero($nomVar,$vector=object,$extra=null){
      if($vector != null){
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
                $Var[$x]=$extra."0".$x;
                }else{
                $Var[$x]=$extra."".$x;
                }
            }//fin each
        }
        $this->set($nomVar,$Var);
      }else{
        $this->set($nomVar,'');
      }
    }//fin AddCero

    function concatenaCI_v2($vector1=null, $nomVar=null, $grupo=null){
        if($vector1 != null){
            foreach($vector1 as $x => $y){
                $cod[$grupo.$this->zero($x)] =$grupo.$this->zero($x).' - '.$y;
            }
            //print_r($cod);
            $this->set($nomVar, $cod);
        }
    }

/**
 *  Informacion de la tabla cpod06_vinculacion_presupuesto
 *
  cod_presi integer NOT NULL,
  cod_entidad integer NOT NULL,
  cod_tipo_inst integer NOT NULL,
  cod_inst integer NOT NULL,
  cod_dep integer NOT NULL,
  ano integer NOT NULL,
  tipo_proyecto text NOT NULL,
  numero_proyecto integer NOT NULL,
  cod_sector integer NOT NULL,
  cod_programa integer NOT NULL,
  cod_sub_prog integer NOT NULL,
  cod_proyecto integer NOT NULL,
  cod_activ_obra integer NOT NULL,
  cod_partida integer NOT NULL,
  cod_generica integer NOT NULL,
  cod_especifica integer NOT NULL,
  cod_sub_espec integer NOT NULL,
  monto numeric(26,2) NOT NULL,
 */

    /*****
     **  ACCIONES
     **/


    function index($ano=null) {

        $this->layout = "ajax";
        $this->verifica_entrada('124');

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

        $this->set('ano', $ano_formular);
    }

    function proyectos($ano, $tipo_proyecto){

            $vector=array();
            $lista = $this->cpod01_proyectos->findAll($this->SQLCA($ano)." and tipo_proyecto='".$tipo_proyecto."' and numero_proyecto IN (SELECT numero_proyecto FROM cpod04_objetivos WHERE ".$this->SQLCA($ano)." and tipo_proyecto='".$tipo_proyecto."')", " numero_proyecto, proyectos","numero_proyecto ASC");
            foreach($lista as $lista1){
                $vector[$lista1['cpod01_proyectos']['numero_proyecto']]=$lista1['cpod01_proyectos']['proyectos'];
            }
            $this->set('vector',$vector);
            $this->set('tipo_proyecto',$tipo_proyecto);
            $this->set('ano', $ano);
    }

    /** partidas */

    function presupuesto($ano, $tipo_proyecto, $numero_proyecto) {

        $this->layout="ajax";

        $grupo=4;
        if(!empty($ano)){
            $this->set('ano',$ano);
            $_SESSION['ano']=$ano-1;
            $activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);

            $lista=$this->cfpd01_ano_partida->generateList("cod_grupo=".$grupo." and ejercicio = ".$_SESSION['ano'], 'cod_partida ASC', null, '{n}.cfpd01_ano_partida.cod_partida', '{n}.cfpd01_ano_partida.denominacion');

            if($lista!=""){
               //$this->AddCero('partida',$lista, CI);
               $this->concatenaCI_v2($lista, 'partida', $grupo);
            }else{
              $this->set('errorMessage', 'No Hay Datos en el  Clasificador de partida para el a&ntilde;o '.$_SESSION['ano']);
              $this->set('partida','');
            }

            $data_presupuesto=$this->cpod06_distribucion_ingresos_propios->findAll($this->SQLCA($ano)." and tipo_proyecto='".$tipo_proyecto."' and numero_proyecto=".$numero_proyecto,null,'cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);
            $monto_vinculacion = $this->cpod06_vinculacion_presupuesto->find($this->SQLCA($ano)." and tipo_proyecto='".$tipo_proyecto."' and numero_proyecto=".$numero_proyecto." and grupo=3", 'SUM(monto) as monto');
            $this->set('data_presupuesto',$data_presupuesto);
            $this->set('monto_vinculacion', $monto_vinculacion);
            $this->set('activar_formulacion',$activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']);
        }
    }

    function selec_generica($partida){

        $this->layout = "ajax";

        $grupo=4;
        $partida=substr($partida,1);

        $this->set('op_grupo', $grupo);
        $this->set('op_partida', $partida);

        if($partida!=null){

            $lista=$this->cfpd01_ano_generica->generateList('where ejercicio='.$_SESSION['ano'].' and cod_grupo ='.$grupo.' and cod_partida = '.$partida.'', ' cod_generica ASC', null, '{n}.cfpd01_ano_generica.cod_generica', '{n}.cfpd01_ano_generica.denominacion');
            //$this->AddCero('generica',$listaGenerica);
            $this->concatena($lista, 'generica');
        }else{
            $this->set('generica', '');
        }

    }//fin generica

    function selec_especifica($grupo=null, $partida=null, $generica=null , $aux=null){
        $this->layout = "ajax";

        $this->set('op_grupo', $grupo);
        $this->set('op_partida', $partida);
        $this->set('op_generica', $generica);
        if($generica!=null){
            $lista=$this->cfpd01_ano_especifica->generateList('where ejercicio='.$_SESSION['ano'].' and cod_grupo =  '.$grupo.'  and cod_partida = '.$partida.' and cod_generica = '.$generica.'', ' cod_especifica ASC', null, '{n}.cfpd01_ano_especifica.cod_especifica', '{n}.cfpd01_ano_especifica.denominacion');
            //$this->AddCero('especifica',$lista);
            $this->concatena($lista, 'especifica');
        }else{   $this->set('especifica', ''); }
    }//fin especifica

    function selec_sub_especifica($grupo=null, $partida=null, $generica=null, $especifica=null, $aux=null) {

        $this->layout = "ajax";

        $this->set('op_grupo', $grupo);
        $this->set('op_partida', $partida);
        $this->set('op_generica', $generica);
        $this->set('op_especifica', $especifica);

        if($especifica!=null){
            $lista=$this->cfpd01_ano_sub_espec->generateList('where ejercicio='.$_SESSION['ano'].' and cod_grupo =  '.$grupo.'  and cod_partida = '.$partida.' and cod_generica = '.$generica.' and cod_especifica = '.$especifica.'', ' cod_sub_espec ASC', null, '{n}.cfpd01_ano_sub_espec.cod_sub_espec', '{n}.cfpd01_ano_sub_espec.denominacion');
            //$this->AddCero('subespecifica',$listaSE);
            $this->concatena($lista, 'subespecifica');
        }else{   $this->set('subespecifica', ''); }
    }//fin seb_especifica

    function selec_auxiliar($grupo=null, $partida=null, $generica=null, $especifica=null, $subespecifica=null, $aux=null){
        $this->layout = "ajax";
        $sql_gpgese =" cod_grupo=".$grupo." and";
        $sql_gpgese .=" cod_partida=".$partida." and";
        $sql_gpgese .=" cod_generica=".$generica." and";
        $sql_gpgese .=" cod_especifica=".$especifica." and";
        $sql_gpgese .=" cod_sub_espec=".$subespecifica." ";
        //$sql_gpgese .=" cod_auxiliar=".$this->data['cfpp05']['codigo']." ";

        $this->set('op_grupo', $grupo);
        $this->set('op_partida', $partida);
        $this->set('op_generica', $generica);
        $this->set('op_especifica', $especifica);
        $this->set('op_subespecifica', $subespecifica);

        if($subespecifica!=null){
            $condicion="where ejercicio=".$_SESSION['ano']." and ".$sql_gpgese;
            $lista=$this->cfpd01_ano_auxiliar->generateList($condicion, ' cod_auxiliar ASC', null, '{n}.cfpd01_ano_auxiliar.cod_auxiliar', '{n}.cfpd01_ano_auxiliar.denominacion');
           //$this->AddCero('auxiliar',$listaAux);
            $this->concatena($lista, 'auxiliar');
        }else{   $this->set('auxiliar', '');}

    }//fin auxiliar


    /* fin partidas */

    function guardar() {

        $this->layout = "ajax";
        
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        /*

          cod_presi integer NOT NULL,
          cod_entidad integer NOT NULL,
          cod_tipo_inst integer NOT NULL,
          cod_inst integer NOT NULL,
          cod_dep integer NOT NULL,
          ano integer NOT NULL,
          tipo_proyecto text NOT NULL,
          numero_proyecto integer NOT NULL,
          cod_sector integer NOT NULL,
          cod_programa integer NOT NULL,
          cod_sub_prog integer NOT NULL,
          cod_proyecto integer NOT NULL,
          cod_activ_obra integer NOT NULL,
          cod_partida integer NOT NULL,
          cod_generica integer NOT NULL,
          cod_especifica integer NOT NULL,
          cod_sub_espec integer NOT NULL,
          monto numeric(26,2) NOT NULL,

         */
        $ano = $this->data['cpod06_distribucion_ingresos_propios']['ano'];
        $tipo_proyecto = $this->data['cpod06_distribucion_ingresos_propios']['tipo_proyecto'];
        $numero_proyecto = $this->data['cpod06_distribucion_ingresos_propios']['numero_proyecto'];

        $grupo=4;
        $cod_partida = $this->data['cpod06_distribucion_ingresos_propios']['cod_partida'];
        $cod_generica = $this->data['cpod06_distribucion_ingresos_propios']['cod_generica'];
        $cod_especifica = $this->data['cpod06_distribucion_ingresos_propios']['cod_especifica'];
        $cod_sub_espec = $this->data['cpod06_distribucion_ingresos_propios']['cod_sub_espec'];
        $cod_auxiliar = $this->data['cpod06_distribucion_ingresos_propios']['cod_auxiliar'];
        $monto = $this->Formato1($this->data['cpod06_distribucion_ingresos_propios']['monto']);

        /*  control de monto con la meta  */

        $monto_vinculacion = $this->cpod06_vinculacion_presupuesto->find($this->SQLCA($ano)." and tipo_proyecto='".$tipo_proyecto."' and numero_proyecto=".$numero_proyecto." and grupo=3", 'SUM(monto) as monto');

        $monto_presupuesto = $this->cpod06_distribucion_ingresos_propios->findAll($this->SQLCA($ano)." and tipo_proyecto='".$tipo_proyecto."' and numero_proyecto=".$numero_proyecto, 'SUM(monto) as monto_presupuesto');


        if($monto_vinculacion[0]['monto']==null){
            $monto_vinculacion[0]['monto']=0;
        }
        if($monto_presupuesto[0][0]['monto_presupuesto']==null){
            $monto_presupuesto[0][0]['monto_presupuesto']=0;
        }

        $activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
        if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){
        
            $monto_total_presupuestario_temp = $monto_presupuesto[0][0]['monto_presupuesto'] + $monto;

            if($monto_vinculacion[0]['monto']>0 && $monto_total_presupuestario_temp <= $monto_vinculacion[0]['monto']){

                /* BUSQUEDA DE SECTOR _ ACTIVIDAD */

                $cfpp02 = $this->cfpd02_activ_obra->find($this->SQLCA($ano-1));

                $cod_sector = $cfpp02['cfpd02_activ_obra']['cod_sector'];
                $cod_programa = $cfpp02['cfpd02_activ_obra']['cod_programa'];
                $cod_sub_prog = $cfpp02['cfpd02_activ_obra']['cod_sub_prog'];
                $cod_proyecto = $cfpp02['cfpd02_activ_obra']['cod_proyecto'];
                $cod_activ_obra = $cfpp02['cfpd02_activ_obra']['cod_activ_obra'];


                $condicion_busqueda_sql = "cod_sector=$cod_sector and cod_programa=$cod_programa and cod_sub_prog=$cod_sub_prog and cod_proyecto=$cod_proyecto and cod_activ_obra=$cod_activ_obra and tipo_proyecto='$tipo_proyecto' and numero_proyecto=$numero_proyecto and cod_partida=$cod_partida and cod_generica=$cod_generica and cod_especifica=$cod_especifica and cod_sub_espec=$cod_sub_espec and cod_auxiliar=$cod_auxiliar and ".$this->SQLCA($ano);

                if($this->cpod06_distribucion_ingresos_propios->findCount($condicion_busqueda_sql) == 0){ // si no existen registros para ese año - primer cargo registrado
                    $sql_insert = "INSERT INTO cpod06_distribucion_ingresos_propios VALUES ('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$tipo_proyecto', '$numero_proyecto', '$cod_sector', '$cod_programa', '$cod_sub_prog', '$cod_proyecto', '$cod_activ_obra', '$cod_partida', '$cod_generica', '$cod_especifica', '$cod_sub_espec', '$cod_auxiliar', $monto)";
                    if ($this->cpod06_distribucion_ingresos_propios->execute($sql_insert)>0) {
                        $this->set('Message_existe','Información registrada correctamente');
                    }else{
                        $this->set('errorMessage','La información no pudo ser registrada');
                    }
                }else{
                    $this->set('errorMessage','El registro ya Existe');
                }
            }else{
                $this->set('errorMessage','Registro no Guardado. El monto del Presupuesto supera la Vinculación de ingresos Propios.');
            }

        }else{
            $this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
        }

        $data_presupuesto=$this->cpod06_distribucion_ingresos_propios->findAll($this->SQLCA($ano)." and tipo_proyecto='".$tipo_proyecto."' and numero_proyecto=".$numero_proyecto,null,'cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC',null,null,null);

        $this->set('data_presupuesto',$data_presupuesto);
        $this->set('monto_vinculacion', $monto_vinculacion);
        $this->set('ano', $ano);
        $this->set('activar_formulacion',$activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']);

    }


    function eliminar($tip_pro, $num_pro, $par, $gen, $esp, $subesp, $au, $ano){

        $this->layout="ajax";
        $activar_formulacion = $this->cfpd01_formulacion->findAll($this->SQLCA_Institucion(), null, 'ano_formular ASC', null);
        if($activar_formulacion[0]['cfpd01_formulacion']['activar_formulacion']=='t'){
        
            $cfpp02 = $this->cfpd02_activ_obra->find($this->SQLCA($ano-1));

            $cod_sector = $cfpp02['cfpd02_activ_obra']['cod_sector'];
            $cod_programa = $cfpp02['cfpd02_activ_obra']['cod_programa'];
            $cod_sub_prog = $cfpp02['cfpd02_activ_obra']['cod_sub_prog'];
            $cod_proyecto = $cfpp02['cfpd02_activ_obra']['cod_proyecto'];
            $cod_activ_obra = $cfpp02['cfpd02_activ_obra']['cod_activ_obra'];

            $condicion_categoria_sql = "cod_sector=$cod_sector and cod_programa=$cod_programa and cod_sub_prog=$cod_sub_prog and cod_proyecto=$cod_proyecto and cod_activ_obra=$cod_activ_obra";
            $condicion_partida_sql = " and tipo_proyecto='$tip_pro' and numero_proyecto=$num_pro and cod_partida=$par and cod_generica=$gen and cod_especifica=$esp and cod_sub_espec=$subesp and cod_auxiliar=$au and ".$this->SQLCA($ano);

            $condicion_delete_sql = $condicion_categoria_sql . $condicion_partida_sql;

            if($this->cpod06_distribucion_ingresos_propios->execute("DELETE FROM cpod06_distribucion_ingresos_propios WHERE " . $condicion_delete_sql)>1){
                $this->set('Message_existe','Registro Eliminado con Exito');
                $resultado=$this->cpod06_distribucion_ingresos_propios->execute("SELECT SUM(monto) AS total FROM cpod06_distribucion_ingresos_propios WHERE ".$condicion_categoria_sql." and tipo_proyecto='".$tip_pro."' and numero_proyecto=".$num_pro." and ".$this->SQLCA($ano));
               $monto_vinculacion = $this->cpod06_vinculacion_presupuesto->find($this->SQLCA($ano)." and tipo_proyecto='".$tip_pro."' and numero_proyecto=".$num_pro." and grupo=3", 'SUM(monto) as monto');

               $this->set('TOTAL',$resultado[0][0]['total']);
               $this->set('monto_vinculacion',$monto_vinculacion[0]['monto']);
            }else{
                $this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
            }
            
        }else{
            $this->set('errorMessage','La Formulacion para el Año '.$activar_formulacion[0]['cfpd01_formulacion']['ano_formular'].' esta Cerrada');
        }
    }

}
?>
