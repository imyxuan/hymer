<?php

namespace PickOne\Hymer\Events;

use Illuminate\Queue\SerializesModels;
use PickOne\Hymer\Models\DataType;

class BreadChanged
{
    use SerializesModels;

    public $dataType;

    public $data;

    public $changeType;

    public function __construct(DataType $dataType, $data, $changeType)
    {
        $this->dataType = $dataType;

        $this->data = $data;

        $this->changeType = $changeType;
    }
}
