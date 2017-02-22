<?php

namespace Just\Shapeshifter\Http\Controllers;

use App\News;
use Illuminate\Database\Eloquent\Model;
use Just\Shapeshifter\Attributes\Collections\ComponentCollection;
use Just\Shapeshifter\Attributes\Collections\Section;
use Just\Shapeshifter\Attributes\Collections\Tab;
use Just\Shapeshifter\Attributes\Text;

class TestController extends AdminController
{
    /**
     * @var string
     */
    protected $model = News::class;

    /**
     * @param ComponentCollection $collection
     *
     * @return ComponentCollection
     */
    protected function components(ComponentCollection $collection) : ComponentCollection
    {
        $collection->tab('First tab', function(Tab $c) {
            $c->add(new Text('title'));

            $c->section('Section A', function(Section $c) {
                $c->add(new Text('intro'));
            });
        });

        $collection->section('Section B', function(Section $c) {
            $c->add(new Text('test'));
        });

        return $collection
            ->add(new Text('name1'))
            ->add(new Text('name2'))
            ->add(new Text('name3'))
            ->add(new Text('name4'));
    }


    protected function columns() : array
    {
        return [
            'title',
            'test',
            'unkown_component'
        ];
    }
}
