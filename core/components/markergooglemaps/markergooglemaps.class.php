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
    public function getChunk($name,$properties = array()) {
        $chunk = null;
		if(!empty($name)){
			if (!isset($this->chunks[$name])) {
				$chunk = $this->modx->getObject('modChunk',array('name' => $name),true);
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
			$out = $chunk->process($properties);
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
	
}