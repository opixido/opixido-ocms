<html>
<head>
    <style type="text/css">
        html, body {
            margin: 0;
            padding: 0;
        }

        * {
            box-sizing: border-box;
        }
    </style>
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-image-editor/latest/tui-image-editor.css">

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/3.3.2/fabric.js"></script>
    <script src="https://uicdn.toast.com/tui.code-snippet/v1.5.0/tui-code-snippet.min.js"></script>
    <script src="https://uicdn.toast.com/tui-color-picker/v2.2.3/tui-color-picker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.3/FileSaver.min.js"></script>
    <script src="https://uicdn.toast.com/tui-image-editor/latest/tui-image-editor.js"></script>
    <!--   <script src="../dist/tui-image-editor.js"></script>
       <script src="./js/theme/white-theme.js"></script>
       <script src="./js/theme/black-theme.js"></script>-->
</head>
<body>
<div id="tui-image-editor"></div>
<script>
    var imageEditor = new tui.ImageEditor(document.querySelector('#tui-image-editor'), {
        includeUI: {
            loadImage: {
                path: "https://<?=$_SERVER['HTTP_HOST'] . $_REQUEST['img']?>",
                name: "oCMS"
            },
            theme: {
                'common.bi.image': 'https://uicdn.toast.com/toastui/img/tui-image-editor-bi.png',
                'common.bisize.width': '251px',
                'common.bisize.height': '21px',
                'common.backgroundImage': 'none',
                'common.backgroundColor': '#1e1e1e',
                'common.border': '0px',

                // header
                'header.backgroundImage': 'none',
                'header.backgroundColor': 'transparent',
                'header.border': '0px',

                // load button
                'loadButton.backgroundColor': '#fff',
                'loadButton.border': '1px solid #ddd',
                'loadButton.color': '#222',
                'loadButton.fontFamily': '\'Noto Sans\', sans-serif',
                'loadButton.fontSize': '12px',

                // download button
                'downloadButton.backgroundColor': '#fdba3b',
                'downloadButton.border': '1px solid #fdba3b',
                'downloadButton.color': '#fff',
                'downloadButton.fontFamily': '\'Noto Sans\', sans-serif',
                'downloadButton.fontSize': '12px',

                // main icons
                'menu.normalIcon.path': './img/icon-d.svg',
                'menu.normalIcon.name': 'icon-d',
                'menu.activeIcon.path': './img/icon-b.svg',
                'menu.activeIcon.name': 'icon-b',
                'menu.disabledIcon.path': './img/icon-a.svg',
                'menu.disabledIcon.name': 'icon-a',
                'menu.hoverIcon.path': './img/icon-c.svg',
                'menu.hoverIcon.name': 'icon-c',
                'menu.iconSize.width': '24px',
                'menu.iconSize.height': '24px',

                // submenu primary color
                'submenu.backgroundColor': '#1e1e1e',
                'submenu.partition.color': '#3c3c3c',

                // submenu icons
                'submenu.normalIcon.path': './img/icon-d.svg',
                'submenu.normalIcon.name': 'icon-d',
                'submenu.activeIcon.path': './img/icon-c.svg',
                'submenu.activeIcon.name': 'icon-c',
                'submenu.iconSize.width': '32px',
                'submenu.iconSize.height': '32px',

                // submenu labels
                'submenu.normalLabel.color': '#8a8a8a',
                'submenu.normalLabel.fontWeight': 'lighter',
                'submenu.activeLabel.color': '#fff',
                'submenu.activeLabel.fontWeight': 'lighter',

                // checkbox style
                'checkbox.border': '0px',
                'checkbox.backgroundColor': '#fff',

                // range style
                'range.pointer.color': '#fff',
                'range.bar.color': '#666',
                'range.subbar.color': '#d1d1d1',

                'range.disabledPointer.color': '#414141',
                'range.disabledBar.color': '#282828',
                'range.disabledSubbar.color': '#414141',

                'range.value.color': '#fff',
                'range.value.fontWeight': 'lighter',
                'range.value.fontSize': '11px',
                'range.value.border': '1px solid #353535',
                'range.value.backgroundColor': '#151515',
                'range.title.color': '#fff',
                'range.title.fontWeight': 'lighter',

                // colorpicker style
                'colorpicker.button.border': '1px solid #1e1e1e',
                'colorpicker.title.color': '#fff'
            },
            menuBarPosition: 'left',
            usageStatistics: false
        },
        usageStatistics: false,

    });


    $('.tui-image-editor-header-logo img').attr('src', '../img/logo.png').css('width', 'auto').css('height', 'auto');
    $('.tui-image-editor-header-buttons > *').remove();
    $('.tui-image-editor-header-buttons').append('<button class="tui-image-editor-download-btn" style="background: #00b4fa;border: none;border-radius: 5px;">Valider</button>');

    $('.tui-image-editor-download-btn').on('click', saveTUI);

    function saveTUI() {

        var newURL = imageEditor.toDataURL();
        $.ajax({
            type: "POST",
            url: "../index.php?xhr=uploadBase64",
            data: {
                image: newURL,
                curTable: "<?=($_REQUEST['curTable'])?>",
                curChamp: "<?=($_REQUEST['curChamp'])?>",
                curName: "<?=($_REQUEST['curName'])?>",
                curId: "<?=(int)$_REQUEST['curId']?>"
            }
        }).done(function (msg) {
            window.opener.$('#lgfield_<?= ($_REQUEST['curChamp'])?> .genform_uploadfile').replaceWith(msg);
            window.close();
        });
        return false;
    }
</script>
</body>
</html>