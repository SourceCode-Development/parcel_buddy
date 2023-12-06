<?php

namespace app\core\form;

use app\core\Model;

class DropdownField extends BaseField
{
    public array $options = [];
    public function __construct(Model $model, string $attribute, array $options = [])
    {
        parent::__construct($model, $attribute);
        $this->options = $options;
    }

    public function renderInput()
    {
        $template = '<select class="form-control%s" name="%s">';
        if(!empty($this->options)){
            foreach($this->options as $option){
                $template = $template . "<option value='" . $option['value'] . "'>" . $option['title'] . "</option>";
            }
        }
        $template = $template . '</select>';
        return sprintf($template,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->attribute,
            $this->model->{$this->attribute},
        );
    }
}