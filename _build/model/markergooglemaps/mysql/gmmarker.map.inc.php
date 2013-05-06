<?php
$xpdo_meta_map['gmMarker']= array (
  'package' => 'markergooglemaps',
  'version' => '1.1',
  'table' => 'googlemaps_marker',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'description' => '',
    'latitude' => 0,
    'longitude' => 0,
    'resource_id' => 0,
    'sort' => 0,
    'config' => NULL,
    'destpage_id' => 0,
  ),
  'fieldMeta' => 
  array (
    'description' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '150',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'latitude' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '9,6',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'longitude' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '9,6',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'resource_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'sort' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'config' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'destpage_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
    'sort' => 
    array (
      'alias' => 'sort',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'sort' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'resource_id' => 
    array (
      'alias' => 'resource_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'resource_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'destpage_id' => 
    array (
      'alias' => 'destpage_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'destpage_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
