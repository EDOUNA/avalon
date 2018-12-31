@extends('mainLayout')
@section('content')
    <script type="text/javascript">
        const chartInterval = {{ $chartInterval }};
        const internalAPI = "{{ url('meter/api/getDomoticzData') }}";
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

            window.setInterval(function () {
                updateCharts();
            }, chartInterval);

            function updateCharts() {
                $.ajax({
                    url: internalAPI,
                    dataType: 'json',
                    type: 'get',
                    contentType: 'application/json',
                    processData: false,
                    success: function (data, textStatus, jQxhr) {
                        $.each(data.utilities, function (index, value) {
                            if (index === 'energy') {
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


    </script>
    <h2>Live monitor</h2>

    <canvas id="energyChart" width="400" height="100"></canvas>
    <canvas id="gasChart" width="400" height="100"></canvas>
@endsection
