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

class genRte
{

    var $toolbar;

    public function __construct($toolbar = 'Default', $tabContent = '')
    {
        $this->toolbar = $toolbar;
        $this->tabContent = $tabContent;
    }

    function gen()
    {

        if (isset($_REQUEST['validRte']) || isset($_REQUEST['validRteClose']) || isset($_REQUEST['validRte_x']) || isset($_REQUEST['validRteClose_x'])) {
            $this->validRte();
        }

        if (isset($_REQUEST['validRteClose']) || isset($_REQUEST['validRteClose_x'])) {
            print('<script type="text/javascript">
			
			window.opener.document.getElementById("prevrte_' . $_REQUEST['champ'] . '").innerHTML = "' .
                limitWords(trim(str_replace(array('"', "\n", "\r"), array('\"', " ", " "), (strip_tags($_REQUEST['content'])))), 50) . '";
			top.close();
			
			</script>');
        } else
            $this->createRte($content);
    }

    function createRte()
    {
        print '
		    <form action="?popup=true&doRte=true" method="post" name="fromRte" id="formRte">

		    <input type="hidden" name="champ" value="' . $_REQUEST['champ'] . '"/>
		    <input type="hidden" name="table" value="' . $_REQUEST['table'] . '"/>
		    <input type="hidden" name="pk" value="' . $_REQUEST['pk'] . '"/>
		    <input type="hidden" name="id" value="' . $_REQUEST['id'] . '"/>

		    <script language="javascript" type="text/javascript" src="./tinymce/tiny_mce.js"></script>
			<script language="javascript" type="text/javascript">

			';
        /*
          print '
          tinyMCE.init({
          mode : "exact",
          elements : "content",
          theme : "advanced",
          plugins : "table,save,advhr,advlink,emotions,iespell,insertdatetime,preview,zoom,searchreplace,print,contextmenu,paste,directionality,fullscreen,styleselect",
          theme_advanced_buttons1 : "",
          theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,zoom,separator",
          theme_advanced_buttons2_add_before: "styleselect,bold,italic,underline,cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
          theme_advanced_buttons3_add_before : "tablecontrols,separator",
          theme_advanced_buttons3_add : "iespell,separator,print,separator,fullscreen",
          theme_advanced_toolbar_location : "top",
          theme_advanced_toolbar_align : "left",
          theme_advanced_disable : "image",
          content_css : "http://www.ined.loc/css/rte.css",
          theme_advanced_styles : "Texte Bleu=texte_bleu;Texte orange=texte_orange",
          plugin_insertdate_dateFormat : "%d/%m/%Y",
          plugin_insertdate_timeFormat : "%H:%M:%S",
          inline_styles : true,
          convert_newlines_to_brs : true,
          language : "fr",

          external_link_list_url : "example_link_list.js",
          });

          </script>
          ';
         */

        print '

		    tinyMCE.init({
		mode : "exact",
		elements : "content",
		theme : "advanced",
		entity_encoding : "raw",
		plugins : "xhtmlxtras,advhr,accessilink,emotions,iespell,insertdatetime,preview,zoom,searchreplace,print,contextmenu,paste,fullscreen,styleselect",
		language : "en",
		theme_advanced_buttons1 : "bold,italic,underline,separator,accessilink,unlink,separator,pastetext,separator,bullist,separator,code,separator,sub,sup,abbr,acronym",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "",
	    plugi2n_insertdate_dateFormat : "%d/%m/%Y",
	    plugi2n_insertdate_timeFormat : "%H:%M:%S",
	    relative_urls : false , 

		paste_use_dialog : false,

		theme_advanced_resize_horizontal : false,

		paste_auto_cleanup_on_paste : true,
		paste_convert_headers_to_strong : true,
		paste_strip_class_attributes : "all",
		paste_remove_spans : true,
		paste_remove_styles : true,
		
		
		convert_fonts_to_spans : true,
		verify_html : false,
valid_elements : ""
+"a[accesskey|charset|class|coords|dir<ltr?rtl|href|hreflang|id|lang|name"
  +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rel|rev"
  +"|shape<circle?default?poly?rect|style|tabindex|title|target|type],"
+"abbr[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"acronym[class|dir<ltr?rtl|id|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"address[class|align|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"applet[align<bottom?left?middle?right?top|alt|archive|class|code|codebase"
  +"|height|hspace|id|name|object|style|title|vspace|width],"
+"area[accesskey|alt|class|coords|dir<ltr?rtl|href|id|lang|nohref<nohref"
  +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup"
  +"|shape<circle?default?poly?rect|style|tabindex|title|target],"
+"base[href|target],"
+"basefont[color|face|id|size],"
+"bdo[class|dir<ltr?rtl|id|lang|style|title],"
+"big[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"blockquote[dir|style|cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
  +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
  +"|onmouseover|onmouseup|style|title],"
+"body[alink|background|bgcolor|class|dir<ltr?rtl|id|lang|link|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onload|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|onunload|style|title|text|vlink],"
+"br[class|clear<all?left?none?right|id|style|title],"
+"button[accesskey|class|dir<ltr?rtl|disabled<disabled|id|lang|name|onblur"
  +"|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|tabindex|title|type"
  +"|value],"
+"caption[align<bottom?left?right?top|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"center[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"cite[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"code[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"col[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|span|style|title"
  +"|valign<baseline?bottom?middle?top|width],"
+"colgroup[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl"
  +"|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|span|style|title"
  +"|valign<baseline?bottom?middle?top|width],"
+"dd[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
+"del[cite|class|datetime|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"dfn[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"dir[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"div[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"dl[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"dt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
+"em/i[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"fieldset[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"font[class|color|dir<ltr?rtl|face|id|lang|size|style|title],"
+"form[accept|accept-charset|action|class|dir<ltr?rtl|enctype|id|lang"
  +"|method<get?post|name|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onreset|onsubmit"
  +"|style|title|target],"
+"frame[class|frameborder|id|longdesc|marginheight|marginwidth|name"
  +"|noresize<noresize|scrolling<auto?no?yes|src|style|title],"
+"frameset[class|cols|id|onload|onunload|rows|style|title],"
+"h1[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"h2[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"h3[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"h4[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"h5[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"h6[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"head[dir<ltr?rtl|lang|profile],"
+"hr[align<center?left?right|class|dir<ltr?rtl|id|lang|noshade<noshade|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|size|style|title|width],"
+"html[dir<ltr?rtl|lang|version],"
+"iframe[align<bottom?left?middle?right?top|class|frameborder|height|id"
  +"|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style"
  +"|title|width],"
+"img[align<bottom?left?middle?right?top|alt|border|class|dir<ltr?rtl|height"
  +"|hspace|id|ismap<ismap|lang|longdesc|name|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|src|style|title|usemap|vspace|width],"
+"input[accept|accesskey|align<bottom?left?middle?right?top|alt"
  +"|checked<checked|class|dir<ltr?rtl|disabled<disabled|id|ismap<ismap|lang"
  +"|maxlength|name|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onselect"
  +"|readonly<readonly|size|src|style|tabindex|title"
  +"|type<button?checkbox?file?hidden?image?password?radio?reset?submit?text"
  +"|usemap|value],"
+"ins[cite|class|datetime|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"isindex[class|dir<ltr?rtl|id|lang|prompt|style|title],"
+"kbd[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"label[accesskey|class|dir<ltr?rtl|for|id|lang|onblur|onclick|ondblclick"
  +"|onfocus|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
  +"|onmouseover|onmouseup|style|title],"
+"legend[align<bottom?left?right?top|accesskey|class|dir<ltr?rtl|id|lang"
  +"|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"li[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title|type"
  +"|value],"
+"link[charset|class|dir<ltr?rtl|href|hreflang|id|lang|media|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|rel|rev|style|title|target|type],"
+"map[class|dir<ltr?rtl|id|lang|name|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"menu[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"meta[content|dir<ltr?rtl|http-equiv|lang|name|scheme],"
+"noframes[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"noscript[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"object[align<bottom?left?middle?right?top|archive|border|class|classid"
  +"|codebase|codetype|data|declare|dir<ltr?rtl|height|hspace|id|lang|name"
  +"|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|standby|style|tabindex|title|type|usemap"
  +"|vspace|width],"
+"ol[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|start|style|title|type],"
+"optgroup[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"option[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick|ondblclick"
  +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
  +"|onmouseover|onmouseup|selected<selected|style|title|value],"
+"p[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"param[id|name|type|value|valuetype<DATA?OBJECT?REF],"
+"pre/listing/plaintext/xmp[align|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
  +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
  +"|onmouseover|onmouseup|style|title|width],"
+"q[cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"s[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
+"samp[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"script[charset|defer|language|src|type],"
+"select[class|dir<ltr?rtl|disabled<disabled|id|lang|multiple<multiple|name"
  +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|size|style"
  +"|tabindex|title],"
+"small[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"span[align|class|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"strike[class|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"strong/b[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"style[dir<ltr?rtl|lang|media|title|type],"
+"sub[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"sup[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"table[align<center?left?right|bgcolor|border|cellpadding|cellspacing|class"
  +"|dir<ltr?rtl|frame|height|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rules"
  +"|style|summary|title|width],"
+"tbody[align<center?char?justify?left?right|char|class|charoff|dir<ltr?rtl|id"
  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
  +"|valign<baseline?bottom?middle?top],"
+"td[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
  +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
  +"|style|title|valign<baseline?bottom?middle?top|width],"
+"textarea[accesskey|class|cols|dir<ltr?rtl|disabled<disabled|id|lang|name"
  +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onselect"
  +"|readonly<readonly|rows|style|tabindex|title],"
+"tfoot[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
  +"|valign<baseline?bottom?middle?top],"
+"th[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
  +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
  +"|style|title|valign<baseline?bottom?middle?top|width],"
+"thead[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
  +"|valign<baseline?bottom?middle?top],"
+"title[dir<ltr?rtl|lang],"
+"tr[abbr|align<center?char?justify?left?right|bgcolor|char|charoff|class"
  +"|rowspan|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title|valign<baseline?bottom?middle?top],"
+"tt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
+"u[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
+"ul[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title|type],"
+"var[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title]"
		
		  

		  
		
	});



	</script>
	';


        /*
          theme_advanced_styles : "Institut=rte_rub_1;Recherche en cours=rte_rub_2;Ressources et documentation=rte_rub_3;Population en chiffres=rte_rub_4;Tout savoir sur la population=rte_rub_5",

          content_css : "http://www.ined.loc/css/rte.css",

          valid_elements : this.valid_elements+""
          +"a[accesskey|charset|class|coords|dir<ltr?rtl|href|hreflang|id|lang|name"
          +"|onblur|onclick|onfocus"
          +"|rel|rev"
          +"|shape<circle?default?poly?rect|style|tabindex|title|target|type],"
          +"abbr[class|dir<ltr?rtl|id|lang|onclick"
          +"|style"
          +"|title],"
          +"acronym[class|dir<ltr?rtl|id|id|lang|onclick"
          +"|style"
          +"|title],"
          +"br[class|clear<all?left?none?right|id|style|title],"
          +"em/i[class|dir<ltr?rtl|id|lang|onclick|style|title],"

          +"strong[class|dir<ltr?rtl|id|lang|onclick|style|title],"
          +"span[align|class|class|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"



          separator,separator,outdent,indent,
          tablecontrols
          link,unlink,anchor,separator
          file_browser_callback : "fileBrowserCallBack",
          verify_html : false,
          valid_elements : ""
          +"a[accesskey|charset|class|coords|dir<ltr?rtl|href|hreflang|id|lang|name"
          +"|onblur|onclick|onfocus"
          +"|rel|rev"
          +"|shape<circle?default?poly?rect|style|tabindex|title|target|type],"
          +"abbr[class|dir<ltr?rtl|id|lang|onclick"
          +"|style"
          +"|title],"
          +"acronym[class|dir<ltr?rtl|id|id|lang|onclick"
          +"|style"
          +"|title],"
          +"address[class|align|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"
          +"base[href|target],"


          +"br[class|clear<all?left?none?right|id|style|title],"
          +"caption[align<bottom?left?right?top|class|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"
          +"cite[class|dir<ltr?rtl|id|lang|onclick"
          +"|style"
          +"|title],"
          +"code[class|dir<ltr?rtl|id|lang|onclick"
          +"|style"
          +"|title],"
          +"col[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
          +"|lang|onclick"
          +"|span|style|title"
          +"|valign<baseline?bottom?middle?top|width],"
          +"colgroup[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl"
          +"|id|lang|onclick"
          +"|span|style|title"
          +"|valign<baseline?bottom?middle?top|width],"
          +"dd[class|dir<ltr?rtl|id|lang|onclick"
          +"|style|title],"
          +"del[cite|class|datetime|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"
          +"dfn[class|dir<ltr?rtl|id|lang|onclick"
          +"|style"
          +"|title],"
          +"dir[class|compact<compact|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"
          +"div[class|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"
          +"dl[class|compact<compact|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"
          +"dt[class|dir<ltr?rtl|id|lang|onclick"
          +"|style|title],"
          +"em/i[class|dir<ltr?rtl|id|lang|onclick"
          +"|style"
          +"|title],"
          +"fieldset[class|dir<ltr?rtl|id|lang|onclick"
          +"|style"
          +"|title],"

          +"h1[class|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"
          +"strong[class|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"
          +"h2[class|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"
          +"h3[class|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"
          +"h4[class|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"
          +"h5[class|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"
          +"h6[class|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"
          +"head[dir<ltr?rtl|lang|profile],"
          +"hr[class|dir<ltr?rtl|id|lang|noshade<noshade|onclick"
          +""
          +"|size|style|title|width],"
          +"html[dir<ltr?rtl|lang|version],"
          +"iframe[align<bottom?left?middle?right?top|class|frameborder|height|id"
          +"|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style"
          +"|title|width],"
          +"img[align<bottom?left?middle?right?top|alt|class|dir<ltr?rtl|height"
          +"|hspace|id|ismap<ismap|lang|longdesc|name|onclick"
          +""
          +"|src|style|title|usemap|vspace|width],"
          +"input[accept|accesskey|align<bottom?left?middle?right?top|alt"
          +"|checked<checked|class|dir<ltr?rtl|disabled<disabled|id|ismap<ismap|lang"
          +"|maxlength|name|onblur|onclick|onfocus"
          +"|onselect"
          +"|readonly<readonly|size|src|style|tabindex|title"
          +"|type<button?checkbox?file?hidden?image?password?radio?reset?submit?text"
          +"|usemap|value],"
          +"ins[cite|class|datetime|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"
          +"isindex[class|dir<ltr?rtl|id|lang|prompt|style|title],"
          +"kbd[class|dir<ltr?rtl|id|lang|onclick"
          +"|style"
          +"|title],"
          +"label[accesskey|class|dir<ltr?rtl|for|id|lang|onblur|onclick"
          +"|onfocus"
          +"|style|title],"
          +"legend[align<bottom?left?right?top|accesskey|class|dir<ltr?rtl|id|lang"
          +"|onclick"
          +"|style|title],"
          +"li[class|dir<ltr?rtl|id|lang|onclick"
          +"|style|title|type"
          +"|value],"
          +"link[charset|class|dir<ltr?rtl|href|hreflang|id|lang|media|onclick"
          +""
          +"|rel|rev|style|title|target|type],"
          +"map[class|dir<ltr?rtl|id|lang|name|onclick"
          +"|style"
          +"|title],"
          +"menu[class|compact<compact|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"
          +"meta[content|dir<ltr?rtl|http-equiv|lang|name|scheme],"
          +"noframes[class|dir<ltr?rtl|id|lang|onclick"
          +"|style"
          +"|title],"
          +"noscript[class|dir<ltr?rtl|id|lang|onclick"
          +"|style"
          +"|title],"
          +"object[align<bottom?left?middle?right?top|archive|class|classid"
          +"|codebase|codetype|data|declare|dir<ltr?rtl|height|hspace|id|lang|name"
          +"|onclick"
          +"|standby|style|tabindex|title|type|usemap"
          +"|vspace|width],"
          +"ol[class|compact<compact|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|start|style|title|type],"
          +"optgroup[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick"
          +""
          +"|style|title],"
          +"option[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick"
          +""
          +"|selected<selected|style|title|value],"
          +"p[class|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"
          +"param[id|name|type|value|valuetype<DATA?OBJECT?REF],"
          +"pre/listing/plaintext/xmp[align|class|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title|width],"
          +"q[cite|class|dir<ltr?rtl|id|lang|onclick"
          +"|style"
          +"|title],"
          +"s[class|dir<ltr?rtl|id|lang|onclick"
          +"|style|title],"
          +"samp[class|dir<ltr?rtl|id|lang|onclick"
          +"|style"
          +"|title],"
          +"script[charset|defer|language|src|type],"
          +"select[class|dir<ltr?rtl|disabled<disabled|id|lang|multiple<multiple|name"
          +"|onblur|onclick|onfocus"
          +"|size|style"
          +"|tabindex|title],"
          +"small[class|dir<ltr?rtl|id|lang|onclick"
          +"|style"
          +"|title],"
          +"span[align|class|class|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"
          +"strike[class|class|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title],"

          +"style[dir<ltr?rtl|lang|media|title|type],"
          +"sub[class|dir<ltr?rtl|id|lang|onclick"
          +"|style"
          +"|title],"
          +"sup[class|dir<ltr?rtl|id|lang|onclick"
          +"|style"
          +"|title],"
          +"table[align<center?left?right|cellpadding|cellspacing|class"
          +"|dir<ltr?rtl|frame|height|id|lang|onclick"
          +"|rules"
          +"|style|summary|title|width],"
          +"tbody[align<center?char?justify?left?right|char|class|charoff|dir<ltr?rtl|id"
          +"|lang|onclick"
          +"|style|title"
          +"|valign<baseline?bottom?middle?top],"
          +"td[abbr|align<center?char?justify?left?right|axis|char|charoff|class"
          +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
          +""
          +"|rowspan|scope<col?colgroup?row?rowgroup"
          +"|style|title|valign<baseline?bottom?middle?top|width],"
          +"textarea[accesskey|class|cols|dir<ltr?rtl|disabled<disabled|id|lang|name"
          +"|onblur|onclick|onfocus"
          +"|onselect"
          +"|readonly<readonly|rows|style|tabindex|title],"
          +"tfoot[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
          +"|lang|onclick"
          +"|style|title"
          +"|valign<baseline?bottom?middle?top],"
          +"th[abbr|align<center?char?justify?left?right|axis|char|charoff|class"
          +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
          +""
          +"|rowspan|scope<col?colgroup?row?rowgroup"
          +"|style|title|valign<baseline?bottom?middle?top|width],"
          +"thead[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
          +"|lang|onclick"
          +"|style|title"
          +"|valign<baseline?bottom?middle?top],"
          +"title[dir<ltr?rtl|lang],"
          +"tr[abbr|align<center?char?justify?left?right|char|charoff|class"
          +"|rowspan|dir<ltr?rtl|id|lang|onclick"
          +"|style"
          +"|title|valign<baseline?bottom?middle?top],"
          +"tt[class|dir<ltr?rtl|id|lang|onclick"
          +"|style|title],"
          +"u[class|dir<ltr?rtl|id|lang|onclick"
          +"|style|title],"
          +"ul[class|compact<compact|dir<ltr?rtl|id|lang|onclick"
          +""
          +"|style|title|type],"




          //		theme_advanced_buttons1_add : "fontselect,fontsizeselect",
          //theme_advanced_buttons1_add_before : "save,newdocument,separator",
          //,forecolor,backcolor

          function fileBrowserCallBack(field_name, url, type) {
          // This is where you insert your custom filebrowser logic
          alert("Filebrowser callback: " + field_name + "," + url + "," + type);
          }


         */
        $this->instanceRte();

        print '<br>
		     <label for="validRte"
		     class="abutton"
		     style="float:left">
		     <input class="inputimage" src="' . t('src_save') . '"
		     type="image" id="validRte" name="validRte"
		     value="Enregistrer et rester sur la page" />
		     Enregistrer et rester sur la page</label />

		     <label for="validRteClose"
		     class="abutton"
		     style="float:left">
		     <input class="inputimage" src="' . t('src_saveas') . '"
		     type="image" id="validRteClose" name="validRteClose"
		     value="Enregistrer et retourner au formulaire" />
		     Enregistrer  et retourner au formulaire</label />


		    </form>
		';
    }

    function instanceRte()
    {
        /*
          $oFCKeditor = new FCKeditor('rteText') ;
          $oFCKeditor->BasePath = '';
          $oFCKeditor->Value = $this->getTextToEdit();
          $oFCKeditor->Width  = '100%' ;
          $oFCKeditor->Height = '500' ;
          return $oFCKeditor->Create() ;
         */
        $text = (strlen($this->tabContent) > 1) ? $this->tabContent : $this->getTextToEdit();

        /* if(!trim($text))
          $text = '<p>&nbsp;Lorem Ipsum</p>';
         */
        //width:100%;height:540px
        print('<textarea name="content" id="content" 
		style="position:absolute;top:0;bottom:120px;right:20px;left:0px;" > ' . $text . ' </textarea >');
    }

    function getTextToEdit()
    {
        if ($_REQUEST['id'] != 'new') {
            /* $sql = 'select ' .$_REQUEST['champ'] .' as text from ' .$_REQUEST['table'] .' where ' .$_REQUEST['pk'] .'=' .$_REQUEST['id'];
              $res =  GetSingle($sql);
             */
            $text = getTradValue($_REQUEST['table'], $_REQUEST['id'], $_REQUEST['champ']);
            return stripslashes($text);
        } else {
            return stripslashes($_SESSION["genform_" . $_REQUEST['table']][$_REQUEST['champ']]);
        }
    }

    function validRte()
    {
        $config = array(
            'indent' => true,
            'output-xhtml' => 'no',
            'output-html' => 'no',
            'output-xml' => 'no',
            'doctype' => 'omit',
            'show-body-only' => true
        );

        // Tidy

        /* if(class_exists('tidy') ) {
          $tidy = new tidy();
          $tidy->parseString($_REQUEST['content'], $config, 'utf8');
          $tidy->cleanRepair();

          $_REQUEST['content'] = trim($tidy);
          }
         */
        $_REQUEST['content'] = str_replace('target="_blank"', 'onclick="return doblank(this);"', stripslashes($_REQUEST['content']));

        if (strlen(trim(strip_tags($_REQUEST['content']))) > 1 && strpos($_REQUEST['content'], '<p>') === false) {
            $_REQUEST['content'] = '<p>' . $_REQUEST['content'] . '</p>';
        }

        /* if(trim($_REQUEST['content']) == '<p>&nbsp;Lorem Ipsum</p>')
          $_REQUEST['content'] = '';
         */
        if ($_REQUEST['id'] != 'new') {
            if (!$_REQUEST['table']) {

                echo ' ERROR : NO TABLE SPECIFIED : ' .
                    print_r($_REQUEST);
                print_r($_SERVER);
            }
            if (!updateLgField($_REQUEST['table'], $_REQUEST['id'], $_REQUEST['champ'], $_REQUEST['content'])) {

                $sql = 'update ' . $_REQUEST['table'] . ' SET ' . $_REQUEST['champ'] . '="' . addslashes($_REQUEST['content']) . '" where ' . $_REQUEST['pk'] . '=' . $_REQUEST['id'] . ' ';
                $res = doSql($sql);
            }
        } else {
            $_SESSION["genform_" . $_REQUEST['table']][$_REQUEST['champ']] = $_REQUEST['content'];
        }
    }

}

?>
