<?php

include_once 'setting.inc.php';

// общие названия
$_lang['vkroulette'] = 'Рулетка-лотерея';
$_lang['vkroulette_menu_desc'] = 'Управление счастливой лотереей';

$_lang['vkroulette_btn_create'] = 'Кнопка создать';
$_lang['vkroulette_btn_updatelist'] = 'Обновить подписчиков и репосты';
$_lang['vkroulette_btn_rewritelist'] = 'Перезаполнить таблицу';
$_lang['id'] = '№ п/п';


// таблица "vkroulette_members" (class="vkrmembers") - Участники
$_lang['vkroulette_members'] = 'Участники';
$_lang['vkroulette_members_intro'] = 'Список участников текущего розыгрыша.';

// таблица "vkroulette_winners" (class="vkrwinners") - Победители
$_lang['vkroulette_winners'] = 'Победители';
$_lang['vkroulette_winners_intro'] = 'Список победителей текущего розыгрыша.';

// обозначения действий
$_lang['vkroulette_member_create'] = 'Добавить новую запись';
$_lang['vkroulette_member_err_ae'] = 'Запись с таким именем уже существует.';
$_lang['vkroulette_member_err_nf'] = 'Запись не найдена.';
$_lang['vkroulette_member_err_ns'] = 'Нет ни одной записи в таблице members.';
$_lang['vkroulette_member_err_remove'] = 'Ошибка при удалении записи.';
$_lang['vkroulette_member_err_save'] = 'Ошибка при сохранении записи.';
$_lang['vkroulette_member_remove'] = 'Удалить запись';
$_lang['vkroulette_member_remove_confirm'] = 'Вы уверены, что хотите удалить текущую запись?';
$_lang['vkroulette_member_update'] = 'Изменить запись';
$_lang['vkroulette_member_updatelist'] = 'Обновление таблицы участников';
$_lang['vkroulette_member_updatelist_confirm'] = 'Будут автоматически добавлены новые пользователи и обновлены данные по репостам.<br>Продолжить?';
$_lang['vkroulette_member_rewritelist'] = 'Перезаполнение таблицы участников';
$_lang['vkroulette_member_rewritelist_confirm'] = 'Будут удалены все текущие записи и заново заполнены.<br>Продолжить?';

//$_lang['vkroulette_winner_autocreate'] = 'Добавить новую запись (авто розыгрыш)';
$_lang['vkroulette_winner_create'] = 'Добавить новую запись';
$_lang['vkroulette_winner_err_ae'] = 'Запись с таким именем уже существует.';
$_lang['vkroulette_winner_err_nf'] = 'Запись не найдена.';
$_lang['vkroulette_winner_err_ns'] = 'Нет ни одной записи в таблице winners.';
$_lang['vkroulette_winner_err_remove'] = 'Ошибка при удалении записи.';
$_lang['vkroulette_winner_err_save'] = 'Ошибка при сохранении записи.';
$_lang['vkroulette_winner_remove'] = 'Удалить запись';
$_lang['vkroulette_winner_remove_confirm'] = 'Вы уверены, что хотите удалить текущую запись?';
$_lang['vkroulette_winner_update'] = 'Изменить запись';
$_lang['vkroulette_winner_find'] = 'Разыграть рулетку';
$_lang['vkroulette_winner_autocreate'] = 'Разыграть рулетку';
$_lang['vkroulette_winner_autocreate_confirm'] = 'Вы уверены, что хотите разыграть конкурс?<br>Будет автоматически добавлен новый победитель в таблицу Winners';
$_lang['vkroulette_winner_err_acr_gs'] = 'Ошибка при подключении внутреннего модуля vkroulette.';

// подписи колонок таблицы
$_lang['vkroulette_member_uid'] = 'id vk';
$_lang['vkroulette_member_first_name'] = 'Имя';
$_lang['vkroulette_member_last_name'] = 'Фамилия';
$_lang['vkroulette_member_screen_name'] = 'Адрес vk';
$_lang['vkroulette_member_photo'] = 'Аватар';
$_lang['vkroulette_member_link'] = 'Ссылка';
$_lang['vkroulette_member_signed'] = 'Подписан?';
$_lang['vkroulette_member_repost'] = 'Репост';
$_lang['vkroulette_member_manager'] = 'Админ';

// подписи колонок таблицы
$_lang['vkroulette_winner_uid'] = 'id vk';
$_lang['vkroulette_winner_first_name'] = 'Имя';
$_lang['vkroulette_winner_last_name'] = 'Фамилия';
$_lang['vkroulette_winner_screen_name'] = 'Адрес vk';
$_lang['vkroulette_winner_photo'] = 'Аватар';
$_lang['vkroulette_winner_link'] = 'Ссылка';
$_lang['vkroulette_winner_data'] = 'Дата';
$_lang['vkroulette_winner_summa'] = 'Сумма';
$_lang['vkroulette_winner_mmbrscount'] = 'Число подписчиков';
$_lang['vkroulette_winner_mmbrsrepost'] = 'Число репостов';
