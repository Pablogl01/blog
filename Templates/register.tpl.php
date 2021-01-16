<?php
    include 'base.tpl.php';
    ?>
    <main><h1 class="text-success">Usuario <?= $_SESSION['uname']; ?></h1>

    </main>
    <div class="container">
  <h2>Registro:</h2>
                                        
  <form action="<?=BASE?>user/crearUser" method="post">
    <p>Nombre: <?php echo $_SESSION["nombre"] ?></p>
    <p>Contraseña: <input type="password" name="password"></p>
    <p>Repetir contraseña: <input type="password" name="password2"></p>
    <p>Email: <input type="text" name="email"></p>
    <br>
    <button type="submit" value="Enviar" >Ejecutar</button>
  </form>
</div>

    <?php
    include 'footer.tpl.php';
    ?>