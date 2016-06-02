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
class MaqueeGallery extends XoopsObject
{

	var $_mHandler = NULL;
	
    function MaqueeGallery($id = null)
    {
		$this->_mHandler = xoops_getmodulehandler('maquee', 'maquee');
		
    	$this->initVar('gid', XOBJ_DTYPE_INT, null, false);
    	$this->initVar('mid', XOBJ_DTYPE_INT, null, false);
    	$this->initVar('weight', XOBJ_DTYPE_INT, null, false);
    	$this->initVar('language', XOBJ_DTYPE_TXTBOX, $GLOBALS['xoopsConfig']['language'], false, 64);
        $this->initVar('description', XOBJ_DTYPE_TXTBOX, null, false, 500);
		$this->initVar('path', XOBJ_DTYPE_TXTBOX, null, false, 255);		
		$this->initVar('filename', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('width', XOBJ_DTYPE_INT, $GLOBALS['xoopsModuleConfig']['default_width'], false);
		$this->initVar('height', XOBJ_DTYPE_INT, $GLOBALS['xoopsModuleConfig']['default_height'], false);
		$this->initVar('extension', XOBJ_DTYPE_TXTBOX, 'jpg', false, 5);
		$this->initVar('url', XOBJ_DTYPE_TXTBOX, 'http://', false, 500);
		$this->initVar('created', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
		
    }
    
    function getForm() {
    	return maquee_gallery_get_form($this, false);
    }
    
    function toArray() {
    	$ret = parent::toArray();
    	$ele = maquee_gallery_get_form($this, true);
    	foreach($ele as $key => $field)
    		$ret['form'][$key] = $field->render();
    	return $ret;
    }
    
	function getMaquee($block=false) {
		$marquee = $this->_mHandler->get($this->getVar('mid'));
      	$ret = parent::toArray();
    	if (!empty($ret['description']))
    		$data = ' alt="'.$ret['description'].'"';
    	$data = '<img src="'.XOOPS_URL.'/modules/maquee/image.php?gid='.$this->getVar('gid').'&mid='.$this->getVar('mid').'"'.$data.' width="'.$marquee->getVar('width').'px"  height="'.$marquee->getVar('height').'px" />';
    	if (!empty($ret['url'])&&!empty($ret['description'])&&$ret['url']!='http://')
    	  	$data = '<a href="'.$ret['url'].'" target="_blank" title="'.$ret['description'].'">'.$data.'</a>';
    	elseif (!empty($ret['url'])&&empty($ret['description'])&&$ret['url']!='http://')
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
class MaqueeGalleryHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
		$this->db = $db;
        parent::__construct($db, 'maquee_gallery', 'MaqueeGallery', "gid", "filename");
    }
    
    function delete($object, $force=true) {
    	if (file_exists($object->getVar('path').(substr($object->getVar('path'), strlen($object->getVar('path'))-1, 1)!=DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:'').$object->getVar('filename'))) {
    		if (unlink($object->getVar('path').(substr($object->getVar('path'), strlen($object->getVar('path'))-1, 1)!=DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:'').$object->getVar('filename')))
    			return parent::delete($object, $force);
    	}
    	return parent::delete($object, $force);
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