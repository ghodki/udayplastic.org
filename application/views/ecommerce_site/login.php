<script>

</script>
<div class="after-header wow fadeInUp">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb rounded-0 bg-light">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Log In</li>
        </ol>
    </nav>
</div>

<!-- Section: Design Block -->
<section class="background-radial-gradient overflow-hidden" id="hidden" >
    <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
        <div class="row gx-lg-5 align-items-center mb-5">
            <div class="col-lg-3 mb-5 mb-lg-0" style="z-index: 10">

            </div>

            <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
                <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>

                <div class="card bg-glass">
                    <div class="card-body px-4 py-5 px-md-5">
                        <h2 class="text-primary">Login to Store</h2>
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                <?php echo $this->session->flashdata('msg'); ?>
                            </div>
                        <?php session_destroy();
                        } ?>
                        <?php
                        $roles_id = $this->session->userdata('roles');
                        if ($roles_id == 1)
                            $send_url = 'https://store.umatic.in/index.php/umatic/admin';
                        else
                            $send_url = 'https://store.umatic.in/index.php/umatic/user';

                        $uuserdata = $this->user_model->getRow('users', ['id' => $this->session->userdata('user_id')]);
                        ?>
                        <form name="formdata" id="formdata" action="<?= $send_url ?>" method="post">
                            <div class="form-group">
                                <input name="email" type="text" value="<?= $uuserdata->email ?>" class="form-control" id="loginId" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <input name="password" type="password" value="<?= $uuserdata->password ?>" class="form-control" placeholder="Password">
                                <div class="text-right text-small mt-2">
                                </div>
                            </div>
                            <button class="btn btn-primary btn-block" type="submit">Sign In</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
</section>
<script>
    $(document).ready(function() {
        // $("#formdata").on('submit', function(e) {
        //     e.preventDefault();
        //     var contactForm = $(this);
        //     $.ajax({
        //         url: contactForm.attr('action'),
        //         type: 'post',
        //         data: contactForm.serialize(),
        //         success: function(response) {
        //             var response = JSON.parse(response);
        //             if (response.status == true) {
        //                 toastr.success(response.message);
        //                 window.location.href = response.redirect;
        //             } else {
        //                 toastr.error(response.message);
        //                 $("#hidden").css("display", "block");

        //             }
        //         }
        //     });
        // })
        $('.btn-block').click();
    })
</script>