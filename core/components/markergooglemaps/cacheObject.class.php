<?php
class cacheObject{
	/**
     * @var cached reference to singleton instance 
     */
    protected static $instance;
    
    /**
     * gets the instance via lazy initialization (created on first usage)
     *
     * @return self
     */
    public static function getInstance($modx)
    {
        
        if (null === self::$instance) {
            self::$instance = new self($modx);
        }
        return self::$instance;
    }

    /**
     * is not allowed to call from outside: private!
     *
     */
    private function __construct($modx){
		$this->_modx = $modx;
	}

    /**
     * prevent the instance from being cloned
     *
     * @return void
     */
    private function __clone(){}

    /**
     * prevent from being unserialized
     *
     * @return void
     */
    private function __wakeup(){}
	
	protected $_modx = null;
	private $_cache = array();
	
	public function clearCache(){
		$this->_cache = array();
	}
	
	public function getData($resID, $name='pagetitle', $object='modResource', $def='-'){
		if(!isset($this->_cache[$object][$resID])){
			$tmp = ((int)$resID > 0) ? $this->_modx->getObject($object, array('id'=> (int)$resID)) : null;
			$this->_cache[$object][$resID] = ($tmp instanceof $object) ? $tmp->toArray() : array();
		}
		return (isset($this->_cache[$object][$resID][$name])) ? $this->_cache[$object][$resID][$name] : $def;
	}
}