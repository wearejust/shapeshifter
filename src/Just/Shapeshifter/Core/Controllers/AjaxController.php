<?php namespace Just\Shapeshifter\Core\Controllers;

use Controller;
use Illuminate\Support\Collection;
use Input;
use Krucas\Notification\Facades\Notification;
use McCool\LaravelAutoPresenter\BasePresenter;
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



        if ($model instanceof BasePresenter) $model = $model->getResource();

        $orderByColumn = isset($model->orderby[0]) ? $model->orderby[0] : 'sortorder';
        $orderByMode = isset($model->orderby[1]) ? $model->orderby[1] : 'ASC';

        $segments = array_reverse(explode('/', Input::get('url')));
        foreach ($segments as $segment) {
            if (is_numeric($segment)) {
                $numeric = $segment;
                break;
            }
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

        $sortorder = (int)Input::get('offset', 0) + 1;
        foreach (Input::get('order') as $new)
        {
            foreach ($records as $rec)
            {
                if ($new == $rec->id)
                {
                    $rec->$orderByColumn = $sortorder;
                    $rec->save();

                    $sortorder++;

                    break;
                }
            }

        }

        return Response::json( array('message' => (string)Notification::successInstant('De volgorde is opgeslagen.'), 'status' => 200) );
    }

	protected function upload()
	{
		$storageDir = Input::get('storagedir');
		$input = Input::file('files');
		$files = new Collection();

		foreach ($input as $file) {
			if ($file->isValid()) {
				$name = \Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
				$extension = $file->getClientOriginalExtension();
				$file_name = $name . '.' . $extension;
				$file->move(public_path() . $storageDir, $file_name);
				$files[$file_name] = $storageDir . $file_name;
			}
		}

		return $files;
	}
}