<?php
    use App\Controllers\UserController;
    
    include 'base.tpl.php';
    ?>
    <main><h1 class="text-success">Usuario <?= $_SESSION['uname']; ?></h1>

    </main>
    <div class="container">
    <h2>Mis Post</h2>                              
        <?php

        if(isset($posts)){
            foreach($posts as $fila){
                $post=$fila['id'];
                echo "<div id='contenido'>";
                    echo "<div id='infopost'><span id='userpost'>".$fila['title']."</span><span id='titpost'>".$fila["user"]."</span><span id='datepost'>".$fila["modify_date"]."</span></div><br>";
                    echo "<div id='divpost'><p id='contpost'>".$fila['cont']."</p></div>";
                    echo"
                <form action='".BASE."index/editPost' method='post'>
                    <button type='submit' name='post' value='$post'>Editar Post</button>
                </form>
                <form action='".BASE."index/elimpost' method='post'>
                    <button type='submit' name='el' value='$post'>Eliminar</button>
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