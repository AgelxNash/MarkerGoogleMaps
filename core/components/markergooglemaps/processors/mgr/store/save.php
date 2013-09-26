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
 
if (!$modx->user->isAuthenticated('mgr')) return $modx->error->failure($modx->lexicon('permission_denied'));

include_once(dirname(dirname(dirname(dirname(__FILE__))))."/cacheObject.class.php");
$cacheObj = cacheObject::getInstance($modx);


$id = isset($data['id']) ? $data['id'] : $_REQUEST['id'];
$id = (int)$id;
$config = isset($data['storeConfig']) ? $data['storeConfig'] : $_REQUEST['storeConfig'];
$storeData = json_decode($config, true);

if ($id == 0) {
	// Create a new store
	$store = $modx->newObject('gmMarker');
	
	$highest = $modx->getObject(
		'gmMarker',
		$modx->newQuery('gmMarker')
			->limit(1)
			->sortby('sort', 'DESC')
	);
	
	$storeData['sort'] = 1;
	$storeData['sort'] += ($highest == null) ? 0 : $highest->get('sort');
	
} else {
	
	$store = $modx->getObject('gmMarker', $id);
}

if($store instanceof gmMarker){
	$storeData['destpage_id'] = $cacheObj->getData($storeData['destpage_id'], 'id', 'modResource', '0');
	$storeData['resource_id'] = $cacheObj->getData($storeData['resource_id'], 'id', 'modResource', '0');

	$store->fromArray($storeData);
	// Save the store
	$store->save();

	// Return it
	$storeArray = $store->toArray();
	$out = $modx->error->success('', $storeArray);
}else{
	$out = $modx->error->failure($modx->lexicon('markergooglemaps.error_update_store'));
}

$modx->mgmaps->clearCache();
return $out;