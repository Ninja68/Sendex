<?php

$settings = array();

$tmp = array(
	'core_path' => array(
		'xtype' => 'textfield',
		'value' => '{base_path}vkroulette/core/components/vkroulette/',
		'area' => 'vkroulette_main',
	),
	'assets_url' => array(
		'xtype' => 'textfield',
		'value' => '/vkroulette/assets/components/vkroulette/',
		'area' => 'vkroulette_main',
	),
	'assets_path' => array(
		'xtype' => 'textfield',
		'value' => '{base_path}vkroulette/assets/components/vkroulette/',
		'area' => 'vkroulette_main',
	),
	'groupparam_id' => array(
		'xtype' => 'textfield',
		'value' => 'input here the ID of vk_group',
		'area' => 'vkroulette_group',
	),
	'groupparam_post_id' => array(
		'xtype' => 'textfield',
		'value' => 'input here the ID of the post',
		'area' => 'vkroulette_group',
	),
	'groupparam_app_id' => array(
		'xtype' => 'textfield',
		'value' => 'input here the ID of application',
		'area' => 'vkroulette_group',
	),
	'groupparam_secret_key' => array(
		'xtype' => 'textfield',
		'value' => 'input here the SECRET_KEY of application',
		'area' => 'vkroulette_group',
	),
	'groupparam_token' => array(
		'xtype' => 'textfield',
		'value' => 'input here the TOKEN of application',
		'area' => 'vkroulette_group',
	),
	'some_setting' => array(
		'xtype' => 'combo-boolean',
		'value' => true,
		'area' => 'vkroulette_main',
	),
);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => 'vkroulette_'.$k,
			'namespace' => PKG_NAME_LOWER,
		), $v
	),'',true,true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;
