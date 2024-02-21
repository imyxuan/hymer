<?php

namespace PickOne\Hymer\Actions;

class EditAction extends AbstractAction
{
    public function getTitle()
    {
        return __('hymer::generic.edit');
    }

    public function getIcon()
    {
        return 'hymer-edit';
    }

    public function getPolicy()
    {
        return 'edit';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-primary float-end edit d-flex gap-2',
        ];
    }

    public function getDefaultRoute()
    {
        return route('hymer.'.$this->dataType->slug.'.edit', $this->data->{$this->data->getKeyName()});
    }
}
