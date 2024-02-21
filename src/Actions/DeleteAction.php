<?php

namespace PickOne\Hymer\Actions;

class DeleteAction extends AbstractAction
{
    public function getTitle()
    {
        return __('hymer::generic.delete');
    }

    public function getIcon()
    {
        return 'hymer-trash';
    }

    public function getPolicy()
    {
        return 'delete';
    }

    public function getAttributes()
    {
        return [
            'class'   => 'btn btn-sm btn-danger float-end delete d-flex gap-2',
            'data-id' => $this->data->{$this->data->getKeyName()},
            'id'      => 'delete-'.$this->data->{$this->data->getKeyName()},
        ];
    }

    public function getDefaultRoute()
    {
        return 'javascript:;';
    }
}
