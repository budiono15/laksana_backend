 <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
<div class="alert alert-info" role="alert">
  <h4 class="alert-heading">DASHBOARD</h4>
  <hr>
  <p class=" text-center mb-5" >Anda Login Sebagai <strong><?= $user['name']; ?></strong></p> 
	<button type="button" class="btn btn-sm btn-primary " data-toggle="modal" data-target="#exampleModal" style="background-color: #70BCFF">
		<i class="fas fa-cogs"></i>
  Control Panel
</button>
</div>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    <p class="modal-title text-center" style="margin-bottom: 20px" id="exampleModalLabel"><i class="far fa-list-alt"></i>
  Content Management System</p>
       <div class="row">
<div class="col-md-3 text-primary text-center">
	<a href="<?php echo base_url('content_satu') ?>"><p class="nav-link small text-info">CONTENT HERO</p></a>
	<i class="fas fa-3x fa-clipboard-list" style="color: #70BCFF"></i>
	</div>
	     
 <div class="col-md-3 text-primary text-center">
	<a href="<?php echo base_url('panel') ?>"><p class="nav-link small text-info" >PANEL</p></a>
	<i class="fas fa-3x fa-solar-panel" style="color: #70BCFF"></i>
	</div>

  <div class="col-md-3 text-primary text-center">
  <a href="<?php echo base_url('content_satu/tentang') ?>"><p class="nav-link small text-info">CONTENT BOTTOM</p></a>
  <i class="fas fa-3x fa-user-friends" style="color: #70BCFF"></i>
  </div>
       </div> <hr>

       		<p class="modal-title text-center" id="exampleModalLabel"><i class="fas fa-users-cog"></i>
  USER CONFIG</p>
              <div class="row">
       <div class="col-md-3 text-primary text-center">
       	<a href="<?php echo base_url('user/changepassword
') ?>"><p class="nav-link small text-primary">GANTI PASSWORD</p></a>
       	<i class="fas fa-3x fa-key" style="color: #70BCFF"></i>
       	</div>

       <div class="col-md-3 text-primary text-center">
       		<a href="<?php echo base_url('user') ?>"><p class="nav-link small text-info">PROFILE SAYA</p></a>
       		<i class="fas fa-3x fa-user" style="color: #70BCFF"></i>
       		</div>

       <div class="col-md-3 text-primary text-center">
       	<a href="<?php echo base_url('user/edit') ?>"><p class="nav-link small text-info">EDIT PROFILE</p></a>
       	<i class="fas fa-3x fa-user-edit" style="color: #70BCFF"></i>
       	</div>
       	     
        <div class="col-md-3 text-primary text-center">
       	<a href="<?php echo base_url('admin/role') ?>"><p class="nav-link small text-info">ROLE ADMIN/USER</p></a>
       	<i class="fas fa-3x fa-user-friends" style="color: #70BCFF"></i>
       	</div>
              </div><hr>


  <p class="modal-title text-center" id="exampleModalLabel" ><i class="fas fa-align-left"></i>
  MENU MANAGEMENT</p>
              <div class="row">
       <div class="col-md-3 text-primary text-center">
        <a href="<?php echo base_url('menu') ?>"><p class="nav-link small text-info">MENU MANAGEMENT</p></a>
        <i class="fas fa-3x fa-tasks" style="color: #70BCFF"></i>
        </div>

       <div class="col-md-3 text-primary text-center">
          <a href="<?php echo base_url('menu/submenu') ?>"><p class="nav-link small text-info">SUB-MENU</p></a>
          <i class="fas fa-3x fa-align-left" style="color: #70BCFF"></i>
          </div>
              </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">KELUAR</button>
      </div>
    </div>
  </div>
</div>


        </div>
      </div>