<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-blue pb-2 pt-2 pt-md-8">
                        <div class="container-fluid">
                            <div class="header-body ">
                                <h2 style="color: white; padding: 10px 0px 10px 0px;" class="text-center">Offer</h2>
                                <?php if ($this->session->flashdata('msg')) { ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                    <?php echo $this->session->flashdata('msg'); ?>
                                </div>
                                <?php } ?>
                                <form action="<?= base_url(); ?>offer/upload" id="signup" enctype="multipart/form-data" method="POST">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-lg-4">
                                            <div class="form-group">
                                                <input type="text" name="heading" id="heading" class="form-control" placeholder="Heading" value="<?= set_value('heading'); ?>" required="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-lg-4">
                                            <div class="form-group">
                                                <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="<?= set_value('title'); ?>" required="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-lg-4">
                                            <div class="form-group">
                                                <input type="url" name="url" id="url" class="form-control" placeholder="URL" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-lg-4">
                                            <div class="form-group">
                                                <input type="file" class="form-control" accept="image/*" name="image" id="image" size="20" />
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-sm-12 col-lg-8">
                                            <div class="form-group">
                                                <textarea name="description" id="YourEditor" placeholder="Description"><?= set_value('description'); ?></textarea>
                                            </div>
                                            <script>
                                                CKEDITOR.replace('YourEditor');
                                            </script>
                                        </div>
                                    </div>
                                    <div class="row mt-2 pb-2">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-default">
                                                Save
                                            </button>
                                            <input type="reset" class="btn btn-secondary" placeholder="Reset">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mb-2 mt-2">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <label class="mb-0" style="vertical-align: -moz-middle-with-baseline;">Offer List</label>
                            <div class="row mt-2">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                                    <?php echo $this->session->flashdata('msg'); ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush" style="width: 100%;" id="dataTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col" width="2%">#</th>
                                            <th>Heading</th>
                                            <th>Title</th>
                                            <th>Photo</th>
                                            <th>Description</th>
                                            <th>Notification</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($offer) && !empty($offer)) {
                                            foreach ($offer as  $key => $cat) {
                                        ?>
                                        <tr>
                                            <td>
                                                <span><?= $key + 1; ?></span>
                                            </td>
                                            <td><?= $cat->heading; ?></td>
                                            <td>
                                                <a href="<?= $cat->url; ?>">
                                                    <?= $cat->title; ?>
                                                </a>
                                            </td>
                                            <td>
                                                <img style="height: 100px;" src="<?= base_url(); ?>uploads/offer/<?= $cat->image; ?>" />
                                            </td>
                                            <td><?= $cat->description; ?></td>
                                            <td>
                                                <?php
                                                    if ($cat->setNotification != 1) {
                                                        echo '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" onchange="setNotification(' . $cat->offerId . ',1)"></div>';
                                                    } else {
                                                        echo '<div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" onchange="setNotification(' . $cat->offerId . ',0)" checked><label class="form-check-label" for="flexSwitchCheckChecked"></label></div>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                        <a href="<?= base_url('offer/edit/') . $cat->offerId ?>" class="dropdown-item cp">
                                                            Edit
                                                        </a>
                                                        <a href="<?= base_url('offer/delete/') . $cat->offerId ?>" onclick="return confirm('Are you sure you want to delete the Offer?');" class="dropdown-item cp">
                                                            Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>