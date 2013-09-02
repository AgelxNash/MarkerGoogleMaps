<?php
/**
* Package in plugins
*
* @package defaultcomponent
* @subpackage build
*/
$plugins = array();
$activeEvent = array(
    'OnSiteRefresh',
    'OnDocFormSave'
);

$plugin = $modx->newObject('modPlugin');
$plugin->fromArray(array(
    'name' => 'MarkerGoogleMaps.ClearCache',
    'description' => 'MarkerGoogleMaps clear cache snippets',
    'plugincode' => getSnippetContent($sources['source_core'].'/elements/plugins/plugin.cachemarkergooglemaps.php'),
    'static' => false,
    'source' => 1,
    'static_file' => 'core/components/'.PKG_NAME_LOWER.'/elements/plugins/plugin.cachemarkergooglemaps.php'
),'',true,true);

$events = array();
foreach ($activeEvent as $eventName) {
    /* @var $event modPluginEvent */
    $event = $modx->newObject('modPluginEvent');
    $event->fromArray(array(
        'event' => $eventName,
        'priority' => 0,
        'propertyset' => 0,
    ),'',true,true);
    $events[] = $event;
}

if (!empty($events)) {
    $plugin->addMany($events);
}

$plugins[] = $plugin;
return $plugins;