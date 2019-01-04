@extends('mainLayout')
@section('content')
    <script type="text/javascript">
        const chartInterval = {{ $chartInterval }};
        const internalDomoticzAPI = "{{ url('meter/api/getDomoticzData') }}";
        const internalGetMeasurementsAPI = "{{ url('meter/api/getMeasurements') }}";
        const internalGetActualTariffsAPI = "{{ url('meter/api/getActualTariffs') }}";

        $(document).ready(function () {
            let energyChart = new Chart($("#energyChart"), {
                type: 'line',
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

            }, chartInterval);

            function runScript() {
                updateCharts();
                getMeasurements(1);
                getMeasurements(3);
            }

            function updateCharts() {
                $.ajax({
                    url: internalDomoticzAPI,
                    dataType: 'json',
                    type: 'get',
                    contentType: 'application/json',
                    processData: false,
                    success: function (data, textStatus, jQxhr) {
                        $.each(data.utilities, function (index, value) {
                            if (index === 'electricity') {
                                addData(energyChart, data.serverTime, data.utilities[index]);
                            } else if (index === 'gas') {
                                addData(gasChart, data.serverTime, data.utilities[index]);
                            }
                        });
                    },
                    error: function (jqXhr, textStatus, errorThrown) {
                        console.log(textStatus);
                    }
                });
            }

            function addData(chart, label, data) {
                chart.data.labels.push(label);
                chart.data.datasets.forEach((dataset) => {
                    dataset.data.push(data);
                });
                chart.update();
            }
        });

        function getMeasurements(deviceID) {
            getActualTariffs(deviceID);
            $.ajax({
                url: internalGetMeasurementsAPI + "/" + deviceID,
                dataType: 'json',
                type: 'get',
                contentType: 'application/json',
                processData: false,
                success: function (data, textStatus, jQxhr) {
                    const deviceType = data.device.device_types.description;
                    const elementID = 'tbody_' + deviceType;
                    $("#" + elementID + " tr").remove();
                    $.each(data.measurement, function (index, value) {
                        let usedInEuro = "";
                        let usedInComparison = "";
                        if (value.usedInComparisonEuro !== null && value.usedInComparisonEuro > 0) {
                            usedInEuro = '(<span class="text-danger">' + value.usedInComparisonEuro + '</span>)';
                        }

                        if (value.usedInComparison !== null && value.usedInComparison > 0) {
                            usedInComparison = '(<span class="text-danger">' + value.usedInComparison + '</span>)';
                        }

                        let row = "<tr>" +
                            "<td>" + value.amount + " " + usedInComparison + "</td>" +
                            "<td>&euro; " + value.usedEuro + " " + usedInEuro + "</td>" +
                            "<td>" + moment(value.timestamp.date).format('DD-MM-YYYY H:mm:ss') + "</td>" +
                            "</tr>";
                        $("#" + elementID).append(row);
                    });
                },
                error: function (jqXhr, textStatus, errorThrown) {
                    $()
                    console.log(textStatus);
                }
            });
        }

        function getActualTariffs(deviceID) {
            $.ajax({
                url: internalGetActualTariffsAPI + "/" + deviceID,
                dataType: 'json',
                type: 'get',
                contentType: 'application/json',
                processData: false,
                success: function (data, textStatus, jQxhr) {
                    const deviceType = data.device_types.description;
                    const elementID = 'tariff_' + deviceType;
                    const tariff = data.device_tariffs.currencies.symbol + " " + parseFloat(data.device_tariffs.amount);
                    $("#" + elementID).text(tariff);
                },
                error: function (jqXhr, textStatus, errorThrown) {
                    console.log(textStatus);
                }
            });
        }
    </script>
    <h2>Live monitor</h2>
    <hr>

    <div class="container-fluid">
        <div class="row">
            <div class="col-9">
                <canvas id="energyChart" width="300" height="100"></canvas>
            </div>
            <div class="col-3">
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
            </div>
        </div>
        <div class="row">
            <div class="col-9">
                <canvas id="gasChart" width="300" height="100"></canvas>
            </div>
            <div class="col-3">
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
@endsection
