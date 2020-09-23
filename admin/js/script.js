function switchInfo(ul, obj) {
    var list_div = gid('recent_info').getElementsByTagName('div');

    for (var i = 0; i < list_div.length; i++) {
        if (list_div[i].id != ul) {
            list_div[i].style.display = "none";
        } else {
            list_div[i].style.display = "block";
        }
    }

    var list_onglet = gid('recent_info').getElementsByTagName('span');

    for (var i = 0; i < list_onglet.length; i++) {
        if (list_onglet[i] != obj) {
            list_onglet[i].style.backgroundColor = "#fafafa";
        } else {
            list_onglet[i].style.backgroundColor = "#fda800";
        }
    }
}

function showHide(ide) {
    obj = gid(ide);
    if (obj) {
        if (obj.style.display == "block")
            obj.style.display = "none";
        else
            obj.style.display = "block";
    }

}

function smallPopup(href) {

    if (href.indexOf('@rubrique_id=') >= 0) {
        var id = href.substr(href.indexOf('=') + 1);

        href = XHRs('index.php?xhr=reallink&id=' + id);

    }
    window.open(href, 'test',
        'width=200,height=200,scrollbars=yes,resizable=yes');

}

function popup(href, larg, haut) {

    if (href.indexOf('@rubrique_id=') >= 0) {
        var id = href.substr(href.indexOf('=') + 1);

        href = XHRs('index.php?xhr=reallink&id=' + id);

    }
    window.open(href, 'test', 'width=' + larg + ',height=' + haut
        + ',scrollbars=yes,resizable=yes');

}

function doblank(atag) {
    window.open(atag.href);
    return false;
}

function doBlank(atag) {
    return doblank(atag);
}

oldBtnNom = "";

/**
 * Preview dans une iframe d'un champ d'une autre table
 */
function genformPreviewFk(curtable, nom, champs, iframenom) {
    if (iframenom)
        ifra = gid("genform_preview_" + iframenom);
    else
        ifra = gid("genform_preview_" + nom);

    if (iframenom)
        btn = gid("genform_preview_" + iframenom + "_btn");
    else
        btn = gid("genform_preview_" + nom + "_btn");

    if (gid('genform_' + nom)) {
        obj = gid('genform_' + nom)
    } else {
        if (nom[(nom.length - 1)] == "_") {
            obj = gid(nom.slice(0, -1));
        } else {
            obj = gid(nom);
        }
    }
    valeur = obj.options[obj.selectedIndex].value;
    if ((ifra.style.display == "none" || valeur != oldValIframe)
        && valeur != "") {

        ifra.src = "index.php?popup=1&preview=1&curTable=" + curtable
            + "&curId=" + valeur + "&champs=" + champs;
        ifra.style.display = "block";

        if (btn.value != "X")
            oldBtnNom = btn.value;
        btn.value = "X";

        oldValIframe = valeur;

    } else {
        ifra.style.display = "none";
        btn.value = oldBtnNom;
    }

}

lgfieldcur = new Array();
window.currentLg = false;

function showLgField(field, lg, skipAjax) {
    $('.lgbtn').removeClass('disabled');
    $('.lgbtn_' + lg).addClass('disabled');

    $('.lgfield').hide();
    $('.lgfield_' + lg).show();
    window.currentLg = lg;
    if (!skipAjax) {
        $('.ajax_lg_select').each(function () {
            if ($(this).val() != lg) {
                $(this).val(lg).change();
            }
        });
    }
    return;
    // alert(field+" - "+lg);
    if (lg == lgfieldcur[field])
        return;

    $("#lgfield_" + field + "_" + lg).show();
    $("#lgbtn_" + field + "_" + lg).addClass('disabled');

    if (lgfieldcur[field]) {
        $("#lgfield_" + field + "_" + lgfieldcur[field]).hide();
        $("#lgbtn_" + field + "_" + lgfieldcur[field]).removeClass('disabled');
    }
    lgfieldcur[field] = lg;

}

function showInfos(info) {
    /*
     * gid('info_picto').innerHTML = info; gid('info_picto').style.left =
     * mouseX+15; gid('info_picto').style.top = mouseY+15;
     * gid('info_picto').style.display = "block";
     */
}

function hideInfos() {
    gid('info_picto').style.display = "none";
}

var IE = document.all ? true : false;
if (!IE) {
    document.captureEvents(Event.MOUSEMOVE)
}

var tempX = 0;
var tempY = 0;

document.onmousemove = function (e) {
    x = (navigator.appName.substring(0, 3) == "Net") ? e.pageX : event.x
        + document.body.scrollLeft;
    mouseX = x;
    y = (navigator.appName.substring(0, 3) == "Net") ? e.pageY : event.y
        + document.body.scrollTop;
    mouseY = y;
};

function getMouseXY(e) {

    if (IE) { // grab the x-y pos.s if browser is IE
        if (event && document.body) {
            tempX = event.clientX + document.body.scrollLeft;
            tempY = event.clientY + document.documentElement.scrollTop;
        } else {
            return false;
        }
    } else { // grab the x-y pos.s if browser is NS
        tempX = e.pageX;
        tempY = e.pageY;
    }
    if (tempX < 0) {
        tempX = 0;
    }
    if (tempY < 0) {
        tempY = 0;
    }
    window.MX = x = mouseX = tempX;
    window.MY = y = mouseY = tempY;
    return true;
}

document.onmousemove = getMouseXY;

function ext(n) {
    return (n[Math.floor(n.length * Math.random())])
}

function insertLorem(obj) {

    a8 = new Array("Il faut savoir ", "Il est de notoriété publique ",
        "Tout le monde sait ", "Il est connu ", "On sait bien ",
        "Il y a longtemps qu'on sait ", "Sachez ",
        "Depuis la nuit des temps on sait ",
        "Depuis toujours nous savons ", "C'est un fait bien établi ",
        "Il a été prouvé scientifiquement ",
        "De tout temps les hommes savent ",
        "Depuis la plus haute antiquité l'humanité sait ",
        "Personne de nos jours n'ignore ",
        "Il est incontestable de nos jours ");
    a1 = new Array("que la maladie", "qu'un mauvais état de santé",
        "que l'état pathologique",
        "que la constatation des problêmes de santé actuels",
        "qu'un état de santé dégradé", "que la médecine allopathique",
        "que la magie homéopathique", "qu'un désordre psychopathologique",
        "que la schizophrénie", "qu'une rupture des énergies",
        "qu'une dysharmonie des corps astraux",
        "que l'inconsistance de la pensée psychique",
        "que le processus du dévoilement identitaire",
        "que la conscience vibratoire de l'humanité",
        "que le corps éthérique ");
    a2 = new Array(
        "est une hiérarchisation entre chaque corps énergétique de nature vibratoire de bas en haut et de haut en bas, le fonctionnel, le lésionnel puis le patapsychologique",
        "est une conséquence de la rupture de l'harmonie cosmique et des blocages des flux énergétiques du corps",
        "est causée par un dysfonctionnement quantique des foyers énergétiques corporels",
        "est la conséquence d'une rupture entre les harmonies mystiques du corps et des flux du ch'i à travers le réseau lymphatique",
        "est causé par un déséquilibre des réseaux cosmo-telluriques au sein de l'environnement proche de chacun",
        "est une des conséquences d'une dysharmonie dans la fractalité des énergies au milieu du réseau corporel",
        "repose sur une violation des lois ésotériques du vide cosmique qui couvre l'Univers et constitue sa gangue",
        "vient de ce que les singularités jouissent d'un processus d'auto-unification mobile et déplacé dans la mesure où un élément paradoxal parcourt et fait résonner des séries aléatoires",
        "est l'extériorisation d'un processus dont l'énergie hante les surfaces et régénêre les polarités discontinues",
        "est une sorte d'agencement machinique qui à travers ses diverses composantes, arrache sa consistance en franchissant des seuils ontologiques et d'irréversibilité non linéaires",
        "représente une circonvolution des affects à l'état brut",
        "est une représentation des systêmes complexes, métastatiques, virals, voués à la seule dimension exponentielle, à l'excentricité et à la scissiparité fractale indéfinie qui ne peuvent plus prendre fin",
        "constitue une fonction avancée de l'indécidabilité telle que démontrée par ses propres variables dans un systême formel",
        "contient l'abstraction mathématique de l'identité phénoménologique qui constitue en soi l'essence de sa propre identité");
    a3 = new Array("que le surnaturel", "que la médiumnité", "que la voyance",
        "que l'astrologie", "que la fonction métapsychique",
        "que l'ètre processuel métaphysique", "que le voyage astral",
        "que l'énergie vitale par le Chi", "que la réalité métaphysique",
        "que l'Etre Suprème", "que le paradigme de l'Autre Science",
        "que l'ésotérisme", "que la médecine holistique",
        "que la programmation neuro-linguistique",
        "que la puissance des forces ancestrales de l'humanité",
        "que la conscience collective", "que la psychanalyse métapsychique");
    a4 = new Array(
        "n'est que l'expression du théorême de Gaudel appliqué aux forces paranormales",
        "est consécutif à la révélation de l'expression mystico-surnaturelle de la réalité et de son caractêre quantique",
        "est une des voies d'expression de la réalité ésotérique d'une autre dimension inconnue",
        "est un des états métapsychiques relevant d'un surréel issu des forces cosmiques en présence",
        "constitue ce qu'on peut appeler un rapport esotérico-indépendant de la nature",
        "est un systême ambivalent de connaissance du paranormal sous-jacent",
        "est une singularité-évênement correspondant à des séries hétérogênes qui s'organisent en un systême ni stable ni instable",
        "admet une échappée de l'agencement hors des coordonnées énergico-spatio-temporelles",
        "représente une relativité ontologique inséparable d'une relativité énonciative, au sens axiologique, et n'est possible qu'à travers la médiation de machines autopoÃÂ¯étiques",
        "se dissout dans les considérations cosmologiques sur le big bang tandis que s'affirme celle d'irréversibilité",
        "est une cognitivité se constituant à l'échelle des quarks",
        "n'est qu'une constante limite qui apparaît comme un rapport dans l'ensemble de l'univers auquel toutes les parties sont soumises sous une condition finie",
        "est une des expressions littérales de la mécanique quantique appliquée a un renouveau paradigmatique",
        "aurait cette filiation maintenue de la cohérence, que ce qui excêde intérieurement le tout ne va pas plus loin qu'à nommer le point limite de ce tout",
        "est la fréquence vibratoire en harmonie avec le corps physique qui agissent de concert sur le haut du cône vibratoire commandant l'ensemble");
    a5 = new Array(" et aussi bien garder à l'esprit ",
        " et ne pas oublier en outre ", " et faire savoir ",
        " mais il faut reconnaître", " il faut noter en outre",
        " et je ne vous apprendrai rien en vous disant",
        " retenez bien aussi", " et il est de science certaine",
        " et il est bien entendu dans tous les esprits",
        "et prendre en considération le fait avéré");
    a6 = new Array(
        "C'est pourquoi je pense ",
        "C'est la raison pour laquelle je crois ",
        "Ceci expliquant cela, je suis d'accord avec le fait ",
        "C'est ainsi que je déclare ",
        "Ainsi il est un fait à prendre en compte ",
        "C'est pourquoi, l'inconscient collectif se doit de garder à l'esprit ",
        "Dans ce cas, nous pouvons aisément dire ",
        "Au regard de ce qui précêde, nous pouvons donc affirmer sans ambages ",
        "En conclusion il n'est pas interdit de penser et de dire ",
        "Cela est donc une preuve irréfutable ");
    a7 = new Array(
        "que l'affinité vibratoire fréquentielle rectificatrice des affects émotionnels archétypiques perturbés, dont la nature humaine est ponctuellement imprégnée, vont par leur effets énergétiques vibratoires, agir sur la constitution énergétique d'un ètre et non sur la maladie elle-mème",
        "qu'Heisenberg avait raison, l'incertitude rêgne partout",
        " que parfois 2 et 2 peuvent ne pas faire 4",
        "l'énergie potentielle est l'énergie de l'évênement pur tandis que les formes d'actualisation correspondent aux effectuations de l'évênement",
        "qu'il n'existe aucune correspondance bi-univoque entre des chaînons linéaires signifiants ou d'arché-écriture et cette catalyse machinique multidimensionnelle et multiréférentielle",
        "que la symétrie d'échelle, la transversalité, le caractêre pathique non discursif de leur expansion nous font sortir de la logique du tiers exclu",
        "que cela nous conforte à renoncer au binarisme ontologique que nous avons précédemment énoncé",
        "que c'est la notion d'échelle qu'il conviendrait ici d'élargir afin de penser les symétries fractales, qu'elles traversent en les engendrant, en terme ontologique et substantiel",
        "qu'il convient qu'un foyer d'appartenance à soi existe quelque part pour que puisse venir à l'existence cognitive quelque étant ou quelque modalité d'ètre que ce soit",
        " que la biosphêre ou la mécanosphêre, accrochées sur cette planête, focalisent un point de vue d'espace de temps et d'énergie, elles tracent un angle de constitution de notre galaxie",
        "que hors de ce point de vue particularisé, le reste n'existe qu'à travers la virtualité de l'existence d'autres machines autopoÃÂ¯étiques au sein d'autres biomécanosphêres saupoudrées dans le cosmos",
        "que l'objectivité résiduelle est ce qui résiste au balayage de l'infinie variation des points de vue constituables sur lui.",
        "que l'on ne se préoccupe pas assez, dans la théorie du Chaos, du phénomêne inverse, de l'hyposensibilité aux conditions initiales, de l'exponentialité inverse des effets par rapport aux causes",
        "le perspectivisme ou relativisme scientifique n'est jamais relatif à un sujet, il ne constitue pas une relativité du vrai, mais au contraire une vérité du relatif, c'est-à-dire des variables dont il ordonne des cas d'aprês les valeurs qu'il en dégage dans son systême de coordonnées.",
        "que la vérité de l'hypothêse du continu ferait loi de ce que l'excês dans le multiple n'a pas d'autre assignation que l'occupation de la place vide, que l'existence de l'inexistant propre du multpile initial.",
        "que le mode vibratoire de la planête Terre permet à la conscience universelle de s'exprimer selon son expression proprement duale et non équivoque.");

    chaine = ext(a8) + ext(a1) + ' ' + ext(a2);
    chaine += ext(a5) + ' ' + ext(a3) + ' ' + ext(a4);
    chaine += '. ' + '\n\n' + ext(a6) + ext(a7);

    obj.value += chaine;
}

function insertDate(obj) {
    var myDate = new Date();
    obj.value += myDate.getDate() + "/" + myDate.getMonth() + "/"
        + myDate.getFullYear();
}

function insertTime(obj) {
    var myDate = new Date();
    obj.value += myDate.getHours() + "/" + myDate.getMinutes() + "/"
        + myDate.getSeconds();
}

function insertUnixTime(obj) {
    var myDate = new Date();
    obj.value += myDate.getTime();
}

function insertPassword(obj) {
    obj.value = generatepass(8);
}

function moveMultiBox(fbox, tbox, ordered) {
    $(fbox).find('option:selected').remove().appendTo(tbox);

    if (!ordered) {
        $(tbox).html($.makeArray($("#" + tbox.id + " option")).sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
    }

    return false;
}

function moveInsideMulti(tbox, direct) {

    var directmore;
    directmore = direct;

    if (direct > 0) {
        /**
         * Go Down
         */
        for (i = tbox.options.length - 1; i >= 0; i--) {

            if (tbox.options[i].selected && tbox.options[i + direct]
                && (i + direct) > 0) {

                tomouv_val = tbox.options[i].value;
                tomouv_txt = tbox.options[i].text;
                tbox.options[i].value = tbox.options[i + direct].value;
                tbox.options[i].text = tbox.options[i + direct].text;
                tbox.options[i].selected = false;
                tbox.options[i + direct].value = tomouv_val;
                tbox.options[i + direct].text = tomouv_txt;
                tbox.options[i + direct].selected = true;
                if (direct > 0) {
                    i += direct;
                }
            }
        }
    } else {
        /**
         * Go Up
         */
        for (i = 1; i < tbox.options.length; i++) {
            if (tbox.options[i].selected && tbox.options[i + direct]
                && (i + direct) > -1) {

                tomouv_val = tbox.options[i].value;
                tomouv_txt = tbox.options[i].text;
                tbox.options[i].value = tbox.options[i + direct].value;
                tbox.options[i].text = tbox.options[i + direct].text;
                tbox.options[i].selected = false;
                tbox.options[i + direct].value = tomouv_val;
                tbox.options[i + direct].text = tomouv_txt;
                tbox.options[i + direct].selected = true;
                if (direct > 0) {
                    i += direct;
                }
            }
        }
    }
    return false;

}

function findPos(obj) {
    var curleft = curtop = 0;
    if (obj.offsetParent) {
        curleft = obj.offsetLeft;
        curtop = obj.offsetTop;
        while (obj = obj.offsetParent) {
            curleft += obj.offsetLeft;
            curtop += obj.offsetTop;
        }
    }
    return [curleft, curtop];
}

/*
 * document.onmousedown = function (e) {
 * 
 * var targ; if (!e) var e = window.event; if (e.target) targ = e.target; else
 * if (e.srcElement) targ = e.srcElement; if (targ.nodeType == 3) // defeat
 * Safari bug targ = targ.parentNode; lastClickedElement = targ;
 *  }
 */

/**
 * ARBO
 *
 */
function getElementsByClassName(strClass, strTag, objContElm) {
    strTag = strTag || "*";
    objContElm = objContElm || document;
    var objColl = objContElm.getElementsByTagName(strTag);
    if (!objColl.length && strTag == "*" && objContElm.all)
        objColl = objContElm.all;
    var arr = new Array();
    var delim = strClass.indexOf('|') != -1 ? '|' : ' ';
    var arrClass = strClass.split(delim);
    for (var i = 0, j = objColl.length; i < j; i++) {
        var arrObjClass = objColl[i].className.split(' ');
        if (delim == ' ' && arrClass.length > arrObjClass.length)
            continue;
        var c = 0;
        comparisonLoop: for (var k = 0, l = arrObjClass.length; k < l; k++) {
            for (var m = 0, n = arrClass.length; m < n; m++) {
                if (arrClass[m] == arrObjClass[k])
                    c++;
                if ((delim == '|' && c == 1)
                    || (delim == ' ' && c == arrClass.length)) {
                    arr.push(objColl[i]);
                    break comparisonLoop;
                }
            }
        }
    }
    return arr;
}

// To cover IE 5.0's lack of the push method
/*
 * Array.prototype.push = function(value) { this[this.length] = value; }
 */

function searchSelect(type) {

    var inputs = gid('formpages').getElementsByTagName('input');

    for (p = 0; p < inputs.length; p++) {
        if (inputs[p].name.indexOf('massiveActions') >= 0) {
            inputs[p].checked = type;
        }
    }

}

function generatepass(plength) {
    var keylist = "abcdefghijklmnopqrstuvwxyz123456789";
    var temp = "";
    temp = "";
    for (i = 0; i < plength; i++) {
        temp += keylist.charAt(Math.floor(Math.random() * keylist.length));
    }
    return temp;
}

/**
 * A la soumission du formulaire on sélectionne tous les items des select multiple de droite
 * Et on convertit le champ en array pour tout récupérer
 */
function doSubmitForm() {
    for (p in multiField) {
        /**
         * Test Pour ne pas le faire deux fois en cas de double click
         */
        if (!multiField[p].converted) {
            for (z = 0; z < (multiField[p].options.length); z++) {
                multiField[p].options[z].selected = true;
            }
            multiField[p].converted = true;
            multiField[p].readonly = "readonly";
            multiField[p].name = multiField[p].name + "[]";
        }
    }
}

function selectToSearch(obj) {

    var o = $("#" + obj);
    o.hide();
    o.after('<input autocomplete="off" type="hidden" name="' + obj + '" id="' + obj + '"  value="'
        + $(o).val() + '" />');

    o.after('<ul class="dropdown-menu" style="position:static;float:none" id="' + obj + '_liste"></ul>');
    $('#' + obj + '_liste').hide();
    o
        .after('<input autocomplete="off" class="relationSelect" type="text" onclick="prepareSelect(this)" onkeyup="searchSelect(this)" rel="'
            + obj
            + '"  id="'
            + obj
            + '_helper" value="'
            + o.find('option:selected').text() + '"/>');
    o.remove();

}

function prepareSelect(o) {
    $(o).select();

    liste = $("#" + $(o).attr('rel') + "_liste");

    if (liste.css('display') == "block") {
        reinitSelect();
    } else {
        $(o).attr('title', $(o).val());
        $(o).val("");
        window.currentSelect = $(o);
        searchSelect(o);
        liste.show();
        $(document).click(
            function (e) {
                if ($(e.target).attr('id') != $(o).attr('id')
                    && !$(e.target).is('.sal')) {
                    reinitSelect();
                }
            });
    }
}

function reinitSelect() {
    window.currentSelect.val(window.currentSelect.attr('title'));
    liste.hide();
}

function searchSelect(o) {
    var url = "index.php?xhr=searchRelation&table=" + $('#curTable').val()
        + "&fk=" + $(o).attr('rel') + "&q=" + encodeURIComponent($(o).val());
    $("#" + $(o).attr('rel') + "_liste").show();
    $("#" + $(o).attr('rel') + "_liste").html(
        '<li><img src="img/loading.gif" alt="Loading" /></li>');
    $("#" + $(o).attr('rel') + "_liste").load(url);
// alert(url);
}

function selectRelationValue(obj) {
    var div = ($(obj).parent().parent().parent());
    div.find('ul').hide();
    div.find("input[type=text]").val($(obj).text());
    div.find("input[type=hidden]").val($(obj).attr('rel'));
    $(document).unbind('click');

}

function toggleFieldset(obj) {
    $(obj).toggleClass('active');
    var fil = $(obj).closest('fieldset');
    var div = $(fil).find('div:first').eq(0);

    div.slideToggle();

    fil.toggleClass('fieldopen').toggleClass('fieldclosed');

    return false;

}

function toggleRteInline(tarea) {
    var obj = $('#' + tarea);
    obj.addClass('sr-only');
    var vval = jQuery.trim(obj.val());
    if (vval.length < 3) {
        vval = '<span class="light">Champ Vide</span>';
    }

    obj.after('<div class="rtePreview well" id="preview_' + tarea
        + '" title="Cliquez-ici pour modifier ce texte" >' + vval
        + '</div>');
    $('#preview_' + tarea + '').click(function () {
        setupTinymce(tarea);
        $(this).remove();
    });

}

function FitToContent(id, maxHeight) {
    var text = id && id.style ? id : document.getElementById(id);
    if (!text)
        return;

    var adjustedHeight = text.clientHeight;
    if (!maxHeight || maxHeight > adjustedHeight) {
        adjustedHeight = Math.max(text.scrollHeight, adjustedHeight);
        if (maxHeight)
            adjustedHeight = Math.min(maxHeight, adjustedHeight);
        if (adjustedHeight > text.clientHeight)
            text.style.height = adjustedHeight + "px";

    } else {

    }
}


function searchSelectMass(co) {
    $('table.table input[type=checkbox]').attr('checked', co);
}

(function () {
    var alphabet = {
        a: /[\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250]/ig,
        aa: /[\uA733]/ig,
        ae: /[\u00E6\u01FD\u01E3]/ig,
        ao: /[\uA735]/ig,
        au: /[\uA737]/ig,
        av: /[\uA739\uA73B]/ig,
        ay: /[\uA73D]/ig,
        b: /[\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253]/ig,
        c: /[\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184]/ig,
        d: /[\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A]/ig,
        dz: /[\u01F3\u01C6]/ig,
        e: /[\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD]/ig,
        f: /[\u0066\u24D5\uFF46\u1E1F\u0192\uA77C]/ig,
        g: /[\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F]/ig,
        h: /[\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265]/ig,
        hv: /[\u0195]/ig,
        i: /[\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131]/ig,
        j: /[\u006A\u24D9\uFF4A\u0135\u01F0\u0249]/ig,
        k: /[\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3]/ig,
        l: /[\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747]/ig,
        lj: /[\u01C9]/ig,
        m: /[\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F]/ig,
        n: /[\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5]/ig,
        nj: /[\u01CC]/ig,
        o: /[\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275]/ig,
        oi: /[\u01A3]/ig,
        ou: /[\u0223]/ig,
        oo: /[\uA74F]/ig,
        p: /[\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755]/ig,
        q: /[\u0071\u24E0\uFF51\u024B\uA757\uA759]/ig,
        r: /[\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783]/ig,
        s: /[\u0073\u24E2\uFF53\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B]/ig,
        ss: /[\u00DF\u1E9E]/ig,
        t: /[\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787]/ig,
        tz: /[\uA729]/ig,
        u: /[\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289]/ig,
        v: /[\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C]/ig,
        vy: /[\uA761]/ig,
        w: /[\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73]/ig,
        x: /[\u0078\u24E7\uFF58\u1E8B\u1E8D]/ig,
        y: /[\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF]/ig,
        z: /[\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763]/ig,
        '': /[\u0300\u0301\u0302\u0303\u0308]/ig
    };
    replaceDiacritics = function (str) {
        for (var letter in alphabet) {
            str = str.replace(alphabet[letter], letter);
        }
        return str;
    };
}());

/**
 * Verifie les tableaux de langue pour les champs URL
 * lors de la creation d'une rubrique
 */
function updateChampUrl(champ, valeur, prevalue) {

    if (prevalue == null) {
        prevalue = "";
    }
    var champS = gid('s' + champ);
    var champ = gid(champ);
    /**
     * Suppression des accents
     */
    valeur = replaceDiacritics(valeur);
    /**
     * Suppression des espaces en trop
     */
    valeur = valeur.trim();
    /**
     * Remplacement des espaces par des tirets
     */
    valeur = valeur.replace(/ /g, "-");
    /**
     * Remplacement des points par des tirets
     */
    valeur = valeur.replace(/\./g, "-");
    /**
     * Remplacement des slash par des tirets
     */
    valeur = valeur.replace(/\//g, "-");

    /**
     * Remplacement de la ponctuation par des tirets
     */
    valeur = valeur.replace(/[\-=_!"#%&'*{},.\/:;?\(\)\[\]@\\$\^*+<>~`\u00a1\u00a7\u00b6\u00b7\u00bf\u037e\u0387\u055a-\u055f\u0589\u05c0\u05c3\u05c6\u05f3\u05f4\u0609\u060a\u060c\u060d\u061b\u061e\u061f\u066a-\u066d\u06d4\u0700-\u070d\u07f7-\u07f9\u0830-\u083e\u085e\u0964\u0965\u0970\u0af0\u0df4\u0e4f\u0e5a\u0e5b\u0f04-\u0f12\u0f14\u0f85\u0fd0-\u0fd4\u0fd9\u0fda\u104a-\u104f\u10fb\u1360-\u1368\u166d\u166e\u16eb-\u16ed\u1735\u1736\u17d4-\u17d6\u17d8-\u17da\u1800-\u1805\u1807-\u180a\u1944\u1945\u1a1e\u1a1f\u1aa0-\u1aa6\u1aa8-\u1aad\u1b5a-\u1b60\u1bfc-\u1bff\u1c3b-\u1c3f\u1c7e\u1c7f\u1cc0-\u1cc7\u1cd3\u2016\u2017\u2020-\u2027\u2030-\u2038\u203b-\u203e\u2041-\u2043\u2047-\u2051\u2053\u2055-\u205e\u2cf9-\u2cfc\u2cfe\u2cff\u2d70\u2e00\u2e01\u2e06-\u2e08\u2e0b\u2e0e-\u2e16\u2e18\u2e19\u2e1b\u2e1e\u2e1f\u2e2a-\u2e2e\u2e30-\u2e39\u3001-\u3003\u303d\u30fb\ua4fe\ua4ff\ua60d-\ua60f\ua673\ua67e\ua6f2-\ua6f7\ua874-\ua877\ua8ce\ua8cf\ua8f8-\ua8fa\ua92e\ua92f\ua95f\ua9c1-\ua9cd\ua9de\ua9df\uaa5c-\uaa5f\uaade\uaadf\uaaf0\uaaf1\uabeb\ufe10-\ufe16\ufe19\ufe30\ufe45\ufe46\ufe49-\ufe4c\ufe50-\ufe52\ufe54-\ufe57\ufe5f-\ufe61\ufe68\ufe6a\ufe6b\uff01-\uff03\uff05-\uff07\uff0a\uff0c\uff0e\uff0f\uff1a\uff1b\uff1f\uff20\uff3c\uff61\uff64\uff65]+/g, "-");

    /**
     * On encode tout le reste en %XY (chinois, russe, etc)
     */
    valeur = encodeURIComponent(valeur);

    /**
     * Remplacement de tous les caractères non latin par des tirets
     */
    valeur = valeur.replace(/[^A-Za-z0-9\%]/g, "-");

    /**
     * Remplacement des double tirets
     */
    valeur = valeur.replace(/\-\-/g, "-");

    if (valeur.charAt(valeur.length - 1) === "-") {
        valeur = valeur.substring(0, valeur.length - 1);
    }

    var checkTab = false;
    for (p in LGs) {
        if (champ.name === "genform_rubrique_url_" + LGs[p]) {
            checkTab = notUrl[LGs[p]];
        }
    }
    incRe = 1;
    newvaleur = valeur;
    if (checkTab) {
        while (checkTab[newvaleur]) {
            newvaleur = valeur + "-" + incRe;
            incRe++;
        }
    }
    valeur = newvaleur;

    champ.value = prevalue + valeur;
    $(champS).html(prevalue + valeur);
    checkFields();
}


/**
 * Verifie que tous les champs de langue ont bien été remplis
 * lors de la creation d'une rubrique
 */
function checkFields() {

    var ml = mfields.length;

    var isok = 0;

    for (p = 0; p < ml; p++) {
        fi = mfields[p];
        if (gid(fi).value.length > 1) {
            isok++;
        }
    }

    window.alertUrl = true;
    if (isok >= 1) {
        $('#genform_ok').attr('disabled', false).parent('label').removeClass('disabled');
    } else {
        $('#genform_ok').attr('disabled', true).parent('label').addClass('disabled');
    }

    if (isok == ml) {
        window.alertUrl = false;
    }

}

$.extend($.expr[':'], {
    'containsi': function (elem, i, match, array) {
        return (elem.textContent || elem.innerText || '').toLowerCase()
            .indexOf((match[3] || "").toLowerCase()) >= 0;
    }
});

function searchInSelect(obj) {
    var selectToSearch = '#' + $(obj).attr('id').replace('_search', '');
    var textToSearch = $(obj).val();
    if (textToSearch.length > 0) {
        $(selectToSearch + " option").hide();
        $(selectToSearch + " option:containsi('" + textToSearch + "')").show();
    } else {
        $(selectToSearch + " option").show();
    }

}

/**
 * Sauvegarde  du formulaire via un CTRL + S
 */
$(window).bind('keydown', function (event) {
    if ((event.ctrlKey || event.metaKey) && !event.shiftKey && !event.altKey) {
        switch (String.fromCharCode(event.which).toLowerCase()) {
            case 's':
                event.preventDefault();
                doSaveAllAndStay();
                break;
        }
    }
});