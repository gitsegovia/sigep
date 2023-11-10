<?php


class Cnmp06ConsultarTrabajadorController extends AppController
{
    public $uses = array('cnmd06_profesiones', 'ccfd04_cierre_mes','cnmd06_datos_personales','cugd05_restriccion_clave');
    public $helpers = array('Html','Ajax','Javascript', 'Sisap');
    public $name = "cnmp06_consultar_trabajador";

    //cnmp06_profesiones2

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
    }//fin before filter


    public function SQLCA($ano=null) //sql para busqueda de codigos de arranque con y sin año
    {$sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
        $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
        $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
        $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
        if($ano!=null) {
            $sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
        } else {
            $sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
        }
        return $sql_re;
    }//fin funcion SQLCA


    public function index($var=null, $var_cont=null)
    {
        $this->layout = "ajax";
        $cod_presi                =       $this->Session->read('SScodpresi');
        $cod_entidad              =       $this->Session->read('SScodentidad');
        $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
        $cod_inst                 =       $this->Session->read('SScodinst');
        $cod_dep                  =       $this->Session->read('SScoddep');

    }//fin index

    public function guardar($var_n=null)
    {
			$this->layout = "ajax";
			if($var_n==null) {
					$cedula_identidad   =   $this->data['cnmp06_consultar_trabajador']['cedula'];
			} else {
					$cedula_identidad =  $var_n;
			}
			$sql_buscar = "SELECT denominacion_dependencia, denominacion_nomina, demonimacion_puesto, fecha_ingreso, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre FROM v_cnmd06_fichas WHERE condicion_actividad_ficha not in (5,6) and cedula_identidad=".$cedula_identidad;
			$datos = $this->cnmd06_profesiones->execute($sql_buscar);

			$this->set('datos', $datos);
			if(count($datos) == 0){
				$this->set('errorMessage', 'Trabajador sin ficha activa');
			}
    }//fin function


}//fin class
