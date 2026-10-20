<div class="modal-header">
    <h5 class="modal-title">Service Call Info</h5>
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
                            <td><?= date('d-m-Y', strtotime($serviceCallData->date)); ?></td>
                        </tr>
                        <tr>
                            <td>Bill Number</td>
                            <td><?= $serviceCallData->billNumber; ?></td>
                        </tr>
                        <tr>
                            <td>Installation Report No.</td>
                            <td><?= $serviceCallData->installationReportNo; ?></td>
                        </tr>
                        <tr>
                            <td>Manual Report No.</td>
                            <td><?= $serviceCallData->manualReportNo; ?></td>
                        </tr>
                        <tr>
                            <td>Bill Status</td>
                            <td><?= $serviceCallData->billStatus; ?></td>
                        </tr>
                        <tr>
                            <?php $partyData = $this->db->select('name, contact, email, address_1')->where('id', $serviceCallData->customerId)->get('parties')->row(); ?>
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
                            <td>Address</td>
                            <td><?= $partyData->address_1; ?></td>
                        </tr>                        
                        <tr>
                            <td>Category</td>
                            <td><?= get_title('category', ['id' => $serviceCallData->categoryId], 'name'); ?></td>
                        </tr>
                        <tr>
                            <td>Brand</td>
                            <td><?= get_title('brand', ['id' => $serviceCallData->brandId], 'name'); ?></td>
                        </tr>
                        <tr>
                            <td>Product Model/Name</td>
                            <td><?= $serviceCallData->productModelName; ?></td>
                        </tr>
                        <tr>
                            <td>Model Number</td>
                            <td><?= $serviceCallData->modelNumber; ?></td>
                        </tr>
                        <tr>
                            <td>Machine Serial No.</td>
                            <td><?= $serviceCallData->machineSerialNo; ?></td>
                        </tr>
                        <tr>
                            <td>Counter Reading</td>
                            <td><?= $serviceCallData->counterReading; ?></td>
                        </tr>
                        <tr>
                            <td>Contract Status</td>
                            <td><?= $serviceCallData->contractStatus; ?></td>
                        </tr>
                        <tr>
                            <td>Call Type</td>
                            <td><?= $serviceCallData->callType; ?></td>
                        </tr>
                        <tr>
                            <td>Installation location: weather product installed at billing address?</td>
                            <td><?= $serviceCallData->installationLocation; ?></td>
                        </tr>
                        <?php if ($serviceCallData->installationLocation === "No") { ?>
                        <tr>
                            <td>Consign Name</td>
                            <td><?= $serviceCallData->consignName; ?></td>
                        </tr>
                        <tr>
                            <td>Designation</td>
                            <td><?= $serviceCallData->designation; ?></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><?= $serviceCallData->installationAddress; ?></td>
                        </tr>
                        <tr>
                            <td>Contact Person</td>
                            <td><?= $serviceCallData->contactPerson; ?></td>
                        </tr>
                        <tr>
                            <td>Contact No.</td>
                            <td><?= $serviceCallData->contactNumber; ?></td>
                        </tr>
                        <tr>
                            <td>Key Operator</td>
                            <td><?= $serviceCallData->keyOperator; ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td>Problem</td>
                            <td><?= $serviceCallData->problem; ?></td>
                        </tr>
                        <tr>
                            <td>Cause</td>
                            <td><?= $serviceCallData->cause; ?></td>
                        </tr>
                        <tr>
                            <td>Black A3</td>
                            <td><?= $serviceCallData->blackA3; ?></td>
                        </tr>
                        <tr>
                            <td>Black A4</td>
                            <td><?= $serviceCallData->blackA4; ?></td>
                        </tr>
                        <tr>
                            <td>Colour A3</td>
                            <td><?= $serviceCallData->colourA3; ?></td>
                        </tr>
                        <tr>
                            <td>Colour A4</td>
                            <td><?= $serviceCallData->colourA4; ?></td>
                        </tr>
                        <tr>
                            <td>Total Reading</td>
                            <td><?= $serviceCallData->totalReading; ?></td>
                        </tr>
                        <tr>
                            <td>Action Taken</td>
                            <td><?= $serviceCallData->actionTaken; ?></td>
                        </tr>
                        <tr>
                            <td>Machine Status</td>
                            <td><?= $serviceCallData->machineStatus; ?></td>
                        </tr>
                        <tr>
                            <td>Invoice Status</td>
                            <td>
                                <?php if ($serviceCallData->reportBill == "Pending") { ?>
                                <label class="badge badge-warning"><i class="fa fa-exclamation-circle"></i> Pending</label>
                                <?php } else { ?>
                                <label class="badge badge-success"><i class="fa fa-thumbs-up"></i> Generated</label>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Total Amount</td>
                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($serviceCallData->grandTotalAmount); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <tr>
                                            <th>Sr</th>
                                            <th>Spare Used/Reg. this call</th>
                                            <th>Part No.</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Amount</th>
                                        </tr>
                                        <?php if (!empty($serviceCallProducts)) { $sr = 0; foreach ($serviceCallProducts as $key => $product) { $sr++;?>
                                        <tr>
                                            <td><?= $sr; ?></td>
                                            <td><?= get_title('product', ['id' => $product->productId], 'p_name'); ?></td>
                                            <td><?= $product->partNumber; ?></td>
                                            <td><?= $product->quantity; ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($product->unitPrice); ?></td>
                                            <td><i class="fa fa-inr"></i> <?= indianMoneyFormat($product->amount); ?></td>
                                        </tr>
                                        <?php } } ?>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td><?= $serviceCallData->created; ?></td>
                        </tr>
                        <tr>
                            <th>Created By</th>
                            <td><?= $serviceCallData->createdBy; ?></td>
                        </tr>
                        <tr>
                            <th>Updated</th>
                            <td><?= $serviceCallData->updated; ?></td>
                        </tr>
                        <tr>
                            <th>Updated By</th>
                            <td><?= $serviceCallData->updatedBy; ?></td>
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