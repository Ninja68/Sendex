<?php
/**
 * The home manager controller for vkroulette.
 *
 */
class vkrouletteHomeManagerController extends vkrouletteMainController {
	/* @var vkroulette $vkroulette */
	public $vkroulette;


	/**
	 * @param array $scriptProperties
	 */
	public function process(array $scriptProperties = array()) {
	}


	/**
	 * @return null|string
	 */
	public function getPageTitle() {
		return $this->modx->lexicon('vkroulette');
	}


	/**
	 * @return void
	 */
	public function loadCustomCssJs() {
		$this->addJavascript($this->vkroulette->config['jsUrl'] . 'mgr/widgets/members.grid.js');
		$this->addJavascript($this->vkroulette->config['jsUrl'] . 'mgr/widgets/winners.grid.js');
		$this->addJavascript($this->vkroulette->config['jsUrl'] . 'mgr/widgets/home.panel.js');
		$this->addJavascript($this->vkroulette->config['jsUrl'] . 'mgr/sections/home.js');
		$this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			MODx.load({ xtype: "vkroulette-page-home"});
		});
		</script>');
	}


	/**
	 * @return string
	 */
	public function getTemplateFile() {
		return $this->vkroulette->config['templatesPath'] . 'home.tpl';
	}
}