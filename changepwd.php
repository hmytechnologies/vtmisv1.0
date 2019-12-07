
<script src="js/jquery-1.4.2.min.js"></script>

<link href="css/validation.css" rel="stylesheet"> 
<div class="row bg-danger alert-danger text-center " >
                    <?php
	  if(!empty($_REQUEST['err']))  {
	   echo "<div class=\"msg\">";
	  echo $_REQUEST['err'];
	  echo "</div>";	
	   }
	  ?>
                </div>
  <div class="row well">

      <script type="text/javascript">

          function checkPassword(str)
          {
              var re = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
              return re.test(str);
          }

          function checkForm(form)
          {
              var password=document.getElementById("password");
              var confirm_password=document.getElementById("confirm_password");
              if(password.value != "" && password.value == confirm_password.value) {
                  if(!checkPassword(password.value)) {
                      alert("Password must contain at least 8 characters, including UPPER Case,lowercase and numbers");
                      password.focus();
                      return false;
                  }
              } else {
                  alert("Error: Please check that you've entered and confirmed your password!");
                  password.focus();
                  return false;
              }
              return true;
          }

      </script>

                    <div class="col-md-4">
                        <h4></h4>
                    </div>
      <form name="register" method="post" id="register" action="action_changepwd.php" onsubmit="return checkForm(this);">
                    <div class="col-md-12">
                            <div class="row">
                                <div class="left-addon form-group has-feedback">
                                    <input type="password" id="pwd_old" name="pwd_old" placeholder="Old Password" class="form-control" required=""  />
                                   <i class="form-control-feedback glyphicon glyphicon-user"></i>
                                </div>
                            </div>
							
                            <div class="row">
                                <div class=" left-addon form-group has-feedback">
                                    <input type='password' id="password" name="password" placeholder="New Password" class="form-control" required="" />
                                    <i class="form-control-feedback glyphicon glyphicon-lock"></i>
                                </div>
                            </div>
                         <div class="row">
                                <div class=" left-addon form-group has-feedback">
                                    <input type='password' id="confirm_password" name="confirm_password" placeholder="Confirm New Password" class="form-control" required="" />                       <i class="form-control-feedback glyphicon glyphicon-lock"></i>
                                </div>
                            </div>


							<div class="row">
							<br>
							</div>
                            <div class="row">
                              
                                <input type="hidden" name="userID" value="<?php echo $_SESSION['user_session'];?>">
                                <input type="submit" name="doUpdate" class="form-control btn btn-default btn-primary" value="Change Password">
                                 <!--<button OnClick="LogIn" Text="Log in" class="form-control btn btn-default btn-primary">Sign In</button>-->
                            </div>
                          
                        
                    </div>
          </form>
       <div class="col-md-3">
                        <h4></h4>
                    </div>
  </div>