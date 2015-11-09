<?
	/**
	 * 	@author IbericaSoft 2015
	 * 	Clase especializada en la persistencia y control de una tienda. Esta clase se debe personalizar si fuera necesario
	 *  para cada tienda. Esta diseñada para ser en la medida de lo posible lo mas estandar posible.
	 *  Aqui tendriamos que añadir las clases nuevas o las personalizaciones ***Custom.class.php de cada tienda
	 */
	
	require_once ( OS_ROOT . '/kernel/negocio/classes/ClientAgent.class.php' );
	
	class Tienda extends Tienda_Instanciador {
		public $email = null;//es una clase para envio de emails
		
		/** Atributos para persistir los valores comunes de cualquier tienda relativos a seguridad, email, ... */		
		public $email_mode=null;
		public $email_host=null;
		public $email_password=null;
		public $email_user=null;
		public $email_contacto=null;
		public $email_auditoria=null;
		
		/** Incluir aqui atributos especificos de esta tienda */
		public $profesion		  = 'Calderas'; //esta web funciona para esta profesion !!!
		public $captcha_result    = null;
		public $captcha_string    = null;
		public $captcha_image     = null;		
		public $estatus_web		  = null;				
		public $listadoServicios  = null;
		public $listadoUbicaciones = null;
		public $listadoMarcas	= null;
		public $listadoSlogan	= null;
		public $listSlides		  = null;
		public $listadoOpiniones   = null;
		public $servicio_default  = null;
		public $ubicacion_default = null;
		public $marca_default = null;
		public $slogan_default = null;
		public $articulo_default = null;
		public $noticia_default = null;		
		public $telefono		  = null;		
		public $email_empresa	  = null;
		public $titulo_web		  = null;
		public $servicio_web  = null;
		public $ubicacion_web = null;		
		
		/** El construct es invocado por index.html */
		public function __construct(){
			$this->estatus_web 			= Utils_OS::getValueAPP(Navigator::$connection, 'ESTATUS_WEB');
			$this->ubicacion_web 		= $this->utf8( Utils_OS::getValueAPP(Navigator::$connection, 'UBICACION_DEFAULT') );
			$this->servicio_web  		= $this->utf8( Utils_OS::getValueAPP(Navigator::$connection, 'SERVICIO_DEFAULT') );
			$this->telefono 			= Utils_OS::getValueAPP(Navigator::$connection, 'TELEFONO_WEB');
			$this->email_empresa 		= Utils_OS::getValueAPP(Navigator::$connection, 'EMAIL_WEB');
			$this->titulo_web 			= $this->utf8( Utils_OS::getValueAPP(Navigator::$connection, 'TITULO_WEB') );
			
			//Datos de configuracion email
			$email_configuracion = explode(',',Utils_OS::getValueAPP(Navigator::$connection, 'EMAIL_CONFIGURACION'));
			$this->email_mode     = $email_configuracion[0];
			$this->email_host	  = $email_configuracion[1];
			$this->email_user     = $email_configuracion[2];
			$this->email_password = $email_configuracion[3];
			$this->email_contacto = explode(',', Utils_OS::getValueAPP(Navigator::$connection, 'EMAIL_CONTACTO'));
					
			//cargar los html de emails
			$this->email = new Email($this->email_mode,$this->email_host,$this->email_user,$this->email_password,$this->email_user,null);
			//$this->html_email_contacto = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/frontoffice/esp/email_contacto.html');
			//$this->html_email_auditoria = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/frontoffice/esp/email_auditoria.html');
		}
		
		/**
		 * Genera una imagen de una operacion aritmetica simple para usarla como captcha
		 */
		public function regenerarCaptcha(){
			$op1 = rand(5,10);
			$op2 = rand(1,5);
			$signo = str_shuffle("+-");
			$this->captcha_string = "Cuanto es $op1$signo[0]$op2=";
			if ( $signo[0]=="-")
				$this->captcha_result = $op1-$op2;
			else
				$this->captcha_result = $op1+$op2;
			parent::$log->debug("Captcha: $this->captcha_string $this->captcha_result");
			
			ob_start();//para capturar la salida del buffer a una variable... si no, tenia que ser a fichero y luego leer, borrar, ....
			$im = imagecreate(150, 30);//tama�o
			$bg = imagecolorallocate($im, 255, 255, 255);//fondo blanco
			$textcolor = imagecolorallocate($im, 0, rand(0,255), rand(0,255));//color aleatorio
			// Write the string at the top left
			imagestring($im, 5, 10, 7, $this->captcha_string, $textcolor);
			//imagestring($im, 5, 10, 7, "* * * * * *", imagecolorallocate($im, 0, 255));
			imagepng($im);
			imagedestroy($im);
			$this->captcha_image = 'data:image/png;base64,'.base64_encode(ob_get_clean());//en base64 para pintarla en bruto
		}
		
		/** Para evitar algunos problemas con los caracteres latinos, en funcion del entorno, convertimos o no a utf8 si hace falta*/
		public function utf8($cadena){			
			if ( OS_MODE=='development' )
				return utf8_encode($cadena);
			else
				return $cadena;
		}
	}
?>
