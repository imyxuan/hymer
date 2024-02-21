<?php

namespace PickOne\Hymer\Actions;

class ViewAction extends AbstractAction
{
    public function getTitle()
    {
        return __('hymer::generic.view');
    }

    public function getIcon()
    {
        return 'hymer-eye';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-warning float-end view d-flex gap-2',
        ];
    }

    public function getDefaultRoute()
    {
        return route('hymer.'.$this->dataType->slug.'.show', $this->data->{$this->data->getKeyName()});
    }
}
