
<?php
$css='<link rel="stylesheet" href="build/jstree/themes/default/style.min.css">';
require_once '/maqueta/head.php';
require_once '/maqueta/header.php';
require_once '/maqueta/menu.php';
?>


<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Soporte | Administracion de Armas </h1>
        </div><!-- /.col -->
        
          
        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title">Administracion Marcas de Armas</h3><!-- /.col -->
          </div><!-- /.row -->
              <!-- BOTONES SUPERIORES -->

              <div class="card-body">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row">
                <a class="btn btn-app pull-left" onclick="MostrarModalInsertarRegistroGenerico();">
                        <i class="far fa-file-alt"></i> Nuevo
                </a>
                <a class="btn btn-app pull-left" onclick="MostrarModalInsertarRegistroGenerico();">
                  <i class="far fa-edit"></i> Editar
                </a>   
                <a class="btn btn-app pull-left" onclick="MostrarModalInsertarRegistroGenerico();">
                  <i class="fas fa-sync-alt"></i> Cambiar Estado
                </a>
                <!-- BOTON DE BUSCAR -->
              </div>
                <label>Buscar:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="example1"></label>
              
                <!-- TABLA DE ESPECIALIDADES maqueta -->

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 " style="overflow:auto;margin-top: 15px;">
                  <div id="jstreeLocalidades">
                    <ul>
                      <li>Root node 1
                        <ul>
                          <li>Child node 1</li>
                          <li><a href="#">Child node 2</a></li>
                        </ul>
                      </li>
                      <li>Root node 1
                        <ul>
                          <li>Child node 1</li>
                          <li><a href="#">Child node 2</a></li>
                        </ul>
                      </li>
                    </ul>
                  </div>
                </div>
<!-- TABLA DE ESPECIALIDADES pie de maqueta -->

              </div>
           </div>

</div>
</div>
</div>

          <?php
require_once '/maqueta/footer.php';
$js='<script src="build/jstree/jstree.min.js"></script>';
require_once '/maqueta/script.php';

?>
<script type="text/javascript">
  $('#jstreeLocalidades').jstree();
</script>