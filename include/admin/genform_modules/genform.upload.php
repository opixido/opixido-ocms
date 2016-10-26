<?php

#
# This file is part of oCMS.
#
# oCMS is free software: you cgan redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# oCMS is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with oCMS. If not, see <http://www.gnu.org/licenses/>.
#
# @author Celio Conort / Opixido 
# @copyright opixido 2012
# @link http://code.google.com/p/opixido-ocms/
# @package ocms
#
global $_Gconfig;
/* Upload particulier */
$gf = new GenFile($this->table_name, $name, $this->id, $this->tab_default_field[ $name ]);

$chemin = $gf->GetWebUrl();
$systemCh = $gf->GetSystemPath();


$doCloseDiv = false;

if (isset($_Gconfig['fileListingFromFolder'][ $this->table_name ]) && ($fileListing = akev($_Gconfig['fileListingFromFolder'][ $this->table_name ], getBaseLgField($name))) && !$this->editMode) {

    $files = glob($fileListing, GLOB_BRACE);

    $this->addBuffer('<div>');
    $this->addBuffer('<select id="genform_' . $name . '_fromfolder" name="genform_' . $name . '_fromfolder">');
    $this->addBuffer('<option value=""> -- ' . t('choisir_dossier') . ' -- </option>');
    $this->addBuffer('<option value="-1"> -- ' . t('delier') . ' -- </option>');
    foreach ($files as $file) {
        $this->addBuffer('<option ' . (($systemCh == $file) ? 'selected="selected"' : '') . ' value=' . alt(basename($file)) . ' >' . basename($file) . '</option>');
    }
    $this->addBuffer('</select>');
    $this->addBuffer('</div>');

    $this->addBuffer('<div id="genform_' . $name . '_divupload">');
    $doCloseDiv = true;
    $_SESSION[ gfuid() ]['curFields'][] = $name . "_fromfolder";
}

if (!$this->onlyData) {
    $this->addBuffer('<div class="upload_fileview">');
}
if ($this->tab_default_field[ $name ]) {


    if (!$this->onlyData) {
        if ($this->editMode) {

            /*
             *
             * On est en visualisation  seulement
             *
             */
            if ($this->isImage($systemCh)) {

                /*
                 *
                 * C'est une image , donc on affiche le thumbnail
                 *
                 */
                $this->addBuffer('<a href="' . $chemin . '" target="_blank">');

                /*
                 * AVEC THUMBS / GD ?
                 */
                if ($this->useThumbs) {
                    $this->addBuffer('<img src="' . $gf->getThumbUrl($this->thumbWidth, $this->thumbHeight) . '" />');
                } else {
                    /*
                     * Sans gd : resize via le navigateur
                     */
                    $this->addBuffer('<img src="' . $chemin . '" width="' . $this->thumbWidth . '" />');
                }
                $this->addBuffer('</a> ');
            } else
                if ($gf->getExtension() == 'flv') {

                    $this->addBuffer(' <a href="' . $chemin . '" target="_blank">' . $this->trad('voir') . '</a> ');
                } else {

                    /*
                     *  Ce n'est pas une image donc on affiche le lien uniquement
                     */
                    $this->addBuffer(' <a href="' . $chemin . '" target="_blank">' . $this->trad('voir') . '</a> ');
                }
        } else {

            /*
             *
             * MODIFICATION
             *
             */


            $this->genHelpImage('help_file', $name);

            $this->addBuffer($gf->genAdminTag());

            $_SESSION[ gfuid() ]['curFields'][] = $name . "_del";
        }
    } else {
        /* Si only Data : on retourne juste l'url */

        if ($this->isImage($systemCh)) {

            /*
             *
             * C'est une image , donc on affiche le thumbnail
             *
             */
            $this->addBuffer('<a href="' . $chemin . '" target="_blank">');

            /*
             * AVEC THUMBS / GD ?
             */
            if ($this->useThumbs) {
                $this->addBuffer('<img alt="" src="' . $gf->getThumbUrl($this->smallThumbWidth, $this->smallThumbHeight) . '"/>');
            } else {
                /*
                 * Sans gd : resize via le navigateur
                 */
                $this->addBuffer('<img src="' . $chemin . '" width="' . $this->thumbWidth . '" />');
            }
            $this->addBuffer('</a> ');
        } else if($gf->isVideo() ){ 
            
            $this->addBuffer(' <a href="' . $chemin . '" target="_blank">' . $this->trad('voir') . '</a> ');
            $this->addBuffer(' <video preload="none" width="'.$this->smallThumbWidth.'" controls src="' . $chemin . '"></video> ');
            
        } else {

            /*
             *  Ce n'est pas une image donc on affiche le lien uniquement
             */
            $this->addBuffer(' <a href="' . $chemin . '" target="_blank">' . $this->trad('voir') . '</a> ');
        }
    }
}
if (!$this->onlyData) {
    $this->addBuffer('</div>');
}
if (!$this->editMode) {


    if (true) {
        $_SESSION[ gfuid() ]['curFields'][] = $name . "_importmanager";
        $this->addBuffer('
                    <div id="container_' . $name . '" class="upload_container">
                        <div id="filelist_' . $name . '" class="upload_filelist"></div>
                            <input type="hidden" value="" id="genform_' . $name . '_importmanager" name="genform_' . $name . '_importmanager"  />
                          <a class="btn btn-inverse" id="genform_' . $name . '_importmanager_link" href="./filemanager/dialog.php?popup=1&type=2&field_id=genform_' . $name . '_importmanager"><img src="' . path_concat(ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_ARBO_SIZE . '/actions/document-open.png') . '" alt="" /> ' . t('choisir') . '</a>  
                        <a class="btn btn-inverse" id="pickfiles_' . $name . '" href="javascript:;"><img src="' . path_concat(ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_ARBO_SIZE . '/actions/document-save-as.png') . '" alt="" /> ' . t('upload_parcourir') . ' </a>
                        
                            <div class="clearer"></div>
                    </div>
<script type="text/javascript">
    $("#genform_' . $name . '_importmanager_link").click(function(e) {
        e.preventDefault();
        var w = window.open(this.href,\'pick\',\'width=900,height=600\');
        w.focus();
        var inp = $("#genform_' . $name . '_importmanager");
            var vali = inp.val();
            $(window).off("focus.filemanager");
            $(window).on("focus.filemanager",function(){
                $(window).off("focus.filemanager");
                if(inp.val() != vali) {
                    var field = inp.closest(".genform_champ_out").attr("id").replace("genform_div_","");
                    genformReloadField(field);
                }
            });
        return false;
    });


     $("#pickfiles_' . $name . '").on("hover dragover dragenter",function() {
        if(window.uploader_' . $name . ') {
             return;
        } 
        $("#pickfiles_' . $name . '").unbind("hover dragover dragenter").hover(refreshUploaders);
        window.uploader_' . $name . ' = new plupload.Uploader({
                runtimes : "html5,flash,gears,silverlight,html4",
                browse_button : "pickfiles_' . $name . '",
                container: "container_' . $name . '",
                max_file_size : "5000mb",
                drop_element: "container_' . $name . '",
                url : "index.php",
                chunk_size : "2mb",
                flash_swf_url : "' . BU . '/admin/plupload/js/plupload.flash.swf",
                silverlight_xap_url : "../js/plupload.silverlight.xap",
                headers:{champ:"' . $name . '",curTable:"' . $this->table_name . '",curId:"' . $this->id . '",xhr:"upload",relOne:' . sql(akev($_REQUEST, 'relOne')) . '},
                multipart_params:{champ:"' . $name . '",curTable:"' . $this->table_name . '",curId:"' . $this->id . '",xhr:"upload",relOne:' . sql(akev($_REQUEST, 'relOne')) . '},
                button_browse_hover : true,
                multiple_queues: true,
                multi_selection : false,
                max_file_count : 1
       });
        window.uploaders.push(window.uploader_' . $name . ');
        window.uploader_' . $name . '.bind("FileUploaded", function (up,file,resp) {
                if(resp.response.indexOf("<div") != -1) {
                    $("#container_' . $name . '").prev().html(resp.response);
                    $("#filelist_' . $name . '").html("");
                }
                window.filesUploading--;
                if(window.filesUploading == 0) {
                        window.onbeforeunload = function(){};
                }
                refreshUploaders();
            });
        window.startUpload' . $name . ' = function () {           
            window.uploader_' . $name . '.settings.multipart_params.curId = $("#curId").val();
            window.uploader_' . $name . '.settings.headers.curId = $("#curId").val();
            window.uploader_' . $name . '.start();
        }
        window.uploader_' . $name . '.bind("FilesAdded", function(up, files) {
                var i = 0;
                if($("#curId").val() == "new") {
                    doSaveAllAndStay(window.startUpload' . $name . ');
                } else {
                    setTimeout("window.startUpload' . $name . '()",500);
                }
                window.filesUploading++;
                
                refreshUploaders();
                $("#filelist_' . $name . '").html("<div class=\'well\' id=\'" + files[i].id + "\'><span class=\'badge\'>" + files[i].name + " (" + plupload.formatSize(files[i].size) + ")  </span> <div class=\'progress progress-striped active\'>Initialisation du transfert <img src=\"img/loading.gif\" alt=\"\" /></div></div>");
        });

        window.uploader_' . $name . '.bind("UploadProgress", function(up, file) {
                $("#"+file.id+" div.progress").html("<div class=\'bar\' style=\"width:"+(file.percent)+"%\">" + file.percent + "%</span>");
                refreshUploaders();
        });
        window.uploader_' . $name . '.init();
        $("#container_' . $name . '")
        .bind("dragenter dragover", function(e) {
            $(this).addClass("hover");
            e.preventDefault();
        })
        .bind("dragexit dragleave drop dragend mouseleave", function(e) {
            $(this).removeClass("hover");
            e.preventDefault();
        });
    });
</script>
                    ');
        return;
    } else {
        /* Bouton parcourir pour uploader le fichier */

        $this->addBuffer("<label for='genform_" . $name . "'>");
        if ($this->tab_default_field[ $name ])
            $this->addBuffer(t('fichier_remplacer'));
        else
            $this->addBuffer(t('fichier_uploader'));

        // debug($name .' ' .getBaseLgField($name));

        if (@ake($_Gconfig['imageAutoResize'], getBaseLgField($name))) {
            $sizes = $_Gconfig['imageAutoResize'][ getBaseLgField($name) ];
            $this->addBuffer(' <span class="light">[' . t('max_size') . ' ' . $sizes[0] . ' x ' . $sizes[1] . ' px]</span>');
        } else
            if (@ake($_Gconfig['imageAutoResizeExact'], getBaseLgField($name))) {
                $sizes = $_Gconfig['imageAutoResizeExact'][ getBaseLgField($name) ];
                $this->addBuffer(' <span class="light">[' . t('exact_size') . ' ' . $sizes[0] . ' x ' . $sizes[1] . ' px]</span>');
            }


        $this->addBuffer(' </label> <br/>');
        $this->addBuffer('

        <input type="file" class="fileupload" id="genform_' . $name . '" name="genform_' . $name . '"  />

    <label class="btn">
    <input class="inputimage" type="image" value="" src="' . t('src_upload') . '"  name="genform_stay"  />
     ' . $this->trad('mettre_en_ligne') . '</label>');


        if (is_dir($_Gconfig['ftpUpload_path'])) {

            //debug(scandir($_Gconfig['ftpUpload_path']));
            $liste = $GLOBALS['gb_obj']->getFileListing($_Gconfig['ftpUpload_path'], false);


            if (count($liste)) {
                $ij = 0;

                $_SESSION[ gfuid() ]['curFields'][] = $name . "_importftp";
                $_SESSION[ gfuid() ]['curFields'][] = $name . "_importftp_x";

                $this->addBuffer('
                    <img class="inputimage" onclick="showHide(\'filelisting_' . $name . '\')" type="image" src="' . t('src_importftp') . '"  alt="' . t('import_from_ftp') . '" />
                                      
                     <div class="filelisting" style="display:none;" id="filelisting_' . $name . '">');


                $this->addBuffer('<label for="genform_' . $name . '_import_' . $ij . '"  ><input type="radio" checked="checked" id="genform_' . $name . '_import_' . $ij . '" name="genform_' . $name . '_importftp" value="0" />' . t('aucun') . '</label>');
                foreach ($liste as $f) {
                    $ij++;
                    $this->addBuffer('<label for="genform_' . $name . '_import_' . $ij . '"><input type="radio" id="genform_' . $name . '_import_' . $ij . '" name="genform_' . $name . '_importftp" value="' . $f . '" />' . $f . '</label>');
                }


                $this->addBuffer('
    <label class="abutton"  style="float:none;width:70px;">
    <input class="inputimage" type="image" value="" src="' . t('src_copy') . '"  name="genform_stay"  />
     ' . $this->trad('copier') . '</label>');

                $this->addBuffer('</div>');
            }
        }
    }
}

if ($doCloseDiv) {
    $this->addBuffer('</div>');
}
