<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCnr2cIpAbxu32ce__6vwmr8vuLWbz5L4M"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#branchId').select2();
        $('#employeeId').select2();
    });
    
    function createRoundedIcon(photoUrl, size = 50, borderRadius = 25) {
        return new Promise((resolve) => {
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            const image = new Image();

            canvas.width = size;
            canvas.height = size;
            image.crossOrigin = 'anonymous';
            image.src = photoUrl;

            image.onload = () => {
                context.clearRect(0, 0, size, size);
                context.save();
                context.beginPath();
                context.arc(size / 2, size / 2, borderRadius, 0, Math.PI * 2, true);
                context.closePath();
                context.clip();
                context.drawImage(image, 0, 0, size, size);
                context.restore();
                resolve(canvas.toDataURL());
            };
        });
    }
    
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: {lat: <?= $Latitude; ?>, lng: <?= $Longitude; ?>}
        });

        var locations = <?= $LocationData; ?>;

        locations.forEach(function(location) {
            var employeeName = location.EmployeeName;
            var color = location.color || '#000000';
            var photoUrl = location.photoUrl || '';
            var pathPoints = location.Points; // Array of all visited points

            createRoundedIcon(photoUrl).then((roundedPhotoUrl) => {
                // Place a marker at the first point (start point)
                if (pathPoints.length > 0) {
                    var startMarker = new google.maps.Marker({
                        position: pathPoints[0],
                        map: map,
                        title: employeeName + ' Start Point',
                        icon: {
                            url: roundedPhotoUrl,
                            scaledSize: new google.maps.Size(50, 50),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(25, 25),
                            labelOrigin: new google.maps.Point(25, 25),
                        },
                        animation: google.maps.Animation.DROP,
                        clickable: true
                    });

                    startMarker.addListener('click', () => {
                        loadEmployeeTrackingData(location.EmployeeId);
                    });
                }

                // Place a marker at the last point (end point)
                if (pathPoints.length > 1) {
                    var endMarker = new google.maps.Marker({
                        position: pathPoints[pathPoints.length - 1],
                        map: map,
                        title: employeeName + ' End Point',
                        icon: {
                            url: roundedPhotoUrl,
                            scaledSize: new google.maps.Size(50, 50),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(25, 25),
                            labelOrigin: new google.maps.Point(25, 25),
                        },
                        animation: google.maps.Animation.DROP,
                        clickable: true
                    });

                    endMarker.addListener('click', () => {
                        loadEmployeeTrackingData(location.EmployeeId);
                    });
                }

                // Draw the polyline through all points
                if (pathPoints.length > 1) {
                    var linePath = new google.maps.Polyline({
                        icons: [{
                            icon: {path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW},
                            offset: '100%',
                            repeat: '100px'
                        }],
                        path: [location.startPoint, location.endPoint],
                        geodesic: true,
                        strokeColor: color,
                        strokeOpacity: 1.0,
                        strokeWeight: 4
                    });

                    linePath.setMap(map);
                }
            });
        });
    }

    function loadEmployeeTrackingData(employeeId) {
        $("#EmployeeModal .modal-dialog .modal-content").load("<?= base_url(); ?>Reports/EmployeeTrackingData/" + employeeId + "/<?= $AttendanceDate; ?>");
        $('#EmployeeModal').modal('show');
    }

    $(document).ready(function() {
        initMap();
    });
</script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-navy card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-navy">
                                    Employee Tracking
                                </h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-lg-2 col-xl-2 col-xxl-2 col-xs-2">
                                        <div class="form-group">
                                            <input type="date" name="Date" value="<?= set_value('Date', $this->input->get('Date') ? $this->input->get('Date') : date('Y-m-d')); ?>" id="Date" class="form-control form-control-sm" title="Date" />
                                        </div>
                                    </div>
                                    <?php if ($this->session->userdata('roles') == 1) { ?>
                                    <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xxl-3 col-xs-3">
                                        <div class="form-group">
                                            <select name="branchId" id="branchId" class="form-control form-control-sm" title="Company Branch">
                                                <option value="" <?= $this->input->get('branchId') == '' ? 'selected' : ''; ?>>Main Company</option>
                                                <?php if ($companyBranches->num_rows()) { foreach ($companyBranches->result() as $key => $branch) { ?>
                                                <option value="<?= $branch->Id; ?>" <?= $this->input->get('branchId') == $branch->Id ? 'selected' : ''; ?>><?= $branch->BranchName; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xxl-3 col-xs-3">
                                        <div class="form-group">
                                            <select name="employeeId" id="employeeId" class="form-control form-control-sm" title="Employee">
                                                <option value="All" <?= $this->input->get('employeeId') == "All" ? 'selected' : ""; ?>>All</option>
                                                <?php if ($employeesData->num_rows()) { foreach ($employeesData->result() as $key => $employee) { ?>
                                                <option value="<?= $employee->Id; ?>" <?= $this->input->get('employeeId') == $employee->Id ? 'selected' : ''; ?>><?= $employee->Name; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-1 col-lg-1 col-xl-1 col-xxl-1 col-xs-1">
                                        <button type="submit" class="btn btn-dark btn-sm">
                                            <i class="fa fa-search text-white"></i>
                                        </button>
                                        <a href="<?= base_url(); ?>Reports/EmployeeTracking" class="btn btn-danger btn-sm" title="Refresh">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xxl-12 col-xs-12">
                                    <div id="map" style="height: 500px; width: 100%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>

<div class="modal fade" id="EmployeeModal" role="dialog" aria-labelledby="title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>