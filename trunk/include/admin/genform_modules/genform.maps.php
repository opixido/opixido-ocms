<?php
$conf = $_Gconfig['mapsFields'][$this->table][$name];
$chp_lat = $conf[0];
$chp_lon = $conf[1];
$chpsAuto = $conf[2];

if (!$this->editMode) {
    /*
     *
     * On est en MODIFICATION
     *
     * */

    $this->genHelpImage('help_maps', $name);


    $chpsAuto2 = array();
    foreach ($chpsAuto as $v) {
        if (isBaseLgField($v, $this->table)) {
            $chpsAuto2[] = $v . '_' . LG_DEF;
        } else if (ake($this->tab_default_field, $v)) {
            $chpsAuto2[] = $v;
        } else {
            $chpsAuto2[] = $v;
        }
    }

    if(isNull($this->tab_default_field[$chp_lat])) {
        $this->tab_default_field[$chp_lat] = $conf[3][0];
    }
    if(isNull($this->tab_default_field[$chp_lon])) {
        $this->tab_default_field[$chp_lon] = $conf[3][1];
    }

    $_SESSION[gfuid()]['curFields'][] = $chp_lat;
    $_SESSION[gfuid()]['curFields'][] = $chp_lon;

    $this->addBuffer('<div class="map_field" id="map_field_' . $name . '">');

    $this->addBuffer('<button class="button geocode">' . t('map_geocode') . '</button>');
    $this->addBuffer('<button class="button usecenter">' . t('map_usecenter') . '</button>');

    $this->addBuffer('<div class="clearer" ></div>');

    $this->addBuffer('<div class="map_map">');
    $this->addBuffer('<div id="map_field_' . $name . '_map" style="width:570px;height:400px;border:1px solid">MAP</div>');
    $this->addBuffer('</div>');

    $this->addBuffer('<div class="map_latlonfields" >');

    $this->addBuffer('<label for="genform_' . $chp_lat . '" >' . t('genform_' . $chp_lat) . '</label><input type="text" id="genform_' . $chp_lat . '" name="genform_' . $chp_lat . '" value="' . $this->tab_default_field[$chp_lat] . '" />');
    $this->addBuffer('<label for="genform_' . $chp_lon . '" >' . t('genform_' . $chp_lon) . '</label><input type="text" id="genform_' . $chp_lon . '" name="genform_' . $chp_lon . '" value="' . $this->tab_default_field[$chp_lon] . '" />');

    $this->addBuffer('</div>');
    $this->addBuffer('</div>');

    echo $this->getBuffer();
    $this->bufferPrint = '';
    ?>

    <script type="text/javascript"
            src="http://maps.googleapis.com/maps/api/js?sensor=false">
    </script>

    <script type="text/javascript">



        $('#map_field_<?php echo $name ?> button.usecenter').click(function() {
            marker.setPosition(map.getCenter());
            $('#genform_<?php echo $chp_lat ?>').val(map.getCenter().lat());
            $('#genform_<?php echo $chp_lon ?>').val(map.getCenter().lng());
            return false;
        });

        $('#map_field_<?php echo $name ?> button.geocode').click(function() {
            var fields = <?php echo json_encode($chpsAuto2) ?>;
            var address = '';
            for (p in fields) {
                t = fields[p];
                if($("#genform_"+t).length) {
                    address += $("#genform_"+t).val()+' ';
                } else {
                    address += t+' ';
                }
            }
            codeAddress(address);
            return false;
        });

        var latlng = new google.maps.LatLng(<?= $this->tab_default_field[$chp_lat] ?>,<?= $this->tab_default_field[$chp_lon] ?>);
        var myOptions = {
            zoom: 8,
            center:latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById("map_field_<?php echo $name ?>_map"),myOptions);
        var marker = new google.maps.Marker({
            map: map,
            position: latlng,
            draggable:true
        });
            
        google.maps.event.addListener(marker, 'position_changed', function() {
            $('#genform_<?php echo $chp_lat ?>').val(marker.getPosition().lat());
            $('#genform_<?php echo $chp_lon ?>').val(marker.getPosition().lng());
        });


        var geocoder = new google.maps.Geocoder();


        
        function codeAddress(address) {
            geocoder.geocode( { 'address': address}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                    map.setCenter(results[0].geometry.location);
                    marker.setPosition(results[0].geometry.location);
                   /* marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location
                    });
                    

                    $('#genform_<?php echo $chp_lat ?>').val(results[0].geometry.location.lat());
                    $('#genform_<?php echo $chp_lon ?>').val(results[0].geometry.location.lng());
                    */
                } else {
                    alert("Geocode was not successful for the following reason: " + status);
                }
            });
        }

    </script>



    <?php
} else {


    $ll = $this->tab_default_field[$chp_lat] . ',' . $this->tab_default_field[$chp_lon];
    $this->addBuffer('(' . $ll . ')');
    $this->addBuffer('<a target="_blank" href="http://maps.google.com/?q=(' . $ll . ')"><img src="http://maps.googleapis.com/maps/api/staticmap?size=300x300&sensor=false&center=' . $ll . '&zoom=13&markers=' . $ll . '" alt="" /></a>');
}
