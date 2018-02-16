<?php
// находим файл конфигурации текущего сайта отноистельно пути текущего файла
require_once dirname(dirname(dirname(__FILE__))) . '/core/config/config.inc.php';
// подключаем класс ModX
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
// создаём класс ModX
$modx = new modX();
$modx->initialize('web');

/** @var vkroulette $vkroulette */
$vkr_corepath = $modx->getOption('vkroulette_core_path',null,$modx->getOption('core_path').'components/vkroulette/').'model/vkroulette/';
//printf('vkroulette_core_path = ' . $modx->getOption('vkroulette_core_path') . '<br>');
//printf( 'core_path = ' .$modx->getOption('core_path') . '<br>');
//printf('Вместе = ' . $vkr_corepath . '<br>');
$vkroulette = $modx->getService('vkroulette','vkroulette',$vkr_corepath);

if (!($vkroulette instanceof vkroulette)) {
	$modx->log(1, 'Не удалось запустить задачу планировщика (CRON) - updatemmbrs.php');
	exit();
}
$modx->log(1, 'INFO: Была запущена задача планировщика -> updatemmbrs.php');

// обновляем таблицу участников
$vkroulette->updatemembers();
// обновляем сделанные репосты
$vkroulette->updatemembersreposts();

// завершаем выполнение файла
exit();


// строка запуска планировщика в крон (полный путь к php файлу)
//	/usr/bin/php /var/www/wowkos/data/www/itray.ru/vkroulette/core/components/vkroulette/elements/snippets/snippet.updatemmbrs.php

// новая строка запуска планировщика
//	/usr/bin/php /var/www/wowkos/data/www/itray.ru/vkroulette/core/updatemmbrs.php

// путь к файлу снипета в планировщике
//	/www/itray.ru/vkroulette/core/updatemmbrs.php

// => путь к файлу конфигу
//				vkroulette/core/file
// require_once dirname(dirname(dirname(__FILE__))) . '/config.inc.php';
// dirname(dirname(dirname(__FILE__))) = www/itray.ru

// путь к modx - уже не нужен, т.к. в конфиге прописан
//	/www/itray.ru/core/model/modx/modx.class.php
