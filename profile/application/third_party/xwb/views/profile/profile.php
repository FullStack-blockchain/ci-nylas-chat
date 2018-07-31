        <!-- page content -->
        <div class="row">
         <div class="col-md-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Profile </h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <?php
                    if ($this->session->flashdata('errors') != null) :
                    ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                    <?php echo $this->session->flashdata('errors'); ?>
                    </div>
                    <?php
                    endif;
                    ?>
                    <?php
                    if ($this->session->flashdata('success') != null) :
                    ?>
                    <div class="alert alert-success alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                    <?php echo $this->session->flashdata('success'); ?>
                    </div>
                    <?php
                    endif;
                    ?>
                    <?php echo form_open_multipart('profile/updateProfile', array('class'=>'form-horizontal form-label-left input_mask')); ?>

                        <code class="help">Max( width:1000pixel, height:1000pixel, size: 2MB )</code>
                        <hr />
                        <p class="error">Please make the application/third_party/xwb/images/ writable. <br /><strong>Example</strong>:<code>sudo chmod 777 -R application/third_party/xwb/images/</code></p>
                        <hr />
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="logo">Upload Profile Picture :</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            
                                <div id='preview' class="center">
                                <img src="<?php echo base_url('profile/view_image/?width=150&height=150&path='.$current_user->picture_path); ?>" alt="Profile Picture" class="img-responsive" id="profile-picture">
                                </div>
                                <input type="file" name="profile_pic" id="profile-pic" accept="image/*" />
                            </div>
                        </div>


                      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" id="first_name" name="first_name" placeholder="First Name" value="<?php echo $current_user->first_name; ?>" />
                        <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                      </div>

                      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo $current_user->last_name; ?>" />
                        <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                      </div>

                      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" id="nick_name" name="nick_name" placeholder="Nick Name" value="<?php echo $current_user->nick_name; ?>" />
                        <span class="fa fa-meh-o form-control-feedback left" aria-hidden="true"></span>
                      </div>
                    
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                        <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Phone Number" value="<?php echo $current_user->phone; ?>" />
                        <span class="fa fa-phone form-control-feedback right" aria-hidden="true"></span>
                      </div>


                     
                      <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                          <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>


              </div>
        </div>
        <!-- /page content -->
