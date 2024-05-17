<?php
/* 
 * This file require for autoload application classes
 * Infrastructure classes load manualy with require or require_one
 */
require __DIR__ .'/ClassLoader.php';

//load class which is base class (other class extend base class)
// example: BaseModel, BaseSearchModel
$baseClasses = [
	__DIR__.'/../Models/BaseModel.php',
	__DIR__.'/../SearchModels/BaseSearchModel.php'
];
foreach($baseClasses as $baseclass) {
  require realpath($baseclass);
}

//load model classes
$models = [
	__DIR__.'/../Models/UserModel.php'
];
foreach($models as $model) {
  require realpath($model);
}

//load search model classes
$srchmodels = [
	__DIR__.'/../SearchModels/OrderSearchModel.php',
	__DIR__.'/../SearchModels/DispatchSearchModel.php',
	__DIR__.'/../SearchModels/BoxSearchModel.php',
	__DIR__.'/../SearchModels/PackSearchModel.php',
	__DIR__.'/../SearchModels/LppmGridSearchModel.php',
	__DIR__.'/../SearchModels/FakePlateSearchModel.php'
];
foreach($srchmodels as $srchmodel) {
  require realpath($srchmodel);
}


//APP\Infrastructure\ClassLoader::loadFiles(__DIR__. '/../Models');
APP\Infrastructure\ClassLoader::loadFiles(__DIR__. '/../Controllers');
APP\Infrastructure\ClassLoader::loadFiles(__DIR__. '/../Databases');
APP\Infrastructure\ClassLoader::loadFiles(__DIR__. '/../Helpers');
APP\Infrastructure\ClassLoader::loadFiles(__DIR__. '/../ModelsSupport');
APP\Infrastructure\ClassLoader::loadFiles(__DIR__. '/../ViewExtensions');
