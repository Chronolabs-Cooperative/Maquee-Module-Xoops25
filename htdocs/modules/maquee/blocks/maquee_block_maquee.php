<?php

function b_maquee_block_maquee_show( $options )
{
	if (empty($options[0])||$options[0]==0)
		return false;
				
	$maquee_handler =& xoops_getmodulehandler('maquee', 'maquee');
	
	$maquee = $maquee_handler->get($options[0]);
	
	$GLOBALS['xoTheme']->addScript('', array('type'=>'text/javascript'), $maquee->getJS(true));
	
	return $maquee->getMaquee(true);

}


function b_maquee_block_maquee_edit( $options )
{
	include_once($GLOBALS['xoops']->path('/modules/maquee/include/formobjects.maquee.php'));

	$maquee = new MaqueeFormSelectMaquee('', 'options[0]', $options[0], 1, false);
	$form = ""._BL_MAQUEE_MID."&nbsp;".$maquee->render();

	return $form ;
}

?>