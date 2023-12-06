<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\exception\ForbiddenException;

class AdminMiddleware extends BaseMiddleware
{
    protected array $actions = [];

    public function __construct($actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if ( Application::$app->user->role_id == 2 ) {
            if(!empty($this->actions) && in_array(Application::$app->controller->action, $this->actions) ){
                throw new ForbiddenException();
            }
        }
    }
}