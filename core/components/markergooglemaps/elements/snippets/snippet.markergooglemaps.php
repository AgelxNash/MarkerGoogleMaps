<?php
$markergooglemaps = $modx->getService('MarkerGoogleMaps','MarkerGoogleMaps', $modx->getOption('markergooglemaps.core_path', null, $modx->getOption('core_path').'components/markergooglemaps/'), $scriptProperties);
if (!($markergooglemaps instanceof markergooglemaps)) return '';

$scriptProperties = $markergooglemaps->checkProps($scriptProperties);
$page = $scriptProperties['page'];
$scriptProperties['page'] = implode(",", $scriptProperties['page']);

$markergooglemaps->loadFront($scriptProperties);

$plh = array();
if($scriptProperties['cacheName']){
	$plh = $markergooglemaps->getCache($scriptProperties['cacheName']);
}
if(empty($plh)){
	$query = $markergooglemaps->getQuery($scriptProperties);
	$totalStores = $modx->getCount('gmMarker', $query);
	$stores = $modx->getCollection('gmMarker', $query);

	$storeListOutput = '';
    $matchedStores = $i = 0;
    foreach($stores as $store) {
		$resId = $store->get('resource_id');
		$resourceArray = $tvArray = array();
		if(empty($resId)){
			$resource = true;
            $resourceArray = $markergooglemaps->getEmptyFields('modResource');
		}else{
			$resource = $modx->getObject('modResource', $resId);
			if($resource!==null){
			  if (!empty($scriptProperties['includeTVs'])) {
				$tvs = $resource->getMany('TemplateVars');
				foreach($tvs as $tv) {
				  if($scriptProperties['processTVs']) {
					$tvArray[$scriptProperties['tvPrefix'] . $tv->get('name')] = $tv->renderOutput($store->get('resource_id'));
				  } else {
					$value = $tv->getValue($store->get('resource_id'));
					if ($scriptProperties['prepareTVs'] && method_exists($tv, 'prepareOutput')) {
					  $value = $tv->prepareOutput($value);
					}
					$tvArray[$scriptProperties['tvPrefix'] . $tv->get('name')] = $value;
				  }
				}
			  }
				$resourceArray = $resource->toArray();
			}else{
                $totalStores--;
            }
		}

		if($resource!==null){
			$storeArray = $store->toArray();

            $props = array_merge(
                $resourceArray,
                $tvArray,
                array(
                    'store' => $storeArray,
                    'totalStores' => $totalStores,
                    'config' => $scriptProperties,
                )
            );
            $storeListOutput .= $markergooglemaps->getChunk($scriptProperties['markerListTpl'], $props);
			$i++;
			$matchedStores++;
		}
	}

	if ($i == 0) {
		$storeListOutput = $markergooglemaps->getChunk($scriptProperties['noResultsTpl']);
	}

	$plh = array(
        'storeList' => $storeListOutput,
        'totalStores' => $totalStores,
        'matchedStores' => $matchedStores,
        'map' => $markergooglemaps->getChunk($scriptProperties['scriptWrapperTpl'], $scriptProperties)
	);

	if($cacheName){
		$markergooglemaps->setCache($scriptProperties['cacheName'],$plh);
	}
}
$modx->toPlaceHolders($plh, 'markergooglemaps');