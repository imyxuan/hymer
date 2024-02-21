<?php

namespace IMyxuan\Hymer\Events;

use Illuminate\Queue\SerializesModels;
use IMyxuan\Hymer\Models\DataType;

class BreadDataRestored
{
    use SerializesModels;

    public $dataType;

    public $data;

    public function __construct(DataType $dataType, $data)
    {
        $this->dataType = $dataType;

        $this->data = $data;

        event(new BreadDataChanged($dataType, $data, 'Restored'));
    }
}
