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

class ParcelController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware([
            'index', 
            'add_parcel', 
            'create_parcel', 
            'edit_parcel', 
            'update_parcel'
        ]));

        $this->registerMiddleware(new AdminMiddleware([
            // 'index', 
            'add_parcel', 
            'create_parcel', 
            'edit_parcel', 
            'update_parcel'
        ]));
    }

    public function index(){
        $is_admin = false;
        $title = 'Parcels';
        if(Application::$app->user->role_id == 1){
            $all_parcels = Parcel::get_parcels();
            $is_admin = true;
        }

        else{
            $user_id = Application::$app->user->id;
            $all_parcels = Parcel::get_parcel_for_user($user_id);
        }

        $send_data = \compact('all_parcels', 'is_admin', 'title');
        $this->setLayout('auth');
        return $this->render('parcels', $send_data);
    }

    public function add_parcel(){
        $title = 'Add parcel';
        $riders = User::findMany(['role_id' => 2]);
        $send_data = \compact('riders', 'title');
        return $this->render('add_parcel', $send_data);
    }

    public function create_parcel(Request $request){
        try{
            $create_parcel = new Parcel();
            if ($request->getMethod() === 'post') {
                $create_parcel->loadData($request->getBody());
                if ($create_parcel->validate() && $create_parcel->save()) {
                    Application::$app->session->setFlash('success', 'Parcel Created Successfully');
                    Application::$app->response->redirect('/parcels');
                    return 'Show success page';
                }
            }
            $this->setLayout('auth');
            return $this->render('add_parcel', [
                'model' => $create_parcel
            ]);
        }
        catch(\Throwable $e){
            \var_dump($e);
        }
    }

    public function edit_parcel(Request $request){
        $title = 'Update parcel';
        $params = $request->getRouteParams();

        if(empty($params) || empty($params['id'])){
            throw new NotFoundException();
        }

        $parcel_id = $params['id'];

        $parcel = Parcel::findOne(['id' => $parcel_id]);
        if(empty($parcel)){
            throw new NotFoundException('Parcel Does not exist');
        }
        $model = new Parcel();
        $model->loadData($parcel);

        $riders = User::findMany(['role_id' => 2]);
        foreach($riders as &$rider){
            $rider['value'] = $rider['id'];
            $rider['title'] = $rider['name'];
        }
        $send_data = \compact('riders', 'parcel', 'model', 'title');

        return $this->render('edit_parcel', $send_data);
    }

    public function update_parcel(Request $request){
        $body = $request->getBody();
        $parcel = new Parcel();
        if ($request->getMethod() === 'post') {
            $parcel->loadData($body);
            if ($parcel->validate() && $parcel->update()) {
                Application::$app->session->setFlash('success', 'Parcel Updated Successfully');
                Application::$app->response->redirect('/parcels');
                return 'Show success page';
            }
        }

        $riders = User::findMany(['role_id' => 2]);
        $model = $parcel;
        foreach($riders as &$rider){
            $rider['value'] = $rider['id'];
            $rider['title'] = $rider['name'];
        }
        $title = 'Edit parcel';
        $send_data = \compact('riders', 'parcel', 'model', 'title');

        $this->setLayout('auth');
        return $this->render('edit_parcel', $send_data);
    }
}

?>