<?php namespace Just\Shapeshifter\Services;

use Route;

class AttributeService
{
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

    public function mutateList( $records, $attributes )
    {
        $_ignored = array('id', 'sortorder', 'updated_at','created_at');

        foreach ($records as $rec)
        {
            foreach ($rec->toArray() as $k=>$r)
            {
                if ( in_array($k, $_ignored) || ! isset($attributes[$k])  ) {
                    continue;
                }

                $attributes[$k]->setAttributeValue($r);
                $rec->setAttribute($k, $attributes[$k]->getDisplayValue() );
            }
        }

        return $records;
    }


    public function attributesToTabs($mode, $attributes, $model)
    {
        $tabs = array();
        foreach ($attributes as $key => $attr)
        {
            if ($mode == 'create' && $attr->hasFlag('hide_add')) continue;

            if ($mode === 'edit')
            {
                if ($attr->hasFlag('hide_edit')) continue;

                $attr->setAttributeValue( $model->{$attr->name} );
                $model->{$attr->name} = $attr->getEditValue($attr->value);
            }

            if ( ! in_array($attr->tab, $tabs))
            {
                $tabs[$attr->tab][] = $attr;
            }
        }

        return $tabs;
    }

    public static function ignoreAttributes($attribute)
    {
        return get_class($attribute) === 'Just\Shapeshifter\Relations\OneToManyRelation';
    }
}
