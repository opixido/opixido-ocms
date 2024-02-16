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

    if (isNull($this->tab_default_field[$chp_lat])) {
        $this->tab_default_field[$chp_lat] = $conf[3][0];
    }
    if (isNull($this->tab_default_field[$chp_lon])) {
        $this->tab_default_field[$chp_lon] = $conf[3][1];
    }

    $_SESSION[gfuid()]['curFields'][] = $chp_lat;
    $_SESSION[gfuid()]['curFields'][] = $chp_lon;

    $this->addBuffer('<div class="map_field" id="map_field_' . $name . '">');

//    $this->addBuffer('<button class="button geocode">' . t('map_geocode') . '</button>');
    //  $this->addBuffer('<button class="button usecenter">' . t('map_usecenter') . '</button>');

    $this->addBuffer('<div class="clearer" ></div>');

    $this->addBuffer('<div class="map_map">');
    $this->addBuffer('<div id="map_field_' . $name . '_map" style="width:100%;height:400px;border:1px solid"></div>');
    $this->addBuffer('</div>');

    $this->addBuffer('<div class="map_latlonfields" >');

    $this->addBuffer('<label for="genform_latLong" >' . t('genform_latLong'). '</label><input type="text" id="genform_latLong" name="genform_latLong" value="' . $this->tab_default_field[$chp_lat] . ',' . $this->tab_default_field[$chp_lon] . '" />');

    $this->addBuffer('<label for="genform_' . $chp_lat . '" >' . t('genform_' . $chp_lat) . '</label><input type="text" id="genform_' . $chp_lat . '" name="genform_' . $chp_lat . '" value="' . $this->tab_default_field[$chp_lat] . '" />');
    $this->addBuffer('<label for="genform_' . $chp_lon . '" >' . t('genform_' . $chp_lon) . '</label><input type="text" id="genform_' . $chp_lon . '" name="genform_' . $chp_lon . '" value="' . $this->tab_default_field[$chp_lon] . '" />');

    $this->addBuffer('</div>');
    $this->addBuffer('</div>');

    echo $this->getBuffer();
    $this->bufferPrint = '';
    ?>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.2.1/dist/geosearch.css"/>
    <script src="https://unpkg.com/leaflet-geosearch@3.2.1/dist/geosearch.umd.js"></script>

    <script type="text/javascript">

        $(function () {


            var curLocation = [$("#<?='genform_' . $chp_lat ?>").val(), $("#<?='genform_' . $chp_lon ?>").val()];

            if (curLocation[0] == 0 && curLocation[1] == 0) {
                curLocation = [48.8534, 2.34];
            }


            var map = L.map("<?='map_field_' . $name . '_map'?>").setView(curLocation, 10);

            const search = new GeoSearch.GeoSearchControl({
                style: 'bar',
                provider: new GeoSearch.OpenStreetMapProvider(),
                updateMap: true,
                showMarker: false,
                showPopup: false
            });

            map.addControl(search);


            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
                    'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                id: 'mapbox/streets-v11',
            }).addTo(map);
            map.attributionControl.setPrefix(false);

            var marker = new L.marker(curLocation, {
                draggable: 'true'
            });

            marker.on('dragend', function (event) {
                var position = marker.getLatLng();
                marker.setLatLng(position, {
                    draggable: 'true'
                }).bindPopup(position).update();
                $("#<?='genform_' . $chp_lat ?>").val(position.lat);
                $("#<?='genform_' . $chp_lon ?>").val(position.lng).keyup();
            });

            $("#<?='genform_' . $chp_lat ?>, #<?='genform_' . $chp_lon ?>").change(function () {
                var position = [parseFloat($("#<?='genform_' . $chp_lat ?>").val()), parseFloat($("#<?='genform_' . $chp_lon ?>").val())];
                marker.setLatLng(position, {
                    draggable: 'true'
                }).bindPopup(position).update();
                map.panTo(position);
                $("#genform_latLong").val([$("#<?='genform_' . $chp_lat ?>").val(),$("#<?='genform_' . $chp_lon ?>").val()]);
            });
            $("#genform_latLong").change(function () {
                var tabLatLong = $(this).val().split(',');
                var position = [parseFloat(tabLatLong[0]), parseFloat(tabLatLong[1])];
                marker.setLatLng(position, {
                    draggable: 'true'
                }).bindPopup(position).update();
                map.panTo(position);
                $("#<?='genform_' . $chp_lat ?>").val(tabLatLong[0]);
                $("#<?='genform_' . $chp_lon ?>").val(tabLatLong[1]).keyup();
            });

            map.addLayer(marker);


            function onMapClick(e) {
                var center = e.latlng;
                marker.setLatLng(center);
                $("#genform_latLong").val(center.lat + ',' + center.lng);

                $("#<?='genform_' . $chp_lat ?>").val(center.lat);
                $("#<?='genform_' . $chp_lon ?>").val(center.lng).keyup();
            };

            map.on('click', onMapClick);

            $('#map_field_<?php echo $name ?> button.usecenter').click(function (e) {
                e.preventDefault();
                marker.setLatLng(map.getCenter());

                $('#genform_<?php echo $chp_lat ?>').val(map.getCenter().lat);
                $('#genform_<?php echo $chp_lon ?>').val(map.getCenter().lng);
                return false;
            });


        });
    </script>

    <style type="text/css">
        #genform_formulaire .leaflet-control-geosearch input[type=text] {
            width: 100%;
        }
    </style>


    <?php
} else {


    $ll = $this->tab_default_field[$chp_lat] . ',' . $this->tab_default_field[$chp_lon];
    $this->addBuffer('(' . $ll . ')');
    $this->addBuffer('<a target="_blank" href="https://maps.google.com/?q=(' . $ll . ')"><img src="https://maps.googleapis.com/maps/api/staticmap?size=300x300&sensor=false&center=' . $ll . '&zoom=13&markers=' . $ll . '" alt="" /></a>');
}
