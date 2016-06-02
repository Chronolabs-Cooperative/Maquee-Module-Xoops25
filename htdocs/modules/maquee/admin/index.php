<?php
	
	include('header.php');
		
	xoops_loadLanguage('admin', 'maquee');
	
	xoops_cp_header();
	
	$op = isset($_REQUEST['op'])?$_REQUEST['op']:"campaign";
	$fct = isset($_REQUEST['fct'])?$_REQUEST['fct']:"list";
	$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
	$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
	$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
	$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
	$filter = !empty($_REQUEST['filter'])?''.$_REQUEST['filter'].'':'1,1';
	
	switch($op) {
		default:
		case "maquee":	
			switch ($fct)
			{
				default:
				case "list":				
					maquee_adminMenu(1);
					
					include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
					include_once $GLOBALS['xoops']->path( "/class/template.php" );
					$GLOBALS['maqTpl'] = new XoopsTpl();
					
					$maquee_handler =& xoops_getmodulehandler('maquee', 'maquee');

					$criteria = $maquee_handler->getFilterCriteria($filter);
					$ttl = $maquee_handler->getCount($criteria);
					$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
					
					$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&op='.$op.'&fct='.$fct.'&filter='.$filter.'&fct='.$fct.'&filter='.$filter);
					$GLOBALS['maqTpl']->assign('pagenav', $pagenav->renderNav());
			
					foreach (array(	'mid','type','name','description','reference','animation','sequence','speed','timeout','width','height','bgcolour','default','created','updated','actioned') as $id => $key) {
						$GLOBALS['maqTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&op='.$op.'&filter='.$filter.'">'.(defined('_AM_MAQUEE_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_MAQUEE_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_MAQUEE_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
						$GLOBALS['maqTpl']->assign('filter_'.strtolower(str_replace('-','_',$key)).'_th', $maquee_handler->getFilterForm($filter, $key, $sort, $op, $fct));
					}
					
					$GLOBALS['maqTpl']->assign('limit', $limit);
					$GLOBALS['maqTpl']->assign('start', $start);
					$GLOBALS['maqTpl']->assign('order', $order);
					$GLOBALS['maqTpl']->assign('sort', $sort);
					$GLOBALS['maqTpl']->assign('filter', $filter);
					$GLOBALS['maqTpl']->assign('xoConfig', $GLOBALS['xoopsModuleConfig']);
										
					$criteria->setStart($start);
					$criteria->setLimit($limit);
					$criteria->setSort('`'.$sort.'`');
					$criteria->setOrder($order);
						
					$maquees = $maquee_handler->getObjects($criteria, true);
					foreach($maquees as $cid => $maquee) {
						$GLOBALS['maqTpl']->append('maquees', $maquee->toArray());
					}
					$GLOBALS['maqTpl']->assign('form', maquee_maquee_get_form(false));
					$GLOBALS['maqTpl']->assign('php_self', $_SERVER['PHP_SELF']);
					$GLOBALS['maqTpl']->display('db:maquee_cpanel_maquee_list.html');
					break;		
					
				case "new":
				case "edit":
					
					maquee_adminMenu(1);
					
					include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
					include_once $GLOBALS['xoops']->path( "/class/template.php" );
					$GLOBALS['maqTpl'] = new XoopsTpl();
					
					$maquee_handler =& xoops_getmodulehandler('maquee', 'maquee');
					if (isset($_REQUEST['id'])) {
						$maquee = $maquee_handler->get(intval($_REQUEST['id']));
					} else {
						$maquee = $maquee_handler->create();
					}
					
					$GLOBALS['maqTpl']->assign('form', $maquee->getForm());
					$GLOBALS['maqTpl']->assign('php_self', $_SERVER['PHP_SELF']);
					$GLOBALS['maqTpl']->display('db:maquee_cpanel_maquee_edit.html');
					break;
				case "save":
					
					$maquee_handler =& xoops_getmodulehandler('maquee', 'maquee');
					$id=0;
					if ($id=intval($_REQUEST['id'])) {
						$maquee = $maquee_handler->get($id);
					} else {
						$maquee = $maquee_handler->create();
					}
					$maquee->setVars($_POST[$id]);
					if ($_POST[$id]['default']==true)
						$maquee->setVar('default', true);
					else 
						$maquee->setVar('default', false);
					if (!$id=$maquee_handler->insert($maquee)) {
						redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_MAQUEE_FAILEDTOSAVE);
						exit(0);
					} else {
						switch($_REQUEST['mode']) {
							case 'new':
								redirect_header('index.php?op='.$op.'&fct=edit&id='.$id, 10, _AM_MSG_MAQUEE_SAVEDOKEY);
								break;
							default:
							case 'edit':
								redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_MAQUEE_SAVEDOKEY);
								break;
						}
						exit(0);
					}
					break;
				case "savelist":
					
					$maquee_handler =& xoops_getmodulehandler('maquee', 'maquee');
					foreach($_REQUEST['id'] as $id) {
						$maquee = $maquee_handler->get($id);
						$maquee->setVars($_POST[$id]);
						if ($_POST[$id]['default']==true)
							$maquee->setVar('default', true);
						else 
							$maquee->setVar('default', false);
						if (!$maquee_handler->insert($maquee)) {
							redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_MAQUEE_FAILEDTOSAVE);
							exit(0);
						} 
					}
					redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_MAQUEE_SAVEDOKEY);
					exit(0);
					break;				
				case "delete":	
								
					$maquee_handler =& xoops_getmodulehandler('maquee', 'maquee');
					$id=0;
					if (isset($_POST['id'])&&$id=intval($_POST['id'])) {
						$maquee = $maquee_handler->get($id);
						if (!$maquee_handler->delete($maquee)) {
							redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_MAQUEE_FAILEDTODELETE);
							exit(0);
						} else {
							redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_MAQUEE_DELETED);
							exit(0);
						}
					} else {
						$maquee = $maquee_handler->get(intval($_REQUEST['id']));
						xoops_confirm(array('id'=>$_REQUEST['id'], 'op'=>$_REQUEST['op'], 'fct'=>$_REQUEST['fct'], 'limit'=>$_REQUEST['limit'], 'start'=>$_REQUEST['start'], 'order'=>$_REQUEST['order'], 'sort'=>$_REQUEST['sort'], 'filter'=>$_REQUEST['filter']), 'index.php', sprintf(_AM_MSG_MAQUEE_DELETE, $maquee->getVar('name')));
					}
					break;
			}
			break;
		case "gallery":	
			switch ($fct)
			{
				default:
				case "list":				
					maquee_adminMenu(2);
					
					include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
					include_once $GLOBALS['xoops']->path( "/class/template.php" );
					$GLOBALS['maqTpl'] = new XoopsTpl();
					
					$gallery_handler =& xoops_getmodulehandler('gallery', 'maquee');
						
					$criteria = $gallery_handler->getFilterCriteria($filter);
					$ttl = $gallery_handler->getCount($criteria);
					$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
										
					$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&op='.$op.'&fct='.$fct.'&filter='.$filter.'&fct='.$fct.'&filter='.$filter);
					$GLOBALS['maqTpl']->assign('pagenav', $pagenav->renderNav());
			
					foreach (array(	'gid','mid','weight','language','description','path','filename','width','height','extension','url','created','updated') as $id => $key) {
						$GLOBALS['maqTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&op='.$op.'&filter='.$filter.'">'.(defined('_AM_MAQUEE_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_MAQUEE_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_MAQUEE_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
						$GLOBALS['maqTpl']->assign('filter_'.strtolower(str_replace('-','_',$key)).'_th', $gallery_handler->getFilterForm($filter, $key, $sort, $op, $fct));
					}
					
					$GLOBALS['maqTpl']->assign('limit', $limit);
					$GLOBALS['maqTpl']->assign('start', $start);
					$GLOBALS['maqTpl']->assign('order', $order);
					$GLOBALS['maqTpl']->assign('sort', $sort);
					$GLOBALS['maqTpl']->assign('filter', $filter);
					$GLOBALS['maqTpl']->assign('xoConfig', $GLOBALS['xoopsModuleConfig']);
										
					$criteria->setStart($start);
					$criteria->setLimit($limit);
					$criteria->setSort('`'.$sort.'`');
					$criteria->setOrder($order);
						
					$gallerys = $gallery_handler->getObjects($criteria, true);
					foreach($gallerys as $gid => $gallery) {
						if (is_object($gallery))					
							$GLOBALS['maqTpl']->append('galleries', $gallery->toArray());
					}
					$GLOBALS['maqTpl']->assign('form', maquee_gallery_get_form(false));
					$GLOBALS['maqTpl']->assign('php_self', $_SERVER['PHP_SELF']);
					$GLOBALS['maqTpl']->display('db:maquee_cpanel_GALLERY_list.html');
					break;		
					
				case "new":
				case "edit":
					
					maquee_adminMenu(2);
					
					include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
					include_once $GLOBALS['xoops']->path( "/class/template.php" );
					$GLOBALS['maqTpl'] = new XoopsTpl();
					
					$gallery_handler =& xoops_getmodulehandler('gallery', 'maquee');
					if (isset($_REQUEST['id'])) {
						$gallery = $gallery_handler->get(intval($_REQUEST['id']));
					} else {
						$gallery = $gallery_handler->create();
					}
					
					$GLOBALS['maqTpl']->assign('form', $gallery->getForm());
					$GLOBALS['maqTpl']->assign('php_self', $_SERVER['PHP_SELF']);
					$GLOBALS['maqTpl']->display('db:maquee_cpanel_GALLERY_edit.html');
					break;
				case "save":
					
					$gallery_handler =& xoops_getmodulehandler('gallery', 'maquee');
					$id=0;
					if ($id=intval($_REQUEST['id'])) {
						$gallery = $gallery_handler->get($id);
					} else {
						$gallery = $gallery_handler->create();
					}
					$gallery->setVars($_POST[$id]);
					
					$uploader = new MaqueeXoopsMediaUploader($GLOBALS['xoopsModuleConfig']['uploaddir'], explode('|', $GLOBALS['xoopsModuleConfig']['allowed_mimetypes']), $GLOBALS['xoopsModuleConfig']['maximum_filesize'], 0, 0, explode('|', $GLOBALS['xoopsModuleConfig']['allowed_file_extensions']));
					$uploader->setPrefix(substr(md5(microtime(true), mt_rand(0,20), 11)));
					  
					if ($uploader->fetchMedia('imagefile')) {
					  	if (!$uploader->upload()) {
					    	if ($gallery->isNew()) {	
					    		maquee_adminMenu(2);
					       		echo $uploader->getErrors();
								maquee_footer_adminMenu();
								xoops_cp_footer();
								exit(0);
					  		}     
					    } else {
					      	if (!$gallery->isNew())
					      		unlink($gallery->getVar('path').(substr($gallery->getVar('path'), strlen($gallery->getVar('path'))-1, 1)!=DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:'').$gallery->getVar('filename'));
					      	
					      	$gallery->setVar('path', $GLOBALS['xoopsModuleConfig']['uploaddir']);
					      	$gallery->setVar('filename', $uploader->getSavedFileName());
					      	
					      	$filename = explode('.', $uploader->getSavedFileName());
					      	$gallery->setVar('extension', $filename[sizeof($filename)]);
					      	
					      	if ($dimension = getimagesize($gallery->getVar('path').(substr($gallery->getVar('path'), strlen($gallery->getVar('path'))-1, 1)!=DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:'').$gallery->getVar('filename'))) {
					      		$gallery->setVar('width', $dimension[0]);
					      		$gallery->setVar('height', $dimension[1]);
					      	} else {
					      		$gallery->setVar('width', 0);
					      		$gallery->setVar('height', 0);
					      	}
					    }      	
				  	} else {
				  		if ($gallery->isNew()) {	
				    		maquee_adminMenu(2);
				       		echo $uploader->getErrors();
							maquee_footer_adminMenu();
							xoops_cp_footer();
							exit(0);
				  		}
				  	}

					if (!$id=$gallery_handler->insert($gallery)) {
						redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_GALLERY_FAILEDTOSAVE);
						exit(0);
					} else {
						switch($_REQUEST['mode']) {
							case 'new':
								redirect_header('index.php?op='.$op.'&fct=edit&id='.$id, 10, _AM_MSG_GALLERY_SAVEDOKEY);
								break;
							default:
							case 'edit':
								redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_GALLERY_SAVEDOKEY);
								break;
						}
						exit(0);
					}
					break;
				case "savelist":
					
					$gallery_handler =& xoops_getmodulehandler('gallery', 'maquee');
					foreach($_REQUEST['id'] as $id) {
						$gallery = $gallery_handler->get($id);
						$gallery->setVars($_POST[$id]);
						if (!$gallery_handler->insert($gallery)) {
							redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_GALLERY_FAILEDTOSAVE);
							exit(0);
						} 
					}
					redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_GALLERY_SAVEDOKEY);
					exit(0);
					break;				
				case "delete":	
								
					$gallery_handler =& xoops_getmodulehandler('gallery', 'maquee');
					$id=0;
					if (isset($_POST['id'])&&$id=intval($_POST['id'])) {
						$gallery = $gallery_handler->get($id);
						if (!$gallery_handler->delete($gallery)) {
							redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_GALLERY_FAILEDTODELETE);
							exit(0);
						} else {
							redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_GALLERY_DELETED);
							exit(0);
						}
					} else {
						$gallery = $gallery_handler->get(intval($_REQUEST['id']));
						xoops_confirm(array('id'=>$_REQUEST['id'], 'op'=>$_REQUEST['op'], 'fct'=>$_REQUEST['fct'], 'limit'=>$_REQUEST['limit'], 'start'=>$_REQUEST['start'], 'order'=>$_REQUEST['order'], 'sort'=>$_REQUEST['sort'], 'filter'=>$_REQUEST['filter']), 'index.php', sprintf(_AM_MSG_GALLERY_DELETE, $gallery->getVar('filename')));
					}
					break;
			}
			break;
		case "text":	
			switch ($fct)
			{
				default:
				case "list":				
					maquee_adminMenu(3);

					include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
					include_once $GLOBALS['xoops']->path( "/class/template.php" );
					
					$GLOBALS['maqTpl'] = new XoopsTpl();
					
					$text_handler =& xoops_getmodulehandler('text', 'maquee');
					$criteria = $text_handler->getFilterCriteria($filter);
					$ttl = $text_handler->getCount($criteria);
					$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
					
					$pagenav = new XoopsPageNav($ttl, $limit, $start, 'start', 'limit='.$limit.'&sort='.$sort.'&order='.$order.'&op='.$op.'&fct='.$fct.'&filter='.$filter.'&fct='.$fct.'&filter='.$filter);
					$GLOBALS['maqTpl']->assign('pagenav', $pagenav->renderNav());
					
					foreach (array(	'tid','mid','weight','language','html','title','url','created','updated') as $id => $key) {
						$GLOBALS['maqTpl']->assign(strtolower(str_replace('-','_',$key).'_th'), '<a href="'.$_SERVER['PHP_SELF'].'?start='.$start.'&limit='.$limit.'&sort='.str_replace('_','-',$key).'&order='.((str_replace('_','-',$key)==$sort)?($order=='DESC'?'ASC':'DESC'):$order).'&op='.$op.'&filter='.$filter.'">'.(defined('_AM_MAQUEE_TH_'.strtoupper(str_replace('-','_',$key)))?constant('_AM_MAQUEE_TH_'.strtoupper(str_replace('-','_',$key))):'_AM_MAQUEE_TH_'.strtoupper(str_replace('-','_',$key))).'</a>');
						$GLOBALS['maqTpl']->assign('filter_'.strtolower(str_replace('-','_',$key)).'_th', $text_handler->getFilterForm($filter, $key, $sort, $op, $fct));
					}
					
					$GLOBALS['maqTpl']->assign('limit', $limit);
					$GLOBALS['maqTpl']->assign('start', $start);
					$GLOBALS['maqTpl']->assign('order', $order);
					$GLOBALS['maqTpl']->assign('sort', $sort);
					$GLOBALS['maqTpl']->assign('filter', $filter);
					$GLOBALS['maqTpl']->assign('xoConfig', $GLOBALS['xoopsModuleConfig']);
					
					$criteria->setStart($start);
					$criteria->setLimit($limit);
					$criteria->setSort('`'.$sort.'`');
					$criteria->setOrder($order);
					
					$texts = $text_handler->getObjects($criteria, true);
					foreach($texts as $cid => $text) {
						if (is_object($text))
							$GLOBALS['maqTpl']->append('texts', $text->toArray());
					}
					
					$GLOBALS['maqTpl']->assign('form', maquee_text_get_form(false));
					$GLOBALS['maqTpl']->assign('php_self', $_SERVER['PHP_SELF']);
					$GLOBALS['maqTpl']->display('db:maquee_cpanel_text_list.html');
					break;		
					
				case "new":
				case "edit":
					
					maquee_adminMenu(3);
					
					include_once $GLOBALS['xoops']->path( "/class/pagenav.php" );
					include_once $GLOBALS['xoops']->path( "/class/template.php" );
					$GLOBALS['maqTpl'] = new XoopsTpl();
					
					$text_handler =& xoops_getmodulehandler('text', 'maquee');
					if (isset($_REQUEST['id'])) {
						$text = $text_handler->get(intval($_REQUEST['id']));
					} else {
						$text = $text_handler->create();
					}
					
					$GLOBALS['maqTpl']->assign('form', $text->getForm());
					$GLOBALS['maqTpl']->assign('php_self', $_SERVER['PHP_SELF']);
					$GLOBALS['maqTpl']->display('db:maquee_cpanel_text_edit.html');
					break;
				case "save":
					
					$text_handler =& xoops_getmodulehandler('text', 'maquee');
					$id=0;
					if ($id=intval($_REQUEST['id'])) {
						$text = $text_handler->get($id);
					} else {
						$text = $text_handler->create();
					}
					$text->setVars($_POST[$id]);
					if (!$id=$text_handler->insert($text)) {
						redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_TEXT_FAILEDTOSAVE);
						exit(0);
					} else {
						switch($_REQUEST['mode']) {
							case 'new':
								redirect_header('index.php?op='.$op.'&fct=edit&id='.$id, 10, _AM_MSG_TEXT_SAVEDOKEY);
								break;
							default:
							case 'edit':
								redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_TEXT_SAVEDOKEY);
								break;
						}
						exit(0);					
					}
					break;
				case "savelist":
					
					$text_handler =& xoops_getmodulehandler('text', 'maquee');
					foreach($_REQUEST['id'] as $id) {
						$text = $text_handler->get($id);
						$text->setVars($_POST[$id]);
						if (!$text_handler->insert($text)) {
							redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_TEXT_FAILEDTOSAVE);
							exit(0);
						} 
					}
					redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_TEXT_SAVEDOKEY);
					exit(0);
					break;				
				case "delete":	
								
					$text_handler =& xoops_getmodulehandler('text', 'maquee');
					$id=0;
					if (isset($_POST['id'])&&$id=intval($_POST['id'])) {
						$text = $text_handler->get($id);
						if (!$text_handler->delete($text)) {
							redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_TEXT_FAILEDTODELETE);
							exit(0);
						} else {
							redirect_header('index.php?op='.$op.'&fct=list&limit='.$limit.'&start='.$start.'&order='.$order.'&sort='.$sort.'&filter='.$filter, 10, _AM_MSG_TEXT_DELETED);
							exit(0);
						}
					} else {
						$text = $text_handler->get(intval($_REQUEST['id']));
						xoops_confirm(array('id'=>$_REQUEST['id'], 'op'=>$_REQUEST['op'], 'fct'=>$_REQUEST['fct'], 'limit'=>$_REQUEST['limit'], 'start'=>$_REQUEST['start'], 'order'=>$_REQUEST['order'], 'sort'=>$_REQUEST['sort'], 'filter'=>$_REQUEST['filter']), 'index.php', sprintf(_AM_MSG_TEXT_DELETE, $text->getVar('title')));
					}
					break;
			}
			break;
	}
	
	maquee_footer_adminMenu();
	xoops_cp_footer();
?>