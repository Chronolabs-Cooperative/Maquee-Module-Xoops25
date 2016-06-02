<?php

	//Primary Defines
	define('_MI_MAQUEE_NAME','JQuery Marquee');
	define('_MI_MAQUEE_DESCRIPTION','Jquery Marquee for images or news ticker');
	define('_MI_MAQUEE_DIRNAME','maquee');

	// Default Langauges
	define('_MI_MAQUEE_NONE','None');
	
	// Admin menu
	define('_MI_MAQUEE_TITLE_ADMENU1','Marquee\'s');
	define('_MI_MAQUEE_ICON_ADMENU1','');
	define('_MI_MAQUEE_LINK_ADMENU1','admin/index.php?op=maquee&fct=list');
	define('_MI_MAQUEE_TITLE_ADMENU2','Image Marquee Gallery');
	define('_MI_MAQUEE_ICON_ADMENU2','');
	define('_MI_MAQUEE_LINK_ADMENU2','admin/index.php?op=gallery&fct=list');
	define('_MI_MAQUEE_TITLE_ADMENU3','Textual Marquee\'s');
	define('_MI_MAQUEE_ICON_ADMENU3','');
	define('_MI_MAQUEE_LINK_ADMENU3','admin/index.php?op=text&fct=list');
	
	// Preferences
	define('_MI_MAQUEE_EDITOR','Editor to Use');
	define('_MI_MAQUEE_EDITOR_DESC','This is the editor to use for ticker items');
	define('_MI_MAQUEE_DEFAULT_ANIMATION_TYPE','Default Animation Type');
	define('_MI_MAQUEE_DEFAULT_ANIMATION_TYPE_DESC','This is the default animation type to display.');
	define('_MI_MAQUEE_DEFAULT_SEQUENCE','DEFAULT Sequence Type');
	define('_MI_MAQUEE_DEFAULT_SEQUENCE_DESC','This is the default type of sequencing to use for the animation.');
	define('_MI_MAQUEE_DEFAULT_SPEED','Default Speed');
	define('_MI_MAQUEE_DEFAULT_SPEED_DESC','Default speed of the animation in milliseconds.');
	define('_MI_MAQUEE_DEFAULT_TIMEOUT','Default Timeout');
	define('_MI_MAQUEE_DEFAULT_TIMEOUT_DESC','Default timeout between sequences of the animation in milliseconds.');
	define('_MI_MAQUEE_DEFAULT_WIDTH','Default Width');
	define('_MI_MAQUEE_DEFAULT_WIDTH_DESC','This is the default width of the maquee');
	define('_MI_MAQUEE_DEFAULT_HEIGHT','Default Height');
	define('_MI_MAQUEE_DEFAULT_HEIGHT_DESC','This is the default height of the maquee');
	define('_MI_MAQUEE_UPLOADDIR','Upload Dir');
	define('_MI_MAQUEE_UPLOADDIR_DESC','Upload Real System Path for Images');
	define('_MI_MAQUEE_SCALE_IMAGES','Scale Images');
	define('_MI_MAQUEE_SCALE_IMAGES_DESC','Whether you wish to scale images to the maquee size!');
	define('_MI_MAQUEE_AUTO_CROP_IMAGES','Auto-crop the images');
	define('_MI_MAQUEE_AUTO_CROP_IMAGES_DESC','Whether you wish to auto crop the images for the maquee size after scaling!');
	define('_MI_MAQUEE_CSS','Maquee CSS');
	define('_MI_MAQUEE_CSS_DESC','This is the standalone css inclusion for the maquees');
	define('_MI_MAQUEE_ALLOWED_FILE_EXTENSION','Allowed Image File Extensions');
	define('_MI_MAQUEE_ALLOWED_FILE_EXTENSION_DESC','List of allowed file extensions for images seperated by a pipe symbol "|"');
	define('_MI_MAQUEE_ALLOWED_MIMETYPES','Allowed Image Mime-types');
	define('_MI_MAQUEE_ALLOWED_MIMETYPES_DESC','List of allowed file mime-types for images seperated by a pipe symbol "|"');
	define('_MI_MAQUEE_MAXIMUM_FILESIZE','Maximum Upload File Size');
	define('_MI_MAQUEE_MAXIMUM_FILESIZE_DESC','Total amount of bytes that an upload can be. (Default 2.5Mbs)');
	define('_MI_MAQUEE_THUMBNAIL_WIDTH','Thumbnail Width');
	define('_MI_MAQUEE_THUMBNAIL_WIDTH_DESC','This is the thumbnail width for images.');
	define('_MI_MAQUEE_THUMBNAIL_HEIGHT','Thumbnail Height');
	define('_MI_MAQUEE_THUMBNAIL_HEIGHT_DESC','This is the thumbnail height for images.');
	define('_MI_MAQUEE_FORCE_JQUERY','Force JQuery Loading with Theme');
	define('_MI_MAQUEE_FORCE_JQUERY_DESC','If your theme doesn\'t have JQuery included you will need to have this turned on');
	
	// Preference Options
	define('_MI_MAQUEE_DEFAULT_ANIMATION_TYPE_SLIDEDOWN','Slide Down Animation');
	define('_MI_MAQUEE_DEFAULT_ANIMATION_TYPE_FADEIN','Fade In Animation');
	define('_MI_MAQUEE_DEFAULT_ANIMATION_TYPE_SLIDEUP','Slide Up Animation');
	define('_MI_MAQUEE_DEFAULT_ANIMATION_TYPE_FADEOUT','Fade Out Animation');
	define('_MI_MAQUEE_DEFAULT_ANIMATION_TYPE_RANDOM','Randomly Selected');
	define('_MI_MAQUEE_DEFAULT_SEQUENCE_SEQUENCE','Weighted Sequence');
	define('_MI_MAQUEE_DEFAULT_SEQUENCE_RANDOM','Random Sequence');
	define('_MI_MAQUEE_DEFAULT_SEQUENCE_RANDOM_START','Random Start then weighted');
	
	//Framework Includes
	define('_MI_FRAMEWORK_WIDEIMAGE','/Frameworks/wideimage/WideImage.php');
	
	//Enumeration
	define('_MI_MAQUEE_TYPE_GALLERY','Gallery maquee');
	define('_MI_MAQUEE_TYPE_TICKER','Textual maquee');
	define('_MI_MAQUEE_ANIMATION_SLIDEUP','Slide up animation');
	define('_MI_MAQUEE_ANIMATION_FADEIN','Fade in animation');
	define('_MI_MAQUEE_ANIMATION_SLIDEDOWN','Slide down animation');
	define('_MI_MAQUEE_ANIMATION_FADEOUT','Fade out animation');
	define('_MI_MAQUEE_ANIMATION_RANDOM','Random selection');
	define('_MI_MAQUEE_ANIMATION_DEFAULT','Default Preferences');
	define('_MI_MAQUEE_SEQUENCE_SEQUENCE','Weighted sequence');
	define('_MI_MAQUEE_SEQUENCE_RANDOM','Random sequence');
	define('_MI_MAQUEE_SEQUENCE_RANDOM_START','Start randomly then sequenced');
	define('_MI_MAQUEE_SEQUENCE_DEFAULT','Default Preferences');
	
	//Include paths
	define('_MI_MAQUEE_JQUERY','/browse.php?Frameworks/jquery/jquery.js');
	define('_MI_MAQUEE_INNERFADE','/modules/maquee/js/innerfade.js');
	define('_MI_MAQUEE_CSSFORANIM','/modules/maquee/css/maquee.php?mid=%s&block=0');
	define('_MI_MAQUEE_CSSFORANIM_BLOCK','/modules/maquee/css/maquee.php?mid=%s&block=1')
	
?>