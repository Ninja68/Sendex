<?php
/**
 * Create an Item
 */
class vkrwinnersAutoCreateProcessor extends modObjectGetListProcessor {
	public $objectType = 'vkrwinners';
	public $classKey = 'vkrwinners';
	public $defaultSortField = 'id';
	public $defaultSortDirection = 'DESC';
	public $renderers = '';


	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
		return $c;
	}


	/**
	 * @param xPDOObject $object
	 *
	 * @return array
	 */
	public function prepareRow(xPDOObject $object) {
		$array = $object->toArray();

		return $array;
	}

	public function beforeQuery() {
		// выполним наше действие перед запросом списка - вызовем функцию автосоздания объекта
		$vkroulette = $this->modx->getService('vkroulette','vkroulette',$this->modx->getOption('vkroulette_core_path',null,$this->modx->getOption('core_path').'components/vkroulette/').'model/vkroulette/');
		if (!($vkroulette instanceof vkroulette)) return _('vkroulette_winner_err_acr_gs');
		$vkroulette->rewriteallmembers();
		$vkroulette->updatemembersreposts();

		return true;
	}
}

return 'vkrwinnersAutoCreateProcessor';