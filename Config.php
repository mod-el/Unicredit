<?php namespace Model\Unicredit;

use Model\Core\Module_Config;

class Config extends Module_Config
{
	/**
	 */
	protected function assetsList()
	{
		$this->addAsset('config', 'config.php', function () {
			return '<?php
$config = [
	\'path\' => \'unicredit\',
	\'tid\' => \'\',
	\'kSig\' => \'\',
];
';
		});

		$this->checkFile('app/modules/UnicreditAssets/Controllers/UnicreditController.php', '<?php namespace Model\\UnicreditAssets\\Controllers;

use Model\\Core\\Controller;

class UnicreditController extends Controller
{
	public function index()
	{
		try {
			$order = $this->model->_Unicredit->checkResponse();
			// TODO: successful answer (useful data: $order[\'order_id\'], $order[\'amount\'])
		} catch(\Exception $e) {
			// TODO: error
		}
	}
}
');
	}

	/**
	 * @return array
	 */
	public function getRules(): array
	{
		$config = $this->retrieveConfig();

		return [
			'rules' => [
				'unicredit' => $config['path'] ?? '',
			],
			'controllers' => [
				'Unicredit',
			],
		];
	}
}
