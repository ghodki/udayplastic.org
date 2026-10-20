<div class="content-wrapper">
    <?php
    // SMS GETWAY
    $sms_api_id = get_title('general_settings', ['general_settings_id' => 88], 'value');
    $sms_temp_id = get_title('general_settings', ['general_settings_id' => 89], 'value');


    // SOCIAL MEDIA
    $google_url = get_title('general_settings', ['general_settings_id' => 90], 'value');
    $fb_url = get_title('general_settings', ['general_settings_id' => 91], 'value');
    $insta_url = get_title('general_settings', ['general_settings_id' => 92], 'value');
    $twiiter_url = get_title('general_settings', ['general_settings_id' => 93], 'value');
    $youtube_url = get_title('general_settings', ['general_settings_id' => 94], 'value');
    $linkdn_url = get_title('general_settings', ['general_settings_id' => 94], 'value');

    $system_email = get_title('general_settings', ['general_settings_id' => 2], 'value');
    $system_title = get_title('general_settings', ['general_settings_id' => 3], 'value');
    $address = get_title('general_settings', ['general_settings_id' => 4], 'value');
    $phone = get_title('general_settings', ['general_settings_id' => 5], 'value');
    $footer_title = get_title('general_settings', ['general_settings_id' => 42], 'value');


    $meta_description = get_title('general_settings', ['general_settings_id' => 24], 'value');
    $meta_keywords = get_title('general_settings', ['general_settings_id' => 25], 'value');
    $meta_author = get_title('general_settings', ['general_settings_id' => 26], 'value');





    ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid" style="margin-top:60px;margin-bottom:200px;">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item active">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Home</a>
                            </li>
                        </ul>



                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">

                            <!-- FRONTEND -->

                            <div id="home" class="tab-pane fade show active" role="tabpanel" aria-labelledby="home-tab">
                                <div class="col-xs-12">
                                    <div class="box">
                                        <div class="box-header">
                                            <h3 class="box-title"><i class="fa fa-gears"></i> General Setting</h3>

                                        </div><!-- /.box-header -->
                                        <!-- form start -->
                                        <style type="text/css">
                                            .setting-fieldset {
                                                border: 1px solid #DBDEE0 !important;
                                                padding: 15px !important;
                                                margin: 0 0 25px 0 !important;
                                                box-shadow: 0px 0px 0px 0px #000;
                                            }

                                            .setting-legend {
                                                font-size: 1.1em !important;
                                                font-weight: bold !important;
                                                text-align: left !important;
                                                width: auto;
                                                color: #428BCA;
                                                padding: 5px 15px;
                                                border: 1px solid #DBDEE0 !important;
                                                margin: 0px;
                                            }
                                        </style>
                                        <form class="form-horizontal" id="generalForm" role="form" method="post" action="<?= base_url() ?>general_setting/update" enctype="multipart/form-data">
                                            <div class="box-body">
                                                <fieldset class="setting-fieldset">
                                                    <legend class="setting-legend">Site Configuration</legend>
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <div class="col-sm-12">
                                                                    <label for="sname">Site Title &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="set your site title here"></i>
                                                                    </label>
                                                                    <input type="text" class="form-control" id="sname" name="sname" value="<?= $system_title ?>" spellcheck="false" data-ms-editor="true">
                                                                    <span class="control-label"></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <div class="col-sm-12">
                                                                    <label for="phone">Phone No &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="Set organization phone number here"></i>
                                                                    </label>
                                                                    <input type="text" class="form-control" id="phone" name="phone" value="<?= $phone ?>" spellcheck="false" data-ms-editor="true">
                                                                    <span class="control-label"></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <div class="col-sm-12">
                                                                    <label for="email">System Email &nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="Set organization email address here"></i>
                                                                    </label>
                                                                    <input type="text" class="form-control" id="email" name="email" value="<?= $system_email ?>" spellcheck="false" data-ms-editor="true">
                                                                    <span class="control-label"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <div class="col-sm-12">
                                                                    <label for="address">Address&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="Set organization address here"></i>
                                                                    </label>
                                                                    <textarea class="form-control" style="resize:none;" id="address" name="address" spellcheck="false" data-ms-editor="true"><?= $address ?></textarea>
                                                                    <span class="control-label">
                                                                    </span>
                                                                    <editor-squiggler>
                                                                        <style>
                                                                            @media print {
                                                                                .ms-editor-squiggles-container {
                                                                                    display: none !important;
                                                                                }
                                                                            }

                                                                            .ms-editor-squiggles-container {
                                                                                all: initial;
                                                                            }
                                                                        </style>
                                                                        <div class="ms-editor-squiggles-container"></div>
                                                                    </editor-squiggler>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <div class="col-sm-12">
                                                                    <label for="footer">Footer&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="Set site footer text here"></i>
                                                                    </label>
                                                                    <input type="text" class="form-control" id="footer" name="footer" value="<?= $footer_title ?>" spellcheck="false" data-ms-editor="true">
                                                                    <span class="control-label">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    
                                                    </div>

            
                                                    <h3 class="text-center">SEO(Search Engine Optimisation)</h3>
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <div class="col-sm-12">
                                                                    <label for="author_name">Author Name&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Author Name"  data-original-title="Author Name"></i>
                                                                    </label>
                                                                    <input type="text" class="form-control" id="author_name" name="author_name" value="<?= $meta_author ?>" spellcheck="false" data-ms-editor="true">
                                                                    <span class="control-label">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <div class="col-sm-12">
                                                                    <label for="keywords">Keywords&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Keywords" data-original-title="Set keyword for searching on google"></i>
                                                                    </label>
                                                                    <input type="text" class="form-control" id="keywords" name="keywords" value="<?= $meta_keywords ?>" spellcheck="false" data-ms-editor="true">
                                                                    <span class="control-label">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <div class="col-sm-12">
                                                                    <label for="description">Description&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Description for Seo" data-original-title="Set Data for Searching on google"></i>
                                                                    </label>
                                                                    <textarea class="form-control" style="resize:none;" id="description" name="description" spellcheck="false" data-ms-editor="true"><?= $meta_description ?></textarea>
                                                                    <span class="control-label">
                                                                    </span>
                                                                    <editor-squiggler>
                                                                        <style>
                                                                            @media print {
                                                                                .ms-editor-squiggles-container {
                                                                                    display: none !important;
                                                                                }
                                                                            }

                                                                            .ms-editor-squiggles-container {
                                                                                all: initial;
                                                                            }
                                                                        </style>
                                                                        <div class="ms-editor-squiggles-container"></div>
                                                                    </editor-squiggler>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-primary">Update</button>
                                                </fieldset>
                                                </ul>

                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
                <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p> -->
            </div>

        </div>
    </div>
</div>
</div>

<script>
    $(function() {
        $("#generalForm").on('submit', function(e) {
            e.preventDefault();
            var contactForm = $(this);
            console.log(contactForm);
            // alert("hello");
            $.ajax({
                url: contactForm.attr('action'),
                type: 'post',
                data: contactForm.serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.status == 'success') {
                        toastr.success(response.message);

                    } else {
                        toastr.error(response.message);

                    }




                }
            });
        });
    });
</script>