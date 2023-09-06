<?php
    include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");

?>

<div class='header-page' >
    <div class='header-page-inner'>
        General Account Settings
    </div>
</div>
<div class="container-fluid">
    <div class="pageContent">
        <div class="panel panel-primary mypanel">
                <div class="panel-body">
                            <div class="pane-content" style='padding-bottom:10px'>
                                <form class="form-horizontal">
                                    <div class="col-md-offset-3 col-md-6">
                                        <br><br>
                                        <div class="form-group">
                                            <div class="errordiv"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Name</label>
                                            <div class="col-sm-8">
                                                <input type='text' class='form-input form-control' id='name' value='<?php echo getUserName($userid); ?>'readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Department</label>
                                            <div class="col-sm-8">
                                                <input type='text' class='form-input form-control' id='reqtype' value='<?php echo getDeptName($userid); ?>' readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Username</label>
                                            <div class="col-sm-8">
                                                <input type='text' class='form-input form-control' id='requisitioner' value='<?php echo getUserUsername($userid); ?>' readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Email</label>
                                            <div class="col-sm-8">
                                                <input type='text' class='form-input form-control' id='name' value='<?php echo getUserName($userid); ?>'readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-4">Password</label>
                                            <div class="col-sm-8">
                                                <a class='btn btn-xs btn-blue2 mybtn' data-toggle='modal' href='#changepasswordmodal'>
                                                        <i class='fa fa-edit fa-lg fa-space'></i>Edit Password
                                                 </a>
                                            </div>
                                        </div>
                                        <br><br><br>
                                    </div>
                                </form>
                            </div>
                </div>
        </div>
			

	</div>
</div>

<div class="modal fade" id="changepasswordmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Change Password
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="col-md-4">
                    <div class='modal-body-info'>
                        <span class='title'>Password</span><br>
                        Please provide current and new password.
                    </div>
                </div>
                <div class="col-md-8">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <div class="errordiv"></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Current Password</label>
                            <div class="col-sm-8">
                                <input type='password' class='form-input form-control currentpassword'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">New Password</label>
                            <div class="col-sm-8">
                                <input type='password' class='form-input form-control newpassword'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Re-type New Password</label>
                            <div class="col-sm-8">
                                <input type='password' class='form-input form-control retypenewpassword'>
                            </div>
                        </div>
                    </form>

                </div>
                                       
            </div>
            <div class="modal-footer">
                            <div class="text-center">
                                <button class='btn btn-blue2 mybtn' id='changepasswordmodal-confirmbtn'>Confirm</button>
                                <button class='btn btn-blue2 mybtn modal-cancelbtn' id='changepasswordmodal-cancelbtn'>Cancel</button>
                            </div>
            </div>
        </div>
    </div>  
</div>
<script type="text/javascript">
    $('.modal-dialog').draggable();
</script>