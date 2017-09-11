/**
    Project: Collapsible Checkbox Tree jQuery Plugin
    Version: 1.0.1
	Author: Lewis Jenkins
	Website: http://www.redcarrot.co.uk/2009/11/11/collapsible-checkbox-tree-jquery-plugin/

    License:
        The CheckTree jQuery plugin is currently available for use in all personal or
        commercial projects under both MIT and GPL licenses. This means that you can choose
        the license that best suits your project and use it accordingly.
*/
(function($) {

    $.fn.collapsibleCheckboxTree = function(options) {

        var defaults = {
            checkParents : true, // When checking a box, all parents are checked
            checkChildren : false, // When checking a box, all children are checked
            uncheckChildren : true, // When unchecking a box, all children are unchecked
            initialState : 'default', // Options - 'expand' (fully expanded), 'collapse' (fully collapsed) or default
            htmlClose : '<img alt="Replier" src="./img/moins.gif">',
            htmlOpen : '<img alt="Déplier" src="./img/plus.gif">'
        };

        var options = $.extend(defaults, options);

        this.each(function() {

            var $root = this;

            // Add button
            //$(this).before('<div class="tree_buttons"><button class="expand abutton">Déplier</button><button class="collapse abutton">Replier</button><button class="default abutton">Montrer les éléments sélectionnés</button></div>');

            // Hide all except top level
            //$("ul", $(this).find('li')).addClass('hide');
            // Check parents if necessary
            if (defaults.checkParents) {
                $("input:checked").parents("li").find("input[type='checkbox']:first").attr('checked', true);
            }
            // Check children if necessary
            if (defaults.checkChildren) {
                $("input:checked").parent("li").find("input[type='checkbox']").attr('checked', true);
            }
            // Show checked and immediate children of checked
            $("li:has(input:checked) > ul", $(this)).removeClass('hide');
            // Add tree links
            $("li", $(this)).prepend('<span>&nbsp;</span>');
            $("li:has(> ul:not(.hide)) > span", $(this)).addClass('expanded').html(defaults.htmlClose);
            $("li:has(> ul.hide) > span", $(this)).addClass('collapsed').html(defaults.htmlOpen);

            // Checkbox function
            $("input[type='checkbox']", $(this)).click(function(){

                // If checking ...
                if ($(this).is(":checked")) {

                    // Show immediate children  of checked
                    $("> ul", $(this).parent("li")).removeClass('hide');
                    // Update the tree
                    $("> span.collapsed", $(this).parent("li")).removeClass("collapsed").addClass("expanded").html(defaults.htmlClose);

                    // Check parents if necessary
                    if (defaults.checkParents) {
                        $(this).parents("li").find("input[type='checkbox']:first").attr('checked', true);
                    }

                    // Check children if necessary
                    if (defaults.checkChildren) {
                        $(this).parent("li").find("input[type='checkbox']").attr('checked', true);
                        // Show all children of checked
                        $("ul", $(this).parent("li")).removeClass('hide');
                        // Update the tree
                        $("span.collapsed", $(this).parent("li")).removeClass("collapsed").addClass("expanded").html(defaults.htmlClose);
                    }


                // If unchecking...
                } else {

                    // Uncheck children if necessary
                    if (defaults.uncheckChildren) {
                        
                        $(this).closest("li").find("input[type='checkbox']").attr('checked', false);
                        // Hide all children
                        $("ul", $(this).closest("li")).addClass('hide');
                        // Update the tree
                        $("span.expanded", $(this).closest("li")).removeClass("expanded").addClass("collapsed").html(defaults.htmlOpen);
                    }
                }

            });

            // Tree function
            $("li:has(> ul) span", $(this)).click(function(){

                // If was previously collapsed...
                if ($(this).is(".collapsed")) {

                    // ... then expand
                    $("> ul", $(this).parent("li")).removeClass('hide');
                    // ... and update the html
                    $(this).removeClass("collapsed").addClass("expanded").html(defaults.htmlClose);

                // If was previously expanded...
                } else if ($(this).is(".expanded")) {

                    // ... then collapse
                    $("> ul", $(this).parent("li")).addClass('hide');
                    // and update the html
                    $(this).removeClass("expanded").addClass("collapsed").html(defaults.htmlOpen);
                }

            });

            // Button functions

            // Expand all
           
            $(this).prev().find(".expand").click(function () {
                // Show all children
                $("ul", $root).removeClass('hide');
                // and update the html
                $("li:has(> ul) > span", $root).removeClass("collapsed").addClass("expanded").html(defaults.htmlClose);
                return false;
            });
            // Collapse all
             $(this).prev().find(".collapse").click(function () {
                // Hide all children
                $("ul", $root).addClass('hide');
                // and update the html
                $("li:has(> ul) > span", $root).removeClass("expanded").addClass("collapsed").html(defaults.htmlOpen);
                return false;
            });
            // Wrap around checked boxes
             $(this).prev().find(".default").click(function () {
                // Hide all except top level
                $("ul", $root).addClass('hide');
                // Show checked and immediate children of checked
                $("li:has(input:checked) > ul", $root).removeClass('hide');
                // and update the html
                $("li:has(> ul:not(.hide)) > span", $root).removeClass('collapsed').addClass('expanded').html(defaults.htmlClose);
                $("li:has(> ul.hide) > span", $root).removeClass('expanded').addClass('collapsed').html(defaults.htmlOpen);
                return false;
            });

            switch(defaults.initialState) {
                case 'expand':
                    $("#expand").trigger('click');
                    break;
                case 'collapse':
                    $("#collapse").trigger('click');
                    break;
            }

        });

        return this;

    };

})(jQuery);
