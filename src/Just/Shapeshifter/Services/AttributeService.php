<?php namespace Just\Shapeshifter\Services;

use Illuminate\Support\Collection;
use Route;

class AttributeService
{
    /**
     * @var Collection
     */
    private $attributes;

    public function __construct(Collection $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getAllPermissions()
    {
        $routes = Route::getRoutes();
        $permissions = array();
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

    public function mutateRecords($records)
    {
        $_ignored = array('id', 'sortorder', 'updated_at','created_at');

        foreach ($records as $rec)
        {
            foreach ($rec->toArray() as $k=>$r)
            {
                if ( in_array($k, $_ignored) || ! isset($this->attributes[$k])  ) {
                    continue;
                }


                $this->attributes[$k]->setAttributeValue($r);
                $rec->setAttribute($k, $this->attributes[$k]->getDisplayValue() );
            }
        }


        return $records;
    }

    public static function ignoreAttributes($attribute)
    {
        return get_class($attribute) === 'Just\Shapeshifter\Relations\OneToManyRelation';
    }
}
