<?php 
	
	require_once('../header.php');
		 
	$mid = (isset($_REQUEST['mid'])?intval($_REQUEST['mid']):exit(0));
	$block = (isset($_REQUEST['block'])?intval($_REQUEST['block']):exit(0));
	
	$maquee_handler = xoops_getmodulehandler('maquee', 'maquee');
	$maquee = $maquee_handler->get($mid);	
	
	header('Content-type: text/css');
	
	if (is_object($maquee)) {
?>@CHARSET "ISO-8859-1";
ul#<?php echo $maquee->getReference($block); ?> {
<?php echo $GLOBALS['xoopsModuleConfig']['css']."\n"; ?>
background: <?php echo $maquee->getVar('bgcolour'); ?>;
}
<?php } ?>