<?php

    namespace App\Controllers;

    use App\Request;
    use App\Controller;
    use App\Model;
    use App\View;
    use App\Session;
    use App\DB;
    use App\Controllers\UserController;

    final class BlogController extends Controller implements Model,View{

        public function __construct(Request $request,Session $session){
            parent::__construct($request,$session);
        }

        function selectCom($id){
            $db=DB::singleton();
                $comentarios = array();
                $postcom = $db->selectWhere("comments",["comment","user","post"],["post",$id]);
                foreach($postcom as $fila){
                    $userPost=$fila['user'];
                    $nombre = $db->selectWhere("user",["username"],["id",$userPost]);
                    $array = array(
                        "comment" => $fila["comment"],
                        "user" => $nombre[0]["username"],
                        "post" => $fila["post"]
                    );
                    array_push($comentarios,$array);
                }
                return $comentarios;
        }

        function selectPosts(){
            $db=DB::singleton();
                $datos = array();
                $comments = array();
                $posts = $db->selectAll("post",["id","title","cont","user","modify_date"]);
                foreach($posts as $fila){
                    $userPost=$fila['user'];
                    $nombre = $db->selectWhere("user",["username"],["id",$userPost]);
                    $com = $this->selectCom($fila['id']);
                    $array = array(
                        "id" => $fila["id"],
                        "title" => $fila["title"],
                        "cont" => $fila["cont"],
                        "user" => $nombre[0]["username"],
                        "modify_date" => $fila["modify_date"],
                        "comments" => $com,
                    );
                    array_push($datos,$array);
                    
                }
                $this->render(['posts'=>$datos],'allPosts');
        }


        function insertPost(){
            $db=$this->getDB();
            $title = filter_input(INPUT_POST,"titpost");
            $cont = filter_input(INPUT_POST,"cont");
            $create_date = date("Y-m-d");
            $userT = $_SESSION['id'];
            if($title == "" or $cont==""){
                $dataview=['title'=>'allPosts'];
                $this->render($dataview,"allPosts");
                
            }
            else{
                $command5="
                insert into post (title,cont,user,create_date,modify_date) values (:title,:cont,:userT,:create_date,:modify_date)";
                try{
                    $resultT = $db->prepare($command5);
                    $resultT->bindParam(":title", $title);
                    $resultT->bindParam(":cont", $cont);
                    $resultT->bindParam(":userT", $userT);
                    $resultT->bindParam(":create_date", $create_date);
                    $resultT->bindParam(":modify_date", $create_date);
                    $resultT->execute();
                }catch(PDOException $e){
                    die($e->getMessage());
                }
                $this->selectPosts();
            }
        
        }

        function misposts(){
            $db=DB::singleton();
            $id = $_SESSION["id"];
                $misdatos = array();
                $misposts = $db->selectWhere("post",["id","title","cont","user","modify_date"],["user",$id]);
                foreach($misposts as $fila){
                    $userPost=$fila['user'];
                    $nombre = $db->selectWhere("user",["username"],["id",$userPost]);
                    $array = array(
                        "id" => $fila["id"],
                        "title" => $fila["title"],
                        "cont" => $fila["cont"],
                        "user" => $nombre[0]["username"],
                        "modify_date" => $fila["modify_date"]
                    );
                    array_push($misdatos,$array);
                }
                
                $this->render(['posts'=>$misdatos],'myProfile');
        }

        function editpost(){
            $db=DB::singleton();
            $post = $_SESSION['epost'];
            $title = filter_input(INPUT_POST, 'postname');
            $cont = filter_input(INPUT_POST, 'cont');
            $modify_date = date("Y-m-d");
                $commandE = "update post set cont = :cont,title = :title, modify_date= :modify_date where id = :id";
                $edit = $db->prepare($commandE);
                $edit->bindParam(":title", $title);
                $edit->bindParam(":cont", $cont);
                $edit->bindParam(":modify_date", $modify_date);
                $edit->bindParam(":id", $post);
                $edit->execute();             
                $this->misposts();
        }

        function elimpost(){
            $db=DB::singleton();
            $post = $_SESSION['epost'];
                $commandEL = "delete from post where id = :id";
                $edit = $db->prepare($commandEL);
                $edit->bindParam(":id", $post);
                $edit->execute(); 
                $commandELI = "delete from comments where post = :post";
                $editL = $db->prepare($commandELI);
                $editL->bindParam(":post", $post);
                $editL->execute(); 

                $this->misposts();
        }

        function insertcomment(){
            $db=DB::singleton();
            $post = filter_input(INPUT_POST, 'co');
            $com = filter_input(INPUT_POST, 'comm');
            $user = $_SESSION['id'];
            $p = $db->selectAll("comments",["id"]);
            $id=count($p)+1;
            $ins = "insert into comments values (:id,:com,:user,:post)";
            $comment = $db->prepare($ins);
                $comment->bindParam(":id", $id);
                $comment->bindParam(":com", $com);
                $comment->bindParam(":user", $user);
                $comment->bindParam(":post", $post);
                $comment->execute();
                $this->selectPosts();
            
        }

        
    
    }      

