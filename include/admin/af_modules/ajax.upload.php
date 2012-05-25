<?php

class ajaxUpload {

    function __construct($af, $champ) {

        $this->af = $af;
        $this->champ = $champ;
        $this->row = $af->row;
        $this->table = $af->table;
        $this->id = $af->id;

        $this->champ_id = $this->table . '_' . $champ . '_' . $this->id;
    }

    function gen() {
        $name = $this->champ . '_' . $this->id;
        $gf = $this->getCurrent();

        $html = $gf->genSmallAdminTag().'
                    <div id="container_' . $name . '" class="upload_container" style="border:0">

                        <a class="btn btn-mini" id="pickfiles_' . $name . '" href="javascript:;"><img src="' . path_concat(ADMIN_PICTOS_FOLDER . ADMIN_PICTOS_ARBO_SIZE . '/actions/document-save-as.png') . '" alt="" /> ' . t('upload_parcourir') . ' </a>
                        <div id="filelist_' . $name . '" class="upload_filelist"></div>
                            <div class="clearer"></div>
                    </div>
<script type="text/javascript">
    $("#pickfiles_' . $name . '").hover(function() {
        $(this).unbind("hover");
        window.uploader_' . $name . ' = new plupload.Uploader({
                runtimes : "gears,flash,html5,silverlight",
                browse_button : "pickfiles_' . $name . '",
                container: "container_' . $name . '",
                max_file_size : "5000mb",
                drop_element: "container_' . $name . '",
                url : "index.php",
                chunk_size : "2mb",
                flash_swf_url : "' . BU . '/admin/plupload/js/plupload.flash.swf",
                silverlight_xap_url : "../js/plupload.silverlight.xap",
                headers:{type:"small",champ:"' . $this->champ . '",curTable:"' . $this->table . '",curId:"' . $this->id . '",xhr:"upload"},
                multipart_params:{type:"small",champ:"' . $this->champ . '",curTable:"' . $this->table . '",curId:"' . $this->id . '",xhr:"upload"},
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
                        window.onbeforeunload = false;
                }
                refreshUploaders();
            });
        window.startUpload' . $name . ' = function () {           
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
                window.onbeforeunload = beforeUnloadUploading;
                $("#filelist_' . $name . '").html("<div id=\'" + files[i].id + "\'>" + files[i].name + " (" + plupload.formatSize(files[i].size) + ") <b>Initialisation du transfert <img src=\"img/loading.gif\" alt=\"\" /></b></div>");
        });

        window.uploader_' . $name . '.bind("UploadProgress", function(up, file) {
                $("#"+file.id+" b").html("<span style=\"width:"+(file.percent*2)+"px\">" + file.percent + "%</span>");
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
</script>';

        return $html;
    }

    /**
     * Retourne un objet genfile
     *
     * @return genfile
     */
    function getCurrent() {

        $gf = new genFile($this->table, $this->champ, $this->id, $this->row);
        return $gf;
    }

}

