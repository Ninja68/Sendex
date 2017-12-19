<?php
// ����������
define('MODX_API_MODE', true);
require '../index.php';

// �������� ��������� ������
$modx->getService('error','error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

echo '<pre>';
print_r($modx->config);
echo '</pre>';

echo('!!!!!!!!');
//jhtfdhgfjhfgkjh
//Пашка, ты балбес!
