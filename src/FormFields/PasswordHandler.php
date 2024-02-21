<?php

namespace PickOne\Hymer\FormFields;

class PasswordHandler extends AbstractHandler
{
    protected $codename = 'password';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('hymer::formfields.password', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
