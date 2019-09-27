<?php namespace Model\Unicredit\Migrations;

use Model\Db\Migration;

class Migration_2019092601_CreateTable extends Migration
{
	public function exec()
	{
		$this->createTable('unicredit_payments');
		$this->addColumn('unicredit_payments', 'order_id', ['null' => false]);
		$this->addColumn('unicredit_payments', 'unicredit_id', ['null' => false]);
		$this->addColumn('unicredit_payments', 'amount', ['type' => 'DECIMAL(10,2)', 'null' => false]);
	}

	public function check(): bool
	{
		return $this->tableExists('unicredit_payments');
	}
}
