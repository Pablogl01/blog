<?php
    include 'base.tpl.php';
    ?>
    <main><h1 class="text-success">Usuario <?= $_SESSION['uname']; ?></h1>

    </main>
    <div class="container">
        <h2>Login correcto:</h2>
                                        
        <form action="<?=BASE?>blog/selectPosts" method="post">
            <button type="submit" value="Enviar">Abrir listas</button>
        </form>
    </div>

    <?php
    include 'footer.tpl.php';
?>