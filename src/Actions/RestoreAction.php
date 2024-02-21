<?php

namespace PickOne\Hymer\Actions;

class RestoreAction extends AbstractAction
{
    public function getTitle()
    {
        return __('hymer::generic.restore');
    }

    public function getIcon()
    {
        return 'hymer-trash';
    }

    public function getPolicy()
    {
        return 'restore';
    }

    public function getAttributes()
    {
        return [
            'class'   => 'btn btn-sm btn-success float-end restore d-flex gap-2',
            'data-id' => $this->data->{$this->data->getKeyName()},
            'id'      => 'restore-'.$this->data->{$this->data->getKeyName()},
        ];
    }

    public function getDefaultRoute()
    {
        return route('hymer.'.$this->dataType->slug.'.restore', $this->data->{$this->data->getKeyName()});
    }
}
