<?php

class Shp100SolicitudController extends AppController {

    var $uses = array('catd02_ficha_datos', 'v_shd001_registro_contribuyentes', 'v_shd100_actividadees', 'v_shd100_solicitud_actividades', 'v_shd100_solicitud',
        'shd100_solicitud_actividades', 'cnmd06_profesiones', 'shd003_codigo_ingresos', 'shd002_cobradores', 'shd100_actividades',
        'shd100_solicitud', 'cugd01_republica', 'shd001_registro_contribuyentes', 'cugd01_estados', 'cugd01_municipios',
        'cugd01_parroquias', 'cugd01_centropoblados', 'cugd01_vialidad', 'cugd01_vereda', 'cugd90_municipio_defecto');
    var $helpers = array('Html', 'Ajax', 'Javascript', 'Sisap');

    function checkSession() {
        if (!$this->Session->check('Usuario')) {
            $this->redirect('/salir/');
            exit();
        } else {
            //$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
            //echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
            $this->requestAction('/usuarios/actualizar_user');
        }
    }

//fin checksession

    function beforeFilter() {
        $this->checkSession();
    }

//fin before filter

    function verifica_SS($i) {
        /**
         * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
         * para ser insertados en todas las tablas.
         * */
        switch ($i) {
            case 1:return $this->Session->read('SScodpresi');
                break;
            case 2:return $this->Session->read('SScodentidad');
                break;
            case 3:return $this->Session->read('SScodtipoinst');
                break;
            case 4:return $this->Session->read('SScodinst');
                break;
            case 5:return $this->Session->read('SScoddep');
                break;
            case 6:return $this->Session->read('entidad_federal');
                break;
            default:
                return "NULO";
        }//fin switch
    }

//fin verifica_SS

    function SQLCA($ano = null) {//sql para busqueda de codigos de arranque con y sin año
        $sql_re = "cod_presi=" . $this->verifica_SS(1) . "  and    ";
        $sql_re .= "cod_entidad=" . $this->verifica_SS(2) . "  and  ";
        $sql_re .= "cod_tipo_inst=" . $this->verifica_SS(3) . "  and ";
        $sql_re .= "cod_inst=" . $this->verifica_SS(4) . "  and  ";
        if ($ano != null) {
            $sql_re .= "cod_dep=" . $this->verifica_SS(5) . "  and  ";
            $sql_re .= "ano=" . $ano . "  ";
        } else {
            $sql_re .= "cod_dep=" . $this->verifica_SS(5) . " ";
        }
        return $sql_re;
    }

//fin funcion SQLCA

    function SQLCX($ano = null) {//sql para busqueda de codigos de arranque con y sin año
        $sql_re = "cod_presi=" . $this->verifica_SS(1) . "  and    ";
        $sql_re .= "cod_entidad=" . $this->verifica_SS(2) . "  and  ";
        $sql_re .= "cod_tipo_inst=" . $this->verifica_SS(3) . "  and ";
        $sql_re .= "cod_inst=" . $this->verifica_SS(4) . " ";
        return $sql_re;
    }

//fin funcion SQLX

    function SQLCAX($ano = null) {//sql para busqueda de codigos de arranque con y sin año
        $sql_re = "cod_republica=" . $this->verifica_SS(1) . "  and    ";
        return $sql_re;
    }

//fin funcion SQLCA

    function zero($x = null) {
        if ($x != null) {
            if ($x < 10) {
                $x = "0" . $x;
            } else if ($x >= 10 && $x <= 99) {
                $x = $x;
            }
        }
        return $x;
    }

//fin zero

    function concatena($vector1 = null, $nomVar = null) {

        if ($vector1 != null) {

            foreach ($vector1 as $x => $y) {

                $cod[$x] = $this->zero($x) . ' - ' . $y;
            }

            $this->set($nomVar, $cod);
        }
    }

//fin concatena

    function index($var = null) {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $modulo = $this->Session->read('Modulo');
        $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep = " . $cod_dep . " and (minimo_tributable !='0.00')";
        $listarepublica = $this->cugd01_republica->generateList(null, 'cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
        $this->concatena($listarepublica, 'cod_pais');
        $this->concatena_sin_cero($this->shd100_actividades->generateList($condicion, "cod_actividad ASC", null, '{n}.shd100_actividades.cod_actividad', '{n}.shd100_actividades.denominacion_actividad'), "lista_actividades");
        $this->Session->delete("DATOS");
        $desde = array('01:00AM', '02:00AM', '03:00AM', '04:00AM', '05:00AM', '06:00AM', '07:00AM', '08:00AM', '09:00AM', '10:00AM', '11:00AM', '12:00AM', '01:00PM', '02:00PM', '03:00PM', '04:00PM', '05:00PM', '06:00PM', '07:00PM', '08:00PM', '09:00PM', '10:00PM', '11:00PM', '12:00PM');
        $this->set('desde', $desde);
        $hasta = array('01:00AM', '02:00AM', '03:00AM', '04:00AM', '05:00AM', '06:00AM', '07:00AM', '08:00AM', '09:00AM', '10:00AM', '11:00AM', '12:00AM', '01:00PM', '02:00PM', '03:00PM', '04:00PM', '05:00PM', '06:00PM', '07:00PM', '08:00PM', '09:00PM', '10:00PM', '11:00PM', '12:00PM');
        $this->set('hasta', $hasta);
        $this->data = null;


        $this->data = null;
        $can_mun_def = $this->cugd90_municipio_defecto->findCount($this->SQLCA_S());

        if ($can_mun_def != 0) {

            $mun_defecto = $this->cugd90_municipio_defecto->findAll($this->SQLCA_S());

            $this->set("mun_defecto", $mun_defecto);
            $this->set("can_mun_def", $can_mun_def);

            $cod_republica = $mun_defecto[0]["cugd90_municipio_defecto"]["cod_republica"];
            $cod_estado = $mun_defecto[0]["cugd90_municipio_defecto"]["cod_estado"];
            $cod_municipio = $mun_defecto[0]["cugd90_municipio_defecto"]["cod_municipio"];

            $this->Session->write('pais', $cod_republica);
            $this->Session->write('esta', $cod_estado);
            $this->Session->write('muni', $cod_municipio);

            $lista_r = $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
            $lista_e = $this->cugd01_estados->generateList("cod_republica=" . $cod_republica, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
            $lista_m = $this->cugd01_municipios->generateList("cod_republica=" . $cod_republica . " and cod_estado=" . $cod_estado, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
            $lista_p = $this->cugd01_parroquias->generateList("cod_republica=" . $cod_republica . " and cod_estado=" . $cod_estado . " and cod_municipio=" . $cod_municipio, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');


            $this->concatena($lista_r, 'vector_r');
            $this->concatena($lista_e, 'vector_e');
            $this->concatena($lista_m, 'vector_m');
            $this->concatena($lista_p, 'vector_p');

            $deno_r = $this->cugd01_republica->findAll("cod_republica=" . $cod_republica);
            $deno_e = $this->cugd01_estados->findAll("cod_republica=" . $cod_republica . " and cod_estado=" . $cod_estado);
            $deno_m = $this->cugd01_municipios->findAll("cod_republica=" . $cod_republica . " and cod_estado=" . $cod_estado . " and cod_municipio=" . $cod_municipio);

            $this->set('deno_r', $deno_r[0]["cugd01_republica"]["denominacion"]);
            $this->set('deno_e', $deno_e[0]["cugd01_estados"]["denominacion"]);
            $this->set('deno_m', $deno_m[0]["cugd01_municipios"]["denominacion"]);

            $this->set('cod_r', $cod_republica);
            $this->set('cod_e', $cod_estado);
            $this->set('cod_m', $cod_municipio);


            $this->set('seleccion_pais', $cod_republica);
            $this->set('seleccion_esta', $cod_estado);
            $this->set('seleccion_muni', $cod_municipio);
        } else {
            $this->set('deno_r', "");
            $this->set('deno_e', "");
            $this->set('deno_m', "");

            $this->set('cod_r', "");
            $this->set('cod_e', "");
            $this->set('cod_m', "");

            $this->set('seleccion_pais', "");
            $this->set('seleccion_esta', "");
            $this->set('seleccion_muni', "");

            $listarepublica = $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
            $this->concatena($listarepublica, 'vector_r');

            $this->set('vector_e', "");
            $this->set('vector_m', "");
            $this->set('vector_p', "");
        }//fin else
    }

//fin index
    /*
      function select3($select=null,$var=null) { //select codigos presupuestarios
      $this->layout = "ajax";//echo $var;
      if($var!=null){
      switch($select){
      case 'republica':
      $this->set('SELECT','estados');
      $this->set('codigo','republica');
      $this->set('seleccion','');
      $this->set('n',1);
      $lista=  $this->cugd01_republica->generateList(null,'cod_republica ASC', null, '{n}.cfpd02_sector.cod_republica', '{n}.cfpd02_sector.denominacion');
      $this->concatena($lista, 'vector');
      break;
      case 'estados':
      //echo $select;
      $this->set('SELECT','municipios');
      $this->set('codigo','estados');
      $this->set('seleccion','');
      $this->set('n',2);
      $this->Session->write('rep',$var);
      $cond=" cod_republica=".$var;
      $lista=  $this->cugd01_estados->generateList($cond, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
      $this->concatena($lista, 'vector');
      break;
      case 'municipios':
      $this->set('SELECT','parroquias');
      $this->set('codigo','municipios');
      $this->set('seleccion','');
      $this->set('n',3);
      $this->set('buitre',true);
      $rep =  $this->Session->read('rep');
      $this->Session->write('est',$var);
      $cond =" cod_republica=".$rep." and cod_estado=".$var;
      $lista=  $this->cugd01_municipios->generateList($cond, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
      $this->concatena($lista, 'vector');
      break;
      case 'parroquias':
      $this->set('SELECT','centros');
      $this->set('codigo','parroquias');
      $this->set('seleccion','');
      $this->set('n',4);
      $rep =  $this->Session->read('rep');
      $est =  $this->Session->read('est');
      $this->Session->write('mun',$var);
      $cond =" cod_republica=".$rep." and cod_estado=".$est." and cod_municipio=".$var;
      $lista=  $this->cugd01_parroquias->generateList($cond, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
      $this->concatena($lista, 'vector');
      break;
      case 'centros':
      $this->set('SELECT','calles');
      $this->set('codigo','centros');
      $this->set('seleccion','');
      $this->set('n',5);
      $rep =  $this->Session->read('rep');
      $est =  $this->Session->read('est');
      $mun =  $this->Session->read('mun');
      $this->Session->write('parr',$var);
      $cond =" cod_republica=".$rep." and cod_estado=".$est." and cod_municipio=".$mun." and cod_parroquia=".$var;
      $lista=  $this->cugd01_centropoblados->generateList($cond, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
      $this->concatena($lista, 'vector');
      break;
      case 'calles':
      //echo 'calle';
      $this->set('SELECT','veredas');
      $this->set('codigo','calles');
      $this->set('seleccion','');
      $this->set('n',6);
      $rep =  $this->Session->read('rep');
      $est =  $this->Session->read('est');
      $mun =  $this->Session->read('mun');
      $parr =  $this->Session->read('parr');
      $this->Session->write('cent',$var);
      $cond =" cod_republica=".$rep." and cod_estado=".$est." and cod_municipio=".$mun." and cod_parroquia=".$parr." and cod_centro=".$var;
      $lista=  $this->cugd01_vialidad->generateList($cond, 'cod_vialidad ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
      if($lista!=null){
      $this->concatena($lista,'vector');
      }else{
      $this->set('vector',array('0'=>'00'));
      }
      break;
      case 'veredas':
      $this->set('SELECT','veredas');
      $this->set('codigo','veredas');
      $this->set('seleccion','');
      $this->set('n',7);
      $this->set('no','no');
      $rep =  $this->Session->read('rep');
      $est =  $this->Session->read('est');
      $mun =  $this->Session->read('mun');
      $parr =  $this->Session->read('parr');
      $cent =  $this->Session->read('cent');
      $this->Session->write('via',$var);
      $cond =" cod_republica=".$rep." and cod_estado=".$est." and cod_municipio=".$mun." and cod_parroquia=".$parr." and cod_centro=".$cent." and cod_vialidad=".$var;
      $lista=  $this->cugd01_vereda->generateList($cond, 'cod_vereda ASC', null, '{n}.cugd01_vereda.cod_vereda', '{n}.cugd01_vereda.denominacion');
      if($lista!=null){
      $this->concatena($lista,'vector');
      }else{
      $this->set('vector',array('0'=>'00'));
      }
      break;
      }//fin wsitch
      }else{
      $this->set('SELECT','');
      $this->set('codigo','');
      $this->set('seleccion','');
      $this->set('n',15);
      $this->set('no','no');
      $this->set('vector','');
      }
      }//fin select codigos presupuestarios

      function mostrar3($select=null,$var=null) { //mostrar3 codigos presupuestarios
      $this->layout = "ajax";
      if( $var!=null && !empty($var)){
      $cond = $this->SQLCAX();
      // $cond2 = $this->SQLCA();
      switch($select){
      case 'pais':
      // $ano =  $this->Session->read('ano');
      $this->Session->write('esta',$var);
      $cond .="  cod_republica=".$var;
      $a=  $this->cugd01_republica->findAll($cond);
      $den=$a[0]['cugd01_republica']['denominacion'];
      $this->set('deno',$den);
      break;
      case 'estados':
      // $ano =  $this->Session->read('ano');
      $this->Session->write('esta',$var);
      $cond .="  cod_estado=".$var;
      $a=  $this->cugd01_estados->findAll($cond);
      $den=$a[0]['cugd01_estados']['denominacion'];
      $this->set('deno',$den);
      break;
      case 'municipios':
      //$ano =  $this->Session->read('ano');
      $esta =  $this->Session->read('esta');
      $this->Session->write('muni',$var);
      $cond .=" cod_estado=".$esta." and cod_municipio=".$var;
      $a=  $this->cugd01_municipios->findAll($cond);
      $den=$a[0]['cugd01_municipios']['denominacion'];
      $this->set('deno',$den);
      break;
      case 'parroquias':
      //$ano =  $this->Session->read('ano');
      $esta =  $this->Session->read('esta');
      $muni =  $this->Session->read('muni');
      $this->Session->write('parr',$var);
      $cond .=" cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$var;
      $a=  $this->cugd01_parroquias->findAll($cond);
      $den=$a[0]['cugd01_parroquias']['denominacion'];
      $this->set('deno',$den);
      break;
      case 'centros':
      //echo 'centro';
      //$ano =  $this->Session->read('ano');
      $esta =  $this->Session->read('esta');
      $muni =  $this->Session->read('muni');
      $parr =  $this->Session->read('parr');
      $this->Session->write('cent',$var);
      $cond .=" cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$parr." and cod_centro=".$var;
      $a=  $this->cugd01_centropoblados->findAll($cond);
      $den=$a[0]['cugd01_centropoblados']['denominacion'];
      $this->set('deno',$den);
      break;
      case 'calles':
      //$ano =  $this->Session->read('ano');
      $esta =  $this->Session->read('esta');
      $muni =  $this->Session->read('muni');
      $parr =  $this->Session->read('parr');
      $cent =  $this->Session->read('cent');
      $this->Session->write('call',$var);
      $cond .=" cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$parr." and cod_centro=".$cent." and cod_vialidad=".$var;
      $a=  $this->cugd01_vialidad->findAll($cond);
      $den=$a[0]['cugd01_vialidad']['denominacion'];
      $this->set('deno',$den);
      break;
      case 'veredas':
      //$ano =  $this->Session->read('ano');
      $esta =  $this->Session->read('esta');
      $muni =  $this->Session->read('muni');
      $parr =  $this->Session->read('parr');
      $cent =  $this->Session->read('cent');
      $call =  $this->Session->read('call');
      $this->Session->write('vere',$var);
      $cond .=" cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$parr." and cod_centro=".$cent." and cod_vialidad=".$call." and cod_vereda=".$var;
      $a=  $this->cugd01_vereda->findAll($cond);
      $den=$a[0]['cugd01_vereda']['denominacion'];
      $this->set('deno',$den);
      break;
      }//fin wsitch
      }else{
      echo "";
      }
      }//fin mostrar3 co

      function mostrar4($select=null,$var=null) { //mostrar3 codigos presupuestarios
      $this->layout = "ajax";
      if( $var!=null){
      $cond = $this->SQLCAX();
      // $cond2 = $this->SQLCA();
      switch($select){
      case 'pais':
      // $ano =  $this->Session->read('ano');
      $this->Session->write('esta',$var);
      $cond .="  cod_republica=".$var;
      $a=  $this->cugd01_republica->findAll($cond);
      $cod= $a[0]['cugd01_republica']['cod_republica'] >9 ?$a[0]['cugd01_republica']['cod_republica'] : "0".$a[0]['cugd01_republica']['cod_republica'] ;
      $this->set('codi',$cod);
      break;
      case 'estados':
      // $ano =  $this->Session->read('ano');
      $this->Session->write('esta',$var);
      $cond .="  cod_estado=".$var;
      $a=  $this->cugd01_estados->findAll($cond);
      $cod= $a[0]['cugd01_estados']['cod_estado'] >9 ?$a[0]['cugd01_estados']['cod_estado'] : "0".$a[0]['cugd01_estados']['cod_estado'] ;
      $this->set('codi',$cod);
      break;
      case 'municipios':
      //$ano =  $this->Session->read('ano');
      $esta =  $this->Session->read('esta');
      $this->Session->write('muni',$var);
      $cond .="  cod_estado=".$esta." and cod_municipio=".$var;
      $a=  $this->cugd01_municipios->findAll($cond);
      $cod=$a[0]['cugd01_municipios']['cod_municipio'] >9 ?$a[0]['cugd01_municipios']['cod_municipio'] : "0".$a[0]['cugd01_municipios']['cod_municipio'] ;
      $this->set('codi',$cod);
      break;
      case 'parroquias':
      $esta =  $this->Session->read('esta');
      $muni =  $this->Session->read('muni');
      $this->Session->write('parr',$var);
      $cond .=" cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$var;
      $a=  $this->cugd01_parroquias->findAll($cond);
      $cod= $a[0]['cugd01_parroquias']['cod_republica'] >9 ?$a[0]['cugd01_parroquias']['cod_republica'] : "0".$a[0]['cugd01_parroquias']['cod_republica'] ;
      $this->set('codi',$cod);
      break;
      case 'centros':
      $esta =  $this->Session->read('esta');
      $muni =  $this->Session->read('muni');
      $parr =  $this->Session->read('parr');
      $this->Session->write('cent',$var);
      $cond .=" cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$parr." and cod_centro=".$var;
      $a=  $this->cugd01_centropoblados->findAll($cond);
      $cod= $a[0]['cugd01_centropoblados']['cod_estado'] >9 ?$a[0]['cugd01_centropoblados']['cod_estado'] : "0".$a[0]['cugd01_centropoblados']['cod_estado'] ;
      $this->set('codi',$cod);
      break;
      case 'calles':
      //$ano =  $this->Session->read('ano');
      $esta =  $this->Session->read('esta');
      $muni =  $this->Session->read('muni');
      $parr =  $this->Session->read('parr');
      $cent =  $this->Session->read('cent');
      $this->Session->write('call',$var);
      $cond .=" cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$parr." and cod_centro=".$cent." and cod_vialidad=".$var;
      $a=  $this->cugd01_vialidad->findAll($cond);
      $cod=$a[0]['cugd01_vialidad']['cod_municipio'] >9 ?$a[0]['cugd01_vialidad']['cod_municipio'] : "0".$a[0]['cugd01_vialidad']['cod_municipio'] ;
      $this->set('codi',$cod);
      break;
      case 'veredas':
      //$ano =  $this->Session->read('ano');
      $esta =  $this->Session->read('esta');
      $muni =  $this->Session->read('muni');
      $parr =  $this->Session->read('parr');
      $cent =  $this->Session->read('cent');
      $call =  $this->Session->read('call');
      $this->Session->write('vere',$var);
      $cond .=" cod_estado=".$esta." and cod_municipio=".$muni." and cod_parroquia=".$parr." and cod_centro=".$cent." and cod_vialidad=".$call." and cod_vereda=".$var;
      $a=  $this->cugd01_vereda->findAll($cond);
      $cod=$a[0]['cugd01_vereda']['cod_municipio'] >9 ?$a[0]['cugd01_vereda']['cod_municipio'] : "0".$a[0]['cugd01_vereda']['cod_municipio'] ;
      $this->set('codi',$cod);
      break;
      }//fin wsitch
      }else{
      echo "";
      }
      }//fin mostrar3 codigos presupuestarios
     */

    function SQLCA_S($ano = null) {//sql para busqueda de codigos de arranque con y sin año
        $sql_re = "cod_presi=" . $this->verifica_SS(1) . "  and    ";
        $sql_re .= "cod_entidad=" . $this->verifica_SS(2) . "  and  ";
        $sql_re .= "cod_tipo_inst=" . $this->verifica_SS(3) . "  and ";
        $sql_re .= "cod_inst=" . $this->verifica_SS(4) . "   ";
        return $sql_re;
    }

//fin funcion SQLCA

    function select3($select = null, $var = null) { //select codigos presupuestarios
        $this->layout = "ajax"; //echo $var;
        //echo 'si';
        if ($var != null) {
            switch ($select) {
                case 'republica':
                    $this->set('SELECT', 'estados');
                    $this->set('codigo', 'pais');
                    $this->set('seleccion', '');
                    $this->set('n', 1);
                    $lista = $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cfpd02_sector.cod_republica', '{n}.cfpd02_sector.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
                case 'estados':
                    //echo $select;
                    $this->set('SELECT', 'municipios');
                    $this->set('codigo', 'estados');
                    $this->set('seleccion', '');
                    $this->set('n', 2);
                    $this->Session->write('pais', $var);
                    $cond = " cod_republica=" . $var;
                    $lista = $this->cugd01_estados->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
                case 'municipios':
                    $this->set('SELECT', 'parroquias');
                    $this->set('codigo', 'municipios');
                    $this->set('seleccion', '');
                    $this->set('n', 3);
                    $this->set('buitre', true);
                    $rep = $this->Session->read('pais');
                    $this->Session->write('esta', $var);
                    $cond = " cod_republica=" . $rep . " and cod_estado=" . $var;
                    $lista = $this->cugd01_municipios->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
                case 'parroquias':
                    $this->set('SELECT', 'centros');
                    $this->set('codigo', 'parroquias');
                    $this->set('seleccion', '');
                    $this->set('n', 4);
                    $rep = $this->Session->read('pais');
                    $est = $this->Session->read('esta');
                    $this->Session->write('muni', $var);
                    $cond = " cod_republica=" . $rep . " and cod_estado=" . $est . " and cod_municipio=" . $var;
                    //echo $cond;
                    $lista = $this->cugd01_parroquias->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
                case 'centros':
                    $this->set('SELECT', 'calles');
                    $this->set('codigo', 'centros');
                    $this->set('seleccion', '');
                    $this->set('n', 5);
                    $rep = $this->Session->read('pais');
                    $est = $this->Session->read('esta');
                    $mun = $this->Session->read('muni');
                    $this->Session->write('parr', $var);
                    $cond = " cod_republica=" . $rep . " and cod_estado=" . $est . " and cod_municipio=" . $mun . " and cod_parroquia=" . $var;
                    $lista = $this->cugd01_centropoblados->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
                    $this->concatena($lista, 'vector');
                    break;
                case 'calles':
                    $this->set('SELECT', 'veredas');
                    $this->set('codigo', 'calles');
                    $this->set('seleccion', '');
                    $this->set('n', 6);
                    $rep = $this->Session->read('pais');
                    $est = $this->Session->read('esta');
                    $mun = $this->Session->read('muni');
                    $parr = $this->Session->read('parr');
                    $this->Session->write('cent', $var);
                    $cond = " cod_republica=" . $rep . " and cod_estado=" . $est . " and cod_municipio=" . $mun . " and cod_parroquia=" . $parr . " and cod_centro=" . $var;
                    $lista = $this->cugd01_vialidad->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
                    if ($lista != null) {
                        $this->concatena($lista, 'vector');
                    } else {
                        $this->set('vector', array());
                    }
                    break;
                case 'veredas':
                    $this->set('SELECT', 'veredas');
                    $this->set('codigo', 'veredas');
                    $this->set('seleccion', '');
                    $this->set('n', 7);
                    $this->set('no', 'no');
                    $rep = $this->Session->read('pais');
                    $est = $this->Session->read('esta');
                    $mun = $this->Session->read('muni');
                    $parr = $this->Session->read('parr');
                    $cent = $this->Session->read('cent');
                    $this->Session->write('vial', $var);
                    $cond = " cod_republica=" . $rep . " and cod_estado=" . $est . " and cod_municipio=" . $mun . " and cod_parroquia=" . $parr . " and cod_centro=" . $cent . " and cod_vialidad=" . $var;
                    $lista = $this->cugd01_vereda->generateList($cond, 'denominacion ASC', null, '{n}.cugd01_vereda.cod_vereda', '{n}.cugd01_vereda.denominacion');
                    if ($lista != null) {
                        $this->concatena($lista, 'vector');
                    } else {
                        $this->set('vector', array('0' => '00'));
                    }
                    break;
            }//fin wsitch
        } else {
            $this->set('SELECT', '');
            $this->set('codigo', '');
            $this->set('seleccion', '');
            $this->set('n', 15);
            $this->set('no', 'no');
            $this->set('vector', '');
        }
    }

//fin select codigos presupuestarios

    function mostrar3($select = null, $var = null) { //mostrar3 codigos presupuestarios
        $this->layout = "ajax"; //echo $var;
        if ($var != null) {
            $cond = "";
            switch ($select) {
                case 'pais':
                    $this->Session->write('pais', $var);
                    $cond .="  cod_republica=" . $var;
                    $a = $this->cugd01_republica->findAll($cond);
                    $den = $a[0]['cugd01_republica']['denominacion'];
                    $this->set('deno', $den);
                    echo "<script>";
                    echo "document.getElementById('s_municipios').innerHTML='<select id=municipios />';  ";
                    echo "document.getElementById('s_parroquias').innerHTML='<select id=parroquias />';  ";
                    echo "document.getElementById('s_centros').innerHTML='<select id=centros />';  ";
                    echo "document.getElementById('s_calles').innerHTML='<select id=calles />';  ";
                    echo "document.getElementById('s_veredas').innerHTML='<select id=veredas />';  ";
                    echo "document.getElementById('c_2').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('c_3').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('c_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('c_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('c_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('c_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_2').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_3').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "</script>";
                    break;
                case 'estados':
                    $pais = $this->Session->read('pais');
                    $this->Session->write('esta', $var);
                    $cond .="  cod_republica=" . $pais . " and cod_estado=" . $var;
                    $a = $this->cugd01_estados->findAll($cond);
                    $den = $a[0]['cugd01_estados']['denominacion'];
                    $this->set('deno', $den);
                    echo "<script>";
                    echo "document.getElementById('s_parroquias').innerHTML='<select id=parroquias />';  ";
                    echo "document.getElementById('s_centros').innerHTML='<select id=centros />';  ";
                    echo "document.getElementById('s_calles').innerHTML='<select id=calles />';  ";
                    echo "document.getElementById('s_veredas').innerHTML='<select id=veredas />';  ";
                    echo "document.getElementById('c_3').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('c_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('c_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('c_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('c_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_3').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "</script>";
                    break;
                case 'municipios':
                    $pais = $this->Session->read('pais');
                    $esta = $this->Session->read('esta');
                    $this->Session->write('muni', $var);
                    $cond .=" cod_republica=" . $pais . " and cod_estado=" . $esta . " and cod_municipio=" . $var;
                    $a = $this->cugd01_municipios->findAll($cond);
                    $den = $a[0]['cugd01_municipios']['denominacion'];
                    $this->set('deno', $den);
                    echo "<script>";
                    echo "document.getElementById('s_centros').innerHTML='<select id=centros />';  ";
                    echo "document.getElementById('s_calles').innerHTML='<select id=calles />';  ";
                    echo "document.getElementById('s_veredas').innerHTML='<select id=veredas />';  ";
                    echo "document.getElementById('c_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('c_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('c_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('c_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_4').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "</script>";
                    break;
                case 'parroquias':
                    $pais = $this->Session->read('pais');
                    $esta = $this->Session->read('esta');
                    $muni = $this->Session->read('muni');
                    $this->Session->write('parr', $var);
                    $cond .=" cod_republica=" . $pais . " and cod_estado=" . $esta . " and cod_municipio=" . $muni . " and cod_parroquia=" . $var;
                    $a = $this->cugd01_parroquias->findAll($cond);
                    $den = $a[0]['cugd01_parroquias']['denominacion'];
                    $this->set('deno', $den);
                    echo "<script>";
                    echo "document.getElementById('s_calles').innerHTML='<select id=calles />';  ";
                    echo "document.getElementById('s_veredas').innerHTML='<select id=veredas />';  ";
                    echo "document.getElementById('c_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('c_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('c_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_5').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "</script>";
                    break;
                case 'centros':
                    //echo $select;
                    $pais = $this->Session->read('pais');
                    $esta = $this->Session->read('esta');
                    $muni = $this->Session->read('muni');
                    $parr = $this->Session->read('parr');
                    $this->Session->write('cent', $var);
                    $cond .=" cod_republica=" . $pais . " and cod_estado=" . $esta . " and cod_municipio=" . $muni . " and cod_parroquia=" . $parr . " and cod_centro=" . $var;
                    $a = $this->cugd01_centropoblados->findAll($cond);
                    if (count($a) == 0) {
                        $cod = 'N/A';
                    } else {
                        $den = $a[0]['cugd01_centropoblados']['denominacion'];
                    }
                    $this->set('deno', $den);
                    echo "<script>";
                    echo "document.getElementById('s_veredas').innerHTML='<select id=veredas />';  ";
                    echo "document.getElementById('c_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('c_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_6').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "</script>";
                    break;
                case 'calles':
                    $pais = $this->Session->read('pais');
                    $esta = $this->Session->read('esta');
                    $muni = $this->Session->read('muni');
                    $parr = $this->Session->read('parr');
                    $cent = $this->Session->read('cent');
                    $this->Session->write('call', $var);
                    $cond .=" cod_republica=" . $pais . " and cod_estado=" . $esta . " and cod_municipio=" . $muni . " and cod_parroquia=" . $parr . " and cod_centro=" . $cent . " and cod_vialidad=" . $var;
                    $a = $this->cugd01_vialidad->findAll($cond);
                    if (count($a) == 0) {
                        $den = 'N/A';
                    } else {
                        $den = $a[0]['cugd01_vialidad']['denominacion'];
                    }
                    $this->set('deno', $den);
                    echo "<script>";
                    echo "document.getElementById('c_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "document.getElementById('d_7').innerHTML='<input type=text  size=10% class=inputtext />';  ";
                    echo "</script>";
                    break;
                case 'veredas':
                    $pais = $this->Session->read('pais');
                    $esta = $this->Session->read('esta');
                    $muni = $this->Session->read('muni');
                    $parr = $this->Session->read('parr');
                    $cent = $this->Session->read('cent');
                    $call = $this->Session->read('call');
                    $this->Session->write('vere', $var);
                    $cond .="cod_republica=" . $pais . " and cod_estado=" . $esta . " and cod_municipio=" . $muni . " and cod_parroquia=" . $parr . " and cod_centro=" . $cent . " and cod_vialidad=" . $call . " and cod_vereda=" . $var;
                    $a = $this->cugd01_vereda->findAll($cond);
                    if (count($a) == 0) {
                        $den = 'N/A';
                    } else {
                        $den = $a[0]['cugd01_vereda']['denominacion'];
                    }
                    $this->set('deno', $den);
                    break;
            }//fin wsitch
        } else {
            echo "";
        }
    }

//fin mostrar3 co

    function mostrar4($select = null, $var = null) { //mostrar3 codigos presupuestarios
        $this->layout = "ajax";
        if ($var != null) {
            //  $cond = "";
            // $cond2 = $this->SQLCA();
            switch ($select) {
                case 'pais':
                    $this->Session->write('pais', $var);
                    $cond = "cod_republica=" . $var; //echo $cond;
                    $a = $this->cugd01_republica->findAll($cond);
                    $cod = $a[0]['cugd01_republica']['cod_republica'] > 9 ? $a[0]['cugd01_republica']['cod_republica'] : "0" . $a[0]['cugd01_republica']['cod_republica'];
                    $this->set('codi', $cod);
                    break;
                case 'estados':
                    $this->Session->write('esta', $var);
                    $pais = $this->Session->read('pais');
                    $cond = "cod_republica=" . $pais . " and cod_estado=" . $var; //echo $cond;
                    $a = $this->cugd01_estados->findAll($cond);
                    $cod = $a[0]['cugd01_estados']['cod_estado'] > 9 ? $a[0]['cugd01_estados']['cod_estado'] : "0" . $a[0]['cugd01_estados']['cod_estado'];
                    $this->set('codi', $cod);
                    break;
                case 'municipios':
                    $pais = $this->Session->read('pais');
                    $esta = $this->Session->read('esta');
                    $this->Session->write('muni', $var);
                    $cond = "cod_republica=" . $pais . " and cod_estado=" . $esta . " and cod_municipio=" . $var;
                    $a = $this->cugd01_municipios->findAll($cond);
                    //echo $cond;
                    $cod = $a[0]['cugd01_municipios']['cod_municipio'] > 9 ? $a[0]['cugd01_municipios']['cod_municipio'] : "0" . $a[0]['cugd01_municipios']['cod_municipio'];
                    $this->set('codi', $cod);
                    break;
                case 'parroquias':
                    $pais = $this->Session->read('pais');
                    $esta = $this->Session->read('esta');
                    $muni = $this->Session->read('muni');
                    $this->Session->write('parr', $var);
                    $cond = "cod_republica=" . $pais . " and cod_estado=" . $esta . " and cod_municipio=" . $muni . " and cod_parroquia=" . $var; //echo $cond;
                    $a = $this->cugd01_parroquias->findAll($cond);
                    //echo $cond;
                    //print_r($a);
                    $cod = $a[0]['cugd01_parroquias']['cod_parroquia'] > 9 ? $a[0]['cugd01_parroquias']['cod_parroquia'] : "0" . $a[0]['cugd01_parroquias']['cod_parroquia'];
                    $this->set('codi', $cod);
                    break;
                case 'centros':
                    $pais = $this->Session->read('pais');
                    $esta = $this->Session->read('esta');
                    $muni = $this->Session->read('muni');
                    $parr = $this->Session->read('parr');
                    $this->Session->write('cent', $var);
                    $cond = "cod_republica=" . $pais . " and cod_estado=" . $esta . " and cod_municipio=" . $muni . " and cod_parroquia=" . $parr . " and cod_centro=" . $var;
                    $a = $this->cugd01_centropoblados->findAll($cond);
                    if (count($a) == 0) {
                        $cod = '00';
                    } else {
                        $cod = $a[0]['cugd01_centropoblados']['cod_centro'] > 9 ? $a[0]['cugd01_centropoblados']['cod_centro'] : "0" . $a[0]['cugd01_centropoblados']['cod_centro'];
                    }
                    $this->set('codi', $cod);
                    break;
                case 'calles':
                    $pais = $this->Session->read('pais');
                    $esta = $this->Session->read('esta');
                    $muni = $this->Session->read('muni');
                    $parr = $this->Session->read('parr');
                    $cent = $this->Session->read('cent');
                    $this->Session->write('call', $var);
                    $cond = "cod_republica=" . $pais . " and cod_estado=" . $esta . " and cod_municipio=" . $muni . " and cod_parroquia=" . $parr . " and cod_centro=" . $cent . " and cod_vialidad=" . $var;
                    $a = $this->cugd01_vialidad->findAll($cond);
                    //print_r($a);
                    if (count($a) == 0) {
                        $cod = '00';
                    } else {
                        $cod = $a[0]['cugd01_vialidad']['cod_vialidad'] > 9 ? $a[0]['cugd01_vialidad']['cod_vialidad'] : "0" . $a[0]['cugd01_vialidad']['cod_vialidad'];
                    }
                    $this->set('codi', $cod);
                    break;
                case 'veredas':
                    $pais = $this->Session->read('pais');
                    $esta = $this->Session->read('esta');
                    $muni = $this->Session->read('muni');
                    $parr = $this->Session->read('parr');
                    $cent = $this->Session->read('cent');
                    $call = $this->Session->read('call');
                    $this->Session->write('vere', $var);
                    $cond = "cod_republica=" . $pais . " and cod_estado=" . $esta . " and cod_municipio=" . $muni . " and cod_parroquia=" . $parr . " and cod_centro=" . $cent . " and cod_vialidad=" . $call . " and cod_vereda=" . $var;
                    $a = $this->cugd01_vereda->findAll($cond);
                    if (count($a) == 0) {
                        $cod = '00';
                    } else {
                        $cod = $a[0]['cugd01_vereda']['cod_vereda'] > 9 ? $a[0]['cugd01_vereda']['cod_vereda'] : "0" . $a[0]['cugd01_vereda']['cod_vereda'];
                    }
                    $this->set('codi', $cod);
                    break;
            }//fin wsitch
        } else {
            echo "";
        }
    }

//fin mostrar3 codigos presupuestarios

    function razon($rif_cedula) {
        $this->layout = "ajax";
        $datos = $this->shd001_registro_contribuyentes->findAll("rif_cedula='" . $rif_cedula . "'");
        $this->set('datos', $datos);
    }

    function contribuyente($rif_cedula) {
        $this->layout = "ajax";
        $datos = $this->shd001_registro_contribuyentes->findAll("rif_cedula='" . $rif_cedula . "'");
        //pr($datos);
        if ($datos != null) {
            $cod_profesion = $datos[0]["shd001_registro_contribuyentes"]["profesion"];
            $cod_pais = $datos[0]["shd001_registro_contribuyentes"]["cod_pais"];
            $cod_estado = $datos[0]["shd001_registro_contribuyentes"]["cod_estado"];
            $cod_municipio = $datos[0]["shd001_registro_contribuyentes"]["cod_municipio"];
            $cod_parroquia = $datos[0]["shd001_registro_contribuyentes"]["cod_parroquia"];
            $cod_centro_poblado = $datos[0]["shd001_registro_contribuyentes"]["cod_centro_poblado"];
            $cod_calle_avenida = $datos[0]["shd001_registro_contribuyentes"]["cod_calle_avenida"];
            $cod_vereda_edificio = $datos[0]["shd001_registro_contribuyentes"]["cod_vereda_edificio"];
            $pais = $this->cugd01_republica->findAll('cod_republica=' . $cod_pais);
            $estados = $this->cugd01_estados->findAll('cod_republica=' . $cod_pais . ' and cod_estado=' . $cod_estado);
            $municipios = $this->cugd01_municipios->findAll('cod_republica=' . $cod_pais . ' and cod_estado=' . $cod_estado . ' and cod_municipio=' . $cod_municipio);
            $parroquias = $this->cugd01_parroquias->findAll('cod_republica=' . $cod_pais . ' and cod_estado=' . $cod_estado . ' and cod_municipio=' . $cod_municipio . ' and cod_parroquia=' . $cod_parroquia);
            $centros = $this->cugd01_centropoblados->findAll('cod_republica=' . $cod_pais . ' and cod_estado=' . $cod_estado . ' and cod_municipio=' . $cod_municipio . ' and cod_parroquia=' . $cod_parroquia . ' and cod_centro=' . $cod_centro_poblado);
            $vialidad = $this->cugd01_vialidad->findAll('cod_republica=' . $cod_pais . ' and cod_estado=' . $cod_estado . ' and cod_municipio=' . $cod_municipio . ' and cod_parroquia=' . $cod_parroquia . ' and cod_centro=' . $cod_centro_poblado . ' and cod_vialidad=' . $cod_calle_avenida);
            $vereda = $this->cugd01_vereda->findAll('cod_republica=' . $cod_pais . ' and cod_estado=' . $cod_estado . ' and cod_municipio=' . $cod_municipio . ' and cod_parroquia=' . $cod_parroquia . ' and cod_centro=' . $cod_centro_poblado . ' and cod_vialidad=' . $cod_calle_avenida . ' and cod_vereda=' . $cod_vereda_edificio);
            $profesiones = $this->cnmd06_profesiones->findAll('cod_profesion=' . $cod_profesion);
            $this->set('profesion', $profesiones);
            $this->set('pais', $pais);
            $this->set('estados', $estados);
            $this->set('municipios', $municipios);
            $this->set('parroquias', $parroquias);
            $this->set('centros', $centros);
            $this->set('vialidad', $vialidad);
            $this->set('vereda', $vereda);
            $this->set('datos', $datos);
        }
    }

    function selecion_actividad($var = null) {

        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $modulo = $this->Session->read('Modulo');
        $condicion = "cod_presi = " . $cod_presi . " and cod_entidad = " . $cod_entidad . " and cod_tipo_inst = " . $cod_tipo_inst . " and cod_inst = " . $cod_inst . " and cod_dep = " . $cod_dep;


        $shd100_actividades_aux = $this->shd100_actividades->findAll($condicion . " and cod_actividad = '" . $var . "'");

        $var1 = "";

        foreach ($shd100_actividades_aux as $ve) {
            $var1 = $ve["shd100_actividades"]["denominacion_actividad"];
            $var2 = $ve["shd100_actividades"]["alicuota"];
            $var3 = $ve["shd100_actividades"]["minimo_tributable"];
        }//fin foreach
        echo'<script>';
        echo" document.getElementById('activ_cod').value        = '" . $var . "'; ";
        echo" document.getElementById('activ_deno').value        = '" . $var1 . "'; ";
        echo" document.getElementById('actv_alicuota').value        = '" . $this->Formato2($var2) . "'; ";
        echo" document.getElementById('minimo').value        = '" . $this->Formato2($var3) . "'; ";
        echo'</script>';


        $this->render("funcion");
    }

//fin fucntion

    function agregar_grilla() {

        $this->layout = "ajax";


        $cod_actividad = $this->data['shp100_solicitud']['cod_actividad'];
        $activ_deno = $this->data['shp100_solicitud']['activ_deno'];
        $actv_alicuota = $this->Formato1($this->data['shp100_solicitud']['actv_alicuota']);
        $minimo = $this->Formato1($this->data['shp100_solicitud']['minimo']);



        if (!isset($_SESSION["DATOS"])) {
            $_SESSION["CUENTA"] = 1;
            $cont = $_SESSION["CUENTA"];
            $_SESSION["DATOS"][$cont]["cod_actividad"] = $cod_actividad;
            $_SESSION["DATOS"][$cont]["activ_deno"] = $activ_deno;
            $_SESSION["DATOS"][$cont]["actv_alicuota"] = $actv_alicuota;
            $_SESSION["DATOS"][$cont]["minimo"] = $minimo;
            $_SESSION["DATOS"][$cont]["activa"] = 1;
            $_SESSION["DATOS"][$cont]["id"] = $cont;

            $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
        } else {

            $cont = $_SESSION["CUENTA"];
            $marca = 0;

            for ($i = 1; $i <= $cont; $i++) {
                if ($cod_actividad == $_SESSION["DATOS"][$i]["cod_actividad"] && $_SESSION["DATOS"][$i]["activa"] == 1) {
                    $marca = 1;
                }//fin if
            }//fin for
            if ($marca == 1) {
                $this->set("errorMessage", "EL REGISTRO YA EXISTE");
            } else {
                $cont = $_SESSION["CUENTA"];
                $cont++;
                $_SESSION["CUENTA"] = $cont;
                $_SESSION["DATOS"][$cont]["cod_actividad"] = $cod_actividad;
                $_SESSION["DATOS"][$cont]["activ_deno"] = $activ_deno;
                $_SESSION["DATOS"][$cont]["actv_alicuota"] = $actv_alicuota;
                $_SESSION["DATOS"][$cont]["minimo"] = $minimo;
                $_SESSION["DATOS"][$cont]["activa"] = 1;
                $_SESSION["DATOS"][$cont]["id"] = $cont;
                $this->set("Message_existe", "EL REGISTRO FUE AGREGADO");
            }//fin else
        }//fin else






        echo'<script>';
        echo" document.getElementById('activ_cod').value           = ''; ";
        echo" document.getElementById('activ_deno').value           = ''; ";
        echo" document.getElementById('actv_alicuota').value        = ''; ";
        echo" document.getElementById('minimo').value     = ''; ";
        echo'</script>';




        $this->set("accion", $_SESSION["DATOS"]);
    }

//fin function

    function guardar() {
        $this->layout = "ajax";
        //pr($this->data);
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $numero_solicitud = str_replace(" ", "", $this->data['shp100_solicitud']['numero_solicitud']);
        $fecha_solicitud = $this->data['shp100_solicitud']['fecha_solicitud'];
        $rif_cedula = $this->data['shp100_solicitud']['rif_constribuyente'];
        $razon_social = $this->data['shp100_solicitud']['razon_social'];
        $numero_ficha_catastral = $this->data['shp100_solicitud']['numero_ficha_catastral'];
        if ($numero_ficha_catastral == null) {
            $numero_ficha_catastral = 0;
        }
        $capital = $this->Formato1($this->data['shp100_solicitud']['capital']);
        $desde = $this->data['shp100_solicitud']['desde'];
        $hasta = $this->data['shp100_solicitud']['hasta'];
        $tipo_establecimiento = $this->data['shp100_solicitud']['tipo_establecimiento'];
        $local = $this->data['shp100_solicitud']['local'];
        $nacionalidad_representante = $this->data['shp100_solicitud']['nacionalidad_representante'];
        $cedula_representante = $this->data['shp100_solicitud']['cedula_representante'];
        $apellidos_nombres = $this->data['shp100_solicitud']['apellidos_nombres'];
        $cod_pais = $this->data['shp100_solicitud']['cod_pais'];
        $cod_estados = $this->data['shp100_solicitud']['cod_estados'];
        $cod_municipios = $this->data['shp100_solicitud']['cod_municipios'];
        $cod_parroquias = $this->data['shp100_solicitud']['cod_parroquias'];
        $cod_centros = $this->data['shp100_solicitud']['cod_centros'];
        $cod_calles = $this->data['shp100_solicitud']['cod_calles'];
        $cod_veredas = $this->data['shp100_solicitud']['cod_veredas'];
        $numero_local_repre = $this->data['shp100_solicitud']['numero_local_repre'];
        $telefono_fijo = $this->data['shp100_solicitud']['telefono_fijo'];
        if ($telefono_fijo == null) {
            $telefono_fijo = 0;
        }
        $telefono_celular = $this->data['shp100_solicitud']['telefono_celular'];
        if ($telefono_celular == null) {
            $telefono_celular = 0;
        }
        $correo_electronico = $this->data['shp100_solicitud']['correo_electronico'];
        if ($correo_electronico == null) {
            $correo_electronico = 0;
        }
        $numero_emple = empty($this->data['shp100_solicitud']['numero_emple']) ? '0' : $this->data['shp100_solicitud']['numero_emple'];
        $numero_obre = empty($this->data['shp100_solicitud']['numero_obre']) ? '0' : $this->data['shp100_solicitud']['numero_obre'];

        $dist_bar = empty($this->data['shp100_solicitud']['dist_bar']) ? '0' : $this->Formato_3_in($this->data['shp100_solicitud']['dist_bar']);
        $dist_funeraria = empty($this->data['shp100_solicitud']['dist_funeraria']) ? '0' : $this->Formato_3_in($this->data['shp100_solicitud']['dist_funeraria']);
        $dist_hosp = empty($this->data['shp100_solicitud']['dist_hosp']) ? '0' : $this->Formato_3_in($this->data['shp100_solicitud']['dist_hosp']);
        $dist_estacion = empty($this->data['shp100_solicitud']['dist_estacion']) ? '0' : $this->Formato_3_in($this->data['shp100_solicitud']['dist_estacion']);
        $dist_insti = empty($this->data['shp100_solicitud']['dist_insti']) ? '0' : $this->Formato_3_in($this->data['shp100_solicitud']['dist_insti']);
        $dist_organismo = empty($this->data['shp100_solicitud']['dist_organismo']) ? '0' : $this->Formato_3_in($this->data['shp100_solicitud']['dist_organismo']);

        $inicio_constitucion = $this->data['shp100_solicitud']['inicio_constitucion'];
        $cierre_constitucion = $this->data['shp100_solicitud']['cierre_constitucion'];
        $inicio_ejercicio = $this->data['shp100_solicitud']['inicio_ejercicio'];
        $cierre_ejercicio = $this->data['shp100_solicitud']['cierre_ejercicio'];
        $registro_mercantil = $this->data['shp100_solicitud']['registro_mercantil'];
        $sucursal = $this->data['shp100_solicitud']['sucursal'];
        $fabricante = $this->data['shp100_solicitud']['fabricante'];
        $categoria_comercial = $this->data['shp100_solicitud']['categoria_comercial'];
        $mercado = $this->data['shp100_solicitud']['mercado'];
        $c1 = $this->data['shp100_solicitud']['c1'];
        $c2 = $this->data['shp100_solicitud']['c2'];
        $c3 = $this->data['shp100_solicitud']['c3'];
        $c4 = $this->data['shp100_solicitud']['c4'];
        $c5 = $this->data['shp100_solicitud']['c5'];
        $c6 = $this->data['shp100_solicitud']['c6'];
        $c7 = $this->data['shp100_solicitud']['c7'];
        $c8 = $this->data['shp100_solicitud']['c8'];
        $c9 = $this->data['shp100_solicitud']['c9'];
        $c10 = $this->data['shp100_solicitud']['c10'];
        $c11 = $this->data['shp100_solicitud']['c11'];
        $c12 = $this->data['shp100_solicitud']['c12'];
        //$    = $this->data['shp100_solicitud'][''];
        //$    = $this->data['shp100_solicitud'][''];
        $SQL_INSERT = "BEGIN; INSERT INTO shd100_solicitud (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud,fecha_solicitud,rif_cedula, numero_ficha_catastral,
	 	capital,horario_trab_desde, horario_trab_hasta, tipo_establecimiento, tipo_local, nacionalidad, cedula_identidad, nombres_apellidos, cod_pais, cod_estado, cod_municipio,
  		cod_parroquia, cod_centro, cod_vialidad, cod_vereda, numero_casa_local, telefonos_fijos, telefonos_celulares, correo_electronico, fecha_inicio_const,
  		fecha_cierre_const, fecha_inicio_econo, fecha_cierre_economico, registro_mercantil, tiene_sucursal, es_fabricante, numero_empleado, numero_obreros,
  		distancia_bar, distancia_hospital, distancia_educativo, distancia_funeraria, distancia_estacion, distancia_gubernam, tilde_reg_mercantil, tilde_fotoco_ci,
  		tilde_acta_const, tilde_uso_conforme, tilde_croquis, tilde_bomberos, tilde_rif, tilde_solvencia, tilde_concejo, tilde_recibo, tilde_planilla, tilde_permiso,numero_patente,categoria_comercial,mercado_cubre)";
        $SQL_INSERT .=" VALUES ($cod_presi,$cod_entidad,$cod_tipo_inst,$cod_inst,$cod_dep,'" . $numero_solicitud . "','" . $fecha_solicitud . "','" . $rif_cedula . "',$numero_ficha_catastral,
	 	$capital,'" . $desde . "','" . $hasta . "',$tipo_establecimiento,$local,$nacionalidad_representante,$cedula_representante,'" . $apellidos_nombres . "',$cod_pais,$cod_estados,$cod_municipios,
	 	$cod_parroquias,$cod_centros,$cod_calles,$cod_veredas,'" . $numero_local_repre . "','" . $telefono_fijo . "','" . $telefono_celular . "','" . $correo_electronico . "','" . $inicio_constitucion . "',
	 	'" . $cierre_constitucion . "','" . $inicio_ejercicio . "','" . $cierre_ejercicio . "','" . $registro_mercantil . "',$sucursal,$fabricante,$numero_emple,$numero_obre,
	 	$dist_bar,$dist_hosp,$dist_insti,$dist_funeraria,$dist_estacion,$dist_organismo,$c1,$c2,
	 	$c3,$c4,$c5,$c6,$c7,$c8,$c9,$c10,$c11,$c12,0,$categoria_comercial,$mercado)";
        $contar_solicitudes = $this->shd100_solicitud->findCount($this->SQLCA() . " and numero_solicitud='$numero_solicitud'");
        if ($contar_solicitudes == 0 || $contar_solicitudes == '0') {
            $sw = $this->shd100_solicitud->execute($SQL_INSERT);
            if ($sw > 1) {
                if (isset($_SESSION["CUENTA"]) && count($_SESSION["CUENTA"]) > 0) {
                    $cont = $_SESSION["CUENTA"];
                    for ($i = 1; $i <= $cont; $i++) {
                        if ($_SESSION["DATOS"][$i]["activa"] == 1) {
                            $cod_actividad = $_SESSION["DATOS"][$i]["cod_actividad"];
                            $sql = "INSERT INTO shd100_solicitud_actividades (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, cod_actividad)";
                            $sql.="VALUES ('" . $cod_presi . "', '" . $cod_entidad . "', '" . $cod_tipo_inst . "', '" . $cod_inst . "', '" . $cod_dep . "', '" . $numero_solicitud . "', '" . $cod_actividad . "');";
                            $sw1 = $this->shd100_solicitud_actividades->execute($sql);
                            if ($sw1 > 1) {
                                
                            } else {
                                break;
                            }
                        }//fin if
                    }//fin for
                } else {
                    $sw1 = 0;
                }

                if ($sw1 > 1) {
                    $this->shd100_solicitud_actividades->execute("COMMIT;");
                    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
                } else {
                    $this->shd100_solicitud_actividades->execute("ROLLBACK;");
                    $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - Actividades no agregadas');
                }
            } else {
                $this->shd100_solicitud->execute("ROLLBACK;");
                $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
            }
        } else {
            $this->set('errorMessage', "Disculpe, El número de solicitud: $numero_solicitud, ya se encuentra registrado");
        }
        $this->index();
        $this->render("index");
    }

    function verifica_numero_solicitud($numero_solicitud) {
        $this->layout = "ajax";
        $contar_solicitudes = $this->shd100_solicitud->findCount($this->SQLCA() . " and numero_solicitud='$numero_solicitud'");
        if ($contar_solicitudes != 0) {
            $this->set('existe', true);
        }
    }

//fin funcion verifica_numero_solicitud

    function consultar($pagina = null) {
        $this->layout = "ajax";
        if ($pagina != null) {
            $pagina = $pagina;
            $this->set('pagina', $pagina);
            $Tfilas = $this->v_shd100_solicitud->findCount($this->SQLCA());
            if ($Tfilas == 0) {
                $this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
                $this->index();
                $this->render("index");
            }
            if ($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datos = $this->v_shd100_solicitud->findAll($this->SQLCA(), null, 'numero_solicitud ASC', 1, $pagina, null);
                foreach ($datos as $row) {
                    $numero_solicitud = $row['v_shd100_solicitud']['numero_solicitud'];
                }
                $datos2 = $this->v_shd100_solicitud_actividades->findAll($this->SQLCA() . " and numero_solicitud='" . $numero_solicitud . "'", null, 'numero_solicitud ASC', null, $pagina, null);
                $this->set('datos', $datos);
                $this->set('datos2', $datos2);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
                $desde = array('01:00AM', '02:00AM', '03:00AM', '04:00AM', '05:00AM', '06:00AM', '07:00AM', '08:00AM', '09:00AM', '10:00AM', '11:00AM', '12:00AM', '01:00PM', '02:00PM', '03:00PM', '04:00PM', '05:00PM', '06:00PM', '07:00PM', '08:00PM', '09:00PM', '10:00PM', '11:00PM', '12:00PM');
                $this->set('desde', $desde);
                $hasta = array('01:00AM', '02:00AM', '03:00AM', '04:00AM', '05:00AM', '06:00AM', '07:00AM', '08:00AM', '09:00AM', '10:00AM', '11:00AM', '12:00AM', '01:00PM', '02:00PM', '03:00PM', '04:00PM', '05:00PM', '06:00PM', '07:00PM', '08:00PM', '09:00PM', '10:00PM', '11:00PM', '12:00PM');
                $this->set('hasta', $hasta);
            }
        } else {
            $pagina = 1;
            $this->set('pagina', $pagina);
            $Tfilas = $this->v_shd100_solicitud->findCount($this->SQLCA());
            if ($Tfilas == 0) {
                $this->set('errorMessage', 'NO SE ENCONTRARON DATOS');
                $this->index();
                $this->render("index");
            }
            if ($Tfilas != 0) {
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $datos = $this->v_shd100_solicitud->findAll($this->SQLCA(), null, 'numero_solicitud ASC', 1, $pagina, null);
            }
            foreach ($datos as $row) {
                $numero_solicitud = $row['v_shd100_solicitud']['numero_solicitud'];
            }
            $datos2 = $this->v_shd100_solicitud_actividades->findAll($this->SQLCA() . " and numero_solicitud='" . $numero_solicitud . "'", null, 'numero_solicitud ASC', null, $pagina, null);
            $this->set('datos2', $datos2);
            $this->set('datos', $datos);
            $this->set('siguiente', $pagina + 1);
            $this->set('anterior', $pagina - 1);
            $this->bt_nav($Tfilas, $pagina);
            $desde = array('01:00AM', '02:00AM', '03:00AM', '04:00AM', '05:00AM', '06:00AM', '07:00AM', '08:00AM', '09:00AM', '10:00AM', '11:00AM', '12:00AM', '01:00PM', '02:00PM', '03:00PM', '04:00PM', '05:00PM', '06:00PM', '07:00PM', '08:00PM', '09:00PM', '10:00PM', '11:00PM', '12:00PM');
            $this->set('desde', $desde);
            $hasta = array('01:00AM', '02:00AM', '03:00AM', '04:00AM', '05:00AM', '06:00AM', '07:00AM', '08:00AM', '09:00AM', '10:00AM', '11:00AM', '12:00AM', '01:00PM', '02:00PM', '03:00PM', '04:00PM', '05:00PM', '06:00PM', '07:00PM', '08:00PM', '09:00PM', '10:00PM', '11:00PM', '12:00PM');
            $this->set('hasta', $hasta);
        }
    }

//fin function consultar2

    function bt_nav($Tfilas, $pagina) {
        if ($Tfilas == 1) {
            $this->set('mostrarS', false);
            $this->set('mostrarA', false);
        } else if ($Tfilas == 2) {
            if ($pagina == 2) {
                $this->set('mostrarS', false);
                $this->set('mostrarA', true);
            } else {
                $this->set('mostrarS', true);
                $this->set('mostrarA', false);
            }
        } else if ($Tfilas >= 3) {
            if ($pagina == $Tfilas) {
                $this->set('mostrarS', false);
                $this->set('mostrarA', true);
            } else if ($pagina == 1) {
                $this->set('mostrarS', true);
                $this->set('mostrarA', false);
            } else {
                $this->set('mostrarS', true);
                $this->set('mostrarA', true);
            }
        }
    }

//fin navegacion\

    function modificar($numero_solicitud = null, $pagina = null) {
        $this->layout = "ajax";
        $datos = $this->v_shd100_solicitud->findAll($this->SQLCA() . " and numero_solicitud='" . $numero_solicitud . "'", null, 'numero_solicitud ASC', 1, null, null);
        $datos2 = $this->v_shd100_solicitud_actividades->findAll($this->SQLCA() . " and numero_solicitud='" . $numero_solicitud . "'", null, 'numero_solicitud ASC', null, null, null);
        $this->set('datos2', $datos2);
        $this->set('datos', $datos);
        $this->set('pagina', $pagina);
        $this->concatena_sin_cero($this->shd100_actividades->generateList($this->SQLCA() . ' and (minimo_tributable !=0.00)', "cod_actividad ASC", null, '{n}.shd100_actividades.cod_actividad', '{n}.shd100_actividades.denominacion_actividad'), "lista_actividades");
        foreach ($datos as $row) {
            $pais = $row['v_shd100_solicitud']['pais_repre'];
            $esta = $row['v_shd100_solicitud']['estado_repre'];
            $muni = $row['v_shd100_solicitud']['municipio_repre'];
            $parr = $row['v_shd100_solicitud']['parroquia_repre'];
            $cent = $row['v_shd100_solicitud']['centro_repre'];
            $vial = $row['v_shd100_solicitud']['vialidad_repre'];
            $vere = $row['v_shd100_solicitud']['vereda_repre'];
        }
        $this->Session->write('pais', $pais);
        $this->Session->write('esta', $esta);
        $this->Session->write('muni', $muni);
        $this->Session->write('parr', $parr);
        $this->Session->write('cent', $cent);
        $this->Session->write('call', $vial);
        $this->Session->write('vere', $vere);
        $listarepublica = $this->cugd01_republica->generateList(null, 'cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
        $this->concatena($listarepublica, 'cod_pais');
        $listaestado = $this->cugd01_estados->generateList('cod_republica=' . $pais, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
        $this->concatena($listaestado, 'cod_estado');
        $listamunicipio = $this->cugd01_municipios->generateList('cod_republica=' . $pais . ' and cod_estado=' . $esta, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
        $this->concatena($listamunicipio, 'cod_municipio');
        $listaparroquia = $this->cugd01_parroquias->generateList('cod_republica=' . $pais . ' and cod_estado=' . $esta . ' and cod_municipio=' . $muni, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
        $this->concatena($listaparroquia, 'cod_parroquia');
        $listacentro = $this->cugd01_centropoblados->generateList('cod_republica=' . $pais . ' and cod_estado=' . $esta . ' and cod_municipio=' . $muni . ' and cod_parroquia=' . $parr, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
        $this->concatena($listacentro, 'cod_centro');
        $listacalle = $this->cugd01_vialidad->generateList('cod_republica=' . $pais . ' and cod_estado=' . $esta . ' and cod_municipio=' . $muni . ' and cod_parroquia=' . $parr . ' and cod_centro=' . $cent, 'cod_vialidad ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
        if ($listacalle != null) {
            $this->concatena($listacalle, 'cod_calle');
        } else {
            $this->set('cod_calle', array());
        }
        $listavereda = $this->cugd01_vereda->generateList('cod_republica=' . $pais . ' and cod_estado=' . $esta . ' and cod_municipio=' . $muni . ' and cod_parroquia=' . $parr . ' and cod_centro=' . $cent . ' and cod_vialidad=' . $vere, 'cod_vereda ASC', null, '{n}.cugd01_vereda.cod_vereda', '{n}.cugd01_vereda.denominacion');
        if ($listavereda != null) {
            $this->concatena($listavereda, 'cod_vereda');
        } else {
            $this->set('cod_vereda', array('0' => '00'));
        }
        $desde = array('01:00AM', '02:00AM', '03:00AM', '04:00AM', '05:00AM', '06:00AM', '07:00AM', '08:00AM', '09:00AM', '10:00AM', '11:00AM', '12:00AM', '01:00PM', '02:00PM', '03:00PM', '04:00PM', '05:00PM', '06:00PM', '07:00PM', '08:00PM', '09:00PM', '10:00PM', '11:00PM', '12:00PM');
        $this->set('desde', $desde);
        $hasta = array('01:00AM', '02:00AM', '03:00AM', '04:00AM', '05:00AM', '06:00AM', '07:00AM', '08:00AM', '09:00AM', '10:00AM', '11:00AM', '12:00AM', '01:00PM', '02:00PM', '03:00PM', '04:00PM', '05:00PM', '06:00PM', '07:00PM', '08:00PM', '09:00PM', '10:00PM', '11:00PM', '12:00PM');
        $this->set('hasta', $hasta);
    }

    function guardar_modificar($numero_solicitud = null, $pagina = null) {//pr($this->data);
        $this->layout = "ajax";
        $fecha_solicitud = $this->data['shp100_solicitud']['fecha_solicitud'];
        $numero_ficha_catastral = $this->data['shp100_solicitud']['numero_ficha_catastral'];
        if ($numero_ficha_catastral == null) {
            $numero_ficha_catastral = 0;
        }
        $capital = $this->Formato1($this->data['shp100_solicitud']['capital']);
        $desde = $this->data['shp100_solicitud']['desde'];
        $hasta = $this->data['shp100_solicitud']['hasta'];
        $tipo_establecimiento = $this->data['shp100_solicitud']['tipo_establecimiento'];
        $local = $this->data['shp100_solicitud']['local'];
        $numero_casa_local = $this->data['shp100_solicitud']['numero_casa_local'];
        $nacionalidad_representante = $this->data['shp100_solicitud']['nacionalidad_representante'];
        $cedula_representante = $this->data['shp100_solicitud']['cedula_representante'];
        $apellidos_nombres = $this->data['shp100_solicitud']['apellidos_nombres'];
        $telefono_fijo = $this->data['shp100_solicitud']['telefono_fijo'];
        if ($telefono_fijo == null) {
            $telefono_fijo = 0;
        }
        $telefono_celular = $this->data['shp100_solicitud']['telefono_celular'];
        if ($telefono_celular == null) {
            $telefono_celular = 0;
        }
        $correo_electronico = $this->data['shp100_solicitud']['correo_electronico'];
        if ($correo_electronico == null) {
            $correo_electronico = 0;
        }
        $inicio_constitucion = $this->data['shp100_solicitud']['inicio_constitucion'];
        $cierre_constitucion = $this->data['shp100_solicitud']['cierre_constitucion'];
        $inicio_ejercicio = $this->data['shp100_solicitud']['inicio_ejercicio'];
        $cierre_ejercicio = $this->data['shp100_solicitud']['cierre_ejercicio'];
        $registro_mercantil = $this->data['shp100_solicitud']['registro_mercantil'];
        $sucursal = $this->data['shp100_solicitud']['sucursal'];
        $fabricante = $this->data['shp100_solicitud']['fabricante'];
        $categoria_comercial = $this->data['shp100_solicitud']['categoria_comercial'];
        $mercado = $this->data['shp100_solicitud']['mercado'];

        $numero_emple = empty($this->data['shp100_solicitud']['numero_emple']) ? '0' : $this->data['shp100_solicitud']['numero_emple'];
        $numero_obre = empty($this->data['shp100_solicitud']['numero_obre']) ? '0' : $this->data['shp100_solicitud']['numero_obre'];
        $dist_bar = empty($this->data['shp100_solicitud']['dist_bar']) ? '0' : $this->Formato_3_in($this->data['shp100_solicitud']['dist_bar']);
        $dist_funeraria = empty($this->data['shp100_solicitud']['dist_funeraria']) ? '0' : $this->Formato_3_in($this->data['shp100_solicitud']['dist_funeraria']);
        $dist_hosp = empty($this->data['shp100_solicitud']['dist_hosp']) ? '0' : $this->Formato_3_in($this->data['shp100_solicitud']['dist_hosp']);
        $dist_estacion = empty($this->data['shp100_solicitud']['dist_estacion']) ? '0' : $this->Formato_3_in($this->data['shp100_solicitud']['dist_estacion']);
        $dist_insti = empty($this->data['shp100_solicitud']['dist_insti']) ? '0' : $this->Formato_3_in($this->data['shp100_solicitud']['dist_insti']);
        $dist_organismo = empty($this->data['shp100_solicitud']['dist_organismo']) ? '0' : $this->Formato_3_in($this->data['shp100_solicitud']['dist_organismo']);

        $cod_pais = $this->data['shp100_solicitud']['cod_pais'];
        $cod_estados = $this->data['shp100_solicitud']['cod_estados'];
        $cod_municipios = $this->data['shp100_solicitud']['cod_municipios'];
        $cod_parroquias = $this->data['shp100_solicitud']['cod_parroquias'];
        $cod_centros = $this->data['shp100_solicitud']['cod_centros'];
        $cod_calles = $this->data['shp100_solicitud']['cod_calles'];
        $cod_veredas = $this->data['shp100_solicitud']['cod_veredas'];
        $c1 = $this->data['shp100_solicitud']['c1'];
        $c2 = $this->data['shp100_solicitud']['c2'];
        $c3 = $this->data['shp100_solicitud']['c3'];
        $c4 = $this->data['shp100_solicitud']['c4'];
        $c5 = $this->data['shp100_solicitud']['c5'];
        $c6 = $this->data['shp100_solicitud']['c6'];
        $c7 = $this->data['shp100_solicitud']['c7'];
        $c8 = $this->data['shp100_solicitud']['c8'];
        $c9 = $this->data['shp100_solicitud']['c9'];
        $c10 = $this->data['shp100_solicitud']['c10'];
        $c11 = $this->data['shp100_solicitud']['c11'];
        $c12 = $this->data['shp100_solicitud']['c12'];




        $guardar = "update shd100_solicitud set cod_pais=" . $cod_pais . ", cod_estado=" . $cod_estados . ", cod_municipio=" . $cod_municipios . ",cod_parroquia=" . $cod_parroquias . ", cod_centro=" . $cod_centros . ", cod_vialidad=" . $cod_calles . ", cod_vereda=" . $cod_veredas . ",
	numero_ficha_catastral=$numero_ficha_catastral,capital=$capital,horario_trab_desde=$desde, horario_trab_hasta=$hasta,numero_casa_local='" . $numero_casa_local . "',
	tilde_reg_mercantil=" . $c1 . ", tilde_fotoco_ci=" . $c2 . ",tilde_acta_const=" . $c3 . ", tilde_uso_conforme=" . $c4 . ", tilde_croquis=" . $c5 . ", tilde_bomberos=" . $c6 . ", tilde_rif=" . $c7 . ", tilde_solvencia=" . $c8 . ", tilde_concejo=" . $c9 . ", tilde_recibo=" . $c10 . ", tilde_planilla=" . $c11 . ", tilde_permiso=" . $c12 . ",
	tipo_establecimiento=$tipo_establecimiento, tipo_local=$local, nacionalidad=$nacionalidad_representante, cedula_identidad=$cedula_representante,
	nombres_apellidos='" . $apellidos_nombres . "', telefonos_fijos='" . $telefono_fijo . "', telefonos_celulares='" . $telefono_celular . "', correo_electronico='" . $correo_electronico . "',
	fecha_inicio_const='" . $inicio_constitucion . "',fecha_cierre_const='" . $cierre_constitucion . "', fecha_inicio_econo='" . $inicio_ejercicio . "', fecha_cierre_economico='" . $cierre_ejercicio . "',
	registro_mercantil='" . $registro_mercantil . "', tiene_sucursal=$sucursal, es_fabricante=$fabricante, numero_empleado=$numero_emple, numero_obreros=$numero_obre,fecha_solicitud='" . $fecha_solicitud . "',
  	distancia_bar=$dist_bar, distancia_hospital=$dist_hosp, distancia_educativo=$dist_insti, distancia_funeraria=$dist_funeraria, distancia_estacion=$dist_estacion, distancia_gubernam=$dist_organismo,categoria_comercial=$categoria_comercial,mercado_cubre=$mercado where numero_solicitud='" . $numero_solicitud . "' and " . $this->SQLCA();

        $sw = $this->shd001_registro_contribuyentes->execute($guardar);
        if ($sw > 1) {
            $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');
        } else {
            $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS - POR FAVOR INTENTE DE NUEVO');
        }
        $this->consultar($pagina);
        $this->render("consultar");
    }

    function eliminar($numero_solicitud = null, $pagina = null) {
        $this->layout = "ajax";
        $sql = "numero_solicitud='" . $numero_solicitud . "'";
        $sql1 = "DELETE  FROM  shd100_solicitud where " . $sql . " and " . $this->SQLCA();
        $sql2 = "DELETE  FROM  shd100_solicitud_actividades where " . $sql . " and " . $this->SQLCA();
        $this->shd100_solicitud->execute($sql1);
        $this->shd100_solicitud_actividades->execute($sql2);

        $y = $this->shd100_solicitud->findCount($this->SQLCA());
        if ($pagina > $y) {
            $pagina = $pagina - 1;
        }
        if ($y != 0) {
            $this->set('Message_existe', 'Registro Eliminado con exito.');
            $this->consultar($pagina); //si es el primero solamente
            $this->render("consultar");
        } else if ($y == 0) {
            $this->set('Message_existe', 'Registro Eliminado con exito.');
            $this->index();
            $this->render("index");
        }//fin if
    }

    function eliminar_g($var1 = null, $var = null) {
        $this->layout = "ajax";
        $sql = "numero_solicitud='" . $var1 . "' and cod_actividad='" . $var . "'";
        $sql1 = "DELETE  FROM  shd100_solicitud_actividades where " . $sql . " and " . $this->SQLCA();
        $this->shd100_solicitud_actividades->execute($sql1);
        $datos2 = $this->v_shd100_solicitud_actividades->findAll($this->SQLCA() . " and numero_solicitud='" . $var1 . "'", null, 'numero_solicitud ASC', null, null, null);
        $this->set('datos2', $datos2);
        $this->set('numero_solicitud', $var1);
    }

    function agregar_g($numero_solicitud = null) {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $cod_actividad = $this->data['shp100_solicitud']['cod_actividad'];
        //$cont=$this->shd10_solicitud_actividades->findCount();
        $sql = "INSERT INTO shd100_solicitud_actividades (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, numero_solicitud, cod_actividad)";
        $sql.="VALUES ('" . $cod_presi . "', '" . $cod_entidad . "', '" . $cod_tipo_inst . "', '" . $cod_inst . "', '" . $cod_dep . "', '" . $numero_solicitud . "', '" . $cod_actividad . "');";
        $sw1 = $this->shd100_solicitud_actividades->execute($sql);
        $datos2 = $this->v_shd100_solicitud_actividades->findAll($this->SQLCA() . " and numero_solicitud='" . $numero_solicitud . "'", null, 'numero_solicitud ASC', null, null, null);
        $this->set('datos2', $datos2);
        $this->set('numero_solicitud', $numero_solicitud);
        echo'<script>';
        echo" document.getElementById('activ_cod').value           = ''; ";
        echo" document.getElementById('activ_deno').value           = ''; ";
        echo" document.getElementById('actv_alicuota').value        = ''; ";
        echo" document.getElementById('minimo').value     = ''; ";
        echo'</script>';
    }

    function buscar_constribuyente($var1 = null) {
        $this->layout = "ajax";
        $this->set("opcion", $var1);
        $this->Session->delete('pista');
    }

//fin function

    function buscar_por_pista($var1 = null, $var2 = null, $var3 = null) {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        if ($var3 == null) {
            $var2 = strtoupper($var2);
            $this->Session->write('pista', $var2);
            //if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
            $Tfilas = $this->v_shd001_registro_contribuyentes->findCount("(" . $this->busca_separado(array("rif_cedula", "razon_social_nombres"), $var2) . ")
							                       	                            and rif_cedula NOT IN (select c.rif_cedula FROM shd100_solicitud c where  cod_presi      =  '" . $cod_presi . "'     and
																																					      cod_entidad    =  '" . $cod_entidad . "'   and
																																					      cod_tipo_inst  =  '" . $cod_tipo_inst . "' and
																																					      cod_inst       =  '" . $cod_inst . "'      and
																																					      cod_dep        =  '" . $cod_dep . "')  ");


            if ($Tfilas != 0) {
                $pagina = 1;
                $Tfilas = (int) ceil($Tfilas / 50);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->v_shd001_registro_contribuyentes->findAll("(" . $this->busca_separado(array("rif_cedula", "razon_social_nombres"), $var2) . ")
											                       	                            and rif_cedula NOT IN (select c.rif_cedula FROM shd100_solicitud c where  cod_presi      =  '" . $cod_presi . "'     and
																																									      cod_entidad    =  '" . $cod_entidad . "'   and
																																									      cod_tipo_inst  =  '" . $cod_tipo_inst . "' and
																																									      cod_inst       =  '" . $cod_inst . "'      and
																																									      cod_dep        =  '" . $cod_dep . "')  ", null, "rif_cedula ASC", 50, 1, null);
                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
        } else {
            $var22 = $this->Session->read('pista');
            $var22 = strtoupper($var22);
            //if(is_int($var22)){$sql   = " (codigo_prod_serv LIKE '%$var22%')  or   ";}else{ $sql = "";}
            $Tfilas = $this->v_shd001_registro_contribuyentes->findCount(" (" . $this->busca_separado(array("rif_cedula", "razon_social_nombres"), $var22) . ")
											                       	                            and rif_cedula NOT IN (select c.rif_cedula FROM shd100_solicitud c where  cod_presi      =  '" . $cod_presi . "'     and
																																									      cod_entidad    =  '" . $cod_entidad . "'   and
																																									      cod_tipo_inst  =  '" . $cod_tipo_inst . "' and
																																									      cod_inst       =  '" . $cod_inst . "'      and
																																									      cod_dep        =  '" . $cod_dep . "')  ");
            if ($Tfilas != 0) {
                $pagina = $var3;
                $Tfilas = (int) ceil($Tfilas / 50);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->v_shd001_registro_contribuyentes->findAll(" (" . $this->busca_separado(array("rif_cedula", "razon_social_nombres"), $var22) . ")
												                       	                            and rif_cedula NOT IN (select c.rif_cedula FROM shd100_solicitud c where  cod_presi      =  '" . $cod_presi . "'     and
																																										      cod_entidad    =  '" . $cod_entidad . "'   and
																																										      cod_tipo_inst  =  '" . $cod_tipo_inst . "' and
																																										      cod_inst       =  '" . $cod_inst . "'      and
																																										      cod_dep        =  '" . $cod_dep . "')  ", null, "rif_cedula ASC", 50, $pagina, null);
                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
        }//fin else
        $this->set("opcion", $var1);
    }

//fin function

    function buscar_constribuyente3($var1 = null) {
        $this->layout = "ajax";
        $this->set("opcion", $var1);
        $this->Session->delete('pista');
    }

//fin function

    function buscar_por_pista3($var1 = null, $var2 = null, $var3 = null) {
        $this->layout = "ajax";

        if ($var3 == null) {
            $var2 = strtoupper($var2);
            $this->Session->write('pista', $var2);
            //if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}
            $Tfilas = $this->v_shd001_registro_contribuyentes->findCount("personalidad_juridica=1 and ((rif_cedula LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%')))");
            if ($Tfilas != 0) {
                $pagina = 1;
                $Tfilas = (int) ceil($Tfilas / 50);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->v_shd001_registro_contribuyentes->findAll("personalidad_juridica=1 and ((rif_cedula LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%')))   ", null, "rif_cedula ASC", 50, 1, null);
                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
        } else {
          
            //if(is_int($var22)){$sql   = " (codigo_prod_serv LIKE '%$var22%')  or   ";}else{ $sql = "";}
            $Tfilas = $this->v_shd001_registro_contribuyentes->findCount("personalidad_juridica=1 and ((rif_cedula LIKE '%$var22%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var22%')))");
            if ($Tfilas != 0) {
                $pagina = $var3;
                $Tfilas = (int) ceil($Tfilas / 50);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->v_shd001_registro_contribuyentes->findAll("personalidad_juridica=1 and ((rif_cedula LIKE '%$var22%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var22%')))  ", null, "rif_cedula ASC", 50, $pagina, null);
                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
        }//fin else
        $this->set("opcion", $var1);
    }

//fin function

    function seleccion_busqueda_venta($var1 = null) {
        $this->layout = "ajax";
        $datos = $this->shd001_registro_contribuyentes->findAll("rif_cedula='" . $var1 . "'");
        if ($datos != null) {
            $cod_profesion = $datos[0]["shd001_registro_contribuyentes"]["profesion"];
            $cod_pais = $datos[0]["shd001_registro_contribuyentes"]["cod_pais"];
            $cod_estado = $datos[0]["shd001_registro_contribuyentes"]["cod_estado"];
            $cod_municipio = $datos[0]["shd001_registro_contribuyentes"]["cod_municipio"];
            $cod_parroquia = $datos[0]["shd001_registro_contribuyentes"]["cod_parroquia"];
            $cod_centro_poblado = $datos[0]["shd001_registro_contribuyentes"]["cod_centro_poblado"];
            $cod_calle_avenida = $datos[0]["shd001_registro_contribuyentes"]["cod_calle_avenida"];
            $cod_vereda_edificio = $datos[0]["shd001_registro_contribuyentes"]["cod_vereda_edificio"];
            $pais = $this->cugd01_republica->findAll('cod_republica=' . $cod_pais);
            $estados = $this->cugd01_estados->findAll('cod_republica=' . $cod_pais . ' and cod_estado=' . $cod_estado);
            $municipios = $this->cugd01_municipios->findAll('cod_republica=' . $cod_pais . ' and cod_estado=' . $cod_estado . ' and cod_municipio=' . $cod_municipio);
            $parroquias = $this->cugd01_parroquias->findAll('cod_republica=' . $cod_pais . ' and cod_estado=' . $cod_estado . ' and cod_municipio=' . $cod_municipio . ' and cod_parroquia=' . $cod_parroquia);
            $centros = $this->cugd01_centropoblados->findAll('cod_republica=' . $cod_pais . ' and cod_estado=' . $cod_estado . ' and cod_municipio=' . $cod_municipio . ' and cod_parroquia=' . $cod_parroquia . ' and cod_centro=' . $cod_centro_poblado);
            $vialidad = $this->cugd01_vialidad->findAll('cod_republica=' . $cod_pais . ' and cod_estado=' . $cod_estado . ' and cod_municipio=' . $cod_municipio . ' and cod_parroquia=' . $cod_parroquia . ' and cod_centro=' . $cod_centro_poblado . ' and cod_vialidad=' . $cod_calle_avenida);
            $vereda = $this->cugd01_vereda->findAll('cod_republica=' . $cod_pais . ' and cod_estado=' . $cod_estado . ' and cod_municipio=' . $cod_municipio . ' and cod_parroquia=' . $cod_parroquia . ' and cod_centro=' . $cod_centro_poblado . ' and cod_vialidad=' . $cod_calle_avenida . ' and cod_vereda=' . $cod_vereda_edificio);
            $profesiones = $this->cnmd06_profesiones->findAll('cod_profesion=' . $cod_profesion);
            $this->set('profesion', $profesiones);
            $this->set('pais', $pais);
            $this->set('estados', $estados);
            $this->set('municipios', $municipios);
            $this->set('parroquias', $parroquias);
            $this->set('centros', $centros);
            $this->set('vialidad', $vialidad);
            $this->set('vereda', $vereda);
            $this->set('datos', $datos);

            $da = $this->catd02_ficha_datos->findAll("cedula_rif='" . $var1 . "'" . " and " . $this->SQLCA());
            if ($da != null) {
                $ficha = $da[0]['catd02_ficha_datos']['cod_ficha'];
                echo'<script>';
                echo "document.getElementById('numero_ficha_catastral').readOnly=false;   ";
                echo'</script>';
            } else {
                $ficha = '';
                echo'<script>';
                echo "document.getElementById('numero_ficha_catastral').readOnly=true;   ";
                echo'</script>';
                $this->set('errorMessage', 'CONTRIBUYENTE NO ESTA REGISTRADO CATASTRALMENTE');
            }
            $this->set('datos', $datos);
            $resul = javascript_encode($datos[0]['shd001_registro_contribuyentes']['razon_social_nombres'], 1);
            echo'<script>';
            echo"document.getElementById('deno_rif').value = \"$resul\"; ";
            echo "document.getElementById('rif_constribuyente').value='" . $datos[0]['shd001_registro_contribuyentes']['rif_cedula'] . "';   ";
            echo "document.getElementById('numero_ficha_catastral').value='" . $ficha . "';   ";
            echo'</script>';
            /* echo "<script>";
              echo "document.getElementById('deno_rif').value='".$datos[0]['shd001_registro_contribuyentes']['razon_social_nombres']."';   ";
              echo "document.getElementById('rif_constribuyente').value='".$datos[0]['shd001_registro_contribuyentes']['rif_cedula']."';   ";
              echo "document.getElementById('numero_ficha_catastral').value='".$ficha."';   ";
              echo "</script>"; */
        } else {
            $vacio = '';
            echo "<script>";
            echo "document.getElementById('deno_rif').value='" . $vacio . "';   ";
            echo "document.getElementById('rif_constribuyente').value='" . $vacio . "';   ";
            echo "document.getElementById('numero_ficha_catastral').value='';   ";
            echo "</script>";
        }
    }

//fin function

    function buscar($var1 = null) {
        $this->layout = "ajax";
        $this->set("opcion", $var1);
        $this->Session->delete('pista');
    }

//fin function

    function ubicacion($rif_cedula = null) {
        $this->layout = "ajax";
        $datos = $this->v_shd001_registro_contribuyentes->findAll("rif_cedula='" . $rif_cedula . "'", null, 'rif_cedula ASC', 1, null, null);
        if ($datos != null) {
            $this->set('datos', $datos);
            foreach ($datos as $row) {
                $pais = $row['v_shd001_registro_contribuyentes']['cod_pais'];
                $esta = $row['v_shd001_registro_contribuyentes']['cod_estado'];
                $muni = $row['v_shd001_registro_contribuyentes']['cod_municipio'];
                $parr = $row['v_shd001_registro_contribuyentes']['cod_parroquia'];
                $cent = $row['v_shd001_registro_contribuyentes']['cod_centro_poblado'];
                $vial = $row['v_shd001_registro_contribuyentes']['cod_calle_avenida'];
                $vere = $row['v_shd001_registro_contribuyentes']['cod_vereda_edificio'];
                $fijo = $row['v_shd001_registro_contribuyentes']['telefonos_fijos'];
                $celu = $row['v_shd001_registro_contribuyentes']['telefonos_celulares'];
                $corr = $row['v_shd001_registro_contribuyentes']['correo_electronico'];
                $nombre = $row['v_shd001_registro_contribuyentes']['razon_social_nombres'];
            }
            if ($fijo == '0') {
                $fijo = '';
            }
            if ($celu == '0') {
                $celu = '';
            }
            if ($corr == '0') {
                $corr = '';
            }
            $this->Session->write('pais', $pais);
            $this->Session->write('esta', $esta);
            $this->Session->write('muni', $muni);
            $this->Session->write('parr', $parr);
            $this->Session->write('cent', $cent);
            $this->Session->write('call', $vial);
            $this->Session->write('vere', $vere);

            $listarepublica = $this->cugd01_republica->generateList(null, 'cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
            $this->concatena($listarepublica, 'cod_pais');
            $listaestado = $this->cugd01_estados->generateList('cod_republica=' . $pais, 'cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
            $this->concatena($listaestado, 'cod_estado');
            $listamunicipio = $this->cugd01_municipios->generateList('cod_republica=' . $pais . ' and cod_estado=' . $esta, 'cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
            $this->concatena($listamunicipio, 'cod_municipio');
            $listaparroquia = $this->cugd01_parroquias->generateList('cod_republica=' . $pais . ' and cod_estado=' . $esta . ' and cod_municipio=' . $muni, 'cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');
            $this->concatena($listaparroquia, 'cod_parroquia');
            $listacentro = $this->cugd01_centropoblados->generateList('cod_republica=' . $pais . ' and cod_estado=' . $esta . ' and cod_municipio=' . $muni . ' and cod_parroquia=' . $parr, 'cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion');
            $this->concatena($listacentro, 'cod_centro');
            $listacalle = $this->cugd01_vialidad->generateList('cod_republica=' . $pais . ' and cod_estado=' . $esta . ' and cod_municipio=' . $muni . ' and cod_parroquia=' . $parr . ' and cod_centro=' . $cent, 'cod_vialidad ASC', null, '{n}.cugd01_vialidad.cod_vialidad', '{n}.cugd01_vialidad.denominacion');
            if ($listacalle != null) {
                $this->concatena($listacalle, 'cod_calle');
            } else {
                $this->set('cod_calle', array());
            }
            $listavereda = $this->cugd01_vereda->generateList('cod_republica=' . $pais . ' and cod_estado=' . $esta . ' and cod_municipio=' . $muni . ' and cod_parroquia=' . $parr . ' and cod_centro=' . $cent . ' and cod_vialidad=' . $vere, 'cod_vereda ASC', null, '{n}.cugd01_vereda.cod_vereda', '{n}.cugd01_vereda.denominacion');
            if ($listavereda != null) {
                $this->concatena($listavereda, 'cod_vereda');
            } else {
                $this->set('cod_vereda', array('0' => '00'));
            }
            echo "<script>";
            echo "document.getElementById('cedula_representante').value='" . $rif_cedula . "';   ";
            echo "document.getElementById('telefonos_fijos').value='" . $fijo . "';   ";
            echo "document.getElementById('telefonos_celulares').value='" . $celu . "';   ";
            echo "document.getElementById('correo_electronico').value='" . $corr . "';   ";
            echo "document.getElementById('apellidos_nombres').value='" . $nombre . "';   ";
            echo "</script>";
        } else {
            $listarepublica = $this->cugd01_republica->generateList(null, 'cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
            $this->concatena($listarepublica, 'cod_pais');
            echo "<script>";
            echo "document.getElementById('cedula_representante').value='" . $rif_cedula . "';   ";
            echo "document.getElementById('telefonos_fijos').value='';   ";
            echo "document.getElementById('telefonos_celulares').value='';   ";
            echo "document.getElementById('correo_electronico').value='';   ";
            echo "document.getElementById('apellidos_nombres').value='';   ";
            echo "</script>";


            $this->data = null;
            $can_mun_def = $this->cugd90_municipio_defecto->findCount($this->SQLCA_S());

            if ($can_mun_def != 0) {

                $mun_defecto = $this->cugd90_municipio_defecto->findAll($this->SQLCA_S());

                $this->set("mun_defecto", $mun_defecto);
                $this->set("can_mun_def", $can_mun_def);

                $cod_republica = $mun_defecto[0]["cugd90_municipio_defecto"]["cod_republica"];
                $cod_estado = $mun_defecto[0]["cugd90_municipio_defecto"]["cod_estado"];
                $cod_municipio = $mun_defecto[0]["cugd90_municipio_defecto"]["cod_municipio"];

                $this->Session->write('pais', $cod_republica);
                $this->Session->write('esta', $cod_estado);
                $this->Session->write('muni', $cod_municipio);

                $lista_r = $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
                $lista_e = $this->cugd01_estados->generateList("cod_republica=" . $cod_republica, 'denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion');
                $lista_m = $this->cugd01_municipios->generateList("cod_republica=" . $cod_republica . " and cod_estado=" . $cod_estado, 'denominacion ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion');
                $lista_p = $this->cugd01_parroquias->generateList("cod_republica=" . $cod_republica . " and cod_estado=" . $cod_estado . " and cod_municipio=" . $cod_municipio, 'denominacion ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion');


                $this->concatena($lista_r, 'vector_r');
                $this->concatena($lista_e, 'vector_e');
                $this->concatena($lista_m, 'vector_m');
                $this->concatena($lista_p, 'vector_p');

                $deno_r = $this->cugd01_republica->findAll("cod_republica=" . $cod_republica);
                $deno_e = $this->cugd01_estados->findAll("cod_republica=" . $cod_republica . " and cod_estado=" . $cod_estado);
                $deno_m = $this->cugd01_municipios->findAll("cod_republica=" . $cod_republica . " and cod_estado=" . $cod_estado . " and cod_municipio=" . $cod_municipio);

                $this->set('deno_r', $deno_r[0]["cugd01_republica"]["denominacion"]);
                $this->set('deno_e', $deno_e[0]["cugd01_estados"]["denominacion"]);
                $this->set('deno_m', $deno_m[0]["cugd01_municipios"]["denominacion"]);

                $this->set('cod_r', $cod_republica);
                $this->set('cod_e', $cod_estado);
                $this->set('cod_m', $cod_municipio);


                $this->set('seleccion_pais', $cod_republica);
                $this->set('seleccion_esta', $cod_estado);
                $this->set('seleccion_muni', $cod_municipio);
            } else {
                $this->set('deno_r', "");
                $this->set('deno_e', "");
                $this->set('deno_m', "");

                $this->set('cod_r', "");
                $this->set('cod_e', "");
                $this->set('cod_m', "");

                $this->set('seleccion_pais', "");
                $this->set('seleccion_esta', "");
                $this->set('seleccion_muni', "");

                $listarepublica = $this->cugd01_republica->generateList(null, 'denominacion ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion');
                $this->concatena($listarepublica, 'vector_r');

                $this->set('vector_e', "");
                $this->set('vector_m', "");
                $this->set('vector_p', "");
            }//fin else
        }//fin else
    }

//fin function

    function buscar_por_pista2($var1 = null, $var2 = null, $var3 = null) {
        $this->layout = "ajax"; //echo 'si2';
        if ($var3 == null) {
            $var2 = strtoupper($var2);
            $this->Session->write('pista', $var2);
            $Tfilas = $this->v_shd100_solicitud->findCount($this->SQLCA() . " and (((rif_cedula LIKE '%$var2%') or (numero_solicitud LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%'))))");
            if ($Tfilas != 0) {
                $pagina = 1;
                $Tfilas = (int) ceil($Tfilas / 50);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->v_shd100_solicitud->findAll($this->SQLCA() . " and (((rif_cedula LIKE '%$var2%') or (numero_solicitud LIKE '%$var2%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var2%'))))", null, "rif_cedula ASC", 50, 1, null);
                $this->set("datosFILAS", $datos_filas); //pr($datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
        } else {//echo 'aa';
            $var22 = $this->Session->read('pista');
            $var22 = strtoupper($var22); //echo "((rif_cedula LIKE '%$var22%') or (numero_solicitud LIKE '%$var22%'))";
            $Tfilas = $this->v_shd100_solicitud->findCount($this->SQLCA() . " and (((rif_cedula LIKE '%$var22%') or (numero_solicitud LIKE '%$var22%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var22%'))))");
            if ($Tfilas != 0) {
                $pagina = $var3;
                $Tfilas = (int) ceil($Tfilas / 50);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                //$datos_filas=$this->v_shd100_solicitud->findAll("((rif_cedula LIKE '%$var22%') or (numero_solicitud LIKE '%$var22%'))",1,1,null);
                $datos_filas = $this->v_shd100_solicitud->findAll($this->SQLCA() . " and (((rif_cedula LIKE '%$var22%') or (numero_solicitud LIKE '%$var22%') or (quitar_acentos(razon_social_nombres) LIKE quitar_acentos('%$var22%'))))", null, "rif_cedula ASC", 50, $pagina, null);
                $this->set("datosFILAS", $datos_filas); //pr($datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
        }//fin else
        $this->set("opcion", $var1);
    }

//fin function

    function consulta2($rif_cedula = null, $numero_solicitud = null) {
        $this->layout = "ajax";

        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $modulo = $this->Session->read('Modulo');
        $c = "rif_cedula='$rif_cedula' and numero_solicitud='$numero_solicitud'";

        $veri = $this->v_shd100_solicitud->findCount($this->SQLCA() . " and " . $c);
        if ($veri != 0) {
            $datacpcp01 = $this->v_shd100_solicitud->findAll($this->SQLCA() . ' and ' . $c);
            $this->set('datos', $datacpcp01);
            $datos2 = $this->v_shd100_solicitud_actividades->findAll($this->SQLCA() . " and numero_solicitud='" . $numero_solicitud . "'", null, 'numero_solicitud ASC', null, null, null);
            $this->set('datos2', $datos2);
            $desde = array('01:00AM', '02:00AM', '03:00AM', '04:00AM', '05:00AM', '06:00AM', '07:00AM', '08:00AM', '09:00AM', '10:00AM', '11:00AM', '12:00AM', '01:00PM', '02:00PM', '03:00PM', '04:00PM', '05:00PM', '06:00PM', '07:00PM', '08:00PM', '09:00PM', '10:00PM', '11:00PM', '12:00PM');
            $this->set('desde', $desde);
            $hasta = array('01:00AM', '02:00AM', '03:00AM', '04:00AM', '05:00AM', '06:00AM', '07:00AM', '08:00AM', '09:00AM', '10:00AM', '11:00AM', '12:00AM', '01:00PM', '02:00PM', '03:00PM', '04:00PM', '05:00PM', '06:00PM', '07:00PM', '08:00PM', '09:00PM', '10:00PM', '11:00PM', '12:00PM');
            $this->set('hasta', $hasta);
        } else {
            $this->index();
            $this->render("index");
        }
    }

//fin function consultar2

    function buscar_actividadx($var1 = null) {
        $this->layout = "ajax";
        $this->set("opcion", $var1);
        $this->Session->delete('pista');
    }

//fin function

    function buscar_por_pistax($var1 = null, $var2 = null, $var3 = null) {
        $this->layout = "ajax";


        if ($var3 == null) {
            $var2 = strtoupper($var2);
            $this->Session->write('pista', $var2);
            //if(is_int($var2)){$sql   = " ((rif_cedula LIKE '%$var2%') or (razon_social LIKE '%$var2%'))  or   ";}else{ $sql = "";}

            $cod_presi = $this->Session->read('SScodpresi');
            $cod_entidad = $this->Session->read('SScodentidad');
            $cod_tipo_inst = $this->Session->read('SScodtipoinst');
            $cod_inst = $this->Session->read('SScodinst');
            $cod_dep = $this->Session->read('SScoddep');

            //$Tfilas = $this->shd100_actividades->findCount("minimo_tributable !=0.00 and cod_presi=" . $cod_presi . "and cod_entidad=" . $cod_entidad . "and cod_tipo_inst=" . $cod_tipo_inst . "and cod_inst=" . $cod_inst . "and cod_dep=" . $cod_dep . "and quitar_acentos(denominacion_actividad) LIKE '%".$var2."%' ");

            $Tfilas = $this->shd100_actividades->findCount("cod_presi=" . $cod_presi . "and cod_entidad=" . $cod_entidad . "and cod_tipo_inst=" . $cod_tipo_inst . "and cod_inst=" . $cod_inst . "and cod_dep=" . $cod_dep . " and quitar_acentos(denominacion_actividad) like '%" . $var2 . "%' and minimo_tributable !=0.00");


            if ($Tfilas != 0) {
                $pagina = 1;
                $Tfilas = (int) ceil($Tfilas / 50);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->shd100_actividades->findAll("cod_presi=" . $cod_presi . "and cod_entidad=" . $cod_entidad . "and cod_tipo_inst=" . $cod_tipo_inst . "and cod_inst=" . $cod_inst . "and cod_dep=" . $cod_dep . " and quitar_acentos(denominacion_actividad) like '%" . $var2 . "%' and minimo_tributable !=0.00", null, "denominacion_actividad ASC", 50, 1, null);
                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {

                $this->set("datosFILAS", '');
            }
        } else {


            //if(is_int($var22)){$sql   = " (codigo_prod_serv LIKE '%$var22%')  or   ";}else{ $sql = "";}
            $Tfilas = $this->shd100_actividades->findCount("cod_presi=" . $cod_presi . "and cod_entidad=" . $cod_entidad . "and cod_tipo_inst=" . $cod_tipo_inst . "and cod_inst=" . $cod_inst . "and cod_dep=" . $cod_dep . " and quitar_acentos(denominacion_actividad) like '%" . $var2 . "%' and minimo_tributable !=0.00");
            if ($Tfilas != 0) {
                $pagina = $var3;
                $Tfilas = (int) ceil($Tfilas / 50);
                $this->set('pag_cant', $pagina . '/' . $Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas = $this->shd100_actividades->findAll("cod_presi=" . $cod_presi . "and cod_entidad=" . $cod_entidad . "and cod_tipo_inst=" . $cod_tipo_inst . "and cod_inst=" . $cod_inst . "and cod_dep=" . $cod_dep . " and quitar_acentos(denominacion_actividad) like '%" . $var2 . "%' and minimo_tributable !=0.00", null, "denominacion_actividad ASC", 50, $pagina, null);
                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina + 1);
                $this->set('anterior', $pagina - 1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
        }//fin else
        $this->set("opcion", $var1);
    }

//fin function

    function funcion() {
        $this->layout = 'ajax';
    }

    function eliminar_grilla($var1 = null) {

        $this->layout = "ajax";
        $_SESSION["DATOS"][$var1]["activa"] = 0;
        $this->set("Message_existe", "EL REGISTRO FUE ELIMINADO");

        $cont = $_SESSION["CUENTA"];
        $marca = 0;

        for ($i = 1; $i <= $cont; $i++) {
            if ($_SESSION["DATOS"][$i]["activa"] == 1) {
                $marca++;
            }//fin if
        }//fin for

        echo'<script>';
        echo" document.getElementById('cuenta_grilla').value = '" . $marca . "'; ";
        echo'</script>';

        /* $mmens= 0;
          $msfre= 0;

          if(isset($_SESSION["DATOS"]) and $_SESSION["DATOS"]!=null){
          $to=0;
          foreach($_SESSION["DATOS"] as $sa){
          $activa= $sa['activa'];
          $total_aforo= $this->Formato1($sa['total_aforos']);
          if($activa==1){
          $to= $to + $total_aforo;
          }
          }

          $frecu = $this->Session->read('frecu');
          if($frecu==1){$fre=1;}
          if($frecu==2){$fre=2;}
          if($frecu==3){$fre=3;}
          if($frecu==4){$fre=6;}
          if($frecu==5){$fre=12;}

          $to=$this->Formato2($to);
          $to=$this->Formato1($to);
          $mmens= ($to/12);
          $mmens=$this->Formato2($mmens);
          $mmens=$this->Formato1($mmens);
          $msfre= $mmens * $fre;


          echo'<script>';
          echo" document.getElementById('monto_mensual').value          = '".$this->Formato2($mmens)."'; ";
          echo" document.getElementById('monto_segun_fre').value          = '".$this->Formato2($msfre)."'; ";
          echo'</script>';

          }
          $this->set("accion", $_SESSION["DATOS"]);
         */
        $this->render('funcion');
    }

//fin function
}