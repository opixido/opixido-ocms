/**
 * Le formulaire en cours correspond il à un enregistrement de la BDD
 * Ou bien est il pour l'instant sans identifiant ?
 * */
function isCurrentFormNew() {
    if ($('#curId').val() == 'new') {
        return true;
    } else {
        return false;
    }
}


/**
 * Insert un ID pour le formulaire en cours
 * et rempli le champ curId
 *
 * @callBack function
 *
 **/
function insertIdForNewForm(callBack) {
    if (!window.insertingNewIdForForm && isCurrentFormNew()) {
        window.insertingNewIdForForm = true;
        window.callbackAfterNewId = callBack;

        $.post('index.php', {
            xhr: "insertIdForNewForm",
            table: $('#curTable').val(),
            curId: 'new',
            'genform_fromForm': 1
        }, fillCurrentFormNewId);
    }
}


/**a
 * CallBack de insertIdForNewForm
 **/
function fillCurrentFormNewId(resp) {
    $('#curId').val(resp);
    if (window.callbackAfterNewId) {
        window.callbackAfterNewId();
    }
}

/**
 * Sauvegarde le formulaire et Recharge uniquement le champ field
 * @param {type} field
 * @returns {undefined}
 */
function genformReloadField(field) {
    var div = $("#genform_div_" + field);
    div.css('opacity', 0.5);
    doSaveAllAndStay(function () {
        $.get('?xhr=genformReloadField&curField=' + field + '&curTable=' + $('#curTable').val() + '&curId=' + $('#curId').val(), function (data) {
            div.replaceWith(data);
            if (window.currentLg) {
                showLgField(field, window.currentLg);
            } else {
                $("#genform_div_" + field).find('.lgbtn').eq(0).click();
            }
        });
    });

}

function deleteFile(table, champ, id, obj, small) {
    if (!small) {
        small = "";
    }
    $(obj).closest('.genform_uploadfile').load('index.php?xhr=deleteFile&curTable=' + table + '&curChamp=' + champ + '&curId=' + id + '&small=' + small + ' div.genform_uploadfile');
}


function arSaveValue(obj, table, champ, id, curtable) {

    if (obj.value) {
        v = obj.value;
    }
    else {
        v = obj;
    }

    //v = espace(v).replace(new RegExp( "\\+", "g" ), "%2B" );
    $.post("index.php", {
        xhr: "ajaxForm",
        table: table,
        champ: champ,
        id: id,
        save: v
    });

    return;
    url = "index.php?xhr=ajaxRelinv&table=" + table + "&field=" + champ + "&id=" + id + "&save=" + v + "&curtable=" + curtable;
    //alert(url);
    XHR(url);
}

function doSaveAllAndStay(func) {

    if ($("#genform_formulaire")[0]) {
        var saveImg = $('#genform_ok img');
        var curSrc = saveImg.attr('src');

        saveImg.attr('src', 'img/loading.gif');
        saveImg.css({'background': 'white', 'border-radius': '20px', 'padding': '3px'});

        $("#genform_stay").val("ajaxsave");

        $.post("index.php", $("#genform_formulaire").serialize(), function (data) {
            $("#curId").val($.trim(data));
            saveImg.attr('src', curSrc);

            saveImg.css({'background': 'none', 'border-radius': '0', 'padding': 0});
            $('body').removeClass('loading');
            if (func) {
                func();
            }
        });
        $("#genform_stay").val("");
    }
    return;
}

function arAddValue(obj, table, fake, id) {
    var tableId = $(obj).closest('table').attr('id');
    if (isCurrentFormNew()) {
        doSaveAllAndStay(function () {
            url = "index.php?xhr=ajaxRelinv&table=" + table + "&fake=" + fake + "&id=" + $('#curId').val();
            XHR(url, "", "", "addRowToTable(\'" + tableId + "\', http.responseText)");
        });
    }
    else {
        url = "index.php?xhr=ajaxRelinv&table=" + table + "&fake=" + fake + "&id=" + id;
        XHR(url, "", "", "addRowToTable(\'" + tableId + "\', http.responseText)");
    }
}

function addRowToTable(table, contenu) {
    $("#" + table + " tbody").append("" + contenu + "");
    $("#" + table + "").tableDnDUpdate();
    checkScripts($("#" + table + " tbody tr:last")[0]);
}

function is_ignorable(nod) {
    return (nod.nodeType == 8) || // A comment node
        ((nod.nodeType == 3) && is_all_ws(nod)); // a text node, all ws
}

function node_before(sib) {
    while ((sib = sib.previousSibling)) {
        if (!is_ignorable(sib))
            return sib;
    }
    return null;
}

function is_all_ws(nod) {
    // Use ECMA-262 Edition 3 String and RegExp features
    return !(/[^\t\n\r ]/.test(nod.data));
}

function node_after(sib) {
    while ((sib = sib.nextSibling)) {
        if (!is_ignorable(sib))
            return sib;
    }
    return null;
}

function arDelete(obj, faketable, id) {
    url = "index.php?xhr=ajaxRelinv&table=" + faketable + "&delete=" + id + "&";
    //obj.parentNode.parentNode.parentNode.parentNode.removeChild(obj.parentNode.parentNode.parentNode);
    XHR(url);
    $(obj).closest('tr').hide('normal', function () {
        $(this).remove()
    });
}

function arGoUp(obj) {
    var tbod = obj.parentNode.parentNode.parentNode;
    var tabl = tbod.parentNode;

    var prev = node_before(tbod);
    if (prev) {
        tabl.insertBefore(tbod, prev);
    }
}

function arGoDown(obj) {
    var tbod = obj.parentNode.parentNode.parentNode;
    var tabl = tbod.parentNode;
    var nex = node_after(tbod);

    if (nex) {
        tabl.insertBefore(nex, tbod);
    }

}


// SUPPRESSION D'UN NOEUD
function FArem(child, nom, id) {
    // si c'est le dernier enfant, on supprimer le UL au dessus
    if (child.parentNode.parentNode.childNodes.length == 1) {
        child.parentNode.parentNode.parentNode.removeChild(child.parentNode.parentNode);
    }
    // sinon juste le LI
    else {
        child.parentNode.parentNode.removeChild(child.parentNode);
    }
    return false;
}

// AJOUT D'UN NOEUD
function FAadd(obj, nom, id) {
    var ul = obj.parentNode.getElementsByTagName('ul')[0];
    var newLi = document.createElement("li");
    ul.appendChild(newLi);

    newLi.id = nom + "_" + Math.round(Math.random() * 1000000);

    XHR(ajaxActionUrl('add', window.arboFull[nom]['vtable'], id, (window.arboFull[nom])), '', newLi.id, 'checkUpDown();');

    //newLi.innerHTML = "csddsdfsd";
    return false;
}


function FAgoUp(obj, nom, id) {
    var tbod = obj.parentNode;
    var tabl = tbod.parentNode;

    var prev = node_before(tbod);

    if (prev) {
        ajaxAction('goup', window.arboFull[nom]['vtable'], id, window.arboFull[nom]);
        tabl.insertBefore(tbod, prev);
        checkUpDown(obj);
    }

    return false;

}


function FAgoDown(obj, nom, id) {
    var tbod = obj.parentNode;
    var tabl = tbod.parentNode;
    var nex = node_after(tbod);

    if (nex) {
        ajaxAction('godown', window.arboFull[nom]['vtable'], id, window.arboFull[nom]);
        tabl.insertBefore(nex, tbod);
        checkUpDown(obj);
    }
    return false;
}

function checkUpDown(obj) {
    var objs = getElementsByClassName('FAgoUp', 'a', gid("racine"));
    //alert(objs);
    for (p in objs) {
        if (node_before(objs[p].parentNode)) {
            objs[p].style.opacity = "1";
        } else {

            objs[p].style.opacity = "0.5";
        }
    }

    var objs = getElementsByClassName('FAgoDown', 'a', gid("racine"));
    for (p in objs) {
        if (node_after(objs[p].parentNode)) {
            objs[p].style.opacity = "1";
        } else {
            objs[p].style.opacity = "0.5";
        }
    }
//alert(objs);
}


function FAdel(obj, nom, id) {
    //url = "index.php?xhr=ajaxRelinv&table="+faketable+"&delete="+id+"&";
    ajaxAction('del', window.arboFull[nom]['vtable'], id);
    obj.parentNode.parentNode.removeChild(obj.parentNode);
    return false;
//XHR(url);
}


function ajaxAction(action, table, id, params) {

    XHRs(ajaxActionUrl(action, table, id, params));


}

function ajaxActionUrl(action, table, id, params) {
    url = "index.php?xhr=ajaxAction&table=" + table + "&action=" + action + "&id=" + id + "&params=" + serialize(params);
    return url;
}

function serialize(mixed_value) {
    // Returns a string representation of variable (which can later be unserialized)
    //
    // version: 906.1807
    // discuss at: http://phpjs.org/functions/serialize
    // +   original by: Arpad Ray (mailto:arpad@php.net)
    // +   improved by: Dino
    // +   bugfixed by: Andrej Pavlovic
    // +   bugfixed by: Garagoth
    // +      input by: DtTvB (http://dt.in.th/2008-09-16.string-length-in-bytes.html)
    // +   bugfixed by: Russell Walker (http://www.nbill.co.uk/)
    // %          note: We feel the main purpose of this function should be to ease the transport of data between php & js
    // %          note: Aiming for PHP-compatibility, we have to translate objects to arrays
    // *     example 1: serialize(['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: 'a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}'
    // *     example 2: serialize({firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'});
    // *     returns 2: 'a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}'
    var _getType = function (inp) {
        var type = typeof inp, match;
        var key;
        if (type == 'object' && !inp) {
            return 'null';
        }
        if (type == "object") {
            if (!inp.constructor) {
                return 'object';
            }
            var cons = inp.constructor.toString();
            match = cons.match(/(\w+)\(/);
            if (match) {
                cons = match[1].toLowerCase();
            }
            var types = ["boolean", "number", "string", "array"];
            for (key in types) {
                if (cons == types[key]) {
                    type = types[key];
                    break;
                }
            }
        }
        return type;
    };
    var type = _getType(mixed_value);
    var val, ktype = '';

    switch (type) {
        case "function":
            val = "";
            break;
        case "boolean":
            val = "b:" + (mixed_value ? "1" : "0");
            break;
        case "number":
            val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":" + mixed_value;
            break;
        case "string":
            val = "s:" + encodeURIComponent(mixed_value).replace(/%../g, 'x').length + ":\"" + mixed_value + "\"";
            break;
        case "array":
        case "object":
            val = "a";
            /*
             if (type == "object") {
             var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
             if (objname == undefined) {
             return;
             }
             objname[1] = serialize(objname[1]);
             val = "O" + objname[1].substring(1, objname[1].length - 1);
             }
             */
            var count = 0;
            var vals = "";
            var okey;
            var key;
            for (key in mixed_value) {
                ktype = _getType(mixed_value[key]);
                if (ktype == "function") {
                    continue;
                }

                okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
                vals += serialize(okey) +
                    serialize(mixed_value[key]);
                count++;
            }
            val += ":" + count + ":{" + vals + "}";
            break;
        case "undefined": // Fall-through
        default: // if the JS object has a property which contains a null value, the string cannot be unserialized by PHP
            val = "N";
            break;
    }
    if (type != "object" && type != "array") {
        val += ";";
    }
    return val;
}


function set_it() {
    document.getElementById('s').outerHTML = '<span id="s">' + document.getElementById('html').value + '</span>';
}

function loadjscssfile(filename, filetype) {
    if (filetype == "js") { //if filename is a external JavaScript file
        var fileref = document.createElement('script');
        fileref.setAttribute("type", "text/javascript");
        fileref.setAttribute("src", filename);
    }
    else if (filetype == "css") { //if filename is an external CSS file
        var fileref = document.createElement("link");
        fileref.setAttribute("rel", "stylesheet");
        fileref.setAttribute("type", "text/css");
        fileref.setAttribute("href", filename);
    }
    if (typeof fileref != "undefined") {
        document.getElementsByTagName("head")[0].appendChild(fileref);
        //alert(fileref);
    }
}


/**
 ARBO

 */

// AJOUT D'UN NOEUD
function addChild(param) {

    var num = param.parentNode.id.substring(3);
    var childrenId = 'ul_' + num;
    var hasChild = document.getElementById(childrenId);

    // Le noeud courant n'a pas d'enfant
    if (!hasChild) {

        var childTagUl = document.createElement('ul');

        childTagUl.setAttribute('id', childrenId);
        param.parentNode.appendChild(childTagUl);

        childTagLi = document.createElement('li');
        var liId = 'li_' + num + '_0';
        childTagLi.setAttribute('id', liId);
        childTagUl.appendChild(childTagLi);

    }
    // le noeud courant a des enfants
    else {

        var numLastChild = document.getElementById(childrenId).lastChild.id.substring(3);
        var next = parseInt(numLastChild.substring(numLastChild.length - 1)) + 1;
        var liId = 'li_' + numLastChild.substring(0, numLastChild.length - 2) + '_' + next;
        var childTagLi = document.createElement('li');
        childTagLi.setAttribute('id', liId);
        document.getElementById('ul_' + num).appendChild(childTagLi);

    }


    // construction du name de l'input pour le récuprérer facilement ensuite
    var inputName = 'n[0][fils]';

    // si le noeud courant n'a pas encore des fils
    if (!hasChild) {

        for (i = 1; i <= (num.length - 1) / 2; i++) {
            n = num.substring(2 * i, 1 + 2 * i);
            inputName = inputName + '[' + n + '][fils]';
        }

        inputName = inputName + '[0][value]';

    }
    // si le noeud courant a déjà des fils
    else {

        for (i = 1; i < (numLastChild.length - 1) / 2; i++) {
            n = numLastChild.substring(2 * i, 1 + 2 * i);
            inputName = inputName + '[' + n + '][fils]';
        }

        inputName = inputName + '[' + next + '][value]';

    }

    /**********************************************************************/


    for (k = 0; k < lgs.length; k++) {
        // flag
        var lagLG = document.createElement('img');
        lagLG.setAttribute('src', './img/flags/' + lgs[k] + '.gif');
        childTagLi.appendChild(lagLG);

        // champ de saisie
        var childTagInput = document.createElement('input');
        childTagInput.setAttribute('type', 'text');
        inputNameLG = inputName + '[' + lgs[k] + ']';
        childTagInput.setAttribute('name', inputNameLG);
        childTagLi.appendChild(childTagInput);
    }

    var childTagButton = document.createElement('a');
    childTagButton.setAttribute('href', '#');
    childTagButton.setAttribute('class', 'addChild');
    childTagButton.setAttribute('onclick', 'addChild(this)');
    childTagLi.appendChild(childTagButton);

    var imgAdd = document.createElement('img');
    imgAdd.setAttribute('src', './pictos/list-add.png');
    childTagButton.appendChild(imgAdd);

    var childTagButton = document.createElement('a');
    childTagButton.setAttribute('href', '#');
    childTagButton.setAttribute('class', 'delChild');
    childTagButton.setAttribute('onclick', 'delChild(this)');
    childTagLi.appendChild(childTagButton);

    var imgDel = document.createElement('img');
    imgDel.setAttribute('src', './pictos/process-stop.png');
    childTagButton.appendChild(imgDel);

}


// SUPPRESSION D'UN NOEUD
function delChild(child) {

    // si c'est le dernier enfant, on supprimer le UL au dessus
    if (child.parentNode.parentNode.childNodes.length == 1) {
        child.parentNode.parentNode.parentNode.removeChild(child.parentNode.parentNode);
    }
    // sinon juste le LI
    else {
        child.parentNode.parentNode.removeChild(child.parentNode);
    }

}

window.uploaders = new Array();
function refreshUploaders() {
    for (p in window.uploaders) {
        if (window.uploaders[p]) {
            if (document.getElementById(window.uploaders[p].settings.container)) {
                try {
                    window.uploaders[p].refresh();
                } catch (e) {
                    delete(window.uploaders[p]);
                }
            } else {

                delete(window.uploaders[p]);
            }
        }
    }
}


function refreshUploaders() {
    for (p in window.uploaders) {
        if (window.uploaders[p]) {
            if (document.getElementById(window.uploaders[p].settings.container)) {
                try {
                    window.uploaders[p].refresh();
                } catch (e) {
                    delete(window.uploaders[p]);
                }
            } else {

                delete(window.uploaders[p]);
            }
        }
    }
}

/**
 * Boutons de prévisualisation
 * @type Boolean|@exp;window@call;open
 */
window.previewWindow = false;
$(document).ready(function () {
    $('.previsu a').click(function (e) {
        e.preventDefault();
        /**
         * Pour virer le cache
         */
        var href = $(this).attr('href') + "&nocache=" + Math.random();
        /**
         * On met à jour après enregistrement via ajax de la page
         */
        doSaveAllAndStay(function () {
            window.previewWindow.location = href + Math.random();
        });

        /**
         * Si la fenetre est déjà ouverte
         * ET que son objet contient toujours un objet "window"
         * Sinon ça signifie qu'elle a été fermée, mais previewWindow
         * reste disponible ...
         */
        if (window.previewWindow && window.previewWindow.window) {
            window.previewWindow.location = href;
        } else {
            window.previewWindow = window.open(href, 'preview', 'width=1024,height=600,scrollbars=yes');
        }

        window.previewWindow.focus();
    });
});