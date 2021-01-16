<?php
    namespace App\Controllers;

        use App\Request;
        use App\Session;
        use App\Controller;
        use App\Controllers\BlogController;

    final class IndexController extends Controller{

        public function __construct(Request $request,Session $session){
            parent::__construct($request,$session);
        }
        
        public function index(){
            $db=$this->getDB();
            $data=$db->selectAll('users');
            // uso de funciones declaradas en el modelo 
            // y definidas en la clase abstracta
            // $stmt=$this->query($db,"SELECT * FROM users ",null);
            $user=$this->session->get('user');
            $dataview=[ 'title'=>'Todo','user'=>$user,
                         'data'=>$data];
            $this->render($dataview);
        }

        function editPost(){
            $_SESSION['epost']=filter_input(INPUT_POST, 'post');
            $db=$this->getDB();
            $dataview=['title'=>'editPost'];
            $this->render($dataview,"editPost");
        }

        function home(){
            $db=$this->getDB();
            $dataview=['title'=>'login_s'];
            $this->render($dataview,"login_s");
        }
       
        function elimpost(){
            $_SESSION['epost']=filter_input(INPUT_POST, 'el');
            $db=$this->getDB();
            $dataview=['title'=>'elimPost'];
            $this->render($dataview,"elimPost");
        }
        
        
    }