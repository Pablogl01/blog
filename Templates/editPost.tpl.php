<?php
    use App\Controllers\UserController;
    
    include 'base.tpl.php';
    ?>
    <main><h1 class="text-success">Usuario <?= $_SESSION['uname']; ?></h1>

    </main>
    <div class="container">
        <h3>Editar Post</h3>

        <form action="<?=BASE?>blog/editpost" method="post">
            <p>Editar Titulo: <input type="text" name="postname"></p>
            <p>Contenido: </p><textarea cols="80" rows="10" style="resize: both;" name="cont"></textarea>
            <button type="submit" value="guardar" >Guardar cambios</button>
        </form>
    </div>

    <?php
    include 'footer.tpl.php';
    ?>