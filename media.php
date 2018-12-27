<?php
  $page_title = 'Lista de imagenes';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php $media_files = find_all('media');?>
<?php
  if(isset($_POST['submit'])) {
    $image = addslashes(file_get_contents($_FILES['image']['tmp_name'])); //SQL Injection defence!
    if (!empty($image)) {
       $image_name = addslashes($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/uploads/products/' .  $_FILES["image"]['name'])) {
           $ext = pathinfo($image_name, PATHINFO_EXTENSION);
           $type_file = 'image/'.$ext;
           global $db;
          $query = "INSERT INTO media (";
          $query .="file_name,file_type";
          $query .=") VALUES (";
          $query .=" '{$image_name}', '{$type_file}'";
          $query .=")";
          if($db->query($query)){
            //sucess
            $session->msg('s'," Archivo Agregado con exito");
            redirect('media.php', false);
          } else {
            //failed
            $session->msg('d',' No se pudo subir archivo');
            redirect('media.php', false);
          }
        }   
    }
    page_require_level(2);  
  }
?>
<?php include_once('layouts/header.php'); ?>
     <div class="row">
        <div class="col-md-6">
          <?php echo display_msg($msg); ?>
        </div>

      <form action="media.php" method="POST" enctype="multipart/form-data">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading clearfix">
            <span class="glyphicon glyphicon-camera"></span>
            <span>Lista de imagenes</span>
            <div class="pull-right">
              <form class="form-inline" action="media.php" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-btn">
                   <label>File: </label><input type="file" name="image" class="btn btn-primary btn-file"/>
                 </span>
                 <button type="submit" name="submit" class="btn btn-default">Subir</button>
               </div>
              </div>
             </form>
            </div>
          </div>
          <div class="panel-body">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-center" style="width: 50px;">#</th>
                  <th class="text-center">Imagen</th>
                  <th class="text-center">Descripción</th>
                  <th class="text-center" style="width: 20%;">Tipo</th>
                  <th class="text-center" style="width: 50px;">Acciones</th>
                </tr>
              </thead>
                <tbody>
                <?php foreach ($media_files as $media_file): ?>
                <tr class="list-inline">
                 <td class="text-center"><?php echo count_id();?></td>
                  <td class="text-center">
                      <img src="uploads/products/<?php echo $media_file['file_name'];?>" class="img-thumbnail" />
                  </td>
                <td class="text-center">
                  <?php echo $media_file['file_name'];?>
                </td>
                <td class="text-center">
                  <?php echo $media_file['file_type'];?>
                </td>
                <td class="text-center">
                  <a href="delete_media.php?id=<?php echo (int) $media_file['id'];?>" class="btn btn-danger btn-xs"  title="Eliminar">
                    <span class="glyphicon glyphicon-trash"></span>
                  </a>
                </td>
               </tr>
              <?php endforeach;?>
            </tbody>
          </div>
        </div>
      </div>
       </form>  
</div>


<?php include_once('layouts/footer.php'); ?>
