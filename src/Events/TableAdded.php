<?php

namespace PickOne\Hymer\Events;

use Illuminate\Queue\SerializesModels;
use PickOne\Hymer\Database\Schema\Table;

class TableAdded
{
    use SerializesModels;

    public $table;

    public function __construct(Table $table)
    {
        $this->table = $table;

        event(new TableChanged($table->name, 'Added'));
    }
}
