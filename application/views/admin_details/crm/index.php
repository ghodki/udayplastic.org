<!-- /.row -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

            <?php if ($this->session->flashdata('msg')) { ?>
                <div class="alert alert-<?php echo $this->session->flashdata('class'); ?>">
                    <?php echo $this->session->flashdata('msg'); ?>
                </div>
            <?php } ?>

            <div class="row mb-2">
                <div class="col-sm-6">
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo "CRM" ?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4">

                    <div class="card m-b-30 ">

                        <div class="card-body ">
                            <div class="col-md-12">
                                <a class="addnew btn btn-info" style="color: #ffffff; margin-bottom: 10px;">Add Role</a>
                            </div>

                            <div class="addnewrole" style="display:none;">

                                <form name="formdata" action="addrole" method="post">

                                    <div class="form-group">
                                        <label for="name">Add Role</label>

                                        <div>
                                            <input class="form-control" placeholder="Name" required="required" name="name" type="text" id="name">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div>
                                            <input type="checkbox" id="selectAll">
                                            <label for="name">Select All</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <?php foreach ($menus_list as $key1 => $menu_val) {  ?>

                                            <input class="module parentModule parentModule_<?= $key1 ?>" id="<?= $key1 ?>" name="permissions[]" type="checkbox" value=<?= $menu_val['parent_menu']->id ?>>
                                            <label for="Master" style="font-weight: 500;"><?= $menu_val['parent_menu']->name ?></label><br>

                                            <div class="form-group" style="padding-left: 15px;">
                                                <?php foreach ($menu_val['child'] as $key => $submenu_val) { ?>

                                                    <input class="module childModule childModule_<?= $key1 ?>" id="<?= $key1 ?>" name="permissions[]" type="checkbox" value=<?= $submenu_val->id ?>>
                                                    <label for=<?= $submenu_val->name ?> style="font-weight: 500;"><?= $submenu_val->name ?></label>&nbsp;


                                                <?php  }  ?>
                                            </div>

                                        <?php } ?>
                                    </div>
                                    <div class="form-group m-b-0">
                                        <div>
                                            <button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
                                            <a href="http://localhost/amigos/admin/roles" class="btn btn-secondary waves-effect m-l-5"> Cancel </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->

                <div class="col-lg-8">
                    <div class="card m-b-30">
                        <div class="card-body">
                            <h4 class="mt-0 header-title">Role List</h4>

                            <table class="table table-dark">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="10%">Role</th>
                                        <th width="60%">Permissions</th>
                                        <th width="25%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php


                                    foreach ($role_list as $key => $value) { ?>
                                        <tr>
                                            <td><?= $key + 1 ?></td>
                                            <td><?= $value['role_name'] ?></td>
                                            <td>
                                                <ul>

                                                    <?php foreach ($value['parent'] as $key => $menu_val) {
                                                        echo "<li>";
                                                        echo  $menu_val['name'] . "&nbsp;(";
                                                        $this->db->select('permissions.name')
                                                            ->from('role_has_permissions')
                                                            ->where('permissions.parents_id', $menu_val['id'])
                                                            ->where('role_has_permissions.role_id', $value['role_id'])
                                                            ->join('permissions', 'role_has_permissions.permissions_id =permissions.id')
                                                            ->order_by("role_has_permissions.permissions_id", "asc");
                                                        $user_assign_child = $this->db->get()->result_array();
                                                        foreach ($user_assign_child as $sub_name) {
                                                    ?>
                                                            <?= $sub_name['name'] ?>,

                                                        <?php } ?>
                                                        &nbsp;)
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('roles/edit/') . $value['role_id'] ?>" class="btn btn-info pull-left" title="Edit Role">Edit</a>

                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
        </div>
    </section>
</div>