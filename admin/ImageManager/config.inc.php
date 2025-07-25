<?php

/**
 * Image Manager configuration file.
 * @author $Author: Wei Zhuo $
 * @version $Id: config.inc.php 27 2004-04-01 08:31:57Z Wei Zhuo $
 * @package ImageManager
 */
ini_set('memory_limit', '320M');
define('IN_ADMIN', true);

error_reporting(E_ALL && ~E_DEPRECATED);

/* On aura toujours besoin de ca */
require_once('../../vendor/autoload.php');
require_once('../../include/include.php');
$gb_obj = new genBase();
$gb_obj->includeConfig();
define('LG', LG_DEF);
$gb_obj->includeBase();
$gb_obj->includeGlobal();
$genMessages = new genMessages();
$gb_obj->includeAdmin();
$gs_obj = new genSecurity();
$gs_obj->needAuth();
clearstatcache();

global $_Gconfig;
/*
  File system path to the directory you want to manage the images
  for multiple user systems, set it dynamically.

  NOTE: This directory requires write access by PHP. That is,
  PHP must be able to create files in this directory.
  Able to create directories is nice, but not necessary.
 */
$IMConfig['base_dir'] = 'c:/www/assets/';
$IMConfig['base_dir'] = $_Gconfig['basePath'];
$IMConfig['base_dir'] = realpath(dirname($_SERVER['SCRIPT_FILENAME']) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

/*
  The URL to the above path, the web browser needs to be able to see it.
  It can be protected via .htaccess on apache or directory permissions on IIS,
  check you web server documentation for futher information on directory protection
  If this directory needs to be publicly accessiable, remove scripting capabilities
  for this directory (i.e. disable PHP, Perl, CGI). We only want to store assets
  in this directory and its subdirectories.
 */

//echo BU;
$IMConfig['base_url'] = BU . '/'; //BU;//$_Gconfig['baseWebPath'];
//$IMConfig['base_url'] = $_Gconfig['baseWebPath'];
//echo $IMConfig['base_url'];
/*
  Possible values: true, false

  TRUE - If PHP on the web server is in safe mode, set this to true.
  SAFE MODE restrictions: directory creation will not be possible,
  only the GD library can be used, other libraries require
  Safe Mode to be off.

  FALSE - Set to false if PHP on the web server is not in safe mode.
 */
$IMConfig['safe_mode'] = true;

/*
  Possible values: 'GD', 'IM', or 'NetPBM'

  The image manipulation library to use, either GD or ImageMagick or NetPBM.
  If you have safe mode ON, or don't have the binaries to other packages,
  your choice is 'GD' only. Other packages require Safe Mode to be off.
 */
define('IMAGE_CLASS', 'GD');


/*
  After defining which library to use, if it is NetPBM or IM, you need to
  specify where the binary for the selected library are. And of course
  your server and PHP must be able to execute them (i.e. safe mode is OFF).
  GD does not require the following definition.
 */
define('IMAGE_TRANSFORM_LIB_PATH', 'C:/"Program Files"/ImageMagick-5.5.7-Q16/');


/* ==============  OPTIONAL SETTINGS ============== */


/*
  The prefix for thumbnail files, something like .thumb will do. The
  thumbnails files will be named as "prefix_imagefile.ext", that is,
  prefix + orginal filename.
 */
$IMConfig['thumbnail_prefix'] = '.';

/*
  Thumbnail can also be stored in a directory, this directory
  will be created by PHP. If PHP is in safe mode, this parameter
  is ignored, you can not create directories.

  If you do not want to store thumbnails in a directory, set this
  to false or empty string '';
 */
$IMConfig['thumbnail_dir'] = '.thumbs';

/*
  Possible values: true, false

  TRUE -  Allow the user to create new sub-directories in the
  $IMConfig['base_dir'].

  FALSE - No directory creation.

  NOTE: If $IMConfig['safe_mode'] = true, this parameter
  is ignored, you can not create directories
 */
$IMConfig['allow_new_dir'] = true;

/*
  Possible values: true, false

  TRUE - Allow the user to upload files.

  FALSE - No uploading allowed.
 */
$IMConfig['allow_upload'] = true;

/*
  Possible values: true, false

  TRUE - If set to true, uploaded files will be validated based on the
  function getImageSize, if we can get the image dimensions then
  I guess this should be a valid image. Otherwise the file will be rejected.

  FALSE - All uploaded files will be processed.

  NOTE: If uploading is not allowed, this parameter is ignored.
 */
$IMConfig['validate_images'] = true;

/*
  The default thumbnail if the thumbnails can not be created, either
  due to error or bad image file.
 */
$IMConfig['default_thumbnail'] = 'img/default.gif';

/*
  Thumbnail dimensions.
 */
$IMConfig['thumbnail_width'] = 96;
$IMConfig['thumbnail_height'] = 96;

/*
  Image Editor temporary filename prefix.
 */
$IMConfig['tmp_prefix'] = '.editor_';
?>