<?php
    include 'base.tpl.php';
    ?>

    </main>
    <div class="container">
  <h2>Login:</h2>
                                        
  <form action="<?=BASE?>user/iniciarS" method="post">
    <p>NNNNNN: <input type="text" name="nombre"></p>
    <p>Contraseña: <input type="password" name="password"></p>
    <br>
    <p><input type="checkbox" name="reg" >Registrarme</p>
    <button type="submit" value="Enviar" >Ejecutar</button>
  </form>
</div>

    <?php
    include 'footer.tpl.php';
    ?>
