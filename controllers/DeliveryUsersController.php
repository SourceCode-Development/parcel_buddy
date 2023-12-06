<?php

namespace app\controllers;


use app\core\Application;
use app\core\Controller;
use app\core\middlewares\{AuthMiddleware,AdminMiddleware};
use app\core\Request;
use app\core\Response;
use app\models\LoginForm;
use app\models\{User, Parcel};
use app\core\exception\NotFoundException;
use ErrorException;

class DeliveryUsersController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware([
            'index',
            'add_delivery_user',
            'create_delivery_user',
            'update_delivery_user',
            'edit_delivery_user'
        ]));

        $this->registerMiddleware(new AdminMiddleware([
            'index',
            'add_delivery_user',
            'create_delivery_user',
            'update_delivery_user',
            'edit_delivery_user'
        ]));
    }

    public function index(){
        $title = 'Delivery Users';
        $riders = User::findMany(['role_id' => 2]);
        $send_data = \compact('riders', 'title');

        $this->setLayout('auth');
        return $this->render('delivery_users', $send_data);
    }

    public function add_delivery_user(){
        $title = 'Add delivery User';
        $model = new User();
        $send_data = \compact('model', 'title');
        return $this->render('add_delivery_user', $send_data);
    }

    public function create_delivery_user(Request $request){
        try{
            $create_user = new User();
            if ($request->getMethod() === 'post') {
                $create_user->loadData($request->getBody());
                if ($create_user->validate() && $create_user->save()) {
                    Application::$app->session->setFlash('success', 'User registered successfully');
                    Application::$app->response->redirect('/delivery_users');
                    return 'Show success page';
                }
            }
            $this->setLayout('auth');
            return $this->render('add_delivery_user', [
                'model' => $create_user
            ]);
        }
        catch(\Throwable $e){
            throw new ErrorException();
        }
    }

    public function edit_delivery_user(Request $request){
        $title = 'Edit delivery user';

        $params = $request->getRouteParams();

        if(empty($params) || empty($params['id'])){
            throw new NotFoundException();
        }

        $user_id = $params['id'];

        $user = User::findOne(['id' => $user_id]);
        if(empty($user)){
            throw new NotFoundException('User does not exist');
        }
        $user->password = '';
        $model = new User();
        $model->loadData($user);

        $send_data = \compact('user', 'model','title');

        return $this->render('edit_delivery_user', $send_data);
    }

    public function update_delivery_user(Request $request){
        $body = $request->getBody();
        $user = new User();
        if ($request->getMethod() === 'post') {
            $user->loadData($body);
            if ($user->validate_for_update() && $user->update()) {
                Application::$app->session->setFlash('success', 'User updated successfully');
                Application::$app->response->redirect('/delivery_users');
                return 'Show success page';
            }
        }

        $title = 'Edit delivery user';
        $model = $user;
        $send_data = \compact('user', 'model', 'title');

        $this->setLayout('auth');
        return $this->render('edit_delivery_user', $send_data);
    }
}

?>