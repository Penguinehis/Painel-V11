<?php

if (basename($_SERVER["REQUEST_URI"]) === basename(__FILE__))
{
	exit('<h1>ERROR 404</h1>Entre em contato conosco e envie detalhes.');
}

?>
<!-- jQuery 2.2.3 -->
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
  $(document).ready(function(){
    $("#myInput").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#myTable tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });
</script>
<!-- Main content -->
<div class="container-fluid">
  <!-- ============================================================== -->
  <!-- Bread crumb and right sidebar toggle -->
  <!-- ============================================================== -->
  <div class="row page-titles">
    <div class="col-md-5 col-8 align-self-center">
      <h3 class="text-themecolor"><a href="home.php">SSH<b>PLUS</a></b></h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="home.php">Início</a></li>
        <li class="breadcrumb-item active">Lista servidores</li>
      </ol>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="card card-outline-info">
        <div class="card-header">
          <h4 class="m-b-0 text-white"><i class="fa fa-server"></i> Servidores</h4>
        </div>
        <div class="col-12"><br>
        <div class="form-responsive">
          <input type="text" id="myInput" placeholder="Pesquisar..." class="form-control">
        </div>
      </div>     
      <div class="table-responsive m-t-40">
        <table id="myTable" class="table table-bordered table-striped">
          <thead>
            <tr>
             <th>Nome</th>
             <th>Tipo</th>
             <th>Região</th>
             <th>Endereço IP</th>
             <th>Login</th>
             <th>OpenVPN</th>
             <th>Contas Criadas</th>
             <th>Acessos Liberados</th>
             <th>Informações</th> 
           </thead>
           <tbody id="myTable">
             <?php





             $SQLServidor = "select * from servidor";
             $SQLServidor = $conn->prepare($SQLServidor);
             $SQLServidor->execute();

					// output data of each row
             if (($SQLServidor->rowCount()) > 0) {

               while($row = $SQLServidor->fetch())


               {
                 $acessos = 0 ;

                 if($row['tipo']=='premium'){
                  $SQLAcessoSSH = "SELECT sum(acesso) AS quantidade  FROM usuario_ssh where id_servidor='".$row['id_servidor']."' ";
                  $SQLAcessoSSH = $conn->prepare($SQLAcessoSSH);
                  $SQLAcessoSSH->execute();
                  $SQLAcessoSSH = $SQLAcessoSSH->fetch();
                  $acessos += $SQLAcessoSSH['quantidade'];

                  $SQLUsuarioSSH = "select * from usuario_ssh WHERE id_servidor = '".$row['id_servidor']."' ";
                  $SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
                  $SQLUsuarioSSH->execute();

                }else{
                 $SQLUsuarioSSH = "select * from usuario_ssh_free WHERE servidor = '".$row['id_servidor']."' ";
                 $SQLUsuarioSSH = $conn->prepare($SQLUsuarioSSH);
                 $SQLUsuarioSSH->execute();
               }

               $qtd_ssh = $SQLUsuarioSSH->rowCount();

               switch($row['tipo']){
                case 'premium':$tipo='Premium';break;
                case 'free':$tipo='Free';break;
                default:$tipo='erro';break;
              }

              $SQLopen = "select * from ovpn WHERE servidor_id = '".$row['id_servidor']."' ";
              $SQLopen = $conn->prepare($SQLopen);
              $SQLopen->execute();
              if($SQLopen->rowCount()>0){
               $openvpn=$SQLopen->fetch();
               $texto="<a href='../admin/pages/servidor/baixar_ovpn.php?id=".$openvpn['id']."' class=\"label label-info\">Baixar</a>";
             }else{
               $texto="<span class=\"label label-danger\">Indisponivel</span>";
             }


             ?>

             <tr>

               <td><?php echo $row['nome'];?></td>
               <td><?php echo $tipo;?></td>
               <td><?php echo ucfirst($row['regiao']);?></td>
               <td><?php echo $row['ip_servidor'];?></td>
               <td><?php echo $row['login_server'];?></td>
               <td><?php echo $texto;?></td>
               <td><?php echo $qtd_ssh;?></td>
               <td><?php echo $acessos;?></td>


               <td>


                <a href="home.php?page=servidor/servidor&id_servidor=<?php echo $row['id_servidor'];?>" class="btn btn-primary">	Visualizar</a>
              </td>
            </tr>




          <?php }
        }

        ?>
      </tbody>
    </table>
  </div>
</div>
</div>
</div>