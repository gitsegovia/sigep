<?php
/*
 * Fecha: 16/03/2023
 *
 * Por Jose G. Segovia R.
 *
 * sisap
 */
class Cmcp00CierreTrimestreController extends AppController
{
    public $name = 'cmcp00_cierre_trimestre';
    public $uses = array('cugd02_dependencia','arrd05','cugd05_restriccion_clave','ccfd03_instalacion', 'ccfd04_cierre_mes', 'cmcd00_cierre_trimestre');
    public $helpers = array('Html','Ajax','Javascript', 'Sisap');

    public function checkSession()
    {
        if (!$this->Session->check('Usuario')) {
            $this->redirect('/salir/');
            exit();
        } else {
            //$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
            //echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
            $this->requestAction('/usuarios/actualizar_user');
        }
    }//fin checksession

    public function beforeFilter()
    {
        $this->checkSession();
    }

    public function AddCero($nomVar, $vector=object, $extra=null)
    {
        if ($vector!=null) {
            if ($extra==null) {
                foreach ($vector as $x) {
                    if ($x<10) {
                        $Var[$x]="0".$x;
                    } else {
                        $Var[$x]=$x;
                    }
                }//fin each
            } else {
                foreach ($vector as $x) {
                    if ($x<10) {
                        $Var[$x]=$extra.".0".$x;
                    } else {
                        $Var[$x]=$extra.".".$x;
                    }
                }//fin each
            }
            $this->set($nomVar, $Var);
        } else {
            $this->set($nomVar, '');
        }
    }//fin AddCero


    public function verifica_SS($i)
    {
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
    }//fin verifica_SS

    public function SQLCA($ano=null)//sql para busqueda de codigos de arranque con y sin año
    {
        $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
        $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
        $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
        $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
        if ($ano!=null) {
            $sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
        } else {
            $sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
        }
        return $sql_re;
    }//fin funcion SQLCA

    public function zero($x=null)
    {
        if ($x != null) {
            if ($x<10) {
                $x="0".$x;
            } elseif ($x>=10 && $x<=99) {
                $x=$x;
            }
        }
        return $x;
    }//fin zero


    public function concatena($vector1=null, $nomVar=null)
    {
        if ($vector1 != null) {
            foreach ($vector1 as $x => $y) {
                $cod[$x] = $this->zero($x).' - '.$y;
            }
            //print_r($cod);

            $this->set($nomVar, $cod);
        }
    }//fin concatena

     public function index()
     {   
        $this->layout = "ajax";
		$ano_ac = $this->ano_ejecucion();
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
        $lista = $this->cugd02_dependencia->generateList("cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst'", $order = 'cod_dependencia', $limit = null, '{n}.cugd02_dependencia.cod_dependencia', '{n}.cugd02_dependencia.denominacion');
        $this->concatena($lista, 'tipo');
        $datos=$this->cmcd00_cierre_trimestre->FindAll($this->condicionNDEP()." and ano=".$this->ano_ejecucion(),null,' ORDER BY cod_dep ASC');

        $this->set('datos',$datos);
        $datos1=$this->arrd05->FindAll($this->condicionNDEP(),null,' ORDER BY cod_dep ASC');
        $this->set('datos1',$datos1);
        $this->set('enable', 'disabled');
        $this->set('ano',$this->ano_ejecucion());
        $trimestre= array('1'=>'Primer Trimestre','2'=>'Segundo Trimestre','3'=>'Tercer Trimestre','4'=>'Cuarto Trimestre');
        $this->concatena($trimestre, 'trimestre');
     }

     function select_tipo($opcion=null,$var = null){
        $this->layout ="ajax";
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');

            switch($opcion){
                case 'codigo':
                    if($var!='agregar'){
                        $this->set('codigo',$var);
                    }else{
                        $this->set('codigo',false);
                    }
                break;
                case 'deno':
                    if($var!='agregar'){
                        $deno = $this->cugd02_dependencia->field('denominacion',"cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$var'", $order ="cod_dependencia ASC");
                        $this->set('deno', $deno);
                    }else{
                        $this->set('deno', false);
                    }
                break;
            }//fin switch

     }//fin select_tipo

    function guardar()
     {
         $this->layout ="ajax";
         $cod_presi = $this->Session->read('SScodpresi');
         $cod_entidad = $this->Session->read('SScodentidad');
         $cod_tipo_inst = $this->Session->read('SScodtipoinst');
         $cod_inst = $this->Session->read('SScodinst');
         //     pr($this->data);
         if ($this->data['cmcp00_cierre']['select']!='') {
             $cod_dep = $this->data['cmcp00_cierre']['select'];
             $ano = $this->data['cmcp00_cierre']['ano'];
             $trimestre_solicitud = $this->data['cmcp00_cierre']['trimestre_solicitud'];
             $verifica=$this->cmcd00_cierre_trimestre->FindAll($this->condicionNDEP()." and cod_dep=".$cod_dep." and ano=".$this->ano_ejecucion());
             if ($verifica!=null) {
                 $this->set('mensajeError', 'este registro ya existe');
             } else {
                 $sql = "INSERT INTO cmcd00_cierre_trimestre VALUES('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '$cod_dep', '$ano', '$trimestre_solicitud', '0')";

                 $sw=$this->cmcd00_cierre_trimestre->execute($sql);
                 if ($sw > 1) {
                     $this->set('mensajeExiste', 'registro exitoso');
                 } else {
                     $this->set('mensajeError', 'los datos no pudieron ser registrados');
                 }
             }//fin verifica null
         } else {
             $this->set('mensajeError', 'Debe ingresar todos los datos');
         }
         /**/
         $datos=$this->cmcd00_cierre_trimestre->FindAll($this->condicionNDEP()." and ano=".$this->ano_ejecucion(),null,' ORDER BY cod_dep ASC');
         $this->set('datos', $datos);
         $this->set('datos1', $this->arrd05->FindAll($this->condicionNDEP(), null, ' ORDER BY cod_dep ASC'));
         echo "<script>";
         echo "document.getElementById('agregar').disabled=false;";
         echo "</script>";
     }//fin guardar


    public function modificar($nomi=null, $i=null)
    {
        $this->layout="ajax";
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');

        $this->set('tipo', $nomi);
        $this->set('k', $i);
        $deno2=$this->cmcd00_cierre_trimestre->execute("select ano,trimestre from cmcd00_cierre_trimestre where ".$this->condicionNDEP()." and cod_dep=".$nomi." and ano=".$this->ano_ejecucion());
        $this->set('deno2', $deno2);
        $deno = $this->cugd02_dependencia->field('denominacion', "cod_tipo_institucion='$cod_tipo_inst' and cod_institucion='$cod_inst' and cod_dependencia='$nomi'", $order ="cod_dependencia ASC");
        $this->set('deno', $deno);

        $datos=$this->cmcd00_cierre_trimestre->FindAll($this->condicionNDEP()." and cod_dep='$nomi' and ano=".$this->ano_ejecucion(), null, ' ORDER BY cod_dep ASC');
        $this->set('datos', $datos);

        $trimestre= array('1'=>'Primer Trimestre','2'=>'Segundo Trimestre','3'=>'Tercer Trimestre','4'=>'Cuarto Trimestre');
        $cierre= array('0'=>'Activo','1'=>'Cerrado');
        $this->concatena($trimestre, 'trimestre');
        $this->concatena($cierre, 'cierre');
    }// fin mostrar

    function guardar_modificar($var=null, $i=null)
    {
        $this->layout="ajax";

        if ($this->data["cmcp00_cierre"]["trimestre_solicitud".$i]!='') {
            $sw=$this->cmcd00_cierre_trimestre->execute("update cmcd00_cierre_trimestre set trimestre=".$this->data["cmcp00_cierre"]["trimestre_solicitud".$i].", estatus=".$this->data["cmcp00_cierre"]["estatus".$i]." where ".$this->condicionNDEP()." and cod_dep=".$var." and ano=".$this->ano_ejecucion());
            if ($sw>1) {
                $this->set('mensajeExiste', 'se ha modificado exitosamente');
            } else {
                $this->set('mensajeError', 'No se pudo modificar,intente nuevamente');
            }
        } else {
            $this->set('mensajeError', 'ingrese la denominacion');
        }
        $datos=$this->cmcd00_cierre_trimestre->FindAll($this->condicionNDEP()." and ano=".$this->ano_ejecucion(), null, ' ORDER BY cod_dep ASC');
        $this->set('datos', $datos);
        $datos1=$this->arrd05->FindAll($this->condicionNDEP(), null, ' ORDER BY cod_dep ASC');
        $this->set('datos1', $datos1);
    }//fin guardar_modificar


    function cancelar()
    {
        $this->layout="ajax";

        $datos=$this->cmcd00_cierre_trimestre->FindAll($this->condicionNDEP()." and ano=".$this->ano_ejecucion(), null, ' ORDER BY cod_dep ASC');
        $this->set('datos', $datos);
        $datos1=$this->arrd05->FindAll($this->condicionNDEP(), null, ' ORDER BY cod_dep ASC');
        $this->set('datos1', $datos1);
    }//fin cancelar

}//fin de la clase

/*

     

    

    


     public function eliminar($cod_nivel_i = null)
     {
         $this->layout ="ajax";

         if ($cod_nivel_i != null) {
             $sql = "DELETE FROM ccfd04_cierre_mes WHERE ".$this->condicionNDEP()." and cod_dep = ".$cod_nivel_i." and ano_cierre_mensual=".$this->ano_ejecucion();
             $this->ccfd03_instalacion->execute($sql);
         }
     }//fin eliminar
*/
