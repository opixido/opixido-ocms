<?php 
/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * File Name: config.php
 * 	Configuration file
 * 
 * File Authors:
 * 		Grant French (grant@mcpuk.net)
 */
 
 
session_start();

/*------------------------------------------------------------------------------*/
/* HTTP over SSL Detection (shouldnt require changing)				*/
/*------------------------------------------------------------------------------*/
$fckphp_config['prot']="http";
$fckphp_config['prot'].=((isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']=='on')?"s":"");
$fckphp_config['prot'].="://";
/*==============================================================================*/

$s = $_SERVER['SCRIPT_FILENAME'];
$s = str_replace($_SERVER['DOCUMENT_ROOT'], '', $s);
$s = substr($s,0,strpos($s,'/admin/'));


/*------------------------------------------------------------------------------*/
/* The physical path to the document root, Set manually if not using apache	*/
/*------------------------------------------------------------------------------*/
$fckphp_config['basedir']=$_SERVER['DOCUMENT_ROOT'].''.$s.'/rtefiles';
$fckphp_config['basedir'] = str_replace('//','/',$fckphp_config['basedir']);
//echo '-'.$fckphp_config['basedir'].'-';
//$fckphp_config['basedir'] = 'D:\Work\FCKEditor\www\FCKeditor.V2\editor\filemanager\browser\mcpuk' ;
/*==============================================================================*/
//mail('conort@gmail.com',$s, $_SERVER['SCRIPT_FILENAME'].'    -    '.$_SERVER['DOCUMENT_ROOT'].'   - '.$fckphp_config['basedir']);

/*------------------------------------------------------------------------------*/
/* Prefix added to image path before sending back to editor			*/
/*------------------------------------------------------------------------------*/
$fckphp_config['urlprefix']=$fckphp_config['prot'].str_replace('//','/',$_SERVER['SERVER_NAME'].'/'.$s.'/rtefiles');

/*==============================================================================*/


/*------------------------------------------------------------------------------*/
/* Path to user files relative to the document root (no trailing slash)		*/
/*------------------------------------------------------------------------------*/
$fckphp_config['UserFilesPath'] = "" ;
/*==============================================================================*/


/*------------------------------------------------------------------------------*/
/* Progressbar handler (script that monitors upload progress) (''=none)
/*------------------------------------------------------------------------------*/
 $fckphp_config['uploadProgressHandler']=''; //No upload progress handler
//$fckphp_config['uploadProgressHandler']=$fckphp_config['prot'].$_SERVER['SERVER_NAME']."/cgi-bin/progress.cgi"; //Perl upload progress handler
/*==============================================================================*/


/*------------------------------------------------------------------------------*/
/* Authentication (auth) :-								*/
/*  - Req		:: Boolean, whether authentication is required		*/
/*  - HandlerClass	:: Name of class to handle authentication in connector	*/
/*------------------------------------------------------------------------------*/
$fckphp_config['auth']['Req']=false;
$fckphp_config['auth']['HandlerClass']='Default';
/*==============================================================================*/


/*------------------------------------------------------------------------------*/
/* Settings for authentication handler :-					*/
/*  - SharedKey :: Shared encryption key (as set in test.php in example)	*/
/*------------------------------------------------------------------------------*/
$fckphp_config['auth']['Handler']['SharedKey']="->Sh4red_K3y-FoR*5enD1NG^auth3nt1caT10n'Info/To\FILE,Brow5er--!";
/*==============================================================================*/


/*------------------------------------------------------------------------------*/
/* Per resource area settings:-							*/
/* - AllowedExtensions	:: Array, allowed file extensions (in lowercase)	*/
/* - AllowedMIME	:: Array, allowed mime types (in lowercase)		*/
/* - MaxSize		:: Number, Maximum size of file uploads in KBytes	*/
/* - DiskQuota		:: Number, Maximum size allowed for the resource area	*/
/* - HideFolders	:: Array, RegExp, matching folder names will be hidden	*/
/* - HideFiles		:: Array, RegExp, matching file names will be hidden	*/
/* - AllowImageEditing	:: Boolean, whether images in this area may be edited	*/
/*------------------------------------------------------------------------------*/
//First area options are commented

//File Area
$fckphp_config['ResourceAreas']['files'] =array(
	
	//Files(identified by extension) that may be uploaded to this area
	'AllowedExtensions'	=>	array("jpg","gif","jpeg","png","tiff","tif","doc","pdf","xls","zip","exe",'docx','xls','xlsx','ppt','pptx','ods'),
	
	//Not implemented yet
	'AllowedMIME'		=>	array(),
	
	//Set the maximum single upload to this area to 2MB (2048Kb)
	'MaxSize'		=>	20048,
	
	//Set disk quota for this resource area to 20MB
	'DiskQuota'		=>	-1,
	
	//By Default hide all folders starting with a . (Unix standard)
	'HideFolders'		=>	array("^\."), 
	
	//By Default hide all files starting with a . (Unix standard)
	'HideFiles'		=>	array("^\."), 
	
	//Do not allow images to be edited in this resource area
	'AllowImageEditing'	=>	false
	);

//Image area
$fckphp_config['ResourceAreas']['images'] =array(
	'AllowedExtensions'	=>	array("jpg","gif","jpeg","png","tiff","tif",),
	'AllowedMIME'		=>	array(),
	'MaxSize'		=>	10024,
	'DiskQuota'		=>	-1,
	'HideFolders'		=>	array("^\."),
	'HideFiles'		=>	array("^\."),
	'AllowImageEditing'	=>	false //Not yet complete, but you can take a look and see
	);

/*==============================================================================*/		


/*------------------------------------------------------------------------------*/
/* Global Disk Quota - Max size of all resource areas				*/
/*------------------------------------------------------------------------------*/
$fckphp_config['DiskQuota']['Global']=-1; //In MBytes (default: 50mb)
/*==============================================================================*/


/*------------------------------------------------------------------------------*/
/* Directory and File Naming :-							*/
/*  -MaxDirNameLength	:: Maximum allowed length of a directory name		*/
/*  -DirNameAllowedChars :: Array of characters allowed in a directory name	*/
/*  -FileNameAllowedChars :: Array of characters allowed in a file name		*/
/*------------------------------------------------------------------------------*/

$fckphp_config['MaxDirNameLength']=25;

$fckphp_config['DirNameAllowedChars']=array();

	//Allow numbers
	for($i=48;$i<58;$i++) array_push($fckphp_config['DirNameAllowedChars'],chr($i));
	
	//Allow lowercase letters
	for($i=97;$i<123;$i++) array_push($fckphp_config['DirNameAllowedChars'],chr($i));
	
	//Allow uppercase letters
	for($i=65;$i<91;$i++) array_push($fckphp_config['DirNameAllowedChars'],chr($i));
	
	//Allow space,dash,underscore,dot
	array_push($fckphp_config['DirNameAllowedChars']," ","-","_",".");
	
$fckphp_config['FileNameAllowedChars']=$fckphp_config['DirNameAllowedChars'];
array_push($fckphp_config['FileNameAllowedChars'],')','(','[',']','~');
/*==============================================================================*/


/*------------------------------------------------------------------------------*/
/* Debugging :-									*/
/*  - Debug	:: Boolean, if set to true a copy of the connector output is 	*/
/*			sent to a file as well as to the client.		*/
/*  - DebugOutput :: File to send debug output to (absolute path)		*/
/*------------------------------------------------------------------------------*/

$fckphp_config['Debug']=false;
$fckphp_config['DebugOutput']="/home/zouzou/hp/DEBUG.TXT";

#Log PHP errors
$fckphp_config['Debug_Errors']=true;
$fckphp_config['Debug_Trace']=true;

#Log Connector output
$fckphp_config['Debug_Output']=true;

#With each logged event display contents of
/* $_GET */ $fckphp_config['Debug_GET']=false;
/* $_POST */ $fckphp_config['Debug_POST']=false;
/* $_SERVER */ $fckphp_config['Debug_SERVER']=false;
/* $_SESSIONS */ $fckphp_config['Debug_SESSIONS']=false;

/*==============================================================================*/


/*------------------------------------------------------------------------------*/
/* Internals :-									*/
/*	ResourceTypes :: Array of valid resource areas				*/
/*	Commands :: Array of valid commands accepted by the connector		*/
/*------------------------------------------------------------------------------*/
$fckphp_config['ResourceTypes'] = array('files','images','Flash','Media');
$fckphp_config['Commands'] = array(
				"CreateFolder",
				"GetFolders",
				"GetFoldersAndFiles",
				"FileUpload",
				"Thumbnail",
				"DeleteFile",
				"DeleteFolder",

				"RenameFile",
				"RenameFolder"
				);
/*==============================================================================*/

?>