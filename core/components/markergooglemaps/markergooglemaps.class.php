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
 * This file is the main class file for StoreLocator.
 *
 * @copyright Copyright (C) 2011, SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
 * @author SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @package storelocator
 */
class MarkerGoogleMaps {
    /**
     * A reference to the modX object.
     * @var modX $modx
     */
    public $modx = null;
    /**
     * The request object for the current state
     * @var markergooglemapsControllerRequest $request
     */
    public $request;
    /**
     * The controller for the current request
     * @var markergooglemapsController $controller
     */
    public $controller = null;
    private $_cacheEmptyField = array();
	private $chunks = array();
	private $pageInfo = array();
	private $_cacheConfig = array();
	const CACHE_KEY = 'markergooglemaps';
	
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('markergooglemaps.core_path',null,$modx->getOption('core_path').'components/markergooglemaps/');
        $assetsPath = $this->modx->getOption('markergooglemaps.assets_path',null,$modx->getOption('assets_path').'components/markergooglemaps/');
        $assetsUrl = $this->modx->getOption('markergooglemaps.assets_url',null,$modx->getOption('assets_url').'components/markergooglemaps/');

        $this->config = array_merge(array(
            'corePath' => $corePath,
            'modelPath' => $corePath.'model/',
            'processorsPath' => $corePath.'processors/',
            'controllersPath' => $corePath.'controllers/',
            'chunksPath' => $corePath.'elements/chunks/',
            'snippetsPath' => $corePath.'elements/snippets/',

            'baseUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl.'css/',
            'jsUrl' => $assetsUrl.'js/',
            'connectorUrl' => $assetsUrl.'connector.php'
        ),$config);

        $this->modx->addPackage('markergooglemaps', $this->config['modelPath']);
        
        if ($this->modx->lexicon) {
            $this->modx->lexicon->load('markergooglemaps:default');
        }
		
		$this->_cacheConfig = array(
			xPDO::OPT_CACHE_KEY => $modx->getOption(
				'cache_source', 
				$config, 
				self::CACHE_KEY
			),
			xPDO::OPT_CACHE_HANDLER => $modx->getOption(
				'cache_source_markergooglemaps', 
				$config, 
				$modx->getOption(xPDO::OPT_CACHE_HANDLER)
			),
			xPDO::OPT_CACHE_FORMAT => (integer) $modx->getOption(
				'cache_resource_format', 
				$config, 
				$modx->getOption(
					xPDO::OPT_CACHE_FORMAT, 
					$config, 
					xPDOCacheManager::CACHE_PHP
				)
			)
		);
    }

    /**
     * Initializes markergooglemaps based on a specific context.
     *
     * @access public
     * @param string $ctx The context to initialize in.
     * @return string The processed content.
     */
    public function initialize($ctx = 'mgr') {
        $output = '';
        switch ($ctx) {
            case 'mgr':
                if (!$this->modx->loadClass('markergooglemaps.request.markergooglemapsControllerRequest',$this->config['modelPath'],true,true)) {
                    return 'Could not load controller request handler.';
                }
                $this->request = new MarkerGoogleMapsControllerRequest($this);
                $output = $this->request->handleRequest();
                break;
        }
        return $output;
    }
    public function getCache($name){
		return $this->modx->cacheManager->get($name,$this->_cacheConfig);
	}
	public function setCache($name,$data){
		return $this->modx->cacheManager->set($name, $data, 0, $this->_cacheConfig);
	}
	public function clearCache(){
		$path = $this->_cacheConfig[xPDO::OPT_CACHE_KEY];
		return $this->modx->cacheManager->clearCache(array($path));
	}
    /**
     * Load the appropriate controller
     * @param string $controller
     * @return null|markergooglemapsController
     */
    public function loadController($controller) {
        if ($this->modx->loadClass('markergooglemapsController',$this->config['modelPath'].'markergooglemaps/request/',true,true)) {
            $classPath = $this->config['controllersPath'].'web/'.$controller.'.php';
            $className = 'markergooglemaps'.$controller.'Controller';
            
            if (file_exists($classPath)) {
                if (!class_exists($className)) {
                    $className = require_once $classPath;
                }
                if (class_exists($className)) {
                    $this->controller = new $className($this,$this->config);
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR,'[markergooglemaps] Could not load controller: '.$className.' at '.$classPath);
                }
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR,'[markergooglemaps] Could not load controller file: '.$classPath);
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[markergooglemaps] Could not load markergooglemapsController class.');
        }
        return $this->controller;
    }
	
    /**
    * Gets a Chunk and caches it; also falls back to file-based templates
    * for easier debugging.
    *
    * @author Shaun McCormick
    * @access public
    * @param string $name The name of the Chunk
    * @param array $properties The properties for the Chunk
    * @return string The processed content of the Chunk
    */
    public function _getChunk($name,$properties = array()) {
        $chunk = null;
        if (!isset($this->chunks[$name])) {
            $chunk = $this->modx->getObject('modChunk',array('name' => $name), false);
            if (empty($chunk)) {
                $chunk = $this->_getTplChunk($name);
                if ($chunk == false) return false;
            }
            $this->chunks[$name] = $chunk->getContent();
        } else {
            $o = $this->chunks[$name];
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($o);
        }
        $chunk->setCacheable(false);
        return $chunk;
    }
    public function getChunk($name,$properties = array()){
        if(!empty($name)){
            $chunk = $this->_getChunk($name);
            if(is_object($chunk)){
                $plh = $this->getPlh($properties);
                $out = $chunk->getContent();
                $out = str_replace(array_keys($plh), array_values($plh), $out);
                if(false!==($pos=strpos($out,"[[+"))){
                    echo $name." - ".$pos."<br />";
                    $chunk->setContent($out);
                    $out = $chunk->process($properties);
                }
            }
        }else{
            $out = '';
        }
        return $out;
    }
    /**
    * Returns a modChunk object from a template file.
    *
    * @author Shaun McCormick
    * @access private
    * @param string $name The name of the Chunk. Will parse to name.chunk.tpl
    * @param string $postFix The postfix to append to the name
    * @return modChunk/boolean Returns the modChunk object if found, otherwise
    * false.
    */
    private function _getTplChunk($name,$postFix = '.tpl') {
        $chunk = false;
        $f = $this->config['chunksPath'].$name.$postFix;

        if (file_exists($f)) {
            $o = file_get_contents($f);
            /* @var modChunk $chunk */
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name',$name);
            $chunk->setContent($o);
        }
        return $chunk;
    }

    private function getPlh($data,$prefix=''){
        $out = array();
        foreach($data as $name=>$item){
            if(is_array($item)){
                $out = array_merge($out,$this->getPlh($item,$name));
            }else{
                $tmp = "[[+".(!empty($prefix) ? $prefix."." : "") . $name."]]";
                $out[$tmp] = $item;
            }
        }
        return $out;
    }
	public function getPageInfo($id){
		return isset($this->pageInfo[$id]) ? $this->pageInfo[$id] : array();
	}

	public function PageInfo(array $ids){
		$this->pageInfo = array();
		if(is_array($ids) && !empty($ids)){
			if($ids == array($this->modx->resource->get('id'))){
				$this->pageInfo[$this->modx->resource->get('id')] = $this->modx->resource->toArray();
			}else{
				$q = $this->modx->newQuery("modResource")->where("id:IN", $ids);
				$q = $this->modx->getCollection("modResource",$q);
				foreach($q as $page){
					$this->pageInfo[$page->get('id')] = $page->toArray();
				}
			}
		}
		return $this->pageInfo;
	}
    public function checkProps($scriptProperties){
        $scriptProperties['apiKey'] = $this->modx->getOption('apiKey', $scriptProperties, $this->modx->getOption('markergooglemaps.apiKey'));
        $scriptProperties['zoom'] = $this->modx->getOption('zoom', $scriptProperties, 8);
        $scriptProperties['storeZoom'] = $this->modx->getOption('storeZoom', $scriptProperties, 13);
        $scriptProperties['width'] = $this->modx->getOption('width', $scriptProperties, 300);
        $scriptProperties['height'] = $this->modx->getOption('height', $scriptProperties, 400);
        $scriptProperties['mapType'] = $this->modx->getOption('mapType', $scriptProperties, 'ROADMAP');
        $scriptProperties['centerLongitude'] = $this->modx->getOption('centerLongitude', $scriptProperties, 6.61480);
        $scriptProperties['centerLatitude'] = $this->modx->getOption('centerLatitude', $scriptProperties, 52.40441);
        $scriptProperties['markerImage'] = $this->modx->getOption('markerImage', $scriptProperties, '');

        $scriptProperties['sortDir'] = $this->modx->getOption('sortDir', $scriptProperties, 'ASC');
        $scriptProperties['sortBy'] = $this->modx->getOption('sortBy', $scriptProperties, 'sort');
        $scriptProperties['limit'] = $this->modx->getOption('limit', $scriptProperties, 0);

        $scriptProperties['tvPrefix'] = $this->modx->getOption('tvPrefix', $scriptProperties,'tv.');
        $scriptProperties['includeTVs'] = $this->modx->getOption('includeTVs',$scriptProperties,0);
        $scriptProperties['prepareTVs'] = $this->modx->getOption('prepareTVs',$scriptProperties,1);
        $scriptProperties['processTVs'] = $this->modx->getOption('processTVs',$scriptProperties,0);
        $scriptProperties['where'] = $this->modx->getOption('where',$scriptProperties,'');

        $scriptProperties['page'] = $this->cleanIDs($this->modx->getOption('page', $scriptProperties, $this->modx->resource->id));

        $scriptProperties['markerListTpl'] = $this->modx->getOption('markerListTpl', $scriptProperties,'sl.markerlist');
        $scriptProperties['noResultsTpl'] = $this->modx->getOption('noResultsTpl', $scriptProperties, 'sl.noresultstpl');
        $scriptProperties['scriptWrapperTpl'] = $this->modx->getOption('scriptWrapperTpl', $scriptProperties, 'sl.scriptwrapper');

        $scriptProperties['autoPosition'] = $this->modx->getOption('autoPosition', $scriptProperties, '1');
        $scriptProperties['cacheName'] = $this->modx->getOption('cacheName', $scriptProperties, null);
        $scriptProperties['jsName'] = $this->modx->getOption('jsName', $scriptProperties, 'mgmaps.js');
        return $scriptProperties;
    }
	final public function cleanIDs($IDs,$sep=',') {
        $out=array();
        if(!is_array($IDs)){
            $IDs=explode($sep,$IDs);
        }
        foreach($IDs as $item){
            $item = trim($item);
            if(is_numeric($item) && (int)$item>=0){ //Fix 0xfffffffff 
                $out[]=(int)$item;
            }
        }
        $out = array_unique($out);
		return $out;
	}
	public function loadFront($prop){
        $this->modx->lexicon->load('markergooglemaps:frontend');
        $jMaps = 'http://maps.googleapis.com/maps/api/js?sensor=false';
        if ($prop['apiKey'] != '') {
            $jMaps .= '&key='.$prop['apiKey'];
        }
        $this->modx->regClientStartupScript($jMaps);
        $this->modx->regClientStartupScript($this->config['jsUrl'].$prop['jsName']);
    }

    public function getQuery($prop){
        $query = $this->modx->newQuery('gmMarker')->sortby($prop['sortBy'], $prop['sortDir']);
        if($prop['limit']>0){
            $query->limit($prop['limit']);
        }
        $where = $this->modx->fromJSON($prop['where']);
        if(!is_array($where)){
            $where = array();
        }
        if(!empty($page)){
            array_merge($where, array(
                'destpage_id:IN'=>$page
            ));
        }
        if(!empty($where)){
            $query->where($where);
        }

        $query->innerJoin("modResource","modResource","modResource.id = gmMarker.destpage_id");
        $query->select(array(
            $this->modx->getSelectColumns('modResource','modResource','',array('id'),true),
            $this->modx->getSelectColumns('gmMarker','gmMarker')
        ));
        return $query;
    }
    public function getEmptyFields($class){
        if(!isset($this->_cacheEmptyField[$class])){
            $data = array_keys($this->modx->getFieldMeta($class));
            $this->_cacheEmptyField[$class] = array_fill_keys($data, '');
        }
        return $this->_cacheEmptyField[$class];
    }
}