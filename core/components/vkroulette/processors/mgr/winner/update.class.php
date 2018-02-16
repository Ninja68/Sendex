<?php
/**
 * Update an Item
 */
class vkrwinnersUpdateProcessor extends modObjectUpdateProcessor {
	public $objectType = 'vkrwinners';
	public $classKey = 'vkrwinners';
	public $languageTopics = array('vkroulette');
	public $permission = 'update_document';
}

return 'vkrwinnersUpdateProcessor';