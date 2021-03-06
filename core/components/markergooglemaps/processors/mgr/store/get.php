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
$store = $modx->getObject('gmMarker', (int)$id);

$storeArray = $store->toArray();
$storeArray['destpage_id'] = $cacheObj->getData($storeArray['destpage_id'], 'id');
$storeArray['resource_id'] = $cacheObj->getData($storeArray['resource_id'], 'id');
return $modx->error->success('', $storeArray);