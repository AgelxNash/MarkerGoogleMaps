<?php
class gmMarker extends xPDOSimpleObject {
    public function toArray($keyPrefix= '', $rawValues= false, $excludeLazy= false, $includeRelated= false) {
        $array = parent::toArray($keyPrefix, $rawValues, $excludeLazy, $includeRelated);
        return $array;
    }
    public function get($k, $format = null, $formatTemplate= null) {
        $data=parent::get($k, $format = null, $formatTemplate= null);
        if(in_array($k,array('latitude','longitude'))){
            $data = str_replace(",",".",$data);
        }
        return $data;
    }
}