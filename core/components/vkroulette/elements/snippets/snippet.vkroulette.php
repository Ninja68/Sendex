<?php
// listing of variables from 'properties.vkroulette.php' to understand phpStorm
/** @var $name1 */
/**	@var $name2 */
/**	@var $name3 */
/**	@var $tplmember */

/** @var array $scriptProperties - массив свойств _build\properties\properties.vkroulette.php
			который формируется в файле _build\data\transport.snippets.php*/
/** @var vkroulette $vkroulette */
$vkr_corepath = $modx->getOption('vkroulette_core_path',null,$modx->getOption('core_path').'components/vkroulette/').'model/vkroulette/';
$vkroulette = $modx->getService('vkroulette','vkroulette',$vkr_corepath,$scriptProperties);

if (!($vkroulette instanceof vkroulette)) return '';

// ----------------------------------

//printf('<br>начальное значение $tplmember - <br>');
//print_r($tplmember);
if (empty($tplmember)) {$tplmember = 'tpl.vkroulette.member';}


//if (!$member = $modx->getObject('vkrmembers',"")) {
//	print $modx->lexicon('vkroulette_member_err_ns');
//	//print('какого хрена');
//	//print_r($txt);
//}
//else{
//// здесь мы конвертируем наш объект (в данном случае таблицу) "vkrmembers" в массив
//	$placeholders = $member->toArray();
//// а далее дополняем этот массив нашими переменными, доступными из свойсв properties "\_build\properties\properties.vkroulette.php"
//	$placeholders['name1'] = $name1;
////$placeholders['message'] = 'here is the text of the message';
//	$placeholders['description'] = 'description here';
//	$placeholders['name2'] = 'f***ing name 2';
//	$placeholders['parameters_token'] = $modx->getOption('vkroulette_groupparam_token');
//
////$output = !empty($tplmember)
////	? $pdoTools->getChunk($tplmember, $placeholders)
////	: 'Parameter "tplmember" is empty';
//
////printf('<br>обработанный ПДО тулс $output - <br>');
////print_r($output);
//// ----------------------------------
//
//	$output = $modx->getChunk($tplmember, $placeholders);
//
//}

// получаем последнего победителя
$lastwinner = $vkroulette->getlastwinner();							// объект в виде массива
$fields = 'uid,first_name,last_name,data,summa';					// выводимые поля
$head_fields = 'id,Имя,Фамилия,Дата выигрыша,Сумма выигрыша';		// выводимые заголовки
$tpl_table_winner = $vkroulette->array_as_table($lastwinner,true, $fields, $head_fields);

// считываем всех победителей конкурса
$winners = $vkroulette->readrecords('vkrwinners');
//$tpl_table_winners = $vkroulette->array_as_table($winners,true, $fields, $head_fields);

// сформируем выводимую таблицу выручную
$tpl_table_winners = '<table border="1" cellspacing="1" cellpadding="1" align="center">';
$tpl_table_winners .= '<thead><tr align="center"><th>№ п/п</th><th>id</th><th>Имя</th><th>Дата выигрыша</th><th>Сумма выигрыша</th></tr></thead>';
$tpl_table_winners .= '<tbody>';
$i = 0;
foreach ($winners as $winner) {
	$i += 1;
	$name = '<a href="'.$winner['link'].'" target="_blank">' . $winner['first_name'].' '.$winner['last_name'] . '</a>';
	$tpl_table_winners .= '<tr><td>'. $i .'</td><td>'. $winner['uid'] .'</td><td>'. $name .'</td><td>'. $winner['data'] .'</td><td>'. $winner['summa'] .'</td></tr>';
}
$tpl_table_winners .= '</tbody></table>';

// считываем текущих участников конкурса
$fields = 'uid,first_name,last_name,repost';						// считываемые поля
$head_fields = 'id,Имя,Фамилия,Увеличенный шанс (репост)';			// выводимые заголовки
$where = array(
	'signed' => true,
	//'repost' => true,						// не фильтруем, т.к. у репостов больше шансов, но принимают участие все
	'manager' => false,
);
$players = $vkroulette->readrecords('vkrmembers', '', $where);
//$tpl_table_players = $vkroulette->array_as_table($players,true, $fields, $head_fields);

// сформируем выводимую таблицу выручную
$tpl_table_players2 = '<table border="1" cellspacing="1" cellpadding="1" align="center">';
$tpl_table_players2 .= '<thead><tr align="center"><th>№ п/п</th><th>id</th><th>Имя</th><th>Увеличенный шанс (репост)</th></tr></thead>';
$tpl_table_players2 .= '<tbody>';
$check = '<img src="/assets/templates/itray/img/check_itray.png" height="20" width="25">';
$i = 0;
foreach ($players as $player) {
	$i += 1;
	$name = '<a href="'.$player['link'].'" target="_blank">' . $player['first_name'].' '.$player['last_name'] . '</a>';
	$tpl_table_players2 .= '<tr><td>'. $i .'</td><td>'. $player['uid'] .'</td><td>'. $name .'</td><td>'. (($player['repost'] == '1') ? $check : '') .'</td></tr>';
}
$tpl_table_players2 .= '</tbody></table>';

// считываем отписавшихся людей
//$fields = 'uid,first_name,last_name,repost';						// считываемые поля
$head_fields = 'id,Имя,Фамилия,Увеличенный шанс (репост)';			// выводимые заголовки
$where = array(
	'signed' => false,
);
$players_old = $vkroulette->readrecords('vkrmembers', '', $where);
//$tpl_table_players = $vkroulette->array_as_table($players,true, $fields, $head_fields);

if (count($players_old) > 0){
	// сформируем выводимую таблицу выручную
	$tpl_table_players3 = '<p>Не принимают участие в конкурсе (отписавшиеся)</p>';
	$tpl_table_players3 .= '<table border="1" cellspacing="1" cellpadding="1" align="center">';
	$tpl_table_players3 .= '<thead><tr align="center"><th>№ п/п</th><th>id</th><th>Имя</th><th>Репост</th></tr></thead>';
	$tpl_table_players3 .= '<tbody>';
	$i = 0;
	foreach ($players_old as $player) {
		$i += 1;
		$name = '<a href="'.$player['link'].'" target="_blank">' . $player['first_name'].' '.$player['last_name'] . '</a>';
		$tpl_table_players3 .= '<tr><td>'. $i .'</td><td>'. $player['uid'] .'</td><td>'. $name .'</td><td>'. (($player['repost'] == '1') ? $check : '') .'</td></tr>';
	}
	$tpl_table_players3 .= '</tbody></table>';
	$tpl_table_players2 .= $tpl_table_players3;
}

/** @var array $placeholders */
$placeholders = array();
// вкладка победители
if (count($lastwinner) > 0) {
	//$placeholders['winner_name'] = $lastwinner['first_name'] . ' ' . $lastwinner['last_name'];
	//$placeholders['winner_link'] = $lastwinner['link'];
	$placeholders['winner_link'] = '<a href="'.$lastwinner['link'].'" target="_blank">'.$lastwinner['first_name'] . ' ' . $lastwinner['last_name'].'</a>';
	//$placeholders['winner_photo'] = $lastwinner['photo'];
	$placeholders['winner_photo'] = '<a href="'.$lastwinner['link'].'" target="_blank"><img src="'.$lastwinner['photo'].'" alt="Победитель"></a>';
	$placeholders['table_winners'] = $tpl_table_winners;
}
else {
	$placeholders['winner_link'] = 'еще не определен';
	$placeholders['winner_photo'] = '<img src="/assets/templates/itray/img/WinCup1000.png" height="219" width="234">';
	$placeholders['table_winners'] = 'Победителей еще не было, испытай свою удачу!';
}
//$placeholders['table_win'] = $tpl_table_winner;

// вкладка текущий розыгрыш
$placeholders['table_play'] = $tpl_table_players2;
//$placeholders['players'] = $players;

$output = $modx->getChunk($tplmember, $placeholders);

//// проверим работу с админами
//$vk_config = array(
//	'group_id' 		=> $vkroulette->vk_config['group_id'],
//	'app_id'        => $vkroulette->vk_config['app_id'],
//	'api_secret'    => $vkroulette->vk_config['api_secret'],
//	'access_token' 	=> $vkroulette->vk_config['access_token'],
//	'filter'		=> 'managers',
//	'v' 			=> '5.27',
//);
//$response_admins = $vkroulette->vk->api('groups.getMembers',$vk_config);
//printf('<br>response_admins 1 = ' . $response_admins . '</br>');
//$vkroulette->pretty_print($response_admins,false,'response_admins 1', true);
//$response_admins = $response_admins['response']['items'];
//printf('<br>response_admins 2 = ' . $response_admins . '</br>');
//$vkroulette->pretty_print($response_admins,false,'response_admins 2', true);
//$admins = array();
//foreach ($response_admins as $ind => $admin) {
//	$admins[] = $admin['id'];
//}
//printf('<br>Admins = ' . $admins . '</br>');
//$vkroulette->pretty_print($admins,false,'$admins', true);


/* by default just return output */
return $output;