 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="0prueba.php" class="brand-link navbar-navy">
      <img src="dist/img/bruttus.png" alt="AdminLTE Logo" class="brand-image img-responsive elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Bruttus ®</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Usuario</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-university"></i>
               <p><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
             Oficina de Judiciales 
                </font></font><i class="right fas fa-angle-left"></i>
               </p>
            </a>
             <!-- Empiezan las opciones del menu Oficina de Judiciales -->
            <ul class="nav nav-treeview" style="display: none;">     
              <li class="nav-item">
                <a href="altaIPPJudiciales.php" class="nav-link">           
                    <i class=" fa-angle-left fas fa-file-export"></i>
                     <p><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> Alta I.P.P
                    </font></font>
                  </p>
                </a>     
              </li>
             </ul>
             <ul class="nav nav-treeview" style="display: none;">     
              <li class="nav-item">
                <a onclick="location.href='estadisticasSecretaria.php'" class="nav-link">
                  <i class=" fa-angle fas fa-search"></i>
                    <p><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Buscar
                     </font>
                     </font>
                    </p>
                 </a>

              </li>
            </ul>
                                                               <ul class="nav nav-treeview" style="display: none;">     
              <li class="nav-item">
                <a onclick="location.href='estadisticasSoporte.php'" class="nav-link">
              
                  
                    <i class=" fa-angle-left fas fa-pencil-alt"></i>
                     <p><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Editar
                    </font></font>
                  </p>
                </a>
           
              </li>

            </ul>
            <ul class="nav nav-treeview" style="display: none;">     
              <li class="nav-item">
                <a onclick="location.href='estadisticasSecretaria.php'" class="nav-link">
                  <i class=" fa-angle-left fas fa-exclamation-circle"></i>
                    <p><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Eliminar
                     </font>
                     </font>
                    </p>
                 </a>

              </li>
            </ul>
            <ul class="nav-item nav-treeview" style="display: none;">     
              <li class="nav-item">
                <a onclick="location.href='estadisticasSecretaria.php'" class="nav-link">
                  <i class=" fa-angle-left fas fa-sync-alt"></i>
                    <p><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Pase
                     </font>
                     </font>
                    </p>
                 </a>

              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-file-signature"></i>
              <p>
                Mis Causas
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="BACKUP.PHP" class="nav-link">
                  <i class="nav-icon far fa-circle text-info"></i>
                  <p>A recepcionar</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="BACKUP.PHP" class="nav-link">
                  <i class="nav-icon far fa-circle text-warning"></i>
                  <p>En proceso</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="BACKUP.PHP" class="nav-link">
                  <i class="nav-icon far fa-circle text-success"></i>
                  <p>Finalizadas</p>
                </a>
              </li>
              </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-envelope-open-text"></i>
              <p>
                Notas
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="BACKUP.PHP" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Recibo para Correo</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="BACKUP.PHP" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Recibo para Depósito </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="BACKUP.PHP" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Elevación Secuestro</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="BACKUP.PHP" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Elevación de IPP</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="BACKUP.PHP" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Citacion</p>
                </a>
              </li>
              
              </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Estadisticas
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="BACKUP.PHP" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>BackUp</p>
                </a>
              </li>
              </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Administración
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="BACKUP.PHP" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>BackUp</p>
                </a>
              </li>
              </ul>
          </li>
     <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-exclamation-triangle"></i>
              <p>
                Desarrollo
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="BACKUP.PHP" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>BackUp</p>
                </a>
              </li>
              </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="MICUENTA.PHP" class="nav-link">
              <i class="nav-icon fas fa-address-card"></i>
              <p>
                Mi Cuenta
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="2login.html" class="nav-link">
              <i class="nav-icon fas fa-user-slash"></i>
              <p>
                Cerrar Sesión
              </p>
            </a>
          </li>            
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>