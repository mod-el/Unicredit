<?php namespace Model\Unicredit;

class Unicredit extends \Model\Core\Module
{
	public function buy(array $param)
	{
		$config = $this->retrieveConfig();

		require(INCLUDE_PATH . 'model/Unicredit/files/IGFS_CG_API/init/IgfsCgInit.php');
		$init = new \IgfsCgInit();
		$init->serverURL = "https://pagamenti.unicredit.it/UNI_CG_SERVICES/services";
		$init->timeout = 15000;
		$init->tid = $config['tid'];
		$init->kSig = $config['kSig'];
		$init->shopID = $param['id'];
		$init->shopUserRef = $param['email'];
		$init->trType = "AUTH";
		$init->currencyCode = "EUR";
		$init->amount = round($param['amount'] * 100, 0);
		$init->langID = "IT";
		$init->notifyURL = BASE_HOST . PATH . $config['path'] . '?cd=' . urlencode($param['id']);
		$init->errorURL = BASE_HOST . PATH . $config['path'] . '?cd=' . urlencode($param['id']);
		$init->disableCheckSSLCert();
		if (!$init->execute()) {
			echo $init->rc . " " . $init->errorDesc;
		} else {
			$this->model->insert('unicredit_payments', [
				'order_id' => $param['id'],
				'unicredit_id' => $init->paymentID,
				'amount' => $param['amount'],
			]);
			header("Location: " . $init->redirectURL);
		}
		die();
	}

	public function checkResponse(): array
	{
		$config = $this->retrieveConfig();

		require(INCLUDE_PATH . 'model/Unicredit/files/IGFS_CG_API/init/IgfsCgVerify.php');
		$verify = new \IgfsCgVerify();
		$verify->serverURL = "https://pagamenti.unicredit.it/UNI_CG_SERVICES/services";
		$verify->timeout = 15000;
		$verify->tid = $config['tid'];
		$verify->kSig = $config['kSig'];
		$verify->shopID = $_GET['cd'];
		$verify->trType = "VERIFY";
		$O = $this->model->select('unicredit_payments', ['order_id' => $_GET['cd']]);
		$verify->paymentID = $O['unicredit_id'];
		$verify->disableCheckSSLCert();
		if (!$verify->execute())
			$this->model->error($verify->rc . " " . $verify->errorDesc);

		return $O;
	}

	/**
	 * @param array $request
	 * @param string $rule
	 * @return array|null
	 */
	public function getController(array $request, string $rule): ?array
	{
		return $rule === 'unicredit' ? [
			'controller' => 'Unicredit',
		] : null;
	}
}
