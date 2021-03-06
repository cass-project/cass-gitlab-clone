<?php

use Phinx\Migration\AbstractMigration;

class ExpertInMigration extends AbstractMigration
{
    public function change()
    {
        $this->table('profile_expert_in')
            ->addColumn('profile_id', 'integer')
            ->addColumn('theme_id', 'integer')
            ->addForeignKey('profile_id', 'profile', 'id', [
                'delete' => 'cascade',
                'update' => 'cascade'
            ])
            ->addForeignKey('theme_id', 'theme', 'id', [
                'delete' => 'cascade',
                'update' => 'cascade'
            ])
        ->create();

        $this->table('profile')
            ->addColumn('expert_in_ids', 'string',['null'=>TRUE])
        ->save();
    }
}
