<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-blue pb-8 pt-5 pt-md-8">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                <?php echo $this->session->flashdata('msg'); ?>
                            </div>
                        <?php

                        }
                        $smtp_host = get_title('general_settings', ['general_settings_id' => 72], 'value');
                        $smtp_port = get_title('general_settings', ['general_settings_id' => 73], 'value');
                        $smtp_user = get_title('general_settings', ['general_settings_id' => 74], 'value');
                        $smtp_pass = get_title('general_settings', ['general_settings_id' => 75], 'value');
                        ?>
                        <div class="container-fluid">
                            <div class="header-body ">
                                <h3 class="text-center mb-3">SMTP SETTING</h3>

                                <form class="form" role="form" method="post" action="<?= base_url() ?>config/smtp_setting" enctype="multipart/form-data" autocomplete="off">
                                    <div class="box-body">
                                        <fieldset class="setting-fieldset">
                                            <div class="row">


                                                <div class="col-sm-6 mainsmtpDIV" style="display: block;">
                                                    <div class="form-group ">
                                                        <div class="col-sm-12">
                                                            <label for="smtp_username">SMTP Username &nbsp;
                                                            </label>
                                                            <input type="text" class="form-control" id="smtp_username" name="smtp_username" autocomplete="off" value="<?= $smtp_user ?>">
                                                            <span class="control-label"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 mainsmtpDIV" style="display: block;">
                                                    <div class="form-group ">
                                                        <div class="col-sm-12">
                                                            <label for="smtp_password">SMTP Password &nbsp;
                                                            </label>
                                                            <input type="password" class="form-control" id="smtp_password" name="smtp_password" autocomplete="off" value="<?= $smtp_pass ?>">
                                                            <span class="control-label"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-6 mainsmtpDIV" style="display: block;">
                                                    <div class="form-group ">
                                                        <div class="col-sm-12">
                                                            <label for="smtp_server">SMTP Server &nbsp;
                                                            </label>
                                                            <input type="text" class="form-control" id="smtp_server" name="smtp_server" value="<?= $smtp_host ?>" spellcheck="false" data-ms-editor="true">
                                                            <span class="control-label"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 mainsmtpDIV" style="display: block;">
                                                    <div class="form-group ">
                                                        <div class="col-sm-12">
                                                            <label for="smtp_port">SMTP Port &nbsp;
                                                            </label>
                                                            <input type="text" class="form-control" id="smtp_port" name="smtp_port" value="<?= $smtp_port ?>" spellcheck="false" data-ms-editor="true">
                                                            <span class="control-label"></span>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </fieldset>

                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="submit" class="btn btn-success" id="smtp_form" value="Update Email Setting">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>