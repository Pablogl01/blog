<?php
    use App\Controllers\UserController;
    
    include 'base.tpl.php';
    ?>
    <main><h1 class="text-success">Usuario <?= $_SESSION['uname']; ?></h1>

    </main>
    <div class="container">
        <h3>¿Estas Seguro?</h3>

        <form action="<?=BASE?>blog/elimpost" method="post">
            <button type="submit" value="guardar" >Eliminar</button>
        </form>
        <form action="<?=BASE?>index/home" method="post">
            <button type="submit" value="guardar" >Cancelar</button>
        </form>
    </div>

    <?php
    include 'footer.tpl.php';
    ?>