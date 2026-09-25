<?php
declare(strict_types=1);
use Phinx\Migration\AbstractMigration;

class CreateOrders extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('orders', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'integer', ['autoIncrement' => true])
              ->addColumn('user_id', 'integer')
              ->addColumn('amount', 'integer')
              ->addColumn('currency', 'string', ['limit' => 3, 'default' => 'VND'])
              ->addColumn('status', 'string', ['limit' => 20, 'default' => 'pending'])
              ->addColumn('gateway', 'string', ['limit' => 20])
              ->addColumn('idempotency_key', 'string', ['limit' => 64])
              ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addIndex(['user_id'])
              ->addIndex(['idempotency_key'], ['unique' => true])
              ->create();

        $this->table('transactions')
             ->addColumn('order_id', 'integer')
             ->addColumn('gateway_txn_id', 'string', ['limit' => 100, 'null' => true])
             ->addColumn('status', 'string', ['limit' => 20])
             ->addColumn('payload', 'text', ['null' => true])
             ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
             ->addIndex(['order_id'])
             ->create();
    }
}
