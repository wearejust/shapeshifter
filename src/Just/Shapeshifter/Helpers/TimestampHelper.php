<?php namespace Just\Shapeshifter\Helpers;

use DB;
use Schema;

class TimestampHelper
{
    public function createTimestampFields($table)
    {
        if( ! Schema::hasColumn($table, 'updated_at')  ) {
            DB::statement('ALTER TABLE '.$table.' ADD updated_at datetime' );
        }
        if( ! Schema::hasColumn($table, 'created_at')  ) {
            DB::statement('ALTER TABLE '.$table.' ADD created_at datetime' );
        }
    }
}
