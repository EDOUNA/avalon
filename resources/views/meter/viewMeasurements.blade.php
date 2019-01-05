@extends('mainLayout')
@section('content')
    <script type="text/javascript">
        const chartInterval = {{ $chartInterval }};
        const internalStaticInterval = "{{ url('meter/api/renderDefaultMeasurements') }}";
        const internalGetMeasurementsAPI = "{{ url('meter/api/getMeasurements') }}";
        const internalGetActualTariffsAPI = "{{ url('meter/api/getActualTariffs') }}";

        $(document).ready(function () {
            let energyChart = new Chart($("#energyChart"), {
                type: 'line',
                responsive: true,
                data: {
                    labels: [],
                    datasets: [{
                        label: 'kWh',
                        data: [],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255,99,132,1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                //beginAtZero: true
                            }
                        }]
                    }
                }
            });

            let gasChart = new Chart($("#gasChart"), {
                type: 'line',
                responsive: true,
                data: {
                    labels: [],
                    datasets: [{
                        label: 'm3',
                        data: [],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.2)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                //beginAtZero: true
                            }
                        }]
                    }
                }
            });

            // Initial set-up
            runScript();

            window.setInterval(function () {
                runScript();
                //}, chartInterval);
            }, 5000);

            function runScript() {
                updateCharts(1);
                updateCharts(3);
            }

            function updateCharts(deviceID) {
                $.ajax({
                    url: internalStaticInterval + "/" + deviceID,
                    dataType: 'json',
                    type: 'get',
                    contentType: 'application/json',
                    success: function (data, textStatus, jQxhr) {
                        console.log(data);
                        removeData(energyChart);
                        removeData(gasChart);
                        let labelArray = [];
                        let amountArray = [];
                        if (data.deviceDetails.deviceType === 'electricity') {
                            $.each(data.measurements, function (index, value) {
                                labelArray.push(value.created_at);
                                amountArray.push(value.amount);
                            });
                            energyChart.data.labels = labelArray;
                            energyChart.data.datasets[0].data = amountArray;

                            energyChart.update();
                        } else if (data.deviceDetails.deviceType === 'gas') {
                            $.each(data.measurements, function (index, value) {
                                labelArray.push(value.created_at);
                                amountArray.push(value.amount);
                            });
                            gasChart.data.labels = labelArray;
                            gasChart.data.datasets[0].data = amountArray;

                            gasChart.update();
                        }
                    },
                    error: function (jqXhr, textStatus, errorThrown) {
                        console.log(textStatus);
                    }
                });
            }

            /**
             * Publish a new chart
             * @param chart
             * @param label
             * @param data
             */
            function addData(chart, label, data) {
                chart.data.labels.push(label);
                chart.data.datasets.forEach((dataset) => {
                    dataset.data.push(data);
                });
                chart.update();
            }

            /**
             * Remove data from the already generated chart
             * @param chart
             */
            function removeData(chart) {
                console.log('Running for ' + chart);
                chart.data.labels.pop();
                chart.data.datasets.forEach((dataset) => {
                    dataset.data.pop();
                });
                chart.update();
            }
        });
    </script>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Static view</h3>
                </div>
                <div class="box-body">
                    <canvas id="energyChart" width="300" height="100"></canvas>
                    <table class="table table-bordered table-striped table-hover table-sm" style="font-size: 11px;"
                           id="table_electricity">
                        <thead>
                        <tr>
                            <th>kWh</th>
                            <th>Tarief
                                <div id="tariff_electricity"></div>
                            </th>
                            <th>Timestamp</th>
                        </tr>
                        </thead>
                        <tbody id="tbody_electricity">

                        </tbody>
                    </table>
                    <canvas id="gasChart" width="300" height="100"></canvas>
                    <table class="table table-bordered table-striped table-hover table-sm" style="font-size: 11px;"
                           id="table_gas">
                        <thead>
                        <tr>
                            <th>m3</th>
                            <th>Tarief
                                <div id="tariff_gas"></div>
                            </th>
                            <th>Timestamp</th>
                        </tr>
                        </thead>
                        <tbody id="tbody_gas">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
