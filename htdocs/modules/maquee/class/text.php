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
class MaqueeText extends XoopsObject
{

    function MaqueeText($id = null)
    {
		$this->initVar('tid', XOBJ_DTYPE_INT, null, false);
    	$this->initVar('mid', XOBJ_DTYPE_INT, null, false);
    	$this->initVar('weight', XOBJ_DTYPE_INT, null, false);
    	$this->initVar('language', XOBJ_DTYPE_TXTBOX, $GLOBALS['xoopsConfig']['language'], false, 64);
        $this->initVar('html', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false, 255);		
		$this->initVar('url', XOBJ_DTYPE_TXTBOX, 'http://', false, 500);
		$this->initVar('created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
		
    }

    function getForm() {
    	return maquee_text_get_form($this, false);
    }
    
    function toArray() {
    	$ret = parent::toArray();
    	$ele = maquee_text_get_form($this, true);
    	foreach($ele as $key => $field)
    		$ret['form'][$key] = $field->render();
    	return $ret;
    }
    
    function getMaquee($block=false) {
    	$myts = MyTextSanitizer::getInstance();
    	$ret = parent::toArray();
    	if (!empty($ret['title']))
    		$data = '<h1>'.$ret['title'].'</h1>';
    	$data .= $myts->displayTarea($ret['html'], true);
    	if (!empty($ret['url'])&&$ret['url']!='http://')
    	  	$data = '<a href="'.$ret['url'].'" target="_blank">'.$data.'</a>';
    	$ret['item'] = $data;
    	return count($ret)>0?$ret:false;
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
class MaqueeTextHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
		$this->db = $db;
        parent::__construct($db, 'maquee_text', 'MaqueeText', "tid", "html");
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
    
	function insert($object, $force=true) {
    	if ($object->isNew()) {
    		$object->setVar('created', time());
    	} else {
    		$object->setVar('updated', time());
    	}
    	return parent::insert($object, $force);
    }
}

?>