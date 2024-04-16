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
use SimpleSoftwareIO\QrCode\Generator;
use ErrorException;

class ParcelController extends Controller
{
    public const PARCEL_DISPLAY_LIMIT = 10;

    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware([
            'index', 
            'parcels_paginated',
            'add_parcel', 
            'create_parcel', 
            'edit_parcel', 
            'update_parcel',
            'change_status',
            'update_parcel_status',
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
            $total_num_of_parcels = Parcel::get_parcel_count();
            
            $all_parcels_source = Parcel::get_parcels();
            $is_admin = true;
        }

        else{
            $user_id = Application::$app->user->id;

            $total_num_of_parcels = Parcel::get_parcel_count_for_user($user_id);
            $all_parcels_source = Parcel::get_parcel_for_user($user_id);
        }

        $all_parcels = [];
        if(!empty($all_parcels_source)){
            foreach($all_parcels_source as $all_parcel){
                $file_path = $_ENV['QRCODES_FOLDER'] . $all_parcel['id'] . '.png';

                if(\file_exists($file_path)){
                    $qr_code_path = $_ENV['BASE_URL'] . '/' . $file_path;
                }
                else{
                    $qr_code_path = $_ENV['BASE_URL'] . '/' . $_ENV['DEFAULT_IMAGE'];
                }

                $all_parcels[] = [
                    'id' => $all_parcel['id'],
                    'recipient_name' => $all_parcel['recipient_name'],
                    'delivery_address' => $all_parcel['delivery_address'],
                    'longitude' => $all_parcel['longitude'],
                    'latitude' => $all_parcel['latitude'],
                    'name' => $all_parcel['name'],
                    'status_title' => $all_parcel['status_title'],
                    'qr_code' => $qr_code_path,
                ];
            }
        }

        $initial_limit = self::PARCEL_DISPLAY_LIMIT;
        $send_data = \compact('all_parcels', 'is_admin', 'title', 'total_num_of_parcels', 'initial_limit');
        $this->setLayout('auth');
        return $this->render('parcels', $send_data);
    }

    public function parcels_paginated(Request $request){
        $params = $request->getRouteParams();

        $page = 1;
        if(!empty($params['page'])){
            $page = $params['page'];
        }

        $offset = self::PARCEL_DISPLAY_LIMIT * ($page - 1);

        if($offset < 0){
            $offset = 0;
        }

        $is_admin = false;
        if(Application::$app->user->role_id == 1){
            $all_parcels_source = Parcel::get_parcels(self::PARCEL_DISPLAY_LIMIT, $offset);
            $total_num_of_parcels = Parcel::get_parcel_count();

            $is_admin = true;
        }

        else{
            $user_id = Application::$app->user->id;

            $total_num_of_parcels = Parcel::get_parcel_count_for_user($user_id);
            $all_parcels_source = Parcel::get_parcel_for_user($user_id, self::PARCEL_DISPLAY_LIMIT, $offset);
        }

        $all_parcels = [];
        if(!empty($all_parcels_source)){
            foreach($all_parcels_source as $all_parcel){
                $file_path = $_ENV['QRCODES_FOLDER'] . $all_parcel['id'] . '.png';

                if(\file_exists($file_path)){
                    $qr_code_path = $_ENV['BASE_URL'] . '/' . $file_path;
                }
                else{
                    $qr_code_path = $_ENV['BASE_URL'] . '/' . $_ENV['DEFAULT_IMAGE'];
                }

                $all_parcels[] = [
                    'id' => $all_parcel['id'],
                    'recipient_name' => $all_parcel['recipient_name'],
                    'delivery_address' => $all_parcel['delivery_address'],
                    'longitude' => $all_parcel['longitude'],
                    'latitude' => $all_parcel['latitude'],
                    'name' => $all_parcel['name'],
                    'status_title' => $all_parcel['status_title'],
                    'qr_code' => $qr_code_path,
                ];
            }
        }

        $send_data = \compact('all_parcels', 'total_num_of_parcels');
        return \json_encode($send_data);
    }

    public function add_parcel(){
        $title = 'Add parcel';
        $riders = User::findMany(['role_id' => 2]);
        $send_data = \compact('riders', 'title');
        return $this->render('add_parcel', $send_data);
    }

    public function change_status(Request $request){
        $title = 'Update parcel status';

        $params = $request->getRouteParams();

        if(empty($params) || empty($params['parcel_id'])){
            throw new NotFoundException();
        }

        $is_admin = false;
        if(Application::$app->user->role_id == 1){
            $is_admin = true;
        }

        $parcel_id = $params['parcel_id'];

        $parcel = Parcel::findOne(['id' => $parcel_id]);
        if(empty($parcel)){
            throw new NotFoundException('Parcel Does not exist');
        }
        $model = new Parcel();
        $model->loadData($parcel);

        $send_data = \compact('title', 'model', 'is_admin');
        return $this->render('update-parcel-status', $send_data);
    }

    public function create_parcel(Request $request){
        try{
            $create_parcel = new Parcel();

            if ($request->getMethod() === 'post') {
                $create_parcel->loadData($request->getBody());
                if ($create_parcel->validate() && $create_parcel->save()) {

                    $qrCodePath = $_ENV['QRCODES_FOLDER'] . $create_parcel->id . '.png';

                    // Check if the directory exists, if not, create it
                    // $directory = dirname($qrCodePath);
                    // if (!file_exists($directory)) {
                    //     mkdir($directory, 0777, true); // Recursive directory creation
                    // }

                    $qrcode = new Generator();

                    $qrcode->size(500)
                        ->margin(2)
                        ->format('png')
                        ->generate($create_parcel->id,$qrCodePath);

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
            // \var_dump($e);
            throw new ErrorException($e->getMessage());
        }
    }

    public function edit_parcel(Request $request){
        $title = 'Update parcel';
        $params = $request->getRouteParams();

        $is_admin = false;
        if(Application::$app->user->role_id == 1){
            $is_admin = true;
        }

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
        $send_data = \compact('riders', 'parcel', 'model', 'title', 'is_admin');

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

    public function update_parcel_status(Request $request){
        $body = $request->getBody();

        if(empty($body['id'])){
            Application::$app->session->setFlash('success', 'No parcel id specified');
            return Application::$app->response->redirect('/parcels');
        }

        $parcel_data = Parcel::get_parcel($body['id']);
        if(empty($parcel_data)){
            Application::$app->session->setFlash('success', 'Parcel does not exist');
            return Application::$app->response->redirect('/parcels');
        }

        $body['id'] = $parcel_data[0]['id'];
        $body['recipient_name'] = $parcel_data[0]['recipient_name'];
        $body['delivery_address'] = $parcel_data[0]['delivery_address'];
        $body['latitude'] = $parcel_data[0]['latitude'];
        $body['longitude'] = $parcel_data[0]['longitude'];
        $body['postcode'] = $parcel_data[0]['postcode'];
        $body['assigned_to'] = $parcel_data[0]['assigned_to'];

        if ($request->getMethod() === 'post') {
            $parcel = new Parcel();
            $parcel->loadData($body);
            if ($parcel->validate() && $parcel->update()) {
                Application::$app->session->setFlash('success', 'Parcel status updated successfully');
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