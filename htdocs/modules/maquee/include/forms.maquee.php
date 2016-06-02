<?php

	function maquee_maquee_get_form($object, $as_array=false) {
		
		if (!is_object($object)) {
			$handler = xoops_getmodulehandler('maquee', 'maquee');
			$object = $handler->create(); 
		}
		
		xoops_loadLanguage('forms', 'maquee');
		$ele = array();
		
		if ($object->isNew()) {
			$sform = new XoopsThemeForm(_FRM_MAQUEE_FORM_ISNEW_MAQUEE, 'maquee', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'new');
		} else {
			$sform = new XoopsThemeForm(_FRM_MAQUEE_FORM_EDIT_MAQUEE, 'maquee', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'edit');
		}
		
		$id = $object->getVar('mid');
		if (empty($id)) $id = '0';
		
		$ele['op'] = new XoopsFormHidden('op', 'maquee');
		$ele['fct'] = new XoopsFormHidden('fct', 'save');
		if ($as_array==false)
			$ele['id'] = new XoopsFormHidden('id', $id);
		else 
			$ele['id'] = new XoopsFormHidden('id['.$id.']', $id);
		$ele['sort'] = new XoopsFormHidden('sort', isset($_REQUEST['sort'])?$_REQUEST['sort']:'created');
		$ele['order'] = new XoopsFormHidden('order', isset($_REQUEST['order'])?$_REQUEST['order']:'DESC');
		$ele['start'] = new XoopsFormHidden('start', isset($_REQUEST['start'])?intval($_REQUEST['start']):0);
		$ele['limit'] = new XoopsFormHidden('limit', isset($_REQUEST['limit'])?intval($_REQUEST['limit']):0);
		$ele['filter'] = new XoopsFormHidden('filter', isset($_REQUEST['filter'])?$_REQUEST['filter']:'1,1');
							
		$ele['type'] = new MaqueeFormSelectType(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_TYPE:''), $id.'[type]', $object->getVar('type'));
		$ele['type']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_TYPE_DESC:''));
		$ele['name'] = new XoopsFormText(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_NAME:''), $id.'[name]', ($as_array==false?35:21),255, $object->getVar('name'));
		$ele['name']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_NAME_DESC:''));
		$ele['description'] = new XoopsFormText(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_DESCRIPTION:''), $id.'[description]', ($as_array==false?35:21), 500, $object->getVar('description'));
		$ele['description']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_DESCRIPTION_DESC:''));
		$ele['reference'] = new XoopsFormText(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_REFERENCE:''), $id.'[reference]', ($as_array==false?35:21), 64, $object->getVar('reference'));
		$ele['reference']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_REFERENCE_DESC:''));
		$ele['animation'] = new MaqueeFormSelectAnimation(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_ANIMATION:''), $id.'[animation]', $object->getVar('animation'));
		$ele['animation']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_ANIMATION_DESC:''));
		$ele['sequence'] = new MaqueeFormSelectSequence(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_SEQUENCE:''), $id.'[sequence]', $object->getVar('sequence'));
		$ele['sequence']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_SEQUENCE_DESC:''));
		$ele['speed'] = new XoopsFormText(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_SPEED:''), $id.'[speed]', ($as_array==false?15:10),12, $object->getVar('speed'));
		$ele['speed']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_SPEED_DESC:''));
		$ele['timeout'] = new XoopsFormText(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_TIMEOUT:''), $id.'[timeout]', ($as_array==false?15:10),12, $object->getVar('timeout'));
		$ele['timeout']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_TIMEOUT_DESC:''));
		$ele['width'] = new XoopsFormText(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_WIDTH:''), $id.'[width]', ($as_array==false?15:10),12, $object->getVar('width'));
		$ele['width']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_WIDTH_DESC:''));
		$ele['height'] = new XoopsFormText(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_HEIGHT:''), $id.'[height]', ($as_array==false?15:10),12, $object->getVar('height'));
		$ele['height']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_HEIGHT_DESC:''));
		$ele['bgcolour'] = new XoopsFormText(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_BGCOLOUR:''), $id.'[bgcolour]', 10,7, $object->getVar('bgcolour'));
		$ele['bgcolour']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_BGCOLOUR_DESC:''));
		$ele['default'] = new XoopsFormRadioYN(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_DEFAULT:''), $id.'[default]', $object->getVar('default'));
		$ele['default']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_DEFAULT_DESC:''));

		if ($object->getVar('created')>0) {
			$ele['created'] = new XoopsFormLabel(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_CREATED:''), date(_DATESTRING, $object->getVar('created')));
		}
		
		if ($object->getVar('updated')>0) {
			$ele['updated'] = new XoopsFormLabel(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_UPDATED:''), date(_DATESTRING, $object->getVar('updated')));
		}
		
		if ($object->getVar('actioned')>0) {
			$ele['actioned'] = new XoopsFormLabel(($as_array==false?_FRM_MAQUEE_FORM_MAQUEE_ACTIONED:''), date(_DATESTRING, $object->getVar('actioned')));
		}	
		
		if ($as_array==true)
			return $ele;
		
		$ele['submit'] = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
		
		$required = array('name');
		
		foreach($ele as $id => $obj)			
			if (in_array($id, $required))
				$sform->addElement($ele[$id], true);			
			else
				$sform->addElement($ele[$id], false);
		
		return $sform->render();
		
	}

	
	function maquee_gallery_get_form($object, $as_array=false) {
		
		if (!is_object($object)) {
			$handler = xoops_getmodulehandler('gallery', 'maquee');
			$object = $handler->create(); 
		}
		
		xoops_loadLanguage('forms', 'maquee');
		$ele = array();
		
		if ($object->isNew()) {
			$sform = new XoopsThemeForm(_FRM_MAQUEE_FORM_ISNEW_GALLERY, 'gallery', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'new');
		} else {
			$sform = new XoopsThemeForm(_FRM_MAQUEE_FORM_EDIT_GALLERY, 'gallery', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'edit');
		}
		
		$sform->setExtra( "enctype='multipart/form-data'" ) ;
		
		$id = $object->getVar('gid');
		if (empty($id)) $id = '0';
		
		$ele['op'] = new XoopsFormHidden('op', 'gallery');
		$ele['fct'] = new XoopsFormHidden('fct', 'save');
		if ($as_array==false)
			$ele['id'] = new XoopsFormHidden('id', $id);
		else 
			$ele['id'] = new XoopsFormHidden('id['.$id.']', $id);
		$ele['sort'] = new XoopsFormHidden('sort', isset($_REQUEST['sort'])?$_REQUEST['sort']:'created');
		$ele['order'] = new XoopsFormHidden('order', isset($_REQUEST['order'])?$_REQUEST['order']:'DESC');
		$ele['start'] = new XoopsFormHidden('start', isset($_REQUEST['start'])?intval($_REQUEST['start']):0);
		$ele['limit'] = new XoopsFormHidden('limit', isset($_REQUEST['limit'])?intval($_REQUEST['limit']):0);
		$ele['filter'] = new XoopsFormHidden('filter', isset($_REQUEST['filter'])?$_REQUEST['filter']:'1,1');

		if ($object->isNew()) {
			$ele['file'] = new XoopsFormFile(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_FILE:''), 'imagefile', $GLOBALS['xoopsModuleConfig']['maximum_filesize']);
			$ele['file']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_FILE_DESC:''));
			$required = array('mid', 'weight', 'file');
		} else {
			$ele['thumbnail'] = new XoopsFormLabel(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_THUMBNAIL:''), '<img src="'.XOOPS_URL.'/modules/maquee/thumbnail.php?gid='.$object->getVar('gid').'" />');
			$ele['thumbnail']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_THUMBNAIL_DESC:''));
			$ele['file'] = new XoopsFormFile(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_REPLACEFILE:''), 'imagefile', $GLOBALS['xoopsModuleConfig']['maximum_filesize']);
			$ele['file']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_REPLACEFILE_DESC:''));
			$required = array('mid', 'weight');
		}
		$ele['mid'] = new MaqueeFormSelectMaquee(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_MAQUEE:''), $id.'[mid]', $object->getVar('mid'), 1, false, false, '_MI_MAQUEE_TYPE_GALLERY');
		$ele['mid']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_MAQUEE_DESC:''));
		$ele['language'] = new MaqueeFormSelectLanguage(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_LANGUAGE:''), $id.'[language]', $object->getVar('language'));
		$ele['language']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_LANGUAGE_DESC:''));
		$ele['description'] = new XoopsFormText(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_DESCRIPTION:''), $id.'[description]', ($as_array==false?35:21), 500, $object->getVar('description'));
		$ele['description']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_DESCRIPTION_DESC:''));	
		$ele['weight'] = new XoopsFormText(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_WEIGHT:''), $id.'[weight]', ($as_array==false?15:10),12, $object->getVar('weight'));
		$ele['weight']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_WEIGHT_DESC:''));
		$ele['url'] = new XoopsFormText(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_URL:''), $id.'[url]', ($as_array==false?35:21), 500, $object->getVar('url'));
		$ele['url']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_URL_DESC:''));
		
		if ($object->getVar('created')>0) {
			$ele['created'] = new XoopsFormLabel(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_CREATED:''), date(_DATESTRING, $object->getVar('created')));
		}
		
		if ($object->getVar('updated')>0) {
			$ele['updated'] = new XoopsFormLabel(($as_array==false?_FRM_MAQUEE_FORM_GALLERY_UPDATED:''), date(_DATESTRING, $object->getVar('updated')));
		}
			
		if ($as_array==true)
			return $ele;
		
		$ele['submit'] = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
		
		foreach($ele as $id => $obj)			
			if (in_array($id, $required))
				$sform->addElement($ele[$id], true);			
			else
				$sform->addElement($ele[$id], false);
		
		return $sform->render();
		
	}
	
	function maquee_text_get_form($object, $as_array=false) {
		
		if (!is_object($object)) {
			$handler = xoops_getmodulehandler('text', 'maquee');
			$object = $handler->create(); 
		}
		
		xoops_loadLanguage('forms', 'maquee');
		$ele = array();	
		
		if ($object->isNew()) {
			$sform = new XoopsThemeForm(_FRM_MAQUEE_FORM_ISNEW_TEXT, 'text', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'new');
		} else {
			$sform = new XoopsThemeForm(_FRM_MAQUEE_FORM_EDIT_TEXT, 'text', $_SERVER['PHP_SELF'], 'post');
			$ele['mode'] = new XoopsFormHidden('mode', 'edit');
		}
		
		$sform->setExtra( "enctype='multipart/form-data'" ) ;
		
		$id = $object->getVar('tid');
		if (empty($id)) $id = '0';
		
		$ele['op'] = new XoopsFormHidden('op', 'text');
		$ele['fct'] = new XoopsFormHidden('fct', 'save');
		if ($as_array==false)
			$ele['id'] = new XoopsFormHidden('id', $id);
		else 
			$ele['id'] = new XoopsFormHidden('id['.$id.']', $id);
		$ele['sort'] = new XoopsFormHidden('sort', isset($_REQUEST['sort'])?$_REQUEST['sort']:'created');
		$ele['order'] = new XoopsFormHidden('order', isset($_REQUEST['order'])?$_REQUEST['order']:'DESC');
		$ele['start'] = new XoopsFormHidden('start', isset($_REQUEST['start'])?intval($_REQUEST['start']):0);
		$ele['limit'] = new XoopsFormHidden('limit', isset($_REQUEST['limit'])?intval($_REQUEST['limit']):0);
		$ele['filter'] = new XoopsFormHidden('filter', isset($_REQUEST['filter'])?$_REQUEST['filter']:'1,1');
					
		$ele['mid'] = new MaqueeFormSelectMaquee(($as_array==false?_FRM_MAQUEE_FORM_TEXT_MAQUEE:''), $id.'[mid]', $object->getVar('mid'), 1, false, false, '_MI_MAQUEE_TYPE_TICKER');
		$ele['mid']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_TEXT_MAQUEE_DESC:''));
		$ele['language'] = new MaqueeFormSelectLanguage(($as_array==false?_FRM_MAQUEE_FORM_TEXT_LANGUAGE:''), $id.'[language]', $object->getVar('language'));
		$ele['language']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_TEXT_LANGUAGE_DESC:''));
		$ele['title'] = new XoopsFormText(($as_array==false?_FRM_MAQUEE_FORM_TEXT_TITLE:''), $id.'[title]', ($as_array==false?35:21), 255, $object->getVar('title'));
		$ele['title']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_TEXT_TITLE_DESC:''));	
		
		$html_configs = array();
		$html_configs['name'] = $id.'[html]';
		$html_configs['value'] = $object->getVar('html');
		$html_configs['rows'] = 35;
		$html_configs['cols'] = 60;
		$html_configs['width'] = "100%";
		$html_configs['height'] = "400px";
		$html_configs['editor'] = $GLOBALS['xoopsModuleConfig']['editor'];
		$ele['html'] = new XoopsFormEditor(($as_array==false?_FRM_MAQUEE_FORM_TEXT_HTML:''), $html_configs['name'], $html_configs);
		$ele['html']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_TEXT_HTML_DESC:''));
		
		$ele['weight'] = new XoopsFormText(($as_array==false?_FRM_MAQUEE_FORM_TEXT_WEIGHT:''), $id.'[weight]', ($as_array==false?15:10),12, $object->getVar('weight'));
		$ele['weight']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_TEXT_WEIGHT_DESC:''));
		$ele['url'] = new XoopsFormText(($as_array==false?_FRM_MAQUEE_FORM_TEXT_URL:''), $id.'[url]', ($as_array==false?35:21), 500, $object->getVar('url'));
		$ele['url']->setDescription(($as_array==false?_FRM_MAQUEE_FORM_TEXT_URL_DESC:''));
	
		if ($object->getVar('created')>0) {
			$ele['created'] = new XoopsFormLabel(($as_array==false?_FRM_MAQUEE_FORM_TEXT_CREATED:''), date(_DATESTRING, $object->getVar('created')));
		}
		
		if ($object->getVar('updated')>0) {
			$ele['updated'] = new XoopsFormLabel(($as_array==false?_FRM_MAQUEE_FORM_TEXT_UPDATED:''), date(_DATESTRING, $object->getVar('updated')));
		}
			
		if ($as_array==true)
			return $ele;
		
		$ele['submit'] = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
		
		$required = array('mid', 'weight', 'html');
		
		foreach($ele as $id => $obj)			
			if (in_array($id, $required))
				$sform->addElement($ele[$id], true);			
			else
				$sform->addElement($ele[$id], false);
		
		return $sform->render();
		
	}
?>