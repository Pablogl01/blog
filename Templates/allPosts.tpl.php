<?php
    use App\Controllers\UserController;
    
    include 'base.tpl.php';
    ?>
    <main><span><h1 class="text-success">Usuario <?= $_SESSION['uname']; ?></h1><span>
    <span><form action="<?=BASE?>blog/misposts" method="post">
            <button type="submit" value="crear" >Mi perfil</button>
    </form></span>
    </main>
    <div class="container">
        <h2>Todos los Post</h2>                              
        <?php

        if(isset($posts)){
            
            foreach($posts as $fila){
                $post = $fila["id"];
                $com = $fila["comments"];
                echo "<div id='contenido'>";
                    echo "<div id='infopost'><span id='userpost'>".$fila['title']."</span><span id='titpost'>".$fila["user"]."</span><span id='datepost'>".$fila["modify_date"]."</span></div><br>";
                    echo "<div id='divpost'><p id='contpost'>".$fila['cont']."</p></div>";
                echo "</div><br>";
                echo "<div id='comentatrios'>";
                foreach($com as $comments){
                        echo "<div>".$comments['user']."</div>";
                        echo "<div id='contcom'><span id='userpost'>".$comments['comment']."</span></div>";   
                }
                echo "<form action='".BASE."blog/insertcomment' method='post'>
                            <textarea cols='40' rows='5' style='resize: both;' name='comm'></textarea>
                            <button type='submit' name='co' value='$post'>Subir comentario</button>
                      </form>";
                echo "</div>";
            }
        }
        else{
            echo "<p>No hay listas para enseñarte</p>";
        }
        ?><br><br><br>

        <h3>Añadir Post</h3>

        <form action="<?=BASE?>blog/insertPost" method="post">
            <span>Titulo: </span><input type="text" name="titpost"><br>
            <p>Contenido: </p><textarea cols="40" rows="5" style="resize: both;" name="cont"></textarea>
            <button type="submit" value="crear" >Subir Post</button>
        </form>
    </div>

    <?php
    include 'footer.tpl.php';
    ?>