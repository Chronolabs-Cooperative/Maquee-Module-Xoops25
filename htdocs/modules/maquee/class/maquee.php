<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}
/**
 * Class for Blue Room Xcenter
 * @author Simon Roberts <simon@xoops.org>
 * @copyright copyright (c) 2009-2003 XOOPS.org
 * @package kernel
 */
class MaqueeMaquee extends XoopsObject
{

	var $_ModConfig = NULL;
	var $_Mod = NULL;
	
	// Animation javascript enumerations
	var $_animations = array('slideup', 'fadein', 'slidedown', 'fadeout');
	
    function MaqueeMaquee($id = null)
    {
    	$config_handler = xoops_gethandler('config');
		$module_handler = xoops_gethandler('module');
		$this->_Mod = $module_handler->getByDirname('maquee');
		$this->_ModConfig = $config_handler->getConfigList($this->_Mod->getVar('mid'));
		
        $this->initVar('mid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('type', XOBJ_DTYPE_ENUM, '_MI_MAQUEE_TYPE_GALLERY', false, false, false, array('_MI_MAQUEE_TYPE_GALLERY','_MI_MAQUEE_TYPE_TICKER'));
		$this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('description', XOBJ_DTYPE_TXTBOX, null, false, 500);		
		$this->initVar('reference', XOBJ_DTYPE_TXTBOX, 'maquee_%id%', false, 64);
        $this->initVar('animation', XOBJ_DTYPE_ENUM, '_MI_MAQUEE_ANIMATION_DEFAULT', false, false, false, array('_MI_MAQUEE_ANIMATION_SLIDEUP','_MI_MAQUEE_ANIMATION_FADEIN','_MI_MAQUEE_ANIMATION_SLIDEDOWN','_MI_MAQUEE_ANIMATION_FADEOUT','_MI_MAQUEE_ANIMATION_RANDOM','_MI_MAQUEE_ANIMATION_DEFAULT'));
        $this->initVar('sequence', XOBJ_DTYPE_ENUM, '_MI_MAQUEE_SEQUENCE_DEFAULT', false, false, false, array('_MI_MAQUEE_SEQUENCE_SEQUENCE','_MI_MAQUEE_SEQUENCE_RANDOM','_MI_MAQUEE_SEQUENCE_RANDOM_START','_MI_MAQUEE_SEQUENCE_DEFAULT'));
		$this->initVar('speed', XOBJ_DTYPE_INT, $this->_ModConfig['default_speed'], false); 
		$this->initVar('timeout', XOBJ_DTYPE_INT, $this->_ModConfig['default_timeout'], false); 
		$this->initVar('width', XOBJ_DTYPE_INT, $this->_ModConfig['default_width'], false);
		$this->initVar('height', XOBJ_DTYPE_INT, $this->_ModConfig['default_height'], false);
		$this->initVar('bgcolour', XOBJ_DTYPE_TXTBOX, '#ffffff', false, 7);
		$this->initVar('default', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('actioned', XOBJ_DTYPE_INT, 0, false);
    }
    
    function getForm() {
    	return maquee_maquee_get_form($this, false);
    }
    
    function toArray() {
    	$ret = parent::toArray();
    	$ele = maquee_maquee_get_form($this, true);
    	foreach($ele as $key => $field)
    		$ret['form'][$key] = $field->render();
    	return $ret;
    }
    
    function getMaquee($block=false) {
    	switch ($this->getVar('type')) {
    		case '_MI_MAQUEE_TYPE_GALLERY':
    			$handler = xoops_getmodulehandler('gallery', 'maquee');
    			break;
    		case '_MI_MAQUEE_TYPE_TICKER':
    			$handler = xoops_getmodulehandler('text', 'maquee');
    			break;
    	}
    	$criteria = new CriteriaCompo(new Criteria('`language`', $GLOBALS['xoopsConfig']['language']));
    	$criteria->add(new Criteria('mid', $this->getVar('mid')));
    	$criteria->setSort('`weight`');
    	$criteria->setOrder('ASC');
    	$objects = $handler->getObjects($criteria, true);
		$data = array();
    	foreach($objects as $id => $object) {
    		$data[$id] = $object->getMaquee($block);	
    	}
    	$ret = parent::toArray();
    	$ret['myreference'] = $this->getReference($block);
    	$ret['data'] = $data;
    	return count($data)>0?$ret:false;
    }
    
    
    function getJS($block=false) {
    	static $_loadedJS = false;
    	static $_loadedCSS = array();
    	
    	xoops_loadLanguage('modinfo', 'maquee');
    	
		switch($this->getVar('animation')) {
			case '_MI_MAQUEE_ANIMATION_SLIDEUP':
				$animationtype = 'slideup';
				break;
			case '_MI_MAQUEE_ANIMATION_FADEIN':
				$animationtype = 'fadein';
				break;
			case '_MI_MAQUEE_ANIMATION_SLIDEDOWN':
				$animationtype = 'slidedown';
				break;
			case '_MI_MAQUEE_ANIMATION_FADEOUT':
				$animationtype = 'fadeout';
				break;
			case '_MI_MAQUEE_ANIMATION_RANDOM':
				mt_srand(microtime(true));
				while(!$animationtype = $this->_animations[mt_rand(0, sizeof($this->_animations))]);
				break;
			default:
			case '_MI_MAQUEE_ANIMATION_DEFAULT':
				if ($this->_ModConfig['default_animation']=='random') {
					mt_srand(microtime(true));
					while(!$animationtype = $this->_animations[mt_rand(0, sizeof($this->_animations))]);
				} else {
					$animationtype = $this->_ModConfig['default_animation'];
				}
				break;
		}
    	switch($this->getVar('sequence')) {
			case '_MI_MAQUEE_SEQUENCE_SEQUENCE':
				$sequence = 'sequence';
				break;
			case '_MI_MAQUEE_SEQUENCE_RANDOM':
				$sequence = 'random';
				break;
			case '_MI_MAQUEE_SEQUENCE_RANDOM_START':
				$sequence = 'random_start';
				break;
			default:
			case '_MI_MAQUEE_SEQUENCE_DEFAULT':
				$sequence = $this->_ModConfig['default_sequence'];
    			break;
		}
		
		if (is_object($GLOBALS['xoTheme'])&&$_loadedJS==false) {
			if ($this->_ModConfig['force_jquery']) {
				$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_MAQUEE_JQUERY, array('type'=>'text/javascript'));
			}
			$GLOBALS['xoTheme']->addScript(XOOPS_URL._MI_MAQUEE_INNERFADE, array('type'=>'text/javascript'));
			$_loadedJS = true;
			if (!isset($_loadedCSS[$this->getVar('mid')])||$_loadedCSS[$this->getVar('mid')]==false) {
				if ($block==false)
					$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL.sprintf(_MI_MAQUEE_CSSFORANIM, $this->getVar('mid')), array('type'=>'text/css'));
				else 
					$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL.sprintf(_MI_MAQUEE_CSSFORANIM_BLOCK, $this->getVar('mid')), array('type'=>'text/css'));
				$_loadedCSS[$this->getVar('mid')] = true;
			}
		}
    	return "$(document).ready( function(){ $('#".$this->getReference($block)."').innerfade({ speed: ".$this->getVar('speed').", timeout: ".$this->getVar('timeout').", ".(strlen($animationtype)>0?"animationtype: '".$animationtype."',":"")." sequence: '".$sequence."', containerheight: '".$this->getVar('height')."px' }); } );";
    }
    
    function getReference($block=false) {
    	if ($block==true)
    		return str_replace('%id%', $this->getVar('mid'), 'block_'.$this->getVar('reference'));
    	else
    		return str_replace('%id%', $this->getVar('mid'), $this->getVar('reference'));
    }

	function runInsertPlugin() {
		
		xoops_loadLanguage('plugins', 'maquee');
		
		include_once($GLOBALS['xoops']->path('/modules/benchmark/plugins/'.constant($this->getVar('type').'_PLUGINFILE').'.php'));

		switch ($this->getVar('type')) {
			case '_MI_MAQUEE_TYPE_GALLERY':
			case '_MI_MAQUEE_TYPE_TICKER':
				switch ($this->getVar('animation')) {
					case '_MI_MAQUEE_ANIMATION_SLIDEIN':
					case '_MI_MAQUEE_ANIMATION_FADEIN':
					case '_MI_MAQUEE_ANIMATION_SLIDEOUT':
					case '_MI_MAQUEE_ANIMATION_FADEOUT':
					case '_MI_MAQUEE_ANIMATION_RANDOM':
					case '_MI_MAQUEE_ANIMATION_DEFAULT':
						switch ($this->getVar('sequence')) {
							case '_MI_MAQUEE_SEQUENCE_SEQUENCE':
							case '_MI_MAQUEE_SEQUENCE_RANDOM':
							case '_MI_MAQUEE_SEQUENCE_RANDOM_START':
							case '_MI_MAQUEE_SEQUENCE_DEFAULT':
								$func = ucfirst(constant($this->getVar('type').'_PLUGIN')).ucfirst(constant($this->getVar('animation').'_PLUGIN')).ucfirst(constant($this->getVar('sequence').'_PLUGIN')).'InsertHook';
								break;
							default:
								return $this->getVar('mid');
								break;
						}
						break;
					default:
						return $this->getVar('mid');
						break;
				}
				break;
			default:
				return $this->getVar('mid');
				break;
		}
		
		if (function_exists($func))  {
			return @$func($this);
		}
		return $this->getVar('mid');
	}
	
	function runGetPlugin() {
				
		xoops_loadLanguage('plugins', 'maquee');
		
		include_once($GLOBALS['xoops']->path('/modules/benchmark/plugins/'.constant($this->getVar('type').'_PLUGINFILE').'.php'));

		switch ($this->getVar('type')) {
			case '_MI_MAQUEE_TYPE_GALLERY':
			case '_MI_MAQUEE_TYPE_TICKER':
				switch ($this->getVar('animation')) {
					case '_MI_MAQUEE_ANIMATION_SLIDEIN':
					case '_MI_MAQUEE_ANIMATION_FADEIN':
					case '_MI_MAQUEE_ANIMATION_SLIDEOUT':
					case '_MI_MAQUEE_ANIMATION_FADEOUT':
					case '_MI_MAQUEE_ANIMATION_RANDOM':
					case '_MI_MAQUEE_ANIMATION_DEFAULT':
						switch ($this->getVar('sequence')) {
							case '_MI_MAQUEE_SEQUENCE_SEQUENCE':
							case '_MI_MAQUEE_SEQUENCE_RANDOM':
							case '_MI_MAQUEE_SEQUENCE_RANDOM_START':
							case '_MI_MAQUEE_SEQUENCE_DEFAULT':
								$func = ucfirst(constant($this->getVar('type').'_PLUGIN')).ucfirst(constant($this->getVar('animation').'_PLUGIN')).ucfirst(constant($this->getVar('sequence').'_PLUGIN')).'GetHook';
								break;
							default:
								return $this;
								break;
						}
						break;
					default:
						return $this;
						break;
				}
				break;
			default:
				return $this;
				break;
		}
		
		if (function_exists($func))  {
			return @$func($this);
		}
		return $this;
	}
}


/**
* XOOPS policies handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS user class objects.
*
* @author  Simon Roberts <simon@chronolabs.coop>
* @package kernel
*/
class MaqueeMaqueeHandler extends XoopsPersistableObjectHandler
{

	var $_gHandler = NULL;
	var $_tHandler = NULL;
	var $_ModConfig = NULL;
	var $_Mod = NULL;
	
	function __construct(&$db) 
    {
		$config_handler = xoops_gethandler('config');
		$module_handler = xoops_gethandler('module');
		$this->_Mod = $module_handler->getByDirname('maquee');
		$this->_ModConfig = $config_handler->getConfigList($this->_Mod->getVar('mid'));
    	
		$this->_gHandler = xoops_getmodulehandler('gallery', 'maquee');
    	$this->_tHandler = xoops_getmodulehandler('text', 'maquee');
    	
    	$this->db = $db;
        parent::__construct($db, 'maquee_maquees', 'MaqueeMaquee', "mid", "name");
    }

    private function runGetArrayPlugin($row) {
		
		xoops_loadLanguage('plugins', 'maquee');
		
		include_once($GLOBALS['xoops']->path('/modules/benchmark/plugins/'.constant($row['type'].'_PLUGINFILE').'.php'));

		switch ($row['type']) {
			case '_MI_MAQUEE_TYPE_GALLERY':
			case '_MI_MAQUEE_TYPE_TICKER':
				switch ($row['animation']) {
					case '_MI_MAQUEE_ANIMATION_SLIDEIN':
					case '_MI_MAQUEE_ANIMATION_FADEIN':
					case '_MI_MAQUEE_ANIMATION_SLIDEOUT':
					case '_MI_MAQUEE_ANIMATION_FADEOUT':
					case '_MI_MAQUEE_ANIMATION_RANDOM':
					case '_MI_MAQUEE_ANIMATION_DEFAULT':
						switch ($row['sequence']) {
							case '_MI_MAQUEE_SEQUENCE_SEQUENCE':
							case '_MI_MAQUEE_SEQUENCE_RANDOM':
							case '_MI_MAQUEE_SEQUENCE_RANDOM_START':
							case '_MI_MAQUEE_SEQUENCE_DEFAULT':
								$func = ucfirst(constant($row['type'].'_PLUGIN')).ucfirst(constant($row['animation'].'_PLUGIN')).ucfirst(constant($row['sequence'].'_PLUGIN')).'GetArrayHook';
								break;
							default:
								return $row;
								break;
						}
						break;
					default:
						return $row;
						break;
				}
				break;
			default:
				return $row;
				break;
		}
		
		if (function_exists($func))  {
			return @$func($row);
		}
		return $row;
	}
	
	private function resetDefault() {
		$sql = "UPDATE " . $GLOBALS['xoopsDB']->prefix('maquee_maquees') . ' SET `default` = 0 WHERE 1 = 1';
		return $GLOBALS['xoopsDB']->queryF($sql);
	}
    
    function insert($obj, $force=true, $run_plugin = false) {
    	$this->recalc();
    	if ($obj->isNew()) {
    		$obj->setVar('created', time());
    	} else {
    		$obj->setVar('updated', time());
    	}
    	if ($obj->vars['default']['changed']==true&&$obj->getVar('default')==true) {
    		$this->resetDefault();
    	}
	   	if ($obj->vars['type']['changed']==true ||
    		$obj->vars['animation']['changed']==true ||
    		$obj->vars['sequence']['changed']==true ||
    		$run_plugin == true ) {	
			$obj->setVar('actioned', time());
			$run_plugin = true;
		}
    	if ($run_plugin == true) {
    		$id = parent::insert($obj, $force);
    		$obj = parent::get($id);
    		if (is_object($obj)) {
	    		$ret = $obj->runInsertPlugin();
	    		return ($ret!=0)?$ret:$id;
    		} else {
    			return $id;
    		}
    	} else {
    		return parent::insert($obj, $force);
    	}
    }
    
    function delete($object, $force=true) {
    	$criteria = new Criteria('mid', $object->getVar('mid'));
    	foreach($this->_gHandler->getObjects($criteria, true) as $gid => $gallery)
    		$this->_gHandler->delete($gallery);
    	foreach($this->_tHandler->getObjects($criteria, true) as $tid => $text)
    		$this->_tHandler->delete($text);
	   	return parent::delete($object, $force);
    }
    
    function get($id, $fields = '*', $run_plugin = true) {
    	$obj = parent::get($id, $fields);
    	if (is_object($obj)&&$run_plugin==true) {
    		return @$obj->runGetPlugin(false);
    	} elseif (is_array($obj)&&$run_plugin==true)
    		return $this->runGetArrayPlugin($obj);
    	else
    		return $obj;
    }
    
    function getObjects($criteria, $id_as_key=false, $as_object=true, $run_plugin = true) {
       	$objs = parent::getObjects($criteria, $id_as_key, $as_object);
    	foreach($objs as $id => $obj) {
    		if (is_object($obj)&&$run_plugin==true) {
    			$objs[$id] = @$obj->runGetPlugin();   			
    		} elseif (is_array($obj)&&$run_plugin==true)
    			$objs[$id] = @$this->runGetArrayPlugin($obj);
    		if (empty($objs[$id])||($as_object==true&&!is_object($objs[$id])))
    			unset($objs[$id]);
    	}
    	return $objs;
    }
    
    function getFilterCriteria($filter) {
    	$parts = explode('|', $filter);
    	$criteria = new CriteriaCompo();
    	foreach($parts as $part) {
    		$var = explode(',', $part);
    		if (!empty($var[1])&&!is_numeric($var[0])) {
    			$object = $this->create();
    			if (		$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_TXTBOX || 
    						$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_TXTAREA) 	{
    				$criteria->add(new Criteria('`'.$var[0].'`', '%'.$var[1].'%', (isset($var[2])?$var[2]:'LIKE')));
    			} elseif (	$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_INT || 
    						$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_DECIMAL || 
    						$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_FLOAT ) 	{
    				$criteria->add(new Criteria('`'.$var[0].'`', $var[1], (isset($var[2])?$var[2]:'=')));			
				} elseif (	$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_ENUM ) 	{
    				$criteria->add(new Criteria('`'.$var[0].'`', $var[1], (isset($var[2])?$var[2]:'=')));    				
				} elseif (	$object->vars[$var[0]]['data_type']==XOBJ_DTYPE_ARRAY ) 	{
    				$criteria->add(new Criteria('`'.$var[0].'`', '%"'.$var[1].'";%', (isset($var[2])?$var[2]:'LIKE')));    				
				}
    		} elseif (!empty($var[1])&&is_numeric($var[0])) {
    			$criteria->add(new Criteria("'".$var[0]."'", $var[1]));
    		}
    	}
    	return $criteria;
    }
        
	function getFilterForm($filter, $field, $sort='created', $op = '', $fct = '') {
    	$ele = maquee_getFilterElement($filter, $field, $sort, $op, $fct);
    	if (is_object($ele))
    		return $ele->render();
    	else 
    		return '&nbsp;';
    }
}

?>