<?php

$delete_files = false;
$create_folders = false;
$delete_folders = false;
$upload_files = false;
$rename_files = false;
$rename_folders = false;
$duplicate_files = false;
$create_text_files = false;
$edit_text_files = false;
$copy_cut_files = false;
$copy_cut_dirs = false;

$_folders = glob($current_path . '*', GLOB_ONLYDIR);
foreach ($_folders as $_folder) {
    $_folder = basename($_folder);
    if ($_folder == 'General' || $_folder == $_SESSION['gs_adminuser']) {

    } else {
        $hidden_folders[] = $_folder;
    }
}
