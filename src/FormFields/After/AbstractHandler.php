<?php

namespace IMyxuan\Hymer\FormFields\After;

use IMyxuan\Hymer\Traits\Renderable;

abstract class AbstractHandler implements HandlerInterface
{
    use Renderable;

    public function visible($row, $dataType, $dataTypeContent, $options)
    {
        return true;
    }

    public function handle($row, $dataType, $dataTypeContent)
    {
        $content = $this->createContent(
            $row,
            $dataType,
            $dataTypeContent,
            $row->details
        );

        return $this->render($content);
    }

    public function getCodename()
    {
        return $this->codename;
    }
}
