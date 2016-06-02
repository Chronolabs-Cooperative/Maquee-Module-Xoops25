<?php
	require_once('header.php');
	
	$maquee_handler = xoops_getmodulehandler('maquee', 'maquee');
	$criteria = new Criteria('`default`', true);
	$maquees = $maquee_handler->getObjects($criteria, false);
	
	if (is_object($maquees[0])) {
		$xoopsOption['template_main'] = 'maquee_index.html';
		include($GLOBALS['xoops']->path('/header.php'));
		$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
		$GLOBALS['xoopsTpl']->assign('maquee', $maquees[0]->getMaquee());
		$GLOBALS['xoTheme']->addScript('', array('type'=>'text/javascript'), $maquees[0]->getJS());
		include($GLOBALS['xoops']->path('/footer.php'));
	} else {
		$xoopsOption['template_main'] = 'maquee_index.html';
		include($GLOBALS['xoops']->path('/header.php'));
		$GLOBALS['xoopsTpl']->assign('php_self', $_SERVER['PHP_SELF']);
		include($GLOBALS['xoops']->path('/footer.php'));
	}
	
?>