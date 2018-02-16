<?php
require_once dirname(dirname(dirname(__FILE__))) . '/core/config/config.inc.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('web');

/** @var vkroulette $vkroulette */
$vkr_corepath = $modx->getOption('vkroulette_core_path',null,$modx->getOption('core_path').'components/vkroulette/').'model/vkroulette/';
$vkroulette = $modx->getService('vkroulette','vkroulette',$vkr_corepath);

if (!($vkroulette instanceof vkroulette)) {
	$modx->log(1, 'Не удалось запустить задачу планировщика (CRON) - findwinner.php');
	exit();
}
$modx->log(1, 'INFO: Была запущена задача планировщика -> findwinner.php');

// определяем нового победителя
$vkroulette->findwinner();

// завершаем выполнение файла
exit();
