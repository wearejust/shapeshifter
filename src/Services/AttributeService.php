<?php

namespace Just\Shapeshifter\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Just\Shapeshifter\Attributes\Attribute;
use Just\Shapeshifter\Relations\OneToManyRelation;
use Route;

class AttributeService
{
    /**
     * @var Collection
     */
    private $attributes;

    /**
     * @param Collection $attributes
     */
    public function __construct(Collection $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @param $records
     *
     * @return mixed
     */
    public function mutateRecords($records)
    {
        $_ignored = ['id', 'sortorder', 'updated_at', 'created_at'];

        foreach ($records as $model) {
            $toLoop = Arr::except($model->toArray(), $_ignored);

            foreach ($toLoop as $k => $r) {
                if ($this->attributes->has($k)) {
                    $model->{$k} = $this->attributes[$k]->getDisplayValue($model);
                }
            }
        }

        return $records;
    }

    /**
     * @param Attribute $attribute
     *
     * @return bool
     */
    public static function ignoreAttributes(Attribute $attribute)
    {
        return get_class($attribute) === OneToManyRelation::class;
    }

    /**
     * @return array
     */
    public function getAllPermissions()
    {
        $routes        = Route::getRoutes();
        $permissions   = [];
        $permissions[] = 'superuser';

        $filter = 'admin.';
        foreach ($routes as $route) {
            $name = $route->getName();
            if (starts_with($name, $filter)) {
                $permissions[] = $name;
            }
        }

        return $permissions;
    }
}
