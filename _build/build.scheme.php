<?php
define('MODX_API_MODE', true);
require_once dirname(dirname(dirname(__FILE__))).'/index.php';

$package = 'markergooglemaps'; // Class name for generation
$prefix = $modx->config['table_prefix']; // table prefix
/*******************************************************/
$Model = dirname(__FILE__).'/model/';
$Schema = dirname(__FILE__).'/schema/';
$xml = $Schema.$package.'.mysql.schema.xml';

rmdir($Model.$package .'/mysql');

$modx->getService('error','error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');
$modx->error->message = null;
$modx->loadClass('transport.modPackageBuilder', '', false, true);
$manager = $modx->getManager();

$generator = $manager->getGenerator();
$generator->writeSchema($xml , $package, 'xPDOObject', $prefix,true);
$generator->parseSchema($xml, $Model);

//$modx->addPackage($package, $Model);

print "Good";