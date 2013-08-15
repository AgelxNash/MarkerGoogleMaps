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
/**
* @package StoreLocator
* @subpackage build
*/
$properties = array(
    array(
        'name' => 'apiKey',
        'desc' => 'markergooglemaps.prop_apikey_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'markergooglemaps:properties',
    ),
    array(
        'name' => 'zoom',
        'desc' => 'markergooglemaps.prop_zoom_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '8',
        'lexicon' => 'markergooglemaps:properties',
    ),
    array(
        'name' => 'storeZoom',
        'desc' => 'markergooglemaps.prop_storezoom_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '13',
        'lexicon' => 'markergooglemaps:properties',
    ),
    array(
        'name' => 'width',
        'desc' => 'markergooglemaps.prop_width_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '300',
        'lexicon' => 'markergooglemaps:properties',
    ),
    array(
        'name' => 'height',
        'desc' => 'markergooglemaps.prop_height_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '400',
        'lexicon' => 'markergooglemaps:properties',
    ),
    array(
        'name' => 'mapType',
        'desc' => 'markergooglemaps.prop_maptype_desc',
        'type' => 'list',
        'options' => array(
			array(
				'text' => 'markergooglemaps.hybrid',
				'value' => 'HYBRID'
			),
			array(
				'text' => 'markergooglemaps.roadmap',
				'value' => 'ROADMAP'
			),
			array(
				'text' => 'markergooglemaps.satellite',
				'value' => 'SATELLITE'
			),
			array(
				'text' => 'markergooglemaps.terrain',
				'value' => 'TERRAIN'
			),
		),
        'value' => 'ROADMAP',
        'lexicon' => 'markergooglemaps:properties',
    ),
    array(
        'name' => 'centerLongitude',
        'desc' => 'markergooglemaps.prop_centerlongitude_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '6.61480',
        'lexicon' => 'markergooglemaps:properties',
    ),
    array(
        'name' => 'centerLatitude',
        'desc' => 'markergooglemaps.prop_centerlatitude_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '52.40441',
        'lexicon' => 'markergooglemaps:properties',
    ),
    array(
        'name' => 'markerImage',
        'desc' => 'markergooglemaps.prop_markerimage_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '0',
        'lexicon' => 'markergooglemaps:properties',
    ),
    array(
        'name' => 'sortDir',
        'desc' => 'markergooglemaps.prop_sortdir_desc',
        'type' => 'list',
        'options' => array(
			array(
				'text' => 'markergooglemaps.asc',
				'value' => 'ASC'
			),
			array(
				'text' => 'markergooglemaps.desc',
				'value' => 'DESC'
			)
		),
        'value' => 'ASC',
        'lexicon' => 'markergooglemaps:properties',
    ),
    array(
        'name' => 'limit',
        'desc' => 'markergooglemaps.prop_limit_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '0',
        'lexicon' => 'markergooglemaps:properties',
    ),
    array(
        'name' => 'storeRowTpl',
        'desc' => 'markergooglemaps.prop_storerowtpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'sl.storerow',
        'lexicon' => 'markergooglemaps:properties',
    ),
    array(
        'name' => 'storeInfoWindowTpl',
        'desc' => 'markergooglemaps.prop_storeinfowindowtpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'sl.infowindow',
        'lexicon' => 'markergooglemaps:properties',
    ),
    array(
        'name' => 'noResultsTpl',
        'desc' => 'markergooglemaps.prop_noresultstpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'sl.noresultstpl',
        'lexicon' => 'markergooglemaps:properties',
    ),
    array(
        'name' => 'scriptWrapperTpl',
        'desc' => 'markergooglemaps.prop_scriptwrappertpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'sl.scriptwrapper',
        'lexicon' => 'markergooglemaps:properties',
    ),
    array(
        'name' => 'scriptStoreMarker',
        'desc' => 'markergooglemaps.prop_scriptstoremarker_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'sl.scriptstoremarker',
        'lexicon' => 'markergooglemaps:properties',
    ),
	array(
        'name' => 'cacheName',
        'desc' => 'markergooglemaps.prop_cacheName_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'markergooglemaps:properties',
    ),
);

return $properties;