                <div class="modal-header">
                    <h5 class="modal-title">Ledger Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-condensed">
                                    <tr>
                                        <th>Date</th>
                                        <td><?= date('d-m-Y', strtotime($LedgerData->date)); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Account Group</th>
                                        <td><?= get_title('accounting_group', ['id' => $LedgerData->accounting_group], 'name'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Ledger Name</th>
                                        <td><?= $LedgerData->name; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Contact Person</th>
                                        <td><?= $LedgerData->ContactPerson; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Mobile</th>
                                        <td><?= $LedgerData->Mobile; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td><?= $LedgerData->Email; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td><?= $LedgerData->Address; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Area</th>
                                        <td><?= $LedgerData->Area; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Type</th>
                                        <td><?= $LedgerData->type; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Opening Balance</th>
                                        <td><i class="fa fa-inr"></i> <?= $LedgerData->opening_balance; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Created</th>
                                        <td><?= $LedgerData->created; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Updated</th>
                                        <td><?= $LedgerData->updated; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
                </div>