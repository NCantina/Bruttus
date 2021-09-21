<?php
require_once 'maqueta/head.php';
require_once 'maqueta/header.php';
require_once 'maqueta/menu_judiciales.php';
?>
<body>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
<div class="preloader flex-column justify-content-center align-items-center" style="height: 0px;">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60" style="display: none;">
  </div>
  <!-- TITULO DE LA TARJETA -->
     <div class="card card-outline card-info">
        <div class="card card-outline card-info">
            <div class="card-header">  
                <h3 class="card-title"><strong> Alta I.P.P</strong>
            </div>
      <div class="card-body">
        <div class="row">
                    <div class="col-lg-1">
            <label id="L_nroOrden">N° Libro</label>
              <input id="nroOrden" type="text" class="form-control impuntDespintar" placeholder="Ingrese Nro. de Libro">
          </div>
          <div class="col-lg-1">
            <label id="L_nroOrden">N° Foja</label>
              <input id="nroOrden" type="text" class="form-control impuntDespintar" placeholder="Ingrese Nro. de Foja">
          </div>
                    <div class="col-lg-1">
            <label id="L_nroOrden">N° de Orden</label>
              <input id="nroOrden" type="text" class="form-control impuntDespintar" placeholder="Ingrese Nro. de Orden">
          </div>
          <div class="col-lg-2">
            <label id="L_apellido">I.P.P N°</label>
              <input id="apellido" type="text" class="form-control impuntDespintar" placeholder="Ingrese Apellido/s">
          </div>
          <div class="form-group  col-lg-2">
            <label id="L_fechaNacimiento">Fecha de Entrada</label> 
              <div id="fechaNacimiento" class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                  <input type="text" id="fechaNacimiento" class="form-control impuntDespintar" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" placeholder="dd/mm/yyyy" data-mask="" im-insert="false">
              </div>
          </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-5">
                <label id="LNL_Delitos">Caratula</label>
                <select class="form-control custom-select impuntDespintar" id="localidad" onchange="CargarLocalidades()">
                <option value="-1" selected="" disabled="">Selecione Localidad</option>
                <option value="1">Canceled</option>option>
                <option value="1">Canceled</option>
                <option value="2">Success</option>
              </select>             
             </div>
        </div>
        <div class="row">
                    <div class="form-group  col-lg-2">
            <label id="L_fechaNacimiento">Fecha del Hecho:</label> 
              <div id="fechaNacimiento" class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                  <input type="text" id="fechaNacimiento" class="form-control impuntDespintar" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" placeholder="dd/mm/yyyy" data-mask="" im-insert="false">
              </div>
          </div>
          <div class="col-lg-3">
            <label id="L_domicilio">Departamento Judicial</label>
            <select class="form-control custom-select impuntDespintar" id="localidad" onchange="CargarLocalidades()">
                <option value="-1" selected="" disabled="">Selecione Localidad</option>
                <option value="1">Canceled</option>option>
                <option value="1">Canceled</option>
                <option value="2">Success</option>
              </select> 
          </div>
          <div class="col-lg-3">
            <label id="L_numero">U.F.I. N°</label>
              <input id="numero" type="text" class="form-control impuntDespintar" placeholder="Ingrese N°">
          </div>
                    <div class="col-lg-2">
            <label id="L_piso">Defensoria N°</label>
              <input id="piso" type="text" class="form-control impuntDespintar" placeholder="Entre Calles">
          </div>
          <div class="col-lg-2">
            <label id="L_departamento">Juez de Garantias N°</label>
              <input id="departamento" type="text" class="form-control impuntDespintar" placeholder="Entre Calles">
          </div>
<div class="col-md-12">
      <div class="row">
        <div class="form-group col-lg-2">
                <label for="inputName" id="L_VicImp">Victima/Imputado</label>
                 <select class="form-control custom-select" id="VicImp" onchange="$('#L_VicImp').removeClass('text-red');">
                  <option value="-1" selected="" disabled="">Seleccionar</option>
                  <option value="0">Denunciante</option>
                  <option value="1">Victima</option>
                  <option value="2">Imputado</option>
                </select>
        </div>
              <div class="form-group  col-lg-4">
                <label for="inputName" id="L_ApellidoyNombre">Apellido</label>
                <input type="text" id="ApellidoyNombre" class="form-control" onkeypress="$('#L_ApellidoyNombre').removeClass('text-red');">
              </div>
                            <div class="form-group  col-lg-4">
                <label for="inputName" id="L_ApellidoyNombre">Nombres</label>
                <input type="text" id="ApellidoyNombre" class="form-control" onkeypress="$('#L_ApellidoyNombre').removeClass('text-red');">
              </div>
              <div>
                <label>
                              <button type="button" class="btn btn-block btn-default btn-sm fa fa-plus" id="AgregarTablaPersonasProcesales" onclick="AgregarTablaPersonasProcesales();"></button>
                            </label>
                 </div>
</div>
          </div>
                
                 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="overflow:auto;height: 125px">
                <table class="table table-condensed">
                  <tbody id="NL_TablaSujetosProcesales">
                    <tr>
                      <th>Tipo de Participacion</th>
                      <th>Apellido y Nombres</th>
                      <th style="width: 10%">Quitar</th>
                    </tr>
                   <tr>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
            <div class="row">
          <div class="col-lg-4">
            <label id="L_jerarquia">Sustraido</label>
              <input id="jerarquia" type="text" class="form-control impuntDespintar" placeholder="Ingrese Nombre/s">
          </div>
          <div class="col-lg-4">
            <label id="L_legajo">Secuestrado</label>
              <input id="legajo" type="text" class="form-control impuntDespintar" placeholder="Ingrese Apellido/s">
          </div>
          <div class="form-group col-lg-4">
              <label for="inputStatus" id="L_division">Lugar del Hecho</label>
              <input id="jerarquia" type="text" class="form-control impuntDespintar" placeholder="Ingrese Nombre/s">
          </div>
        </div>
<div class="col-md-12">

      <div class="row">
        <div class="form-group col-lg-2">
                <label for="inputName" id="L_VicImp">Secretario de Actuaciones</label>
                <input id="jerarquia" type="text" class="form-control impuntDespintar" placeholder="Ingrese Nombre/s">
        </div>
              <div class="form-group  col-lg-2">
                <label for="inputName" id="L_ApellidoyNombre">Jerarquia</label>
                <input type="text" id="ApellidoyNombre" class="form-control" onkeypress="$('#L_ApellidoyNombre').removeClass('text-red');">
              </div>
              <div class="form-group  col-lg-2">
                <label for="inputName" id="L_ApellidoyNombre">Legajo</label>
                <input type="text" id="ApellidoyNombre" class="form-control" onkeypress="$('#L_ApellidoyNombre').removeClass('text-red');">
              </div>
                            <div class="form-group  col-lg-3">
                <label for="inputName" id="L_ApellidoyNombre">Apellido</label>
                <input type="text" id="ApellidoyNombre" class="form-control" onkeypress="$('#L_ApellidoyNombre').removeClass('text-red');">
              </div>
                                          <div class="form-group  col-lg-3">
                <label for="inputName" id="L_ApellidoyNombre">Nombre</label>
                <input type="text" id="ApellidoyNombre" class="form-control" onkeypress="$('#L_ApellidoyNombre').removeClass('text-red');">
              </div>
            </div>
<div class="col-md-12">

      <div class="row">
        <div class="form-group col-lg-2">
                <label for="inputName" id="L_VicImp">Oficial Jefe</label>
                <input id="jerarquia" type="text" class="form-control impuntDespintar" placeholder="Ingrese Nombre/s">
        </div>
              <div class="form-group  col-lg-2">
                <label for="inputName" id="L_ApellidoyNombre">Jerarquia</label>
                <input type="text" id="ApellidoyNombre" class="form-control" onkeypress="$('#L_ApellidoyNombre').removeClass('text-red');">
              </div>
              <div class="form-group  col-lg-2">
                <label for="inputName" id="L_ApellidoyNombre">Legajo</label>
                <input type="text" id="ApellidoyNombre" class="form-control" onkeypress="$('#L_ApellidoyNombre').removeClass('text-red');">
              </div>
                            <div class="form-group  col-lg-3">
                <label for="inputName" id="L_ApellidoyNombre">Apellido</label>
                <input type="text" id="ApellidoyNombre" class="form-control" onkeypress="$('#L_ApellidoyNombre').removeClass('text-red');">
              </div>
                                          <div class="form-group  col-lg-3">
                <label for="inputName" id="L_ApellidoyNombre">Nombre</label>
                <input type="text" id="ApellidoyNombre" class="form-control" onkeypress="$('#L_ApellidoyNombre').removeClass('text-red');">
              </div>
            </div>
                         
                             <div class="row">
          <div class="form-group  col-lg-2">
            <label id="L_fechaNacimiento">Fecha de Salida</label> 
              <div id="fechaNacimiento" class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                  <input type="text" id="fechaNacimiento" class="form-control impuntDespintar" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" placeholder="dd/mm/yyyy" data-mask="" im-insert="false">
              </div>
          </div>
          <div class="form-group col-lg-5">
                        <label id="L_acercaDe">Notas:</label>
                        <input id="acercaDe"  type="text" class="form-control impuntDespintar" placeholder="Ingrese datos de Interés">
          </div>
                    <div class="form-group col-lg-5">
                        <label id="L_acercaDe">Observaciones:</label>
                        <input id="acercaDe"  type="text" class="form-control impuntDespintar" placeholder="Ingrese datos de Interés">
          </div>
                    </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                   <div class="row">
                                                      <div class="col-sm-offset-6 col-md-offset-8 col-lg-offset-9  col-xs-12 col-sm-4 col-md-4 col-lg-3">    
            <button type="button" id="Modificar" onclick="BuscarRegistroNuevo();" class="btn btn-block btn-outline-info">Buscar
            </button>
           </div>
                                                     <div class="col-sm-offset-6 col-md-offset-8 col-lg-offset-9  col-xs-12 col-sm-4 col-md-4 col-lg-3">    
            <button type="button" id="Modificar" onclick="ModificarRegistroNuevo();" class="btn btn-block btn-outline-info">Modificar
            </button>
           </div>

                                  <div class="col-sm-offset-6 col-md-offset-8 col-lg-offset-9  col-xs-12 col-sm-4 col-md-4 col-lg-3">    
            <button type="button" id="Modificar" onclick="BorrarRegistroNuevo();" class="btn btn-block btn-outline-danger">Borrar
            </button>
           </div>

              <div class="col-sm-offset-6 col-md-offset-8 col-lg-offset-9  col-xs-12 col-sm-4 col-md-4 col-lg-3">    
            <button type="button" id="Guardar" onclick="GuardarRegistroNuevo();" class="btn btn-block btn-outline-info">Guardar
            </button>
           </div>

        </div>
</div>




</div>
<!-- /.card-body -->
</div>







            </div>
          </div>
          <!-- /.col-md-6 -->

<?php


require_once 'maqueta/footer.php';
require_once 'maqueta/script.php';


?>