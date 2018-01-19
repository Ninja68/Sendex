<?php
// listing of variables from 'properties.vkroulette.php' to understand phpStorm
/** @var $name1 */
/**	@var $name2 */
/**	@var $name3 */
/**	@var $tplmember */

/** @var array $scriptProperties */
/** @var vkroulette $vkroulette */
$vkroulette = $modx->getService('vkroulette','vkroulette',$modx->getOption('vkroulette_core_path',null,$modx->getOption('core_path').'components/vkroulette/').'model/vkroulette/',$scriptProperties);

/** @var pdoTools $pdoTools */
//$pdoTools = $modx->getService('pdoTools');

if (!($vkroulette instanceof vkroulette)) return '';
//if (!($vkroulette instanceof vkroulette) || !($pdoTools instanceof pdoTools)) return '';


// ----------------------------------

//printf('<br>начальное значение $tplmember - <br>');
//print_r($tplmember);
if (empty($tplmember)) {$tplmember = 'tpl.vkroulette.member';}


if (!$member = $modx->getObject('vkrmembers',"")) {
	print $modx->lexicon('vkroulette_member_err_ns');
	//print('какого хрена');
	//print_r($txt);
}
else{
// здесь мы конвертируем наш объект (в данном случае таблицу) "vkrmembers" в массив
	$placeholders = $member->toArray();
// а далее дополняем этот массив нашими переменными, доступными из свойсв properties "\_build\properties\properties.vkroulette.php"
	$placeholders['name1'] = $name1;
//$placeholders['message'] = 'here is the text of the message';
	$placeholders['description'] = 'description here';
	$placeholders['name2'] = 'f***ing name 2';
	$placeholders['parameters_token'] = $modx->getOption('vkroulette_groupparam_token');

//$output = !empty($tplmember)
//	? $pdoTools->getChunk($tplmember, $placeholders)
//	: 'Parameter "tplmember" is empty';

//printf('<br>обработанный ПДО тулс $output - <br>');
//print_r($output);
// ----------------------------------

	$output = $modx->getChunk($tplmember, $placeholders);

}


// выполним заполнение базы
$fill_res = array();
$vkroulette->fillmembers($fill_res);
//$vkroulette->pretty_print($fill_res,false,'ChekedRepost');

$vkroulette->updatemembers();

/* by default just return output */
return '';