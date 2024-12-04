@extends('layouts.admin')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark"
                href="{{ route('admin.dashboard') }}">Overview</a></li>
        {{--            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>--}}
    </ol>
    <h5 class="font-weight-bolder mb-0">Dashboard</h5>
</nav>
@stop

@section('content')



<div class="row">

    <div class="col-sm-12 col-lg-9">
        <div class="row">

            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Yesterday</p>
                                    <h5 class="font-weight-bolder mb-0 totalRevenueyesterday">

                                    </h5>

                                </div>
                            </div>
                            <div class="d-flex mt-2">
                                <span class="badge-pill bg-light w-100 text-center p-2 text-muted h5">
                                    04-12-2024,Mon
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">This Month</p>
                                    <h5 class="font-weight-bolder mb-0 totalRevenueThisMonth">

                                    </h5>

                                </div>
                            </div>
                            <div class="d-flex mt-2">
                                <span class="badge-pill bg-light w-100 text-center p-2 text-muted h5">
                                04-12-2024 - 04-12-2024
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Previous Month</p>
                                    <h5 class="font-weight-bolder mb-0 totalRevenueLastMonth">

                                    </h5>

                                </div>
                            </div>
                            <div class="d-flex mt-2">
                                <span class="badge-pill bg-light w-100 text-center p-2 text-muted h5">
                                04-12-2024 - 04-12-2024
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 mt-2">
                <div class="card z-index-2">
                    <!-- <div class="card-header pb-0">
                    <h6>Sales overview</h6>
                    <p class="text-sm">
                        <i class="fa fa-arrow-up text-success"></i>
                        <span class="font-weight-bold">4% more</span> in 2021
                    </p>
                </div> -->
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-line" class="chart-canvas" style="height: 600px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 text-end">
        <a class="btn bg-gradient-dark mb-0 report" href="{{ route('admin.generate-report') }}"><i class="fas fa-plus"></i>&nbsp;&nbsp;Refresh Data</a>

        </div> -->

    </div>
  
</div>
<div class="row mt-4">
</div>


@stop


@section('scripts')
<!-- Script For Chart -->

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css"
    rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Ensure jQuery is loaded -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chart; // Declare the chart variable outside the function to keep track of it

function graph() {

    var ctx = document.getElementById('chart-line').getContext('2d');

    if (chart) {
        // Destroy the existing chart instance
        chart.destroy();
    }
    chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                    label: 'Impression',
                    type: 'line',
                    data: [],
                    borderColor: '#00bfff',
                    backgroundColor: 'rgba(0, 191, 255, 0.1)',
                    fill: true,
                    yAxisID: 'y-right',
                    tension: 0.3,
                    borderWidth: 3
                },
                {
                    label: 'Ecpm',
                    data: [],
                    backgroundColor: '#ff3d67',
                    borderRadius: {
                        topLeft: 55, // Radius for the top-left corner
                        topRight: 55, // Radius for the top-right corner
                        bottomLeft: 35, // No radius for the bottom-left corner
                        bottomRight: 35 // No radius for the bottom-right corner
                    },
                    yAxisID: 'y-left'
                }
            ]
        },
        options: {

            responsive: true,
            maintainAspectRatio: false,
            scales: {
                'y-left': { // left y-axis
                    type: 'linear',
                    position: 'left',
                    ticks: {
                        beginAtZero: true
                    }
                },
                'y-right': { // right y-axis
                    type: 'linear',
                    position: 'right',
                    ticks: {
                        beginAtZero: false,

                    },
                    grid: {
                        drawOnChartArea: true // only draw grid lines for left y-axis
                    }
                },
                x: {
                    grid: {
                        display: true
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Revenue per Day'
                }
            }
        }
    });


    let unitId = [];

    // Collect all checked checkboxes values
    $('input[type="checkbox"]:checked').each(function() {
        let value = $(this).val();
        if (!unitId.includes(value)) {
            unitId.push(value);
        }
    });

    let timePeriod = $('.timePeriod').val();

    $.ajax({
        url: "{{ route('graphData') }}",
        type: 'GET',
        data: {
            unitId: unitId,
            timePeriod: timePeriod,
            _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
        },
        success: function(response) {
            const dates = response.array_value.totaldata.map(item => item.date);
            const impressions = response.array_value.totaldata.map(item => item.impressions);
            const ecpm = response.array_value.totaldata.map(item => item.ecpm);

            // Update the chart's data
            chart.data.labels = dates;
            chart.data.datasets[0].data = impressions;
            chart.data.datasets[1].data = ecpm;

            // Update the chart to display the new data
            chart.update();
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}




graph();
</script>
<!-- End of  Script For Chart -->




<script>
$(document).ready(function() {
    // Handle click events on elements with the 'report' class
    $(document).on('click', '.report', function(e) {

        // Show the alert after a 2-second delay
        setTimeout(function() {
            Swal.fire({
                title: 'Data is being generated..... ',
                text: 'Kindly wait........',
                icon: 'info',
                showConfirmButton: false // Hide the confirm button
            });
        }, 2000);
    });
});


$('#date').datepicker({
    startView: 0,
    minViewMode: 0,
    maxViewMode: 2,
    multidate: true,
    multidateSeparator: "-",
    autoClose: true,
    beforeShowDay: highlightRange,
}).on("changeDate", function(event) {
    var dates = event.dates,
        elem = $('#date');
    if (elem.data("selecteddates") == dates.join(",")) return;
    if (dates.length > 2) dates = dates.splice(dates.length - 1);
    dates.sort(function(a, b) {
        return new Date(a).getTime() - new Date(b).getTime()
    });
    elem.data("selecteddates", dates.join(",")).datepicker('setDates', dates);
});

function highlightRange(date) {
    var selectedDates = $('#date').datepicker('getDates');
    if (selectedDates.length === 2 && date >= selectedDates[0] && date <= selectedDates[1]) {
        return 'highlighted';
    }
    return '';
}

$(document).ready(function() {
    // Initialize an array to hold the checked values
    let unitId = [];

    // Function to update the checked values array
    function updateCheckedValues() {
        unitId = []; // Reset the array

        // Iterate over each checked checkbox and add its value to the array
        $('input[type="checkbox"]:checked').each(function() {
            let value = $(this).val();
            if (!unitId.includes(value)) {
                unitId.push(value);
            }
        });
        let timePeriod = $('.timePeriod').val();
        $.ajax({
            url: "{{ route('dashboardData') }}",
            type: 'GET',
            data: {
                unitId: unitId,
                timePeriod: timePeriod,
                _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
            },
            success: function(response) {
                console.log('Response:', response.array_value.totalRevenueLastMonth);

                $('.totalRevenueLastMonth').html('$' + response.array_value.totalRevenueLastMonth);
                $('.totalRevenueyesterday').html('$' + response.array_value.totalRevenueyesterday);
                $('.totalRevenueThisMonth').html('$' + response.array_value.totalRevenueThisMonth);


                //window.location.href = response.redirectUrl;
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });



        // Log the updated array
        // console.log('Checked Values:', checkedValues);
    }

    // Event listener for checkbox changes
    $(document).on('change', 'input[type="checkbox"]', function() {
        updateCheckedValues();


    });

    $(document).on('change', 'input[type="checkbox"], .timePeriod', function() {
        graph();
    });

    // Trigger change event on page load for all initially checked checkboxes
    $('input[type="checkbox"]:checked').each(function() {
        $(this).trigger('change');
    });
});

$(document).ready(function() {
    // When the dropdown value changes
    $('.timePeriod').on('change', function() {
        // Get the selected value
        var selectedValue = $(this).val();

        // Set the value of the input field with the ID 'date'
        $('#date').val(selectedValue);

        // Optionally, trigger a custom event or function to update a date picker
        // if you have a date picker attached to this input
        $('#date').trigger('change');
    });

    // Function to simulate focus effect (optional)
    window.focused = function(element) {
        $(element).addClass('focus');
    };

    window.defocused = function(element) {
        $(element).removeClass('focus');
    };
});

$(document).on('click', '.unselectAll', function() {

    $('input[type="checkbox"]').prop('checked', false);

    // Optionally, you can trigger a change event if there are any change event listeners
    $('input[type="checkbox"]').trigger('change');

});
$(document).on('click', '.selectAll', function() {

    $('input[type="checkbox"]').prop('checked', true);

    // Optionally, you can trigger a change event if there are any change event listeners
    $('input[type="checkbox"]').trigger('change');

});
</script>

@stop