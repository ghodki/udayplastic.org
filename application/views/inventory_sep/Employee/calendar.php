<style type="text/css">
    #calendar {
        max-width: 1200px;
        margin: 0 auto;
    }
</style>
<script type="text/javascript" src="<?= base_url(); ?>assets/fullcalendar/index.global.js"></script> 

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid"> 
            <div class="row"> 
                <div class="col-md-12 col-xl-12 col-lg-12"> 
                    <div class="card card-outline card-dark">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-dark">Calendar</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var holiday_value = <?= json_encode($holidays); ?>;
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialDate: '<?= date('Y-m-d'); ?>',
            editable: true,
            selectable: true,
            businessHours: true,
            dayMaxEvents: true, // allow "more" link when too many events
            events: holiday_value
        });
        calendar.render();
    });
</script>