<?php
/**
 * Create an Item
 */
class vkrwinnersCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'vkrwinners';
	public $classKey = 'vkrwinners';
	public $languageTopics = array('vkroulette');
	public $permission = 'new_document';


	/**
	 * @return bool
	 */
	public function beforeSet() {
		$this->modx->log(3, 'Меня запустили -> vkrwCreateProc');
		$alreadyExists = $this->modx->getObject('vkrwinners', array(
			'id' => $this->getProperty('id'),
		));
		if ($alreadyExists) {
			$this->modx->error->addField('id', $this->modx->lexicon('vkroulette_item_err_ae'));
		}

		return !$this->hasErrors();
	}

}

return 'vkrwinnersCreateProcessor';