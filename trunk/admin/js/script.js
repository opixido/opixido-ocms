function switchInfo(ul, obj) {
    var list_div = gid('recent_info').getElementsByTagName('div');

    for ( var i = 0; i < list_div.length; i++) {
        if (list_div[i].id != ul) {
            list_div[i].style.display = "none";
        } else {
            list_div[i].style.display = "block";
        }
    }

    var list_onglet = gid('recent_info').getElementsByTagName('span');

    for ( var i = 0; i < list_onglet.length; i++) {
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

    if(gid('genform_' + nom)){
        obj = gid('genform_' + nom)
    }else{
        if(nom[(nom.length-1)] == "_"){
            obj = gid(nom.slice(0, -1));
        }else{
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
function showLgField(field, lg) {
    // alert(field+" - "+lg);
    if (lg == lgfieldcur[field])
        return;

    $("#lgfield_" + field + "_" + lg).show();
    gid("lgbtn_" + field + "_" + lg).className = "lgbtn_on";

    if (lgfieldcur[field]) {
        $("#lgfield_" + field + "_" + lgfieldcur[field]).hide();
        gid("lgbtn_" + field + "_" + lgfieldcur[field]).className = "lgbtn";
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

document.onmousemove = function(e) {
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
			
    if(!ordered ) {
        $(tbox).html($.makeArray($("#"+tbox.id+" option")).sort(function (a, b) {
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
    return [ curleft, curtop ];
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
    for ( var i = 0, j = objColl.length; i < j; i++) {
        var arrObjClass = objColl[i].className.split(' ');
        if (delim == ' ' && arrClass.length > arrObjClass.length)
            continue;
        var c = 0;
            comparisonLoop: for ( var k = 0, l = arrObjClass.length; k < l; k++) {
                for ( var m = 0, n = arrClass.length; m < n; m++) {
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

function doSubmitForm() {
    for (p in multiField) {
        for (z = 0; z < (multiField[p].options.length); z++) {
            multiField[p].options[z].selected = true;
        }
        multiField[p].readonly = "readonly";
        multiField[p].name = multiField[p].name + "[]";

    }
// updateRTEs();

}

function selectToSearch(obj) {

    var o = $("#" + obj);
    o.hide();
    o.after('<input type="hidden" name="' + obj + '" id="' + obj + '"  value="'
        + $(o).val() + '" />');

    o.after('<ul class="ulajax" id="' + obj + '_liste"></ul>');
    $('#' + obj + '_liste').hide();
    o
    .after('<input class="relationSelect" type="text" onclick="prepareSelect(this)" onkeyup="searchSelect(this)" rel="'
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
            function(e) {
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
    + "&fk=" + $(o).attr('rel') + "&q=" + $(o).val();
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
    var fil = $(obj).closest('fieldset');
    var div = $(fil).find('div:first').eq(0);

    div.slideToggle();

    fil.toggleClass('fieldopen').toggleClass('fieldclosed');

    return false;

}

function toggleRteInline(tarea) {
    var obj = $('#' + tarea);
    obj.hide();
    var vval = obj.val();
    if (vval.length < 3) {
        vval = '<span class="light">Champ Vide</span>';
    }

    obj.after('<div class="rtePreview" id="preview_' + tarea
        + '" title="Cliquez-ici pour modifier ce texte" >' + vval
        + '</div>');
    $('#preview_' + tarea + '').click(function() {
        obj.show();
        $(this).hide();
        setupTinymce(tarea);
    });
// setupTinymce
// setupTinymce
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
    $('table.genform_table input[type=checkbox]').attr('checked',co);
}


/**
 * Verifie les tableaux de langue pour les champs URL
 * lors de la creation d'une rubrique
 */
function updateChampUrl(champ,valeur) {

    var champ = gid(champ);
    valeur = valeur.toLowerCase();
    var re = /\$|,|@|#|~|`|\%|\*|\^|\&|\(|\)|\+|\=|\[|\-|\_|\]|\[|\}|\{|\;|\:|\'|\"|\<|\>|\?|\||\\|\!|\$|\.\£\°\§\//g;
    valeur = valeur.replace(re,"-");
    re = /é|è|ê|ë|€/g;
    valeur = valeur.replace(re,"e");
    re = /à|â|ä/g;
    valeur = valeur.replace(re,"a");
    re = /ò|ô|ö/g;
    valeur = valeur.replace(re,"o");
    re = /û|ü|ù|µ/g;
    valeur = valeur.replace(re,"u");
    re = /ç/g;
    valeur = valeur.replace(re,"c");
    valeur = valeur.replace(/ /g,"-");
    valeur = valeur.replace('.',"-");
    valeur = valeur.replace("/","-");
    var i=0;
    valeur = valeur.replace(/[^A-Za-z0-9]/g,"-");
    while( valeur.search("--") >= 0 && i<20) {
        valeur = valeur.replace(/__/g,"-");
        i++;
    }
    if(valeur.charAt(valeur.length-1) == "-") {
        valeur = valeur.substring(0,valeur.length-1);
    }
    var checkTab = false;
    for(p in LGs) {
        if(champ.name == "genform_rubrique_url_"+LGs[p]) {
            checkTab = notUrl[LGs[p]];
        }
    }
    incRe = 1;
    newvaleur = valeur;
    if(checkTab) {
        while(checkTab[newvaleur]) {
            newvaleur = valeur+"-"+incRe;
            incRe++;
        }
    }
    valeur = newvaleur;

    champ.value = valeur;
    checkFields();
}


/**
 * Verifie que tous les champs de langue ont bien été remplis
 * lors de la creation d'une rubrique
 */
function checkFields() {                  
                                    		
    ml = mfields.length;

    isok = 0;
    for(p=0;p<ml;p++) {
        fi = mfields[p];
        if(gid(fi).value.length > 1) {
            isok++;
        }
    }
    if(isok == ml) {
        $('#genform_ok').attr('disabled',false).parent('label').removeClass('disabled');                    
    } else {                   
        $('#genform_ok').attr('disabled',true).parent('label').addClass('disabled');
    }

}

$.extend($.expr[':'], {
  'containsi': function(elem, i, match, array)
  {
    return (elem.textContent || elem.innerText || '').toLowerCase()
    .indexOf((match[3] || "").toLowerCase()) >= 0;
  }
});
        
function searchInSelect(obj) {
    var selectToSearch = '#'+$(obj).attr('id').replace('_search','');
    var textToSearch = $(obj).val();
    if(textToSearch.length > 0) {
        $(selectToSearch+" option").hide();
        $(selectToSearch+" option:containsi('"+textToSearch+"')").show();
    } else {
        $(selectToSearch+" option").show();
    }

}