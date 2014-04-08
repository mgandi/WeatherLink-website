<?php

class Highcharts extends CApplicationComponent
{
	/**
	 * @var bool|null Whether to republish assets on each request.
	 * If set to true, all highcharts assets will be republished on each request.
	 * Passing null to this option restores the default handling of CAssetManager of highcharts assets.
	 */
	public $forceCopyAssets = false;
	
	/**
	 * @var CClientScript Something which can register assets for later inclusion on page.
	 * For now it's just the `Yii::app()->clientScript`
	 */
	public $assetsRegistry;

	/**
	 * @var string handles the assets folder path.
	 */
	public $_assetsUrl;

	/**
	 * Initializes the component.
	 */
	public function init()
	{
        $this->setRootAliasIfUndefined();

		$this->setAssetsRegistryIfNotDefined();

		$this->includeAssets();

		parent::init();
	}

	/**
	 *
	 */
	protected function setRootAliasIfUndefined()
	{
		if (Yii::getPathOfAlias('highcharts') === false) {
			Yii::setPathOfAlias('highcharts', realpath(dirname(__FILE__) . '/..'));
		}
	}

	protected function setAssetsRegistryIfNotDefined()
	{
		if (!$this->assetsRegistry) {
            $this->assetsRegistry = Yii::app()->getClientScript();
        }
	}

	/**
	 *
	 */
	protected function includeAssets()
	{
		$this->registerJsPackagesIfEnabled();
	}

	/**
	 * If `enableJS` is not `false`, register our Javascript packages
	 */
	protected function registerJsPackagesIfEnabled()
	{
		$this->registerAssetJs('highcharts.src.js');
		$this->registerAssetJs('highcharts-more.src.js');
		$this->registerAssetJs('highcharts-wind.src.js');
	}

	/**
	 * Register a javascript file in the asset's js folder
	 *
	 * @param string $name the js file name to register
	 * @param int $position the position of the JavaScript code.
	 *
	 * @see CClientScript::registerScriptFile
	 */
	public function registerAssetJs($name, $position = CClientScript::POS_END)
	{
		$this->assetsRegistry->registerScriptFile($this->getAssetsUrl() . "/js/{$name}", $position);
	}

	/**
	 * Returns the URL to the published assets folder.
	 * @return string an absolute URL to the published asset
	 */
	public function getAssetsUrl()
	{
		if (isset($this->_assetsUrl)) {
			return $this->_assetsUrl;
		} else {
			return $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
				Yii::getPathOfAlias('highcharts'),
				false,
				-1,
				$this->forceCopyAssets
			);
		}
	}
}