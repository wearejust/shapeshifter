<?php namespace Just\Shapeshifter\Helpers;

use DB;
use Schema;

class TimestampHelper
{
    /**
     * @var string
     */
    private $table;

    /**
     * @param $table
     */
    public function __construct($table)
    {
        $this->table = $table;
    }

    /**
     * Create updated_at and created_at fields if they don't exist
     */
    public function createFields()
    {
        $fields = array('updated_at', 'created_at');
        foreach($fields as $field)
        {
            if ( ! Schema::hasColumn($this->table, $field))
            {
                DB::statement("ALTER TABLE {$this->table} ADD {$field} datetime");
            }
        }
    }
}
