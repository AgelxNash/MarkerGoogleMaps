<?php
$activeEvent = array(
    'OnSiteRefresh',
    'OnDocFormSave'
);

if(in_array($modx->event->name, $activeEvent)) {
    $markergooglemapsCorePath = $modx->getOption('markergooglemaps.core_path',null,$modx->getOption('core_path').'components/markergooglemaps/');
    require_once $markergooglemapsCorePath.'markergooglemaps.class.php';
    $mgmaps = new MarkerGoogleMaps($modx);
    $mgmaps->clearCache();
}