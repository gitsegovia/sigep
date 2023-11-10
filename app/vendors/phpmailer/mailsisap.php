<?php


class MailSisap
{

var $to                   = array();
var $remitente            = 'soportesisap@gmail.com';//from
var $nombre_remitente     = 'Error Sisap';
var $Asunto               = null;
var $Mensaje              = null;
var $Destinatario         = array();
var $DireccionRespuesta   = 'soportesisap@gmail.com';//from
var $ArchivosAdjuntos     = array();//archivos adjuntos
var $mensaje_retorno      = "Mensaje enviado correctamente";//mensaje de retorno




function sendgmail(){
	$host_img_mail = "http://".$_SERVER['HTTP_HOST']."/";//HTTP_HOST
	$mostrar = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'';
	require_once('phpmailer.php');
	require_once('smtp.php');
    $mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "ssl";
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 465;
	$mail->Username = "soportesisap@gmail.com";
	$mail->Password = "soporte.sisap";

	$mail->From = "soportesisap@gmail.com";
	$mail->FromName = "Soporte SISAP";
	$mail->Subject = "".$this->Asunto." ".$mostrar;
	$mail->MsgHTML('<div style="text-align:left; color:red;font-size:15px;">ERROR SISAP</div><br/>'.$this->Mensaje);
	if(count($this->ArchivosAdjuntos)>0){
         foreach($this->ArchivosAdjuntos as $adjunto){
         	$mail->AddAttachment(WWW_ROOT.$adjunto);
         }
	}

	if(count($this->Destinatario)>0){
         foreach($this->Destinatario as $destinatario){
         	$mail->AddAddress($destinatario['email'],$destinatario['nombre']);
         }
	}

	$mail->IsHTML(true);

	if(!$mail->Send()) {
	  return "Error: " . $mail->ErrorInfo;
	} else {
	  return $this->mensaje_retorno;
	}
}










function sendmail() {

    $host_img_mail = "http://".$_SERVER['HTTP_HOST']."/";//HTTP_HOST
    $destinatario = $this->NombreDestinatario." <".$this->Destinatario.">";
	$asunto = $this->Asunto;
	$cuerpo = '<html>
				<head><title>Gobernación Bolivariana del Estado Falcón</title></head>
				<body>
					<div style="text-align:left;"><img src="'.$host_img_mail.'img/mail/top_mail.jpg" align="center"/></div>
					<p>
						'.$this->Mensaje.'
					</p>' .
							'<p>--</p>
				</body>
				</html>';

	//para el envío en formato HTML
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

	//dirección del remitente
	$headers .= "From: ".$this->nombre_remitente." <".$this->remitente.">\r\n";

	//dirección de respuesta, si queremos que sea distinta que la del remitente
	$headers .= "Reply-To: ".$this->DireccionRespuesta."\r\n";

	//ruta del mensaje desde origen a destino
	$headers .= "Return-path: ".$this->remitente."\r\n";

	//direcciones que recibián copia
	$headers .= "Cc: \r\n";

	//direcciones que recibirán copia oculta
	$headers .= "Bcc: \r\n";

	if (mail($destinatario,$asunto,$cuerpo,$headers))
	{
		return 'Correo Electronico Enviado Exitosamente';
	}
	else
	{
		return 'falló al enviar el Correo Electronico';
	}


}


	function getRealIp()
	{
	    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
	    {
	      $ip=$_SERVER['HTTP_CLIENT_IP']. " [1]";
	    }
	    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
	    {
	      $ip=$_SERVER['HTTP_X_FORWARDED_FOR']. " [2]";
	    }
	    else
	    {
	      $ip=$_SERVER['REMOTE_ADDR']. " [3]";
	    }
	    return $ip;
	}


	function obtener_ip () {
		$devolver = "";
		if($_SERVER["HTTP_X_FORWARDED_FOR"])
		{
			if($pos=strpos($_SERVER["HTTP_X_FORWARDED_FOR"]," "))
			{
				$devolver .="IP local: ".substr($_SERVER["HTTP_X_FORWARDED_FOR"],0,$pos)." - IP Pública: ".substr($_SERVER["HTTP_X_FORWARDED_FOR"],$pos+1);
				$hostlocal=substr($_SERVER["HTTP_X_FORWARDED_FOR"],$pos+1);
			}else{
				$devolver .="IP Publica: ".$_SERVER["HTTP_X_FORWARDED_FOR"];
				$hostlocal=$_SERVER["HTTP_X_FORWARDED_FOR"];
			}
			if($_SERVER["REMOTE_ADDR"])
				$devolver .=" - Proxy: ".$_SERVER["REMOTE_ADDR"];
		}else{
			$devolver .="IP Pública: ".$_SERVER["REMOTE_ADDR"];
			$hostlocal=$_SERVER["REMOTE_ADDR"];
			if($hostlocal!=$_SERVER["REMOTE_ADDR"])
				$devolver .=" - Hostname: ".$hostlocal;
		}
		$hostname=gethostbyaddr($hostlocal);
		if($hostlocal!=$hostname)
			$devolver .="<br>Hostname: ".$hostname;


       return $devolver;

	}//fin funcion obtener_ip


}
?>