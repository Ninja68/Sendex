<?php
/**
 * Remove an Item
 */
class vkrwinnersRemoveProcessor extends modObjectRemoveProcessor {
	public $checkRemovePermission = true;
	public $objectType = 'vkrwinners';
	public $classKey = 'vkrwinners';
	public $languageTopics = array('vkroulette');

}

return 'vkrwinnersRemoveProcessor';