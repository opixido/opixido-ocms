
/**
 * Si on imprime un bout de tout ça,
 * On nettoie  un peu le HTML et on force l'affichage des images masquées
 *
 **/
function cleanHtmlForPrint(obj) {
    $(obj).find('article.page').fadeTo(0,1);
    $(obj).find('img.lazy').each(function() {
        $(this).attr('src',$(this).attr('data-original'));
    });
    $(obj).find('.internav, .print').remove();
}

window.zIndex = 99;

$(document).ready(function(e) {


    $('article.page').css('position','absolute');

    /**
     * Bouton d'impression des rubriques
     **/
    $('button.print').click(function(e) {
        e.preventDefault();


        $($(this).attr('rel')).printArea({
            mode:'iframe',
            preCall:cleanHtmlForPrint
        });

        return false;
    });


    /**
     * Afficher sans opacity le 1er de chaque ligne
     **/
    $('#droite section').find('.page:gt(0)').fadeTo(0,0.5);

    /**
     * Quelques variables à définir et redéfinir quand on resize
     */
    function onLoadAndResize() {

        /**
         * Largeur automatique du bloc complet
         **/
        $('#droite').css('width',$(document).width()-260);

        /**
         * Hauteur de notre "viewport"
         **/
        window._height  = $('#droite').height()

    }
    onLoadAndResize();


    /**
     * Quand on clic sur les liens de nav à gauche ...
     * On scroll vers la ligne !
     **/
    $('#menu a').click(function(e) {
        
        e.preventDefault();

        var idTo = '#'+$(this).attr('href').split("#")[1];

        /**
         * Position de la ligne en Y vers où on scroll
         **/
        var toY = $(idTo).position().top+$('#droite').scrollTop();
        /**
         * Go GO !
         */
        $('#droite').stop(true).animate({
            scrollTop:toY
        }, 2000,'easeOutQuart');

        $(this).blur();

        $('#menu a.active').removeClass('active');//.switchClass('active','inactive','fast');
        $(this).addClass('active');//.switchClass('inactive','active','fast');

        window.location.hash = "page_"+idTo.replace('#','');

        return false;
    });


    /**
     * Slider
     **/
    $('ul.slider').each(function() {

        var h = '<ul class="sliderliste">';
        var lis = $(this).find('li');
        var nbImg = lis.length;
        var color = $(this).closest('section').attr('data-color');       
        
        if(nbImg == 1) {
            return;
        }
        
        for(var p=1;p<=nbImg;p++) {
            h += '<li><a class="btn" rel="'+p+'">'+p+'<span></span></a></li>';
        }
        h += '</ul>';
        $(this).find('img').each(function() {
            if($(this).attr('alt') != "" ) {
                $(this).closest('li').append('<div class="title" style="color:'+color+'">'+$(this).attr('alt')+'</div>');
            }            
        });
        
        $(this).append(h);

        var ul = this;
        $(this).find('a.btn').click(function() {
            if($(this).hasClass('active')) {
                return;
            }
            window.zIndex++;
            $(ul).find('a.active').removeClass('active');
            $(ul).find('li').eq($(this).attr('rel')-1).fadeTo(0,0).css('z-index',window.zIndex).fadeTo(300,1);
            $(this).addClass('active');
        });
        $(ul).find('li').eq(0).css('z-index','99');
        $(ul).find('.sliderliste li:eq(0) a').addClass('active');
    });


    /**
     * Chargement des images uniquement lorsqu'elle apparaissent dans le viewport
     **/
    window.lazy = $("img.lazy").lazyload({
        effect : "fadeIn",
        container: $("#droite"),
        event:'scrollstop',
        threshold : 200,
        failure_limit : 20
    });






    /**
     * Liens précédents / suivants
     * au sein des pages
     */
    $('a.internav').click(function(e) {

        /**
         * Same old friend ...
         **/
        e.preventDefault();

        /**
         * Objet "ligne" parent
         **/
        var obj = $(this).closest('section.ligne');

        /**
         * Page de destination
         **/
        var pageDest = $($(this).attr('href'));

        /**
         * Page courrante
         **/
        var pageCurrent = $(this).closest('article.page');
        
        if(pageDest.attr('id') == pageCurrent.attr('id')) {
            return false;
        }
        
        /**
         * on calcul le déplacement de cet objet "ligne" jusqu'à la position de l'objet demandé
         **/
        var toX = pageDest.position().left+obj.scrollLeft();

        /**
         * Et on anime le tout
         **/
        obj.animate({
            left:-toX
        },750,'easeOutQuart',function(){
            /**
             * Une fois l'anim terminée on balance un event scroll
             * pour vérifier les images en lazyload
             * @todo trouver une solution plus légère si possible ...
             */
            obj.trigger('scroll');
        });

        /**
         * On transparentifize la page actuelle
         **/
        $(this).closest('article.page').fadeTo('normal',0.5);
        
        /**
         * Enfin on anime l'apparition en transparence de la page
         * suivante / précédente
         **/
        pageDest.animate({
            opacity:1
        });
       



        $(this).blur();

        var toY = obj.position().top+$('#droite').scrollTop();
        /**
         * Go GO !
         */
        $('#droite').stop(true).animate({
            scrollTop:toY
        }, 1000,'easeOutQuart');

        window.location.hash = "page_"+$(this).attr('href').replace("#","");

        return false;
    });



    /**
     * Liste des "lignes" et de leurs positions en Y
     **/
    var sections = {},i=0,defScroll=$('#droite').scrollTop();
    $('section.ligne').each(function(){
        sections[this.id] = $(this).offset().top+defScroll+$(this).height()/2;
    //$('#menu a[href=#' + i+']').attr('data-color',$(this).attr('data-color'));
    });
    //console.log(sections);


    window._height  = $('#droite').height();

    /**
     * Quand on scroll on active le lien correspondant
     * dans le menu de navigation
     **/
    window.currentSel = false;
    $('#droite').bind('scrollstop',(function(){
        /**
         * Position actuelle de scroll
         **/
        var pos = $(this).scrollTop();
        //console.log(_height);
        /**
         * On parcourt le tableau des positions des lignes
         * Et on compare ...
         **/
        for(i in sections){
            if(sections[i] >= pos && (sections[i] < pos + _height) ){
                //if(sections[i].pos + sections[i].height >= pos && sections[i].pos < pos ){
                //console.log('OK : '+i+' : '+sections[i]+ ' >= '+pos);
                if(i != window.currentSel) {
                    $('#menu a.active').removeClass('active');//.switchClass('active','inactive','fast');//.css('border-left','4px solid #eee');
                    $('#menu a[href$=#' + i+']').addClass('active');//.switchClass('inactive','active');//.css('border-left','4px solid '+$('#'+i).attr('data-color'));
                    window.currentSel = i;
                }
                break;
            }
        }
    }));


    /**
     * Au resize de la page on resize l'ensemble
     **/
    $(window).resize(onLoadAndResize);

    //$('#droite').overscroll({direction:'vertical',persistThumbs:true,wheelDelta:30,scrollDelta:7});
    $('#droite').trigger('scroll');
    

});

/**
 * Si on a un hash dans l'URL
 * On annule le placement par défaut du navigateur
 * Et on scroll joliement au bon endroit
 */
$(window).load(function() {
    setTimeout('reScroll()', 100);
})

function reScroll() {
    $('#droite').scrollLeft(0).scrollTop(0);
    if(window.location.hash) {
        var h =(window.location.hash).replace("page_","");
        var a = $('a[href$='+h+']');
        a.click();
    }
}