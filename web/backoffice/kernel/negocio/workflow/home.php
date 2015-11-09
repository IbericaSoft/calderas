<?
/**
 * Metodo que se ejecuta siempre
 */
function _cache(Navigator $instance, Tienda $shop){
	//Cargamos los datos de las tablas seo_* pero solo se hará la primera vez que se invoque esta carga, 
	//después se tirará de valores cacheados. Tendrás que cerrar el navegador para volver a refrescar estos datos
	cargaListas($instance,$shop);
}

/** 
 * Este metodo se ejecuta cuando un usuario llega a la web sin especificar ningun servicio, es nuestra home.
 * 1. las listas de datos ya estan cargadas (cache)
 * 2. la home pinta el servicio y la ubicacion por defecto si la hay, si no, escoge aleatoriamente
 * 3. terminamos de tunear las cadenas de sercio con la ubicacion
 * 4. escogemos aleatoriamente los datos de marca,slogan,articulos(que ya se han cargado en cargaListas)
 * 5. finalmente, pedimos reemplazar del html las cadenas entre-llaves con los datos variables calculados aqui
 */
function home(Navigator $instance, Tienda $shop){
	if ( $shop->servicio_web==null || $shop->servicio_web=="" ){ //si no me han dicho que hay uno por defecto, seleccion aleatoria
		$shop->servicio_default = seleccionAleatoriaListasDeDatos($shop->listadoServicios);
	}else{ //pero si me han dicho que hay uno por defecto, lo busco y lo cargo... ojito porque tiene que existir si no, cataplas!
		$shop->servicio_web = seo_friendly_url($shop->servicio_web);
		Navigator::$log->debug("El servicio por defecto es: $shop->servicio_web");
		$shop->servicio_default = buscaDatoEnLista($shop->listadoServicios, 'seo_url', $shop->servicio_web);
	}
	
	//un poco de tunning para algunas cadenas
	$shop->servicio_default[seo_h1].=" en {ubicacion}";
	$shop->servicio_default[seo_title].=" en {ubicacion}";
	
	if ( $shop->ubicacion_web==null || $shop->ubicacion_web=="" ){ //si no me han dicho que hay una por defecto, seleccion aleatoria
		$shop->ubicacion_default = seleccionAleatoriaListasDeDatos($shop->listadoUbicaciones);
	}else{ //pero si me han dicho que hay una por defecto, la busco y la cargo... ojito porque tiene que existir si no, cataplas!
		$shop->ubicacion_web = seo_friendly_url($shop->ubicacion_web);
		Navigator::$log->debug("La ubicacion por defecto es: $shop->ubicacion_web");
		$shop->ubicacion_default= buscaDatoEnLista($shop->listadoUbicaciones, 'ubicacion', $shop->ubicacion_web);
	}
	
	//resto de datos aleatorios
	$shop->marca_default = seleccionAleatoriaListasDeDatos($shop->listadoMarcas);
	$shop->slogan_default = seleccionAleatoriaListasDeDatos($shop->listadoSlogan);
	//las noticias y los articulos hacen una seleccion aleatorio por defecto siempre
	
	//terminamos
	terminarPagina($instance,$shop);
}

/**
 * Este método se ejecuta para las páginas genericas que son: somos-diferentes, nuestros-clientes, nosotros, listado servicios, etc, vamos
 * las que nos son ni la home, ni servicio ni servicio-tecnico !!
 */
function paginasGenericos(Navigator $instance, Tienda $shop){
	//ubicacion 
	if ( $shop->ubicacion_web==null || $shop->ubicacion_web=="" ){ //si no me han dicho que hay una por defecto, seleccion aleatoria
		$shop->ubicacion_default = seleccionAleatoriaListasDeDatos($shop->listadoUbicaciones);
	}else{ //pero si me han dicho que hay una por defecto, la busco y la cargo... ojito porque tiene que existir si no, cataplas!
		$shop->ubicacion_web = seo_friendly_url($shop->ubicacion_web);
		Navigator::$log->debug("La ubicacion por defecto es: $shop->ubicacion_web");
		$shop->ubicacion_default= buscaDatoEnLista($shop->listadoUbicaciones, 'ubicacion', $shop->ubicacion_web);
	}
	switch ( $instance->getAction() ){
		case 'somos-diferentes':
			$shop->servicio_default[seo_title]=$shop->titulo_web;
			terminarPagina($instance,$shop);
			break;
		case 'nuestros-clientes':
			$shop->servicio_default[seo_title]=$shop->titulo_web;
			terminarPagina($instance,$shop);
			break;
		case 'nosotros':
			$shop->servicio_default[seo_title]=$shop->titulo_web;
			terminarPagina($instance,$shop);
			break;
		case 'nuestros-servicios':
			$shop->servicio_default[seo_title]=$shop->titulo_web;
			terminarPagina($instance,$shop);
			break;
		case 'contactar':
			$shop->servicio_default[seo_title]=$shop->titulo_web;
			//terminarPagina($instance,$shop);
			break;
	}
		
}

/**
 * Este metodo se ejecuta cuando se solicita un servicio.
 * 1. la listas de datos ya estan cacheadas (servicio, slogan, ubicaciones, etc.)
 * 2. busca el servicio y la ubicacion solicitados para comprobar que existan, si no, elige uno aleatorio
 * 3. terminamos de tunear las cadenas de sercio ubicacion etc, con arreglillos, como ajustar el mapa (que es muy grande en la bbdd), o añadir al titulo
 * de la pagina la ubicación
 * 4. finalmente, pedimos reemplazar en el html las cadenas encorchetadas con los datos variables calculados aqui.
 */
function servicio(Navigator $instance, Tienda $shop){
	//buscamos el servicio que nos dicen, por seguridad, si no lo encontramos, cargamos uno aleatorio
	$shop->servicio_default = buscaDatoEnLista($shop->listadoServicios,"seo_url",seo_friendly_url($_REQUEST[servicio]));	
	if ( $shop->servicio_default==null ){
		$shop->servicio_default = seleccionAleatoriaListasDeDatos($shop->listadoServicios);
	}
	//buscamos la ubicacion que nos dicen, por seguridad, si no lo encontramos, cargamos uno aleatorio
	$shop->ubicacion_default = buscaDatoEnLista($shop->listadoUbicaciones,"ubicacion",seo_friendly_url($_REQUEST[ubicacion]));
	if ( $shop->ubicacion_default==null ){
		$shop->ubicacion_default = seleccionAleatoriaListasDeDatos($shop->listadoUbicaciones);
	}
	//tuneado del servicio para ajustar el mapa a la pagina, añadir al titulo al ubicacion
	$shop->ubicacion_default[maps]=str_replace('width="300" height="300"', 'width="350" height="125"', $shop->ubicacion_default[maps]);
	$shop->servicio_default[seo_title].=" en {ubicacion}";
	
	//reemplazar valores encorchetados del html
	terminarPagina($instance,$shop);
}

/**
 * Este metodo se ejecuta cuando se solicita un servicio tecnico (fagor,roca,junkers,etc.)
 * 1. la listas de datos ya estan cacheadas (servicio, slogan, ubicaciones, etc.)
 * 2. busca el servicio-tecnico y la ubicacion solicitados para comprobar que existan, si no, elige uno aleatorio
 * 3. terminamos de tunear las cadenas de sercio ubicacion etc, con arreglillos, como ajustar el mapa (que es muy grande en la bbdd), o añadir al titulo
 * de la pagina la ubicación
 * 4. finalmente, pedimos reemplazar en el html las cadenas encorchetadas con los datos variables calculados aqui.
 */
function servicio_tecnico(Navigator $instance, Tienda $shop){
	//buscamos el servicio que nos dicen, por seguridad, si no lo encontramos, cargamos uno aleatorio
	$shop->marca_default = buscaDatoEnLista($shop->listadoMarcas,"marcamodelo",seo_friendly_url($_REQUEST[marca]));
	if ( $shop->marca_default==null ){
		$shop->marca_default = seleccionAleatoriaListasDeDatos($shop->listadoMarcas);
	}
	//buscamos la ubicacion que nos dicen, por seguridad, si no lo encontramos, cargamos uno aleatorio
	$shop->ubicacion_default = buscaDatoEnLista($shop->listadoUbicaciones,"ubicacion",seo_friendly_url($_REQUEST[ubicacion]));
	if ( $shop->ubicacion_default==null ){
		$shop->ubicacion_default = buscaDatoEnLista($shop->listadoUbicaciones,"grupo",seo_friendly_url($_REQUEST[ubicacion]));
		if ( $shop->ubicacion_default==null )
			$shop->ubicacion_default = seleccionAleatoriaListasDeDatos($shop->listadoUbicaciones);
	}
	//tuneado del servicio para ajustar el mapa a la pagina, añadir al titulo al ubicacion
	$shop->ubicacion_default[maps]=str_replace('width="300" height="300"', 'width="350" height="125"', $shop->ubicacion_default[maps]);
	//$shop->ubicacion_default[maps]=str_replace("/Ver mapa m.s grande/", '', $shop->ubicacion_default[maps]);
	$shop->marca_default[seo_title]="@{telefono} Servicio Técnico ".$shop->marca_default[marcamodelo]." en {ubicacion}";

	//reemplazar valores encorchetados del html
	terminarPagina($instance,$shop);
}


/** carga todos los servicios profesionales del tipo indicado en $shop->profesion */
function cargaTodosLosServiciosProfesionales(Navigator $instance, Tienda $shop){
	$sql   		= "SELECT * FROM seo_servicios WHERE status='ON' AND profesion='$shop->profesion' ORDER BY seo_h1;";
	$resultado 	= Navigator::getConnection()->query($sql);
	$datos = array();
	while ( $rows = Navigator::getConnection()->getColumnas($resultado) ){
		array_push($datos, array(
			"seo_h1"=>$shop->utf8($rows[seo_h1]),
			"seo_title"=>$shop->utf8($rows[seo_title]),
			"seo_url"=>seo_friendly_url($rows[seo_url]),
			"seo_meta"=>$shop->utf8($rows[seo_meta]),
			"seo_description"=>$shop->utf8($rows[seo_description]),
			"fotosytexto"=>$shop->utf8($rows[fotosytexto]),
			"combinable"=>$rows[combinable]
		));
	}
	$shop->listadoServicios = $datos;
}

/**
 * carga todas las ubicaciones 
 */
function cargaTodasLasUbicaciones(Navigator $instance, Tienda $shop){
	$sql   		= "SELECT * FROM seo_ubicaciones WHERE status='ON' ORDER BY ubicacion;";
	$resultado 	= Navigator::getConnection()->query($sql);
	$datos = array();
	while ( $rows = Navigator::getConnection()->getColumnas($resultado) ){
		array_push($datos, array(
			"tipo"=>$shop->utf8($rows[tipo]),
			"grupo"=>$shop->utf8($rows[grupo]),
			"ubicacion"=>$shop->utf8($rows[ubicacion]),
			"maps"=>$rows[maps],
			"combinable"=>$rows[combinable]
		));
	}
	$shop->listadoUbicaciones = $datos;
}

/**
 * carga todas las marcas-modelo
 */
function cargaTodasLasMarcasModelo(Navigator $instance, Tienda $shop){
	$sql   		= "SELECT * FROM seo_marcaymodelo WHERE profesion='$shop->profesion' AND status='ON' ORDER BY marcamodelo;";
	$resultado 	= Navigator::getConnection()->query($sql);
	$datos = array();
	while ( $rows = Navigator::getConnection()->getColumnas($resultado) ){
		array_push($datos, array(
			"marcamodelo"=>$shop->utf8($rows[marcamodelo]),
			"seo_slogan"=>$shop->utf8($rows[seo_slogan]),
			"seo_comercial"=>$shop->utf8($rows[seo_comercial]),
			"seo_meta"=>$shop->utf8($rows[seo_meta]),
			"fotoytexto"=>$shop->utf8($rows[fotoytexto])
		));
	}
	$shop->listadoMarcas = $datos;
}

/**
 * carga todos los slogan
 */
function cargaTodosLosSlogan(Navigator $instance, Tienda $shop){
	$sql   		= "SELECT * FROM seo_slogan WHERE profesion='$shop->profesion' AND status='ON';";
	$resultado 	= Navigator::getConnection()->query($sql);
	$datos = array();
	while ( $rows = Navigator::getConnection()->getColumnas($resultado) ){
		array_push($datos, array(
		"slogan"=>$shop->utf8($rows[slogan])
		));
	}
	$shop->listadoSlogan = $datos;
}

/** seleccion aleatoria de un articulo y una noticia */
function cargaTodasLasNoticiasArticulos(Navigator $instance, Tienda $shop){
	//articulo	
	$sql   		= "SELECT * FROM seo_articulos WHERE profesion='$shop->profesion' AND status='ON';";
	$resultado 	= Navigator::getConnection()->query($sql);
	$datos = array();
	while ( $rows = Navigator::getConnection()->getColumnas($resultado) ){
		array_push($datos, array(
		"titulo"=>$shop->utf8($rows[titulo]),
		"articulo"=>$shop->utf8($rows[articulo]),
		"foto"=>$shop->utf8($rows[foto]),
		"votos"=>$shop->utf8($rows[votos]),
		"media_votos"=>$shop->utf8($rows[media_votos]),
		"opiniones"=>$shop->utf8($rows[opiniones])
		));
	}
	$shop->articulo_default = seleccionAleatoriaListasDeDatos($datos);//de todas los articulos, nos quedamos con uno

	//noticia
	$sql   		= "SELECT * FROM seo_noticias WHERE profesion='$shop->profesion' AND status='ON';";
	$resultado 	= Navigator::getConnection()->query($sql);
	$datos = array();
	while ( $rows = Navigator::getConnection()->getColumnas($resultado) ){
		array_push($datos, array(
		"titulo"=>$shop->utf8($rows[titulo]),
		"noticia"=>$shop->utf8($rows[noticia]),
		"foto"=>$shop->utf8($rows[foto]),
		"votos"=>$shop->utf8($rows[votos]),
		"media_votos"=>$shop->utf8($rows[media_votos]),
		"opiniones"=>$shop->utf8($rows[opiniones])
		));
	}
	$shop->noticia_default = seleccionAleatoriaListasDeDatos($datos);
}

/**
 * carga todos las opiniones de clientes
 * */
function cargaOpiniones(Navigator $instance, Tienda $shop){
	$sql   		= "SELECT * FROM seo_opiniones WHERE status='ON' AND profesion='$shop->profesion' ORDER BY fecha desc;";//de mas reciente a mas viejas
	$resultado 	= Navigator::getConnection()->query($sql);
	$datos = array();
	while ( $rows = Navigator::getConnection()->getColumnas($resultado) ){
		array_push($datos, array(
		"cliente"=>$shop->utf8($rows[cliente]),
		"opinion"=>$shop->utf8($rows[opinion]),
		"valoracion"=>seo_friendly_url($rows[valoracion]),
		"repetiria"=>$shop->utf8($rows[repetiria])
		));
	}
	$shop->listadoOpiniones = $datos;
}

/** Carga los datos de todas las tablas seo* de la bbdd en sus variables *_default que usaremos luego aqui y en el html 
 *  Tambien tenemos la posibilidad de pasarle a este método un booleano para indicar que queremos que de cada lista, seleccione uno
 *  de maneara aleatoria */
function cargaListas(Navigator $instance, Tienda $shop){
	//if ( $shop->listadoServicios==null )
		cargaTodosLosServiciosProfesionales($instance,$shop);
	$shop->servicio_default = seleccionAleatoriaListasDeDatos($shop->listadoServicios);
	//if ( $shop->listadoUbicaciones==null )
		cargaTodasLasUbicaciones($instance,$shop);
	$shop->ubicacion_default = seleccionAleatoriaListasDeDatos($shop->listadoUbicaciones);
	//if ( $shop->listadoMarcas==null )
		cargaTodasLasMarcasModelo($instance,$shop);
	$shop->marca_default = seleccionAleatoriaListasDeDatos($shop->listadoMarcas);
	//if ( $shop->listadoSlogan==null )
		cargaTodosLosSlogan($instance,$shop);
	$shop->slogan_default = seleccionAleatoriaListasDeDatos($shop->listadoSlogan);
	//if ( $shop->listadoOpiniones==null )
		cargaOpiniones($instance,$shop);
	cargaTodasLasNoticiasArticulos($instance, $shop);
	
	//rating
	//media opiniones
	$positivos=0;
	$negativos=0;
	foreach ($shop->listadoOpiniones as $item){
		if ( $item[repetiria]=="SI" )
			$positivos++;
		else
			$negativos++;
	}
	$instance->addData("positivos", $positivos);
	$instance->addData("negativos", $negativos);
	$media = floor((($positivos*5)/count($shop->listadoOpiniones)));
	$instance->addData("media", $media);
	$instance->addData("total_opiniones", count($shop->listadoOpiniones));
}

/**
 * Reemplaza las cadenas que quedan en el html {+++} con sus valores variables. Este método lo debemos
 * llamar siempre antes de terminar. No es necesario que esten todos los valores.
 */
function terminarPagina(Navigator $instance, Tienda $shop){
	$shop->regenerarCaptcha();
	$instance->replaceAll("{host}", HOST);
	$instance->replaceAll("{title}", $shop->servicio_default[seo_title]);
	$instance->replaceAll("{titleservicio}", $shop->marca_default[seo_title]);
	$instance->replaceAll("{telefono}", $shop->telefono);
	$instance->replaceAll("{email}", $shop->email_empresa);
	$instance->replaceAll("{meta}", $shop->servicio_default[seo_meta]);
	$instance->replaceAll("{metaservicio}", $shop->marca_default[seo_meta]);
	$instance->replaceAll("{ubicacion}", $shop->ubicacion_default[ubicacion]);
	$instance->replaceAll("{ubicacion_url}", seo_friendly_url($shop->ubicacion_default[ubicacion]));
	$instance->replaceAll("{marca}", $shop->marca_default[marcamodelo]);
	$instance->replaceAll("{logo}", strtolower($shop->marca_default[marcamodelo]));
	$instance->replaceAll("{fecha}", date('Y-m-d',time()-(2*24*60*60)));//hoy menos dos dias
}

/** de un array de datos, selecciona uno aleatoriamente y lo devuelve */
function seleccionAleatoriaListasDeDatos($arrLista){
	$aleatorio = mt_rand(0,count($arrLista)-1);
	return $arrLista[$aleatorio];
}

/** busca un dato en una lista, si lo encuentra, devuelve el elemento entero de la lista */
function buscaDatoEnLista($lista, $key, $valor){
	foreach ($lista as $data){
		$cadena = seo_friendly_url($data[$key]);
		//Navigator::$log->debug("comparando: <$cadena y $valor>");
		if ( $cadena==$valor ){
			//Navigator::$log->debug("<$cadena y $valor> son iguales");
			return $data;
		}
	}
	return null;
}

/** Envio de un email de contacto */
function contactar(Navigator $instance, Tienda $shop){
	paginasGenericos($instance, $shop);
	Navigator::$log->debug("Me llega esto del formulario: ".$_POST[teloemail].'/'.$_POST[captcha]." -- y deberian contestarme con esto:".$shop->captcha_result);
	$captcha_valido = $shop->captcha_result;
	$shop->regenerarCaptcha();//importante, cada intento genera un nuevo captcha
	$valido = true;
	
	if ( seo_friendly_url($_POST[teloemail])=="" ){
		$instance->addData("resultado-contactar", "Tienes que indicarnos una forma de contacto, ya sea email o teléfono, si no,
				será imposible que te demos respuesta !!.
				 <a class=\"mellamais\" href=\"#mellamais\">Intentar otra vez</a>");
		$valido = false;
	}
	
	if ( $valido && seo_friendly_url($_POST[captcha])=="" ){
		$instance->addData("resultado-contactar", "Por favor, responde a la sencilla operación aritmética. Lo hacemos para evitar
				el uso fraudulento de nuestro servicio !!.
				 <a class=\"mellamais\" href=\"#mellamais\">Intentar otra vez</a>");
		$valido = false;
	}
	
	if ( $valido && seo_friendly_url($_POST[captcha])!=$captcha_valido ){
		$instance->addData("resultado-contactar", "Lo sentimos pero la respuesta a la operación aritmética no es correcta. Intentamos evitar
				el uso fraudulento de nuestro servicio. Esta operación queda registrada por seguridad. 
				 <a class=\"mellamais\" href=\"#mellamais\">Intentar otra vez</a>");
		$valido = false;
	}
	
	if ( $valido ) {
		//este sera el texto que vea el cliente si sus datos son correctos para enviarnos un email de contacto
		$instance->addData("resultado-contactar", "Perfecto en cuanto sea posible (máximo 1 hora) contactaremos con usted. <a href=\"/home.html\">Continuar</a>");	
		$html = "<br><br>Telefono/Email: $_POST[teloemail]<br>Asunto: $_POST[asunto]<br><br>";	
		foreach ( $shop->email_contacto as $correo ){
			$shop->email->enviar($correo, "Ibericasoft", "Solicitud de contacto desde ".HOST, $html, "");
			if ( $shop->email->getErrorEnvio() )
				Navigator::$log->error( "Error en el envio de solicitud de contacto"."<".trim($correo).">".$shop->email->getErrorEnvio() );
		}
	}
	
	terminarPagina($instance, $shop);
}


function cleanStringUrl($text) {
	$utf8 = array(
			'/[áàâãªä]/u'   =>   'a',
			'/[ÁÀÂÃÄ]/u'    =>   'A',
			'/[ÍÌÎÏ]/u'     =>   'I',
			'/[íìîï]/u'     =>   'i',
			'/[éèêë]/u'     =>   'e',
			'/[ÉÈÊË]/u'     =>   'E',
			'/[óòôõºö]/u'   =>   'o',
			'/[ÓÒÔÕÖ]/u'    =>   'O',
			'/[úùûü]/u'     =>   'u',
			'/[ÚÙÛÜ]/u'     =>   'U',
			'/ç/'           =>   'c',
			'/Ç/'           =>   'C',
			'/ñ/'           =>   'n',
			'/Ñ/'           =>   'N',
			'/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
			'/ /'           =>   '_', // nonbreaking space (equiv. to 0x160)
	);
	return preg_replace(array_keys($utf8), array_values($utf8), $text);
}

function seo_friendly_url($string){
	$string = str_replace(array('[\', \']'), '', $string);
	$string = preg_replace('/\[.*\]/U', '', $string);
	$string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
	$string = htmlentities($string, ENT_COMPAT, 'utf-8');
	$string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
	$string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
	return strtolower(trim($string, '-'));
}
?>