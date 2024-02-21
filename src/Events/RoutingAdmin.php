<?php

namespace PickOne\Hymer\Events;

use Illuminate\Queue\SerializesModels;

class RoutingAdmin
{
    use SerializesModels;

    public $router;

    public function __construct()
    {
        $this->router = app('router');

        // @deprecate
        //
        event('hymer.admin.routing', $this->router);
    }
}
