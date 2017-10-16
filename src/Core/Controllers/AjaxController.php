<?php

namespace Just\Shapeshifter\Core\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Krucas\Notification\Facades\Notification;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AjaxController extends Controller
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    protected function sortorderChange()
    {
        if (! $model = $this->request->get('model')) {
            return new JsonResponse(['You have to specify an model']);
        }

        /** @var Model $model */
        $model = ucfirst($model);
        $model = new $model();

        $orderByColumn = isset($model->orderby[0]) ? $model->orderby[0] : 'sortorder';
        $orderByMode   = isset($model->orderby[1]) ? $model->orderby[1] : 'ASC';

        $segments = array_reverse(explode('/', $this->request->get('url')));
        $numeric  = null;
        foreach ($segments as $segment) {
            if (is_numeric($segment)) {
                $numeric = $segment;
                break;
            }
        }

        if (null !== $numeric && $this->request->has('relation')) {
            $model = $model->findOrNew($numeric);

            $records = $model->{$this->request->get('relation')}(function ($q) use ($orderByColumn, $orderByMode) {
                $q->orderBy($orderByColumn, $orderByMode);
            })->get();
        } else {
            $records = $model::orderBy($orderByColumn, $orderByMode);

            foreach ($this->request->get('filter', []) as $q) {
                $records->whereRaw($q);
            }

            $records = $records->get();
        }

        $newOrder = 1;
        foreach ($this->request->get('order') as $new) {
            $new = (int) $new;
            foreach ($records as $rec) {
                if ($new == $rec->id) {
                    $rec->sortorder = $newOrder;
                    $rec->save();

                    $newOrder++;

                    break;
                }
            }
        }

        return new JsonResponse(
            ['message' => (string) Notification::successInstant('De volgorde is opgeslagen.'), 'status' => 200]
        );
    }

    /**
     * @return Collection
     */
    protected function upload()
    {
        /** @var UploadedFile[] $input */
        $input      = $this->request->file('files');
        $storageDir = $this->request->get('storagedir');
        $files      = new Collection();

        if ($input) {
            foreach ($input as $file) {
                if ($file->isValid()) {
                    $name       = str_slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                    $extension  = $file->getClientOriginalExtension();
                    $path       = public_path() . $storageDir;

                    if (File::exists($path . $name . '.' . $extension)) {
                        $num = 2;
                        foreach (File::glob($path . $name . '-*.' . $extension) as $existing) {
                            $existing = pathinfo($existing)['filename'];
                            if (preg_match('/-(\d+)$/', $existing, $matches)) {
                                $num = max($num, (int) $matches[1] + 1);
                            }
                        }
                        $name .= '-' . $num;
                    }

                    $file_name = $name . '.' . $extension;
                    $file->move($path, $file_name);
                    $files[$file_name] = $storageDir . $file_name;
                }
            }
        }

        return $files;
    }
}
