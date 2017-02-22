<?php

namespace Just\Shapeshifter\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Just\Shapeshifter\Attributes\Collections\ComponentCollection;
use Just\Shapeshifter\Attributes\Collections\Section;
use Just\Shapeshifter\Attributes\Collections\Tab;
use Just\Shapeshifter\Attributes\Text;

class Test2Controller extends AdminController
{
    /**
     * @var string
     */
    protected $model = \App\Blog::class;

    /**
     * @param \Just\Shapeshifter\Attributes\Collections\ComponentCollection $collection
     *
     * @return ComponentCollection
     */
    protected function components(ComponentCollection $collection) : ComponentCollection
    {
        $collection->tab('tabX', function(Tab $c) {
            $c->add(new Text('testcees'));

            $c->section('sectionX', function(Section $c) {
                $c->add(new Text('test'));
            });
        });

        $collection->section('sectionX', function(Section $c) {
            $c->add(new Text('test'));
        });

        return $collection
            ->add(new Text('name1'))
            ->add(new Text('name2'))
            ->add(new Text('name3'))
            ->add(new Text('name4'));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return mixed
     */
    protected function indexQuery(Model $model)
    {
        return $model->get();
    }
}
