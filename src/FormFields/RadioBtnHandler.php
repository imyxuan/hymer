<?php

namespace PickOne\Hymer\FormFields;

class RadioBtnHandler extends AbstractHandler
{
    protected $name = 'Radio Button';
    protected $codename = 'radio_btn';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('hymer::formfields.radio_btn', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
