<?php
use Migrations\AbstractMigration;

class AuthToken extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('auth_token');
        $table->addColumn('user_id', 'integer', [
            'limit' => 11
        ]);
        $table->addColumn('access_token', 'string', [
            'limit'=>512,
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('refresh_token', 'string', [
            'limit'=>512,
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => false,
            'update'=>'CURRENT_TIMESTAMP'
        ]);
        $table->addForeignKey('user_id','users','id');
        $table->create();
    }
}
