<?php

namespace app\core\form;

use app\core\Model;

abstract class BaseHiddenField
{

    public Model $model;
    public string $attribute;
    public string $type;

    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    public function __toString()
    {
        return sprintf('%s',
            $this->renderInput(),
        );
    }

    abstract public function renderInput();
}