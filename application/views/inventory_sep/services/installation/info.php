<div class="modal-header">
    <h5 class="modal-title">Installation Info</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="bg-pink">
                        <tr>
                            <th>Column's Name</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Date</td>
                            <td><?= date('d-m-Y', strtotime($installationData->date)); ?></td>
                        </tr>
                        <tr>
                            <td>Bill Number</td>
                            <td><?= $installationData->billNumber; ?></td>
                        </tr>
                        <tr>
                            <td>Manual Report No.</td>
                            <td><?= $installationData->manualReportNo; ?></td>
                        </tr>
                        <tr>
                            <td>Warranty Duration</td>
                            <td><?= $installationData->warrantyDuration; ?></td>
                        </tr>
                        <tr>
                            <td>Installation Date</td>
                            <td><?= date('d-m-Y', strtotime($installationData->installationDate)); ?></td>
                        </tr>
                        <tr>
                            <td>Category</td>
                            <td><?= get_title('category', ['id' => $installationData->categoryId], 'name'); ?></td>
                        </tr>
                        <tr>
                            <td>Brand</td>
                            <td><?= get_title('brand', ['id' => $installationData->brandId], 'name'); ?></td>
                        </tr>
                        <tr>
                            <td>Product Model/Name</td>
                            <td><?= $installationData->productModelName; ?></td>
                        </tr>
                        <tr>
                            <td>Machine Serial No.</td>
                            <td><?= $installationData->machineSerialNo; ?></td>
                        </tr>
                        <tr>
                            <td>Engineer Code/Name</td>
                            <td><?= $installationData->engineerCodeName; ?></td>
                        </tr>
                        <tr>
                            <td>Meter Reading</td>
                            <td><?= $installationData->meterReading; ?></td>
                        </tr>
                        <tr>
                            <td>Installation location: weather product installed at billing address?</td>
                            <td><?= $installationData->installationLocation; ?></td>
                        </tr>
                        <?php if ($installationData->installationLocation === "No") { ?>
                        <tr>
                            <td>Consign Name</td>
                            <td><?= $installationData->consignName; ?></td>
                        </tr>
                        <tr>
                            <td>Designation</td>
                            <td><?= $installationData->designation; ?></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><?= $installationData->installationAddress; ?></td>
                        </tr>
                        <tr>
                            <td>Contact Person</td>
                            <td><?= $installationData->contactPerson; ?></td>
                        </tr>
                        <tr>
                            <td>Contact No.</td>
                            <td><?= $installationData->contactNumber; ?></td>
                        </tr>
                        <tr>
                            <td>Key Operator</td>
                            <td><?= $installationData->keyOperator; ?></td>
                        </tr>
                        <?php } 
                            $partyData = $this->db->select('name, email, contact, ContactPerson, address_1')->where('id', $installationData->customerId)->get('parties')->row();
                        ?>
                        <tr>
                            <td>Customer Name</td>
                            <td><?= $partyData->name; ?></td>
                        </tr>
                        <tr>
                            <td>Contact No.</td>
                            <td><?= $partyData->contact; ?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><?= $partyData->email; ?></td>
                        </tr>
                        <tr>
                            <td>Contact Person</td>
                            <td><?= $partyData->ContactPerson; ?></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><?= $partyData->address_1; ?></td>
                        </tr>
                        <tr>
                            <td>Key Operator</td>
                            <td><?= $installationData->customerKeyOperator; ?></td>
                        </tr>
                        <tr>
                            <td>Type</td>
                            <td><?= $installationData->type; ?></td>
                        </tr>
                        <tr>
                            <th colspan="2">Place Of Installation Details.</th>
                        </tr>
                        <tr>
                            <td>Customer Name</td>
                            <td><?= $installationData->installCustomerName; ?></td>
                        </tr>
                        <tr>
                            <td>Contact No.</td>
                            <td><?= $installationData->installContactNo; ?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><?= $installationData->installEmail; ?></td>
                        </tr>
                        <tr>
                            <td>Place Of Installation</td>
                            <td><?= $installationData->placeOfInstallation; ?></td>
                        </tr>
                        <tr>
                            <td>Telephone No. 1</td>
                            <td><?= $installationData->telephoneNoOne; ?></td>
                        </tr>
                        <tr>
                            <td>Telephone No. 2</td>
                            <td><?= $installationData->telephoneNoTwo; ?></td>
                        </tr>
                        <tr>
                            <td>Fax/Mobile No.</td>
                            <td><?= $installationData->faxMobileNo; ?></td>
                        </tr>
                        <tr>
                            <td>Business Start Time</td>
                            <td><?= $installationData->businessStartTime; ?></td>
                        </tr>
                        <tr>
                            <td>Business End Time</td>
                            <td><?= $installationData->businessEndTime; ?></td>
                        </tr>
                        <tr>
                            <td>Weekly Off</td>
                            <td><?= $installationData->weeklyOff; ?></td>
                        </tr>
                        <tr>
                            <td>Training Name</td>
                            <td>
                                <?php
                                    $trainingName = explode(',', $installationData->trainingName);
                                    $sr = 1;
                                    foreach ($trainingName as $key => $trainingData) {
                                        echo $trainingData ? $sr++ . " " . $trainingData . ", " : "";
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Add Accessories Installation</th>
                            <td>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <tr>
                                            <th>Product Name/Code</th>
                                            <th>Description</th>
                                            <th>Serial No.</th>
                                        </tr>
                                        <?php if (!empty($installationProducts)) { foreach ($installationProducts as $key => $product) { ?>
                                        <tr>
                                            <td><?= get_title('product', ['id' => $product->productId], 'p_name'); ?></td>
                                            <td><?= $product->productDescription; ?></td>
                                            <td><?= $product->serialNumber; ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Docs</th>
                            <td>
                                <?php if (!empty($installationData->docs)) { ?>
                                <img src="<?= base_url(); ?>uploads/serviceDocs/<?= $installationData->docs; ?>" style="height: 50px;" alt="<?= $installationData->docs; ?>" />
                                <?php } ?>                                
                            </td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td><?= $installationData->created; ?></td>
                        </tr>
                        <tr>
                            <th>Created By</th>
                            <td><?= $installationData->createdBy; ?></td>
                        </tr>
                        <tr>
                            <th>Updated</th>
                            <td><?= $installationData->updated; ?></td>
                        </tr>
                        <tr>
                            <th>Updated By</th>
                            <td><?= $installationData->updatedBy; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
</div>