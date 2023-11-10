<?php
/*
CakePHP jQuery helper.

This helper will make easy to include jQuery source, plugins and scripts.


@copyright: Marco Pegoraro, info(at)consulenza-web.com
@url: http://www.consulenza-web.com/cakephp-jquery-helper.dc-28.html

*/
class JqueryHelper extends Helper {
	
	# Load jQuery's core library located in "webroot/js/jQuery/".                                 #
	function core() {
		// Identify Cake's installation path for calculating relative urls of external script.    #
		$path 	= '/'.strRev(subStr( strRev(ROOT), 0, strPos( str_replace("\\", "/", strRev(ROOT)), "/")));
		
		// Composizione dell'url del file da caricare.                                            #
		$url 	= $path.'/js/jQuery/jQuery.js';
		
		return $this->script($url);
	} // EndOf "core()" ###########################################################################
	
	
	# Provide xHTML code to include a jQuery's plugin with it's own css.                          #
	# A plugin must be located in "webroot/js/jQuery/" in a folder named as "pl_pluginname".      #
	# This folder will contain the plugin's core library: "plugin.jquery.js", one or more css     #
	# files named like media: "screen.css" and other files like images, plugin description.       #
	function plugin($name = '', $media = 'screen') {
		// Identify Cake's installation path for calculating relative urls of external script.    #
		$path 	= '/'.strRev(subStr( strRev(ROOT), 0, strPos( str_replace("\\", "/", strRev(ROOT)), "/")));
		
		// Require xHTML code to include the plugin core library.                                 #
		$code = $this->script($path.'/js/jQuery/pl_'.$name.'/'.$name.'.jquery.js');
		
		// This method allow to request multiple css media by passing a comma separated string:   #
		// $jquery->plugin($name, 'screen,print,mobile')                                          #
		foreach ( split(',', $media) as $el ) $code.= $this->pluginCss($path.'/js/jQuery/pl_'.$name.'/', trim($el));
		
		return $code;
	} // EndOf "plugin()" #########################################################################
	
	
	# Provide xHTML code to include multiple jQuery's plugin at once by passing comma separated   #
	# string. This method accept multiple css media like "plugin".                                #
	# $jquery->plugins('interface,iwin,form', 'screen,print');                                    #
	function plugins($names = '', $media = 'screen') {
		$code = '';
		foreach ( split(',', $names) as $el ) $code.= $this->plugin(trim($el), $media);
		
		return $code;
	} // EndOf "plugins()" ########################################################################
	
	
	# Provide code to run a "functional" jQuery file by name.                                     #
	# A call to "blog" script will look for "webroot/js/jq_blog.js".                              #
	# This method allow for loading multiple file once:                                           #
	# $jquery->run("blog,chat,images");                                                           #
	# This method allow for loading annidiated script:                                            #
	# $jquery->run("test/foo,images,blog,communications/chat/write");                             #
	function run($script = '') {
		// Identify Cake's installation path for calculating relative urls of external script.    #
		$path 	= '/'.strRev(subStr( strRev(ROOT), 0, strPos( str_replace("\\", "/", strRev(ROOT)), "/")));
		
		$code = '';
		foreach( split(',', $script) as $el ) {
			$p = strRev(subStr(strRev(str_replace('\\', '/', trim($el))), strPos(strRev(str_replace('\\', '/', trim($el))), '/'), strLen(strRev(str_replace('\\', '/', trim($el))))));
			if ( strPos($p, '/') ) {
				$s = subStr(trim($el), strLen($p), strLen(trim($el)));
			} else {
				$s = $p;
				$p = '';
			}
			$code.= $this->script($path.'/js/'.$p.'jq_'.$s.'.js');
		}
		
		return $code;
	} // Fine "run()" #############################################################################
	
	
	# Provide code to run a controller related script named like:                                 #
	# "webroot/js/jq_controller_controllername.js".                                               #
	function controller() {
		return $this->run('controller_'.$this->params['controller']);
	} // EndOf: "controller()" ####################################################################
	
	
	# Provide code to run an controller's action related script named like:                       #
	# "webroot/js_controller_controllername_action.js".                                           #
	function controllerAction() {
		return $this->run('action_'.$this->params['controller'].'_'.$this->params['action']);
	} // EndOf: "controllerAction()" ##############################################################
	
	
	# Provide code to run an action related script named like:                                    #
	# "webroot/js_action_actionname.js".                                                          #
	function action() {
		return $this->run('controller_'.$this->params['action']);
	} // EndOf: "action()" ########################################################################
	
	
	// Provide a Javascript inclusion tag for jQuery file.                                        #
	function script($url = '') {
		// Inclusion code will generate only if requested file exists.                            #
		if ( is_File( WWW_ROOT.str_replace('/', DS, subStr($url, strPos($url, 'js/'), strLen($url))) ) )
			return $this->output('<script type="text/javascript" src="'.$url.'"></script>');
	} // EndOf "script()" #########################################################################
	
	
	// Provide a CSS inclusion tag for jQuery plugin's stylesheet.                                #
	function pluginCss($plugin = '', $media = '') {
		// Inclusion code will generate only if requested file exists.                            #
		if ( is_File( WWW_ROOT.str_replace("/", DS, subStr($plugin, strPos($plugin, 'js/'), strLen($plugin))).$media.'.css' ) )
			return $this->output('<link rel="stylesheet" type="text/css" href="'.$plugin.$media.'.css" media="'.$media.'" />');
	} // EndOf "pluginCss()" ######################################################################
}
?>