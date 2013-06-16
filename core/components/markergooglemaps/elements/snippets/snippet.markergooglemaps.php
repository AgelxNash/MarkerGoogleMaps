<?php
/**
 * StoreLocator
 *
 * Copyright 2011-12 by SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
 *
 * This file is part of StoreLocator.
 *
 * StoreLocator is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * StoreLocator is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * StoreLocator; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package StoreLocator
 */
// Load the userValueList class
$markergooglemaps = $modx->getService('MarkerGoogleMaps','MarkerGoogleMaps', $modx->getOption('markergooglemaps.core_path', null, $modx->getOption('core_path').'components/markergooglemaps/'), $scriptProperties);
if (!($markergooglemaps instanceof markergooglemaps)) return '';

// Configuration parameters
$apiKey = $modx->getOption('apiKey', $scriptProperties, $modx->getOption('markergooglemaps.apiKey'));
$zoom = $modx->getOption('zoom', $scriptProperties, 8);
$storeZoom = $modx->getOption('storeZoom', $scriptProperties, 13);
$width = $modx->getOption('width', $scriptProperties, 300);
$height = $modx->getOption('height', $scriptProperties, 400);
$mapType = $modx->getOption('mapType', $scriptProperties, 'ROADMAP');
$centerLongitude = $modx->getOption('centerLongitude', $scriptProperties, 6.61480);
$centerLatitude = $modx->getOption('centerLatitude', $scriptProperties, 52.40441);
$markerImage = $modx->getOption('markerImage', $scriptProperties, '0');
$sortDir = $modx->getOption('sortDir', $scriptProperties, 'ASC');
$sortBy = $modx->getOption('sortBy', $scriptProperties, 'sort');
$limit = $modx->getOption('limit', $scriptProperties, 0);

$tvPrefix = $modx->getOption('tvPrefix', $scriptProperties,'tv.');
$includeTVs = $modx->getOption('includeTVs',$scriptProperties,0);
$prepareTVs = $modx->getOption('prepareTVs',$scriptProperties,1);
$processTVs = $modx->getOption('processTVs',$scriptProperties,0);

$page = $modx->getOption('page', $scriptProperties, $modx->resource->id);


$storeRowTpl = $modx->getOption('storeRowTpl', $scriptProperties, 'sl.storerow');
$storeInfoWindowTpl = $modx->getOption('storeInfoWindowTpl', $scriptProperties, 'sl.infowindow');
$noResultsTpl = $modx->getOption('noResultsTpl', $scriptProperties, 'sl.noresultstpl');

// Developers templating parameters
$scriptWrapperTpl = $modx->getOption('scriptWrapperTpl', $scriptProperties, 'sl.scriptwrapper');
$scriptStoreMarker = $modx->getOption('scriptStoreMarker', $scriptProperties, 'sl.scriptstoremarker');

// Load lexicon
$modx->lexicon->load('markergooglemaps:frontend');

// Register the google maps API
if ($apiKey != '') {
	$modx->regClientStartupScript('http://maps.googleapis.com/maps/api/js?sensor=false&key='.$apiKey);
} else {
	$modx->regClientStartupScript('http://maps.googleapis.com/maps/api/js?sensor=false');
}

$modx->regClientStartupHTMLBlock($markergooglemaps->getChunk($scriptWrapperTpl, array(
	'centerLatitude' => $centerLatitude,
	'centerLongitude' => $centerLongitude,
	'zoom' => $zoom,
	'mapType' => $mapType
)));

// Parse store chunks
$query = $modx->newQuery('gmMarker')
              ->sortby($sortBy, $sortDir)
              ->limit($limit);
if($page>0){
    $query->where(array(
        'destpage_id:='=>$page,
    ));
}
$totalStores = $modx->getCount('gmMarker', $query);
$stores = $modx->getCollection('gmMarker', $query);
$storeListOutput = '';
$i = 0;
$matchedStores = 0;
foreach($stores as $store) {

	$resource = $modx->getObject('modResource', $store->get('resource_id'));
	
	// Get TVs that belong to resource
  $tvArray = array();
  if (!empty($includeTVs)) {
    $tvs = $resource->getMany('TemplateVars');
    foreach($tvs as $tv) {
      if($processTVs) {
        $tvArray[$tvPrefix . $tv->get('name')] = $tv->renderOutput($store->get('resource_id'));
      } else {
        $value = $tv->getValue($store->get('resource_id'));
        if ($prepareTVs && method_exists($tv, 'prepareOutput')) {
          $value = $tv->prepareOutput($value);
        }
        $tvArray[$tvPrefix . $tv->get('name')] = $value;
      }
    }
  }

	// If the resource doesn't exist just skip it
	if ($resource != null) {
		$storeArray = $store->toArray();
		$resourceArray = $resource->toArray();
		$storeListOutput .= $markergooglemaps->getChunk($storeRowTpl, array_merge(
			$resourceArray,
			$tvArray,
			array(
				'store' => $storeArray,
				'totalStores' => $totalStores,
				'onclick' => 'markergooglemapsMap.setCenter(new google.maps.LatLng('.$store->get('latitude').','.$store->get('longitude').')); markergooglemapsMap.setZoom('.$storeZoom.');'
			)
		));

        $storeListOutput .= $markergooglemaps->getChunk($storeInfoWindowTpl, array_merge(
			$resourceArray,
			$tvArray,
			array(
				'store' => $storeArray,
				'totalStores' => $totalStores
			)
		));
		$storeListOutput .= $markergooglemaps->getChunk($scriptStoreMarker, array_merge(
			$resourceArray,
			$tvArray,
			array(
				'store' => $storeArray,
				'markerImage' => $markerImage
			)
		));
		
		$i++;
		$matchedStores++;
	}
}
 
// Nothing is found
if ($i == 0) {
	$storeListOutput = $markergooglemaps->getChunk($noResultsTpl);
}

// Parse output to place holders
$modx->toPlaceHolders(array(
	'map' => "<div id=\"markergooglemaps_canvas\" style=\"width: {$width}px; height: {$height}px;\"></div><style>#markergooglemaps_canvas img{max-width: none;}</style>",
	'storeList' => $storeListOutput,
	'totalStores' => $totalStores,
	'matchedStores' => $matchedStores
), 'markergooglemaps');