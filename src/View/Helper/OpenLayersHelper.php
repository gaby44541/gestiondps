<?php
/*
  CakePHP Google Map V3 - Helper to CakePHP framework that integrates a Google Map in your view
  using Google Maps API V3.

  Copyright (c) 2012 Marc Fernandez Girones: marc.fernandezg@gmail.com

  MIT LICENSE:
  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files (the "Software"), to deal
  in the Software without restriction, including without limitation the rights
  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  copies of the Software, and to permit persons to whom the Software is
  furnished to do so, subject to the following conditions:

  The above copyright notice and this permission notice shall be included in
  all copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
  THE SOFTWARE.

  @author      Marc Fernandez Girones <marc.fernandezg@gmail.com>
  @version     3.0
  @license     OPPL

  Date       May 13, 2010

  This helper uses the latest Google API V3 so you don't need to provide or get any Google API Key

*/

namespace App\View\Helper;

use Cake\View\Helper;
/*
 * CakeMap -- a openlayers integrated application built on CakePHP framework.
 * Dic 30, 2011  Geekia S.L.L.
 *
 * @author      javiermaties <javier@geekia.es>
 * @version     1.0
 * @license     OPPL
 *
 */

class OpenLayersHelper extends Helper {

    var $helpers = array('Html');

    public $scriptpuntos = '';
	
	protected $_defaultConfig = [
		'id' => 'mymap',
		'layers' => [ 
			'Outdoors' => 'mapbox.outdoors',
			'Satellite' => 'mapbox.satellite',
			'Satellites' => 'mapbox.satellite-streets',
			'Streets' => 'mapbox.streets',
			'Dark' => 'mapbox.dark',
			'Grayscale' => 'mapbox.light',
		],
		'overs' => [ 
			'Raon' => ['lat'=>0,'long'=>0],
			'Chatel' => ['lat'=>0,'long'=>0],
		]
	];

    public function map($default, $style = 'width: 100%; height: 100%', $type = null) {


    }
	
    function layers() {
		
		$layer = [];
		$over = [];
		
		$id = $this->getConfig('id');
		$layers = $this->getConfig('layers');
		$overs = $this->getConfig('overs');
		
		foreach( $layers as $layer_key => $layer_val ){
			$layer[] = "\"".$layer_key."\": L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {id: '".$layer_val."', attribution: ''})";
		}
		foreach( $overs as $over_key => $over_val ){
			$over[] = "L.marker([".$over_val['lat'].",".$over_val['long']."]).bindPopup('".$over_key."').addTo(antennes)";
		}		
		$out  = "var antennes = L.layerGroup();\n";
		$out .= implode(',',$over).";\n";
		$out .= "var baseLayers = {".implode(',',$layer)."};\n";
		$out .= "var overLayers = {\"Antennes\":antennes};\n";
		$out .= "L.control.layers(baseLayers, overLayers).addTo(".$id.");\n";
		
		echo $out;
		
    }

    function gpx( $traces = [] ) {
		
		$id = $this->getConfig('id');
		
		$traces = (array) $traces;
		
		foreach( $traces as $trace ){
			echo "new L.GPX('".$trace."', {async: true}).on('loaded', function(e) {
				map.fitBounds(e.target.getBounds());
			}).addTo(".$id.");";			
		}
	
    }

    function routing( $depart_lat , $depart_long , $arrivee_lat , $arrivee_long ) {
		
		//$this->prepend( 'script' , $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.4.0/gpx.min.js') );
		
		$id = $this->getConfig('id');
		
		echo "L.Routing.control({
		  waypoints: [
			L.latLng(".$depart_lat.",".$depart_long."),
			L.latLng(".$arrivee_lat.",".$arrivee_long.")
		  ]
		}).addTo(".$id.")";
	
    }
	
    function addMarkers(&$data, $icon = null, $type = null) {
        $out = "<script type=\"text/javascript\">
			//<![CDATA[
			";
        
        if ($type=='cloudmade'){
            $out .= '
                var markers = [];
                ';
        }
        
        for ($i = 0; $i < count($data); $i++) {
            switch ($type) {
                case 'cloudmade':
                    $out .='
                        var pos = new CM.LatLng(' . $data[$i]['lat'] . ',' . $data[$i]['long'] . ');
                        var marca = new CM.Marker(pos);
                        markers.push(marca);
                        marca.bindInfoWindow("' . $data[$i]['title'] . '");
                        /*map.openInfoWindow(pos, "' . $data[$i]['title'] . '", {maxWidth: 200});*/';
                        
                    break;
                
                
                case 'leaflet':
                    $out .='
                        var markerLocation = new L.LatLng(' . $data[$i]['lat'] . ',' . $data[$i]['long'] . ');
                        var marker = new L.Marker(markerLocation);
                        map.addLayer(marker);
                        marker.bindPopup("' . $data[$i]['title'] . '");
            ';
                    break;
                default:
                    break;
            }
        }
        if ($type=='cloudmade'){
            $out .= '
                var clusterer = new CM.MarkerClusterer(map, {clusterRadius: 70});
                clusterer.addMarkers(markers);';
        }
        $out .= "
				//]]>
			</script>";
        return $out;
    }
	
}
?>