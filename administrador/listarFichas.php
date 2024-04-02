<?php
session_start();
require '../conecion.php';
require '../Assets/funcion.php';



$idUser = $_SESSION['id'];
$tipoUsuario = $_SESSION['tipo'];
if(empty($_SESSION['tipo'])) {
    echo "<script>window.location.href='../index.php'; </script>";  
}
?>

<!DOCTYPE html>
<html>
<head>
  <?php include '../navAdmi.php';?>
	<title>Lista de Fichas</title>
  <link rel="icon" type="image/png" sizes="16x16" href="../situ.png">
 <link rel="stylesheet" type="text/css" href="../estilos.css">
  <link rel="stylesheet" type="text/css" href="../Assets/fonts/style.css">
  <link rel="stylesheet" type="text/css" href="../Assets/bootstrap-4.3.1-dist/css/bootstrap.min.css">
<link rel="icon" type="image/png" sizes="16x16" href="../situ.png">
    
  <link rel="stylesheet" type="text/css" href="../css/sweetalert.css">
  <script type="text/javascript" src="../js/sweetalert.js"></script>
</head>
<body class=" bg-white">
<?php require '../navegacion.php';?>

<?php

 $registros="SELECT COUNT(*) AS totalRegistros FROM ficha WHERE estado = 1";
          if( $conectarBD->query($registros)->num_rows > 0){

           foreach ($conectarBD->query($registros) as $fila){

          $totalRegistros=$fila['totalRegistros'];
          $canPagina= 5;

          if (empty($_GET['pagina'])) {
            $pagina= 1;
          }else{
            $pagina=$_GET['pagina'];
          }
          $desde=($pagina-1)*$canPagina;

          $totalPaginas=ceil($totalRegistros/$canPagina);

        }}
        
  if ($idUser==1) {
     $sql = "SELECT * from ficha LIMIT $desde,$canPagina"; 
    }else{
      $sql = "SELECT * from ficha WHERE estado = 1 LIMIT $desde,$canPagina";
    }
     
     
     if ($conectarBD->query($sql)->num_rows > 0){

 ?>

<br>
<div>
  <div  style="margin-top: 2px; margin-left: 120px;">
 <a href="registrarFicha.php" class="btn btn-success font-weight-bold" title="Crear Ficha"><span class="icon-plus1"></span> Fichas</a>
</div>
  <h1 align="center" class="font-weight-bold" style="margin-top: -3%;">  Listar Fichas </h1>
    <form class="form-inline float-right" action="buscarFichas.php" method="get" style="margin-top: -40px; margin-right: 110px;" >
          <input class="form-control mr-sm-2 border border-dark" type="text" name="busqueda" id="busqueda" placeholder="Buscar">
         
        </form>
   </div> 
 <div style="margin-left: 112px; margin-top: -20px;">


<a onclick="exportarPdf()" title="Exportar a PDF" class="btn"  style="font-size: 40px; cursor: pointer;" >
  <span class="icon-file-pdf text-danger"></span></a>

<a onclick="exportarExcel()"  title="Exportar a EXCEL" class="btn"  id="excel" style="font-size: 40px; margin-left: -20px; cursor: pointer; " >
  <span class="icon-file-excel text-success"></span></a>
</div>

<?php


if($conectarBD->connect_error){
    
    die("conexion fallida ".$conectarBD->connect_error);
    }
  ?>

<div align="right" style="margin-top: 3px; margin-right: 117px;">
  <h5>Total Fichas:
<span class="text-primary font-weight-bold">
  <?php 
echo $totalRegistros;
  ?>
  </span>
</h5>
</div>

   <div align="center" class="container">     
    <table  class='table table-hover table-light' >
      <tr align="center" class='bg-primary'>
        <th>N° Ficha</th>
        <th>Nombre Programa de Formación</th>
        <th>Area</th>
        <th>Cantidad Aprendices</th>
        <th>Inicio Formación</th>
        <th>Fin Formacion</th>
        <?php if ($idUser==1) {?>
        <th>Estado</th>
      <?php } ?>
        <th>Sena Empresa</th>
        <th colspan="2">Acciones</th></tr>
    <?php
  
         
         foreach ($conectarBD->query($sql) as $value){
             ?>
             <tr align='center' class='font-weight-bold'  >
              <td><?php echo $value['id_ficha'];?></td>
              <td>
  <?php
    $sqlF= "SELECT * FROM programas WHERE id_programaF=".$value['id_programaF'];
       if ($conectarBD->query($sqlF)->num_rows > 0){

         foreach ($conectarBD->query($sqlF) as $fila){
          $nombreP= $fila['nombreP'];
        }
}
    echo $nombreP;

      
              ;?>
                
              </td>
              <td><?php 
  $sqlA= "SELECT * FROM area WHERE id_area=".$value['id_area'];
       if ($conectarBD->query($sqlA)->num_rows > 0){

         foreach ($conectarBD->query($sqlA) as $fila){
          $nombreArea= $fila['nombreArea'];
        }
}
    echo $nombreArea;


              ?></td>
              <td><?php echo $value['cantidad_aprendices'];?></td>
              <td><?php echo $value['inicio_formacion'];?></td>
              <td><?php echo $value['fin_formacion'];?></td>
              <?php if ($idUser==1) {?>
              <td><?php 
              switch ($value['estado']) {
                case '0':
                  $value['estado'] = "Inactivo";
                  
                  break;
                case '1':
                $value['estado'] = "Activo";
              
                  break;
              }
              if ($value['estado']=='Activo') {
                ?>
                <span class="text-success"><?php echo $value['estado']?></span>
                <?php 

              }else{
                ?>
                <span class="text-danger"><?php echo $value['estado']?></span>
                <?php
              }
             ?></td>
            <?php } ?>
              <td><?php 
              switch ($value['estadoSE'] ) {
                case '0':
                  $value['estadoSE'] = "No";
                  echo "<h5 class='font-weight-bold'><span class='text-danger' >".$value['estadoSE']."</span></h5>";
                  break;
                case '1':
                $value['estadoSE'] = "Si";
                echo "<h5 class='font-weight-bold'><span class='text-success' >".$value['estadoSE']."</span></h5>";
                  break;
              }
              ?></td>
              <td  > <div class='btn-group' role='group'>
              <a class='btn btn-secondary' title="Editar Ficha" href = './editarFicha.php?token=<?php echo $value['token'];?> '><span class="icon-edit" style="font-size: 25px"></span></a>
              <?php if ($tipoUsuario=='administrador') {
                       if ($idUser==1) {
                        if ($value['estado']=='Inactivo') {
              ?> 
         <a class='btn btn-light' title="Activar Ficha" href='./eliminarFicha.php?token=<?php echo $value['token'];?>'><span class="icon-cancel text-danger" style="font-size: 25px"></span></a><?php  }else{
          ?>
          <a class='btn btn-light' title="Eliminar Ficha" href='./eliminarFicha.php?token=<?php echo $value['token'];?>'><span class="icon-checkmark1 text-success" style="font-size: 25px"></span></a>
          <?php 
         }  
              }else{
                ?>
                <a class='btn btn-danger' title="Eliminar Ficha" href='./eliminarFicha.php?token=<?php echo $value['token'];?>'><span class="icon-bin text-light" style="font-size: 25px"></span></a>
                <?php 
              }
            }
               ?>
         <a class='btn ' style="background:orange;" title="Estado Sena Empresa" href='./eliminarFicha.php?estadoSE=<?php echo $value['token'];?>'><span class="icon-hammer" style="color: purple"></span></a>
             </div>
             </td>
          </tr>
           <?php                    
         }
          
         ?>
         
         </table>
       </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
              <?php 
                if ($pagina!=1) {
                
              ?>
              <li class="page-item">
                <a class="page-link font-weight-bold" href="?pagina=<?php echo 1;?>" >INICIO</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="?pagina=<?php echo $pagina -1;?>" ><span class="icon-circle-left text-primary"></span></a>
              </li>
              <?php 
            }
              for ($i=1; $i <= $totalPaginas ; $i++) { 
                if ($i == $pagina) {
                  echo '<li class="page-item page-link bg-success text-light">'.$i.'</li>' ;
                }else{
               echo '<li class="page-item"><a class="page-link" href="?pagina='.$i.'">'.$i.'</a></li>';
              }}

              if ($pagina !=$totalPaginas) {
              
               ?>
              <li class="page-item">
                <a class="page-link" href="?pagina=<?php echo $pagina +1;?>"><span class="icon-circle-right text-primary"></span></a>
              </li>
              <li class="page-item">
                <a class="page-link font-weight-bold" href="?pagina=<?php echo $totalPaginas;?>">FIN</a>
              </li>
              <?php
               } 
              ?>
            </ul>
          </nav>
  <?php
   
}else{

  echo "<div class='container'><br>";
     die(include '../errorAlgoSM.php');
               }
  echo "</div>";

     $conectarBD->Close();

?>
<script type="text/javascript" src="../Assets/Jquery/jQuery_v3.4.1.js"></script>
<script type="text/javascript" src="../Assets/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>

<script type="text/javascript">
  function exportarExcel() {
    swal({
  title: "Advertencia",
  text: "¿Desea Exportar Todas las Fichas a Excel?",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#28A745",
  confirmButtonText: "Si",
  cancelButtonColor: "#DC3545",
  cancelButtonText: "No",
  closeOnConfirm: true,
  closeOnCancel: true
},
function(isConfirm){
  if (isConfirm) {
    window.location="./pdf_Ficha.php?E=1";

  } 
});

}

  function exportarPdf() {
    swal({
  title: "Advertencia",
  text: "¿Desea Exportar Todas las Fichas a PDF?",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#28A745",
  confirmButtonText: "Si",
  cancelButtonColor: "#DC3545",
  cancelButtonText: "No",
  closeOnConfirm: true,
  closeOnCancel: true
},
function(isConfirm){
  if (isConfirm) {
    window.location="./descargarPDF_F.php";
  } 
});

}
</script>

</body>
</html>

