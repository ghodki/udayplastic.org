<style type="text/css">
    .topheadingsection {
        height: calc(45vh - 90px);
    }
    .inner-container {
        min-height: 200px;
    }
</style>

<div class="topheadingsection">
    <div class="container text-center">
        <h1>Vacancy</h1>
        <p class="px-md-5 font-22">
            Become a member of the Uday Plastic Family
        </p>
    </div>
</div>

<section class="inner-container">
    <div class="container">
        <div class="row">
            <?php if (isset($Vacancy) && !empty($Vacancy)) { ?>
            <div class="col-md-8 col-sm-8 col-lg-8 col-xs-8 col-xl-8 col-xxl-8 order-md-last">
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 col-xl-12 col-xxl-12">
                                <h5><?= $Vacancy->Designation; ?></h5>
                                <?php if (empty($Vacancy->CompanyBranchId)) { ?>
                                <small><?= get_title('inventory_billing', ['id' => $Vacancy->CompId], 'company_name'); ?></small>
                                <?php } else { ?>
                                <small><?= get_title('companybranch', ['Id' => $Vacancy->CompanyBranchId], 'BranchName'); ?></small>    
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4 col-sm-4 col-lg-4 col-xs-4 col-xl-4 col-xxl-4">
                                <i class="fa fa-briefcase"></i> Experience : <?= $Vacancy->Experience; ?>
                            </div>
                            <div class="col-md-4 col-sm-4 col-lg-4 col-xs-4 col-xl-4 col-xxl-4">
                                <i class="fa fa-map-marker"></i> Location : <?= $Vacancy->Location; ?>
                            </div>
                            <div class="col-md-4 col-sm-4 col-lg-4 col-xs-4 col-xl-4 col-xxl-4">
                                <i class="fa fa-user-plus"></i> Openings : <?= $Vacancy->Openings; ?>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 col-xl-12 col-xxl-12">
                                Job Description : <?= $Vacancy->JobDescription; ?>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 col-xl-12 col-xxl-12">
                                Requirements : <?= $Vacancy->Requirements; ?>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 col-xl-12 col-xxl-12">
                                <?= $Vacancy->Other; ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <label class="pull-left">
                            <?php
                                $inputDateTime = new DateTime($Vacancy->Created);
                                $currentDateTime = new DateTime();
                                $interval = $currentDateTime->diff($inputDateTime);
                                if ($interval->invert == 1) {
                                    echo $interval->days . ' Days Ago';
                                }
                            ?>
                        </label>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="col-md-4 col-sm-4 col-lg-4 col-xs-4 col-xl-4 col-xxl-4 order-md-last">
                <form class="enquiryfoms-sty" action="<?= base_url(); ?>index/carrer_apply/<?= $VacancyId; ?>" enctype="multipart/form-data" method="POST">
                    <input type="hidden" name="VacancyId" value="<?= set_value('VacancyId', $VacancyId); ?>" id="VacancyId" />
                    <div class="card" style="background: #FFE3E3;">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-center">Apply</h4>
                            </div>
                        </div>
                        <div class="card-body">                        
                            <div class="form-group">
                                <input type="text" name="name" value="<?= set_value('name'); ?>" id="name" class="form-control" placeholder="Name" required="" />
                                <span class="text-danger"><?= form_error('name'); ?></span>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" value="<?= set_value('email'); ?>" id="email" class="form-control" placeholder="Email" required="" />
                                <span class="text-danger"><?= form_error('email'); ?></span>
                            </div>
                            <div class="form-group">
                                <input type="tel" name="mobile" value="<?= set_value('mobile'); ?>" id="mobile" class="form-control" placeholder="Mobile" maxlength="10" minlength="10" required="" />
                                <span class="text-danger"><?= form_error('mobile'); ?></span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="qualification" value="<?= set_value('qualification'); ?>" id="mobile" class="form-control" placeholder="Highest Qualification" />
                            </div>
                            <div class="form-group">
                                <textarea name="address" id="address" class="form-control" rows="2" placeholder="Address"></textarea>
                            </div>
                            <div class="form-group">
                                <input type="file" name="ressume" id="ressume" class="form-control" title="Ressume" />
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-group d-grid">
                                <button type="submit" class="btn btn-primary rounded btn-full">Apply Online</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>