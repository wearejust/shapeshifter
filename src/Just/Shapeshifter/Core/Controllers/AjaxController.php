<?php namespace Just\Shapeshifter\Core\Controllers;

use Controller;
use Input;
use Krucas\Notification\Facades\Notification;
use Request;
use Response;

class AjaxController extends Controller
{
	protected function sortorderChange()
	{
		if ( ! $model = ucfirst(Input::get('model')) )
        {
            return Response::json( array('You have to specify an model') );
        }

        $model = new $model;
        $orderByColumn = isset($model->orderby[0]) ? $model->orderby[0] : 'sortorder';
        $orderByMode = isset($model->orderby[1]) ? $model->orderby[1] : 'ASC';

        $segments = array_reverse(explode('/', Input::get('url')));
        foreach ($segments as $segment) {
            if (is_numeric($segment)) $numeric = $segment;
        }


        if (Input::has('relation') && isset($numeric))
        {
            $model = $model->where('id', $numeric)->first();

            $records = $model->{Input::get('relation')}(function($q) use($orderByColumn, $orderByMode) {
                $q->orderBy( $orderByColumn, $orderByMode );
            })->get();
        }
        else
        {
            $records = $model::orderBy( $orderByColumn, $orderByMode )->get();
        }

		$sortorder = 1;	
		foreach (Input::get('order') as $new)
		{
			foreach ($records as $rec)
			{
				if ($new == $rec->id)
                {
					$rec->sortorder = $sortorder;
					$rec->save();

					$sortorder++;

					break;
				}
			}
			
		}

		return Response::json( array('message' => (string)Notification::successInstant('De nieuwe volgorde is opgeslagen'), 'status' => 200) );
	}

}

?>
