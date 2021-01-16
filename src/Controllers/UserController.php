<?php

    namespace App\Controllers;

    use App\Request;
    use App\Controller;
    use App\Model;
    use App\View;
    use App\Session;
    use App\DB;

    final class UserController extends Controller implements Model,View{

        public function __construct(Request $request,Session $session){
            parent::__construct($request,$session);
        }
    
        function log(){
            if (isset($_POST['email'])&&!empty($_POST['email'])
            &&isset($_POST['passw'])&&!empty($_POST['passw']))
            {
                $email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
                $pass=filter_input(INPUT_POST,'passw',FILTER_SANITIZE_STRING);
            
           
                $user=$this->auth($email,$pass);
                if ($user){
                    $this->session->set('user',$user);
                    //si usuari valid
                    if(isset($_POST['remember-me'])&&($_POST['remember-me']=='on'||$_POST['remember-me']=='1' )&& !isset($_COOKIE['remember'])){
                        $hour = time()+3600 *24 * 30;
                        $path=parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
                        setcookie('uname', $user['uname'], $hour,$path);
                        setcookie('email', $user['email'], $hour,$path);
                        setcookie('active', 1, $hour,$path);          
                    }
                    header('Location:'.BASE.'user/dashboard');
                }
                else{
                    header('Location:'.BASE.'user/login');
                }
            
            }
        }

        function dashboard(){
            
            $user=$this->session->get('user');
            $data=$this->getDB()->selectAllWithJoin('tasks','users',['tasks.id','tasks.description','tasks.due_date'],'user','id');
            $this->render(['user'=>$user,'data'=>$data],'dashboard');
        }

        function login(){
            $db=$this->getDB();
            $dataview=['title'=>'login'];
            $this->render($dataview,"login");
        }
    
        function iniciarS(){
            $nombre = filter_input(INPUT_POST,"nombre");
            $password = filter_input(INPUT_POST,"password");
            $quieroR = filter_input(INPUT_POST,"reg");
            
            $command2="
            SELECT * FROM user WHERE username = :nombre";
            try{
                $db=$this->getDB();
                $resultL = $db->prepare($command2);
                $resultL->bindParam(":nombre", $nombre);
                $resultL->execute();
                $countL = $resultL->rowcount();
                $comprob = $resultL->fetchAll();
                if($countL>0){
                    $passv = $comprob[0];
                    $con = password_verify($password,$passv['passwd']);
                    if($con){
                        if($quieroR==true){
                            $dataview=['title'=>'login'];
                            $this->render($dataview,"login");
                        }
                        else{
                            $_SESSION["uname"]=$nombre;
                            $_SESSION["id"]=$passv['id'];
                            $dataview=['title'=>'login_s'];
                            $this->render($dataview,"login_s");
                        }
                    }
                    else{
                        $dataview=['title'=>'login'];
                        $this->render($dataview,"login");
                    }
                }
                else{
                    if ($quieroR){
                        $_SESSION["nombre"]=$nombre;
                        $dataview=['title'=>'register'];
                        $this->render($dataview,"register");
                        //setcookie("nombre",$nombre);
                    }
                    else{
                        $dataview=['title'=>'login'];
                        $this->render($dataview,"login");
                    }
                }
    
            }catch(PDOException $e){
                die($e->getMessage());
            }
        }

        function crearUser(){
            $db=$this->getDB();
            $name = $_SESSION['nombre'];
            $pass = filter_input(INPUT_POST,"password");
            $pass2 = filter_input(INPUT_POST,"password2");
            $email = filter_input(INPUT_POST,"email");
            $quieroR = false;
            $passE = password_hash($pass,PASSWORD_BCRYPT, ['cost'=>4]);
            $role = 1;
            $data = [$email,$name,$passE,$role];

            if(isset($archivo)){
                //$datos = implode(',',$archivo);
                $email = $archivo[0];
                $name = $archivo[1];
                $pass = $archivo[2];
                $role = $archivo[3];
            }else{
                $datos = '*';
            }
            $command3 = "
                INSERT INTO user (username,email,passwd,rol) VALUES (:name,:email,:passE,:role)";
            try{
                $result = $db->prepare($command3);
                $result->bindParam(":email", $email);
                $result->bindParam(":name", $name);
                $result->bindParam(":passE", $passE);
                $result->bindParam(":role", $role);
                $result -> execute();
                $dataview=['title'=>'login'];
                $this->render($dataview,"login");
            }catch(PDOException $e){
                die($e->getMessage());
            }
        }

        function selectLista(){
            $db=DB::singleton();
            $id=$_SESSION['id'];
            $command4="
            SELECT * FROM task WHERE user = :id";
            try{
                $result = $db->prepare($command4);
                $result->bindParam(":id", $id);
                $result->execute();
                $count = $result->rowcount();
                $comprobT = $result->fetchAll();
                if(empty($comprobT)){
                    echo "No hay listas para enseñarte";
                }
                else{
                    foreach($comprobT as $fila){
                            $idT = $fila['id'];
                            echo "<p id='lista'>".$fila['description']." | ";
                            echo substr($fila['due_date'],0,-9)."</p><form action='".BASE."redirect/verItems' method='post'> <button type='submit' value='$idT' name='id'>Ver Lista</button> </form>";
                    }
                }
            }catch(PDOException $e){
                die($e->getMessage());
            }
        }

        function insertLista(){
            $db=$this->getDB();
            $name = filter_input(INPUT_POST,"tableName");
            $userT = $_SESSION['id'];
            $list_data = filter_input(INPUT_POST,"list-start");

            if($name == "" or $list_data==null){
                $dataview=['title'=>'task'];
                $this->render($dataview,"task");
                
            }
            else{
                $command5="
                insert into task (description,user,due_date) values (:name,:userT,:list_data)";
                try{
                    $resultT = $db->prepare($command5);
                    $resultT->bindParam(":name", $name);
                    $resultT->bindParam(":userT", $userT);
                    $resultT->bindParam(":list_data", $list_data);
                    $resultT->execute();
                }catch(PDOException $e){
                    die($e->getMessage());
                }
                $dataview=['title'=>'task'];
                $this->render($dataview,"task");
            }
        
        }

        function verItems(){
            $db=DB::singleton();
            $tab = $_SESSION["tab2"];

            $command5="
            SELECT * FROM task_item where task = :tab";
                try{
                    $resultIT = $db->prepare($command5);
                    $resultIT->bindParam(":tab", $tab);
                    $resultIT->execute();
                    $comprobIT = $resultIT->fetchAll();
                        if(empty($comprobIT)){
                            echo "<p>No hay listas para enseñarte</p>";
                        }
                        else{
                            foreach($comprobIT as $filaIT){
                                $idIT = $filaIT['id'];
                                echo "<div id='item'><p id='lista'>".$filaIT['item']." | ";
                                if($filaIT['completed']){
                                    echo "hecho</p><form action='".BASE."redirect/editI' method='post'> <button type='submit' value='$idIT' name='idIT'>Editar item</button> </form><br><form action='".BASE."user/elimI' method='post'> <button type='submit' value='$idIT' name='idITE'>Eliminar item</button> </form><div>";
                                }
                                else{
                                    echo "por hacer</p><form action='".BASE."redirect/editI' method='post'> <button type='submit' value='$idIT' name='idIT'>Editar item</button> </form><br><form action='".BASE."user/elimI' method='post'> <button type='submit' value='$idIT' name='idITE'>Eliminar item</button> </form><div>";
                                }
                            }
                            
                        }
                }catch(PDOException $e){
                    die($e->getMessage());
                }
        }
    
        function editarItems(){
            $db=$this->getDB();
            $name = filter_input(INPUT_POST,"ItemName");
            $est = filter_input(INPUT_POST,"estado");
            $it = $_SESSION['p'];
            if($name == "" or $est==null){
                $dataview=['title'=>'editLista'];
                $this->render($dataview,"editLista");
                
            }
            else{
                if($est == "Hecho"){
                    $e = 1;
                }
                else{
                    $e = 0;
                }
                $command5="
                UPDATE task_item SET item = :name, completed = :e WHERE id = :it";
                try{
                    $resultT = $db->prepare($command5);
                    $resultT->bindParam(":name", $name);
                    $resultT->bindParam(":e", $e);
                    $resultT->bindParam(":it", $it);
                    $resultT->execute();
                }catch(PDOException $e){
                    die($e->getMessage());
                }
                $dataview=['title'=>'task'];
                $this->render($dataview,"task");
            }
        }

        function elimI(){
            $db=$this->getDB();
            $itE = filter_input(INPUT_POST,"idITE");
            $command6="
                DELETE FROM task_item WHERE id = :itE";
                try{
                    $resultT = $db->prepare($command6);
                    $resultT->bindParam(":itE", $itE);
                    $resultT->execute();
                }catch(PDOException $e){
                    die($e->getMessage());
                }
                $dataview=['title'=>'task'];
                $this->render($dataview,"task");
        }

        function insertItem(){
            $item = filter_input(INPUT_POST,"itemName");
            $task = $_SESSION['tab2'];
            $db=$this->getDB();
            $command6="
                insert into task_item (item,completed,task) VALUES (:item,false,:task)";
            try{
                $resultIT = $db->prepare($command6);
                $resultIT->bindParam(":item", $item);
                $resultIT->bindParam(":task", $task);
                $resultIT->execute();
            }catch(PDOException $e){
                die($e->getMessage());
            }
            $dataview=['title'=>'verItems'];
            $this->render($dataview,"verItems");
        }
    
    }      

