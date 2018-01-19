<?php
/**
 * The base class for vkroulette.
 */

class vkroulette {
	/* @var modX $modx */
	public $modx;
	/* @var vkrouletteControllerRequest $request */
	protected $request;
	public $initialized = array();
	public $chunks = array();
	/* @var object $vk */
	public $vk;
	/* @var array $vk_config */
	public $vk_config;


	/**
	 * @param modX $modx
	 * @param array $config
	 */
	function __construct(modX &$modx, array $config = array()) {
		$this->modx =& $modx;

		$corePath = $this->modx->getOption('vkroulette_core_path', $config, $this->modx->getOption('core_path') . 'components/vkroulette/');
		$assetsUrl = $this->modx->getOption('vkroulette_assets_url', $config, $this->modx->getOption('assets_url') . 'components/vkroulette/');
		$connectorUrl = $assetsUrl . 'connector.php';

		$this->config = array_merge(array(
			'assetsUrl' => $assetsUrl,
			'cssUrl' => $assetsUrl . 'css/',
			'jsUrl' => $assetsUrl . 'js/',
			'imagesUrl' => $assetsUrl . 'images/',
			'connectorUrl' => $connectorUrl,

			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'chunksPath' => $corePath . 'elements/chunks/',
			'templatesPath' => $corePath . 'elements/templates/',
			'chunkSuffix' => '.chunk.tpl',
			'snippetsPath' => $corePath . 'elements/snippets/',
			'processorsPath' => $corePath . 'processors/'
		), $config);

		$this->modx->addPackage('vkroulette', $this->config['modelPath']);
		$this->modx->lexicon->load('vkroulette:default');

		// подключаем файл работы с api vk
		$group_id = $this->modx->getOption('vkroulette_groupparam_id');			// 155335527
		$post_id = $this->modx->getOption('vkroulette_groupparam_post_id');
		$alias = $this->modx->uri;
		$this->vk_config = array(
			'group_id' 		=> $group_id,				// 155335527
			'post_id'		=> $post_id,				// 1
			'app_id'        => $this->modx->getOption('vkroulette_groupparam_app_id'),			// 6226105
			'api_secret'    => $this->modx->getOption('vkroulette_groupparam_secret_key'),		// 8UuENGyjxrenGWIVBQRj
			'access_token' 	=> $this->modx->getOption('vkroulette_groupparam_token'),			// be9809dd91736d97ae63f7d2d5e98b0c04ff48a250cdd93f1ebb0c059898a8156c0211dfc9580ca8c83fd
			'callback_url'  => 'http://'.$_SERVER['SERVER_NAME'].'/'.$alias["alias"],
			'fields' 		=> 'photo_200,members_count',											// поля для получения инфы о группе
			'owner_id' 		=> '-'.$group_id,
			'offset' 		=> 0,
			'count' 		=> 1000,
			'type'			=> 'post',
			'item_id'		=> $post_id,
			'filter'		=> 'copies',
			'v' 			=> '5.27',
		);
		ini_set('default_charset', 'utf-8');
		error_reporting(E_ALL);

		// подключаем файлы работы с api vk
		require_once $this->modx->getOption('base_path') . '/vkroulette/_build/includes/VK.php';
		require_once $this->modx->getOption('base_path') . '/vkroulette/_build/includes/VKException.php';

		$this->vk = new VK\VK($this->vk_config['app_id'], $this->vk_config['api_secret'], $this->vk_config['access_token']);

	}


//	/**
//	 * Initializes vkroulette into different contexts.
//	 *
//	 * @access public
//	 *
//	 * @param string $ctx The context to load. Defaults to web.
//	 */
//	public function initialize($ctx = 'web') {
//		switch ($ctx) {
//			case 'mgr':
//				if (!$this->modx->loadClass('vkroulette.request.vkrouletteControllerRequest', $this->config['modelPath'], true, true)) {
//					return 'Could not load controller request handler.';
//				}
//				$this->request = new vkrouletteControllerRequest($this);
//
//				return $this->request->handleRequest();
//				break;
//			case 'web':
//
//				break;
//			default:
//				/* if you wanted to do any generic frontend stuff here.
//				 * For example, if you have a lot of snippets but common code
//				 * in them all at the beginning, you could put it here and just
//				 * call $vkroulette->initialize($modx->context->get('key'));
//				 * which would run this.
//				 */
//				break;
//		}
//		return true;
//	}
//
//
//	/**
//	 * Gets a Chunk and caches it; also falls back to file-based templates
//	 * for easier debugging.
//	 *
//	 * @access public
//	 *
//	 * @param string $name The name of the Chunk
//	 * @param array $properties The properties for the Chunk
//	 *
//	 * @return string The processed content of the Chunk
//	 */
//	public function getChunk($name, array $properties = array()) {
//		$chunk = null;
//		if (!isset($this->chunks[$name])) {
//			$chunk = $this->modx->getObject('modChunk', array('name' => $name), true);
//			if (empty($chunk)) {
//				$chunk = $this->_getTplChunk($name, $this->config['chunkSuffix']);
//				if ($chunk == false) {
//					return false;
//				}
//			}
//			$this->chunks[$name] = $chunk->getContent();
//		}
//		else {
//			$o = $this->chunks[$name];
//			$chunk = $this->modx->newObject('modChunk');
//			$chunk->setContent($o);
//		}
//		$chunk->setCacheable(false);
//
//		return $chunk->process($properties);
//	}
//
//
//	/**
//	 * Returns a modChunk object from a template file.
//	 *
//	 * @access private
//	 *
//	 * @param string $name The name of the Chunk. Will parse to name.chunk.tpl by default.
//	 * @param string $suffix The suffix to add to the chunk filename.
//	 *
//	 * @return modChunk/boolean Returns the modChunk object if found, otherwise
//	 * false.
//	 */
//	private function _getTplChunk($name, $suffix = '.chunk.tpl') {
//		$chunk = false;
//		$f = $this->config['chunksPath'] . strtolower($name) . $suffix;
//		if (file_exists($f)) {
//			$o = file_get_contents($f);
//			$chunk = $this->modx->newObject('modChunk');
//			$chunk->set('name', $name);
//			$chunk->setContent($o);
//		}
//
//		return $chunk;
//	}

	/**
	 * Определяем победителя текущего конкурса:
	 * 1) определяем возможность розыгрыша
	 * 		дата и время начала розыгрыша должны соответствовать
	 * 		в таблице победителей не должно быть текущей даты розыгрыша - "vkroulette_winners"
	 * 2) считываем таблицу участников текущего конкурса - "vkroulette_members"
	 * 		(она содержит только текущих участников, т.к. после определения победителя очищается)
	 * 3) формируем общее пространство диапазонов шансов на победу
	 *		+ 20% каждому участнику, сделавшему новый репост записи текущего розыгрыша
	 * 		(колич участников * 100%) + (колич участников * количество бонусов)
	 * 4) получаем случайное число из нашего пространства на победу
	 * 5) выявляем нашего победителя из общего пространства
	 * 		??? ??? ??? НУЖНО ЛИ СОРТИРОВАТЬ ТАБЛИЦУ ??? ??? ???
	 * 		последовательно увеличиваем диапазон, пока не достигнем нашего победного значения
	 * 		на ком остановились, тот и победитель
	 * 6) добавляем нового победителя в таблицу победителей - "vkroulette_winners"
	 * 7) очищаем таблицу текущих участников - "vkroulette_members"
	 *
	 * @return bool
	 */
	function findwinner(){
		return true;
	}

	/**
	 * Заполнение таблицы участников текущего конкурса:
	 * 1) определяем возможность заполнения
	 *        если это день розыгрыша победителя, то формирование таблицы должно ограничиваться по времени
	 *        после розыгрыша начальное заполнение таблицы будет определено следующим днём
	 * 2) определяем текущих участников розыгрыша:
	 *        - считываем всех участников текущей группы
	 *        - считываем список людей, сделавших репост нашей записи
	 *        - считываем список людей, сделавших НОВЫЙ репост нашей записи в текущем розыгрыше
	 * 3) формируем конечную таблицу/массив для записи в таблицу
	 *        -
	 * 4) заполняем таблицу "vkroulette_members":
	 *        ??? ??? ??? наверно не имеет смысла заморачиваться и динамически добавлять/удалять людей ??? ??? ???
	 *        ??? ??? ??? просто очистил таблицу и заново её заполнил, отсортировав участников по uid ??? ??? ???
	 *        - очищаем текущую таблицу - "vkroulette_members"
	 *        - сортируем таблицу участников по uid
	 *        - формируем mySQL запрос
	 *            ??? ??? ??? есть ли ограничения на количество одновременно добавляемых строк ??? ??? ???
	 *            - разбиваем запрос на несколько этапов по Х штук (например 100)
	 *        - исполняем запрос на добавление участников
	 *
	 * @param $fill_res
	 *
	 * @return bool
	 */
	function fillmembers(&$fill_res){
		// 1) проверка можно ли запускать функцию
		//if count($vkconf) < 3 return false;

		$group_id = $this->vk_config['group_id'];
		$total = 0;
		// получаем информацию о группе
		$info_group = $this->vk->api('groups.getById', $this->vk_config);
		if ($info_group['response']) { // проверка на успешный запрос
			$total = $info_group['response'][0]['members_count'];
		}

		// получаем список всех участников группы
		$membersGroups = array();
		$this->getMembers25k($group_id, $membersGroups, $total);

		// получаем список людей, сделавших репость записи
		$users_repost = $this->vk->api('wall.getReposts', $this->vk_config);
		$users_repost = $users_repost['response']['profiles'];

		// получаем список людей, сделавших репость записи
		$users_likes = $this->vk->api('likes.getList', $this->vk_config);
		$users_likes = $users_likes['response']['items'];


		// выведем оба списка для визуального сравнения массивов
		$this->pretty_print($membersGroups,false,'get_Members');
		$this->pretty_print($users_repost,false,'get_Reposts');
		$this->pretty_print($users_likes,false,'get_Likes');

		// выведем людей, сделавших репост, но не состоящих в группе
		$bad_users_repost = array();
		foreach ($users_likes as $user)
		{
			// проверяем вхождение текущего сделавшего репост на вхождение в массив подписчиков группы
			if (in_array($user,$membersGroups))
				// состоит в группе, добавляем в возвращаемый список
				$fill_res[] = $user;
			else
				$bad_users_repost[] = $user;
		}
		$this->pretty_print($fill_res,false,'Cheked_Repost');
		$this->pretty_print($bad_users_repost,false,'Not_Signed');
		//echo "</ul>";

		//print_r($membersGroups);
		//$membersGroups = array_intersect($membersGroups, $users_repost_ids);
		//print_r($membersGroups);
		//return str_replace("[+members_count+]", $info_group, $modx->getChunk($outerTpl));

// определяем случайного члена группы
		$win_id = mt_rand(0,count($fill_res)-1);
		print_r('Случайное число = ' . $win_id);
		print_r ('<p>Наш победитель: <h3><a href="https://vk.com/id'.$fill_res[$win_id].'">'.$fill_res[$win_id].'</a></h3></p>');
	}

	/**
	 * Заполнение таблицы участников текущего конкурса:
	 *
	 *
	 * @return bool
	 */
	function fillmembers2(){
		// на входе массив id пользователей и класс работы с запросами к Вконтакте
		// считываем текущий список участников сообщества
		// сравниваем с полученным списком
		// определяем кого надо добавить
		// добавляем


		// вариант №1 - считываем данные таблицы 'members'
		$memberscollection = $this->modx->getCollection('vkrmembers');
		$new_array = array();
		foreach ($memberscollection as $mmbrcl){
			$new_array[$mmbrcl->get('uid')] = $mmbrcl->toarray();
			$mmbrcl->set('signed',true);
			$mmbrcl->save();
		}
		$this->pretty_print($new_array,false, 'Members getCollection');

//		// вариант №2 - считываем данные таблицы 'members'
//		$q = $this->modx->newQuery('vkrmembers');				// создаём запрос
//		$q->limit(1000,0);								// задаем параметры
//		$q->prepare();												// подготавливаем/проверяем на ошибки
//		$q->stmt->execute();										// исполняем (всё это на сервере наверно)
//		$res = $q->stmt->fetchAll(PDO::FETCH_ASSOC);		// получаем данные запроса в виде массива
//		$this->pretty_print($res,false,'Members - fetch_query');

//		// вариант №3
//		$sql = 'SELECT * FROM ' . $this->modx->getTableName('vkrmembers');
//		$q = new xPDOCriteria($this->modx, $sql);
//		$res = $this->modx->getCollection('vkrmembers', $q);
//		$new_array2 = array();
//		foreach ($res as $v) {
//			$new_array[$v->get('uid')] = $v->toarray();
//		}
//		$this->pretty_print($new_array2,false, 'Members sql getCollection');

		// Вариант №4 - sql
		$res = $this->modx->query('SELECT * FROM ' . $this->modx->getTableName('vkrmembers'));
		$this->pretty_print($res,false, 'sql Query');

		// Вариант №5 - sql
		$res = $this->modx->exec('SELECT * FROM ' . $this->modx->getTableName('vkrmembers'));
		$this->pretty_print($res,false, 'sql EXEC');
	}

	/**
	 * Полное перезаполнение таблицы участников сообщества
	 * 1) удаляем все имеющиеся записи (полностью очищаем таблицу)
	 * 		либо запросом
	 * 		либо пообъектно (поштучно каждую запись)
	 * 2) получаем количество участников группы
	 * 3) получаем список участников сообщества
	 * 4) добавляем новых участников по полученному списку участников
	 *		отдельной функцией
	 *
	 * @param string $class
	 *
	 * @return void
	 */
	function rewriteallmembers($class = 'vkrmembers'){
		//$this->readrecords($class);
		//return ;

		// 1 очищаем текущий список
		$table_name = $this->modx->getTableName($class);
		$results = $this->modx->exec('TRUNCATE ' . $table_name);
		// TRUNCATE modx_vkroulette_members

		// 2 получаем информацию о группе
		$group_id = $this->vk_config['group_id'];
		$info_group = $this->vk->api('groups.getById', $this->vk_config);
		// проверка на успешный запрос
		if ($info_group['response']) $total = $info_group['response'][0]['members_count'];
		else $total = 0;

		// 3 получаем список всех участников группы
		$membersGroups = array();
		// если участников группы ноль - значит некого добавлять
		if ($total == 0) return;
		//elseif ($total < 25000)
		//	$this->getMembers25k($group_id, $membersGroups, $total);
		else {
			$count = round($total/25000) + 1;
			for ($i = 0; $i < $count; $i++) {
				// устанавливаем смещение по пользователям
				$this->vk_config['offset'] = $i * 25000;
				// объединения массива "$membersGroups" не делаем, потому что внутри функции это делается
				$this->getMembers25k($group_id, $membersGroups, $total);
			}
			// обнуляем смещение
			$this->vk_config['offset'] = 0;
			//тестируем запросы к ВК ... с ключом доступа сообщества ограничение имеет 20 запросов в секунду ( = 25 к * 20 = 500 к пользователей)
//			$vk_config = array(
//				'group_id' 		=> $group_id,				// 155335527
//				'app_id'        => $this->vk_config['app_id'],
//				'api_secret'    => $this->vk_config['api_secret'],		// 8UuENGyjxrenGWIVBQRj
//				'access_token' 	=> $this->vk_config['access_token'],			// be9809dd91736d97ae63f7d2d5e98b0c04ff48a250cdd93f1ebb0c059898a8156c0211dfc9580ca8c83fd
//				//'offset' 		=> $i * 25,
//				'count' 		=> 25,
//				//'filter'		=> 'managers',
//				'v' 			=> '5.27',
//			);
//			for ($i = 0; $i < $count; $i++) {
//				//print_r($i);
//				//$this->getMembers25k($group_id, $membersGroups, $total);
//				$vk_config['offset'] = $i * 25;
//				$info_group = $this->vk->api('groups.getMembers',$vk_config);
//				printf('<br>i = ' . $i. ', IG = ' . $info_group);	//  . ', IG = ' . $info_group
//				$this->pretty_print($info_group,false, 'api getMembers');
//				$info_group = $info_group['response']['items'];
//				$this->pretty_print($info_group,false, 'api getMembers');
//			}
		}
		$this->pretty_print($membersGroups, false,'getMembers25k');

		// 4 добавляем всех пользователей в базу данных
		$this->addmembers($membersGroups,$class);
	}	// finished

	/**
	 * Обновление таблицы участников сообщества - добавление новых подписчиков
	 * 1) получаем список участников сообщества
	 * 2) получаем список участников из базы данных
	 * 3) находим новых пользователей
	 * 4) добавляем новых участников по полученному списку участников
	 *
	 * @param string $class
	 *
	 * @return void
	 */
	function updatemembers($class = 'vkrmembers'){
		// 1 получаем количество и список участников сообщества
		$group_id = $this->vk_config['group_id'];
		$info_group = $this->vk->api('groups.getById', $this->vk_config);
		// проверка на успешный запрос
		if ($info_group['response']) $total = $info_group['response'][0]['members_count'];
		else $total = 0;
		// 3 получаем список всех участников группы
		$membersGroups = array();
		// если участников группы ноль - значит некого добавлять
		if ($total == 0) return;
		//elseif ($total < 25000)
		//	$this->getMembers25k($group_id, $membersGroups, $total);
		else {
			$count = round($total/25000) + 1;
			for ($i = 0; $i < $count; $i++) {
				// устанавливаем смещение по пользователям
				$this->vk_config['offset'] = $i * 25000;
				// объединения массива "$membersGroups" не делаем, потому что внутри функции это делается
				$this->getMembers25k($group_id, $membersGroups, $total);
			}
			// обнуляем смещение
			$this->vk_config['offset'] = 0;
		}
		$this->pretty_print($membersGroups, false,'VK_getMembers25k');

		// 2 получаем список участников из базы данных
		$fields = 'uid';		// считываемые поля через запятую
		$members_mysql = $this->readrecords($class, $fields);
		$members_mysql = $this->readrecords_sql($class, $fields);

		// 4 добавляем всех пользователей в базу данных
		//$this->addmembers($membersGroups,$class);
	}

	/**
	 * Чтение нужных полей из базы данных
	 *
	 * @param string $class - имя класса считываемой таблицы
	 * @param string $fields - считываемые поля таблицы через запятую
	 * @param string $where - полностью прописанный текст условия отбора
	 *
	 * @return array
	 */
	function readrecords($class = 'vkrmembers', $fields = '', $where = ''){
		$memberscollection = $this->modx->getCollection($class);
		$new_array = array();
		$fields_array = explode(',', $fields);
		$fields_count = count($fields_array);
		/* @var vkrmembers $mmbrcl */
		foreach ($memberscollection as $mmbrcl){
			//$new_array[$mmbrcl->get('uid')] = $mmbrcl->toarray();
			//$mmbrcl->set('signed',true);
			//$mmbrcl->save();
			if ($fields_count == 1)
				// считаем только указанное поле в массив без идентификаторов
				$new_array[] = $mmbrcl->get($fields);
			else {
				// если указано несколько полей, то создаём вложенный массив с указанными полями
				$new_array_element = array();
				foreach ($fields_array as $fld)
					$new_array_element[$fld] = $mmbrcl->get($fld);
				// возвращаемый массив делаем с индексами по VK.id
				$new_array[$mmbrcl->get('uid')] = $new_array_element;
			}
		}
		//print_r($memberscollection);
		$this->pretty_print($new_array,false, 'RR getCollection toArray');

		return $new_array;
	}

	/**
	 * Чтение нужных полей из базы данных через запрос MySQL
	 *
	 * @param string $class - имя класса считываемой таблицы
	 * @param string $fields - считываемые поля таблицы через запятую
	 * @param string $where - полностью прописанный текст условия отбора
	 *
	 * @return array
	 */
	function readrecords_sql($class = 'vkrmembers', $fields = '*', $where = ''){
		$table_name = $this->modx->getTableName($class);

		// вариант №1 - делаем запрос и работаем с запросом построчно в виде массива
		$sql = 'SELECT `' . $fields . '` FROM ' . $table_name . ((empty($where)) ? '' : $where);
		print_r('<br>'.$sql);
		$results = $this->modx->query($sql);
		$new_array = array();
		$fields_array = explode(',', $fields);
		while ($r = $results->fetch(PDO::FETCH_ASSOC)) {
			//$new_array[$r['uid']] = $r;
			//array_merge($new_array,$r);
			//print_r($r);
			if (($fields != '*') && (count($fields_array) == 1))
				$new_array[] = $r[$fields];
			elseif (strpos($fields,'uid') === true)
				$new_array[$r['uid']] = $r;
			else
				$new_array[] = $r;
		}
		$this->pretty_print($new_array,false,'RRsql fetch query');

		// вариант №2 - делаем запрос и выгружаем весь результат в массив и работаем с массивом
		$results = $this->modx->query($sql);
		$results = $results->fetchAll(PDO::FETCH_ASSOC);
		$this->pretty_print($results,false,'RRsql fetch all query');

		//// вариант №3 - пример для выполнения запроса, который не требует возвращения конкретных строк
		//$results = $this->modx->exec('TRUNCATE ' . $table_name);		// возвращается количество затронутых строк
		//print_r($results);

		//// вариант №4
		//$results = $this->modx->query('TRUNCATE ' . $table_name);		// возвращает объект PDOStatement
		//print_r($results);

		return $new_array;
	}

	/**
	 * Сброс таблицы участников текущего конкурса:
	 *        а именно устанавливаем во все записи в поле 'repost' значение 'false'
	 *
	 * @param string $class - имя класса объекта, с которым работаем
	 * @param string $field - имя изменяемого свойства
	 * @param bool $value - новое значение изменяемого поля
	 *
	 * @return bool
	 */
	function resetmembers($class = 'vkrmembers', $field = 'repost', $value = false){
		// ВАРИАНТ №1 - работаем с объектами по отдельности
		// считываем все объекты
		$memberscollection = $this->modx->getCollection($class);

		// запоминаем новое значение
		$new_value = array(
			$field => $value,
			'signed' => false		// если надо еще и данное поле обнулить
		);

		/** @var modX $mmbrcl */
		foreach ($memberscollection as $mmbrcl){
			// изменяем нужное поле
			$mmbrcl->set('repost',false);		// вручную указываем устанавливаемое поле и его значение
			$mmbrcl->set('signed',false);

			// или можем вот так изменить
			$mmbrcl->fromarray($new_value);		// берем имя поля и его значение из массива (можно сразу несколько значений)

			// сохраняем элемент
			$mmbrcl->save();
		}

		// ВАРИАНТ №2 - или выполним SQL-запрос через PDO
		//		Update имя_таблица Set имя_колонки = новое_значение Where условие_отбора
		//	Exmpl:	Update vkroulette_members Set repost = false
		$results = $this->modx->query('Update vkroulette_members Set repost = false');

		// ВАРИАНТ №3 - или выполним SQL-запрос через xPDO

	}

	/**
	 * Добавление новых участников группы
	 *
	 * @param array $new_members - массив идентификаторов пользователей для добавления
	 * @param string $class - имя класса объекта, с которым работаем
	 *
	 * @return bool
	 */
	function addmembers($new_members, $class = 'vkrmembers'){
		// на входе массив с id-шниками новых членов группы
		// получаем информацию о пользователях через api vk
		// добавляем новые записи в таблицу

		// пример запроса
//		INSERT INTO  `itraybd`.`modx_vkroulette_members` (
//		`id` , `uid` , `first_name` , `last_name` , `screen_name` , `photo` , `link` , `signed` , `repost`
//		)
//		VALUES (
//		NULL ,  '9198708',  'Пашка',  'Попов',  'pavel1504',  'тут ссылка на фотку',  'тут ссылка на страницу',  '0',  '1'
//		);

		// разбиваем наш массив на части по 999 штук, т.к. api vk имеет ограничение в 1000 штук
		// а т.к. есть ограничение между запросами и на их количество в секунду,
		// 		будем в перерывах между запросами сразу записывать полученные данные в базу, вместо задержек
		$new_array = array_chunk($new_members, 999, true);
		//$new_members_info = array();
		foreach ($new_array as $new){
			// получаем информацию об участниках сообщества
			$users = $this->vk->api('users.get', array(
				'user_ids' => implode(',', $new),		// переводим текущий массив идентификаторов в строку
				'fields' => 'first_name,last_name,screen_name,photo_200',
				'access_token' => $this->vk_config['access_token']
				)
			);
			//// записываем всё в объединенный массив
			//$new_members_info = array_merge($new_members_info,$users['response']);
			// проверяем результат запроса
			if ($users['response']) {
				$new_members_info = $users['response'];
				$this->pretty_print($new_members_info,false, 'users.get');
				$addfields = array(
//					'signed' => 1,
//					'repost' => 0,
//					'manager' => 0,
				);		// в принципе эти доп поля не нужны, т.к. имеют значения по умолчанию
				// каждый элемент создаём отдельно, заполняя его поля по полученный данным
				foreach ($new_members_info as $fields) {
					// формируем недостающие поля
					$new_member = $this->modx->newObject($class, $fields);
					//$new_member->set('link', 'https://vk.com/' . $fields['screen_name']);		// или так: 'https://vk.com/id' . $fields['uid']
					//$new_member->set('photo', $fields['photo_200']);
					$addfields['link'] = 'https://vk.com/' . $fields['screen_name'];
					$addfields['photo'] = $fields['photo_200'];
					$new_member->fromArray($addfields);
					$new_member->save();
				}
			}
		}

//		// теперь создаём новые объекты
//		// каждый элемент создаём отдельно, заполняя его данные по полученный ранее данным
//		foreach ($new_members_info as $fields) {
//			$new_class = $this->modx->newObject($class, $fields);
//			//$new_class->fromArray($fields);
//			$new_class->save();
//		}
//
//		// или же создаём sql запросом
//		$sql = 'INSERT INTO `Ostatki1s_proba` (`uid`, `first_name`, `last_name`, `screen_name`, `photo`, `link`) VALUES';
//		// указываем и колонки, и значения
//		// добавляем по одной строке
//		foreach ($new_members_info as $new) {
//			$sql_result = $this->modx->exec($sql . '(`' . implode('`, `',$new) . '`,`https://vk.com/id'.$new['uid'] . '`)');
//		}
	}	// finished

	/**
	 * Функция считывает подписчиков группы и записывает их в массив $membersGroups
	 *
	 * @param $group_id - ID сообщества
	 * @param $membersGroups - получаемый массив участников
	 * @param $len - количество участников сообщества
	 * @param $vk - объект для работы с api vk
	 *
	 * @return void
	 */
	function getMembers25k ($group_id, &$membersGroups, $len) {

		$code =  'var members = API.groups.getMembers({"group_id": ' . $group_id . ', "v": "5.27", "sort": "id_asc", "count": "1000", "offset": ' . count($membersGroups) . '}).items;' // делаем первый запрос и создаем массив
			.	'var offset = 1000;' // это сдвиг по участникам группы
			.	'while (offset < 25000 && (offset + ' . count($membersGroups) . ') < ' . $len . ')' // пока не получили 20000 и не прошлись по всем участникам
			.	'{'
			.	'members = members + "," + API.groups.getMembers({"group_id": ' . $group_id . ', "v": "5.27", "sort": "id_asc", "count": "1000", "offset": (' . count($membersGroups) . ' + offset)}).items;' // сдвиг участников на offset + мощность массива
			.	'offset = offset + 1000;' // увеличиваем сдвиг на 1000
			.	'};'
			.	'return members;';
		//print_r($code); die("asdasdasdasd");
		$data = $this->vk->api("execute", array('code' => $code));
		if ($data['response']) {
			// print_r($data); die("123123132");
			// $membersGroups = $membersGroups.concat(JSON.parse("[" + data.response + "]")); // запишем это в массив
			//$array = explode(',', $data['response']);
			// print_r($data['response']); die();
			//  $membersGroups = array_merge($membersGroups, $array); // запишем это в массив
			$membersGroups = array_merge($membersGroups, $data['response']);
			// print_r($membersGroups);
//                $('.member_ids').html('Загрузка: ' + membersGroups.length + '/' + members_count);
			if ($len >  count($membersGroups)) {// если еще не всех участников получили
				sleep(rand(0, 1));
				$this->getMembers25k($group_id, $membersGroups, $len); // задержка [0,1]  с. после чего запустим еще раз
			}
			else { // если конец то
				// print_r("Готово");
				//print_r($membersGroups);

//				$user = $this->vk->api('users.get', array( // вызов запроса на информацию о сообществе и получения количества участников и фотографии 200х200 px
//					// 'user_ids' => $membersGroups[0],
//					'fields' => 'nickname,crop_photo,photo_50, photo_100, photo_200_orig, photo_200, photo_400_orig, photo_max, photo_max_orig, sex'
//				));
				// print_r( $user);
			}
		} else {
			// print_r($data); // в случае ошибки выведем её
		}

	}

	/**
	 * Выводит массив в виде дерева
	 *
	 * @param mixed $in - Массив или объект, который надо обойти
	 * @param boolean $opened - Раскрыть дерево элементов по-умолчанию или нет?
	 * @param string $name - отображаемое имя массива
	 *
	 * @return void
	 */
	function pretty_print($in,$opened = true, $name = 'Array'){
		if($opened)
			$opened = ' open';
		if(is_object($in) or is_array($in)){
			echo '<div>';
			echo '<details'.$opened.'>';
			echo '<summary>';
			echo (is_object($in)) ? 'Object {'.count((array)$in).'}':$name.' ['.count($in).']';
			echo '</summary>';
			$this->pretty_print_rec($in, $opened);
			echo '</details>';
			echo '</div>';
		}
	}
	function pretty_print_rec($in, $opened, $margin = 10){
		if(!is_object($in) && !is_array($in))
			return;

		foreach($in as $key => $value){
			if(is_object($value) or is_array($value)){
				echo '<details style="margin-left:'.$margin.'px" '.$opened.'>';
				echo '<summary>';
				echo (is_object($value)) ? $key.' {'.count((array)$value).'}':$key.' ['.count($value).']';
				echo '</summary>';
				$this->pretty_print_rec($value, $opened, $margin+10);
				echo '</details>';
			}
			else{
				switch(gettype($value)){
					case 'string':
						$bgc = 'red';
						break;
					case 'integer':
						$bgc = 'green';
						break;
				}
				echo '<div style="margin-left:'.$margin.'px">'.$key . ' : <span style="color:'.$bgc.'">' . $value .'</span> ('.gettype($value).')</div>';
			}
		}
	}
}