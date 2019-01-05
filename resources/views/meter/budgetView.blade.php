@extends('mainLayout')
@section('content')
    <script type="text/javascript">
        const chartInterval = {{ $refreshInterval }};
        const internalBudgetAPI = "{{ url('meter/api/getBudget/d') }}";

        $(document).ready(function () {
            let budgetChart = new Chart($("#budgetChart"), {
                type: 'pie',
                responsive: true,
                data: {
                    labels: [],
                    datasets: [{
                        label: null,
                        data: [],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)'
                        ],
                        borderWidth: 1
                    }]
                }
            });

            // Initial set-up
            runScript();

            window.setInterval(function () {
                runScript();
            }, chartInterval);

            function runScript() {
                updateCharts();

                // Update the last timestamp
                $("#lastUpdateTimestamp").text(moment().format('HH:mm'));
            }

            function updateCharts() {
                $.ajax({
                    url: internalBudgetAPI,
                    dataType: 'json',
                    type: 'get',
                    contentType: 'application/json',
                    success: function (data, textStatus, jQxhr) {
                        let labelArray = [];
                        let amountArray = [];

                        $.each(data.devices, function (index, value) {
                            amountArray.push(value.amount);
                            labelArray.push(value.description);
                        });

                        budgetChart.data.labels = labelArray;
                        budgetChart.data.datasets[0].data = amountArray;
                        budgetChart.update();

                        let progressBar = $("#budgetProcessBar");
                        let cssProgressPercentage = 100;

                        if (data.budgetPercentage < 100) {
                            cssProgressPercentage = data.budgetPercentage;
                        }

                        progressBar.addClass(data.progressBarClass);
                        progressBar.attr('aria-valuenow', cssProgressPercentage).css('width', cssProgressPercentage + "%");
                        progressBar.text(data.budgetCurrency + " " + data.budgetSpent + " / " + data.budgetCurrency + " " + data.budgetAllowed);
                    },
                    error: function (jqXhr, textStatus, errorThrown) {
                        console.log(textStatus);
                    }
                });
            }
        });
    </script>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Budgetoverzicht</h3>
                    <div class="pull-right" style="font-size: 12px;">
                        Laatst geüpdatet om <span id="lastUpdateTimestamp"></span>
                    </div>
                </div>
                <div class="box-body">
                    <div class="progress-bar" id="budgetProcessBar" role="progressbar" aria-valuenow="0"
                         aria-valuemin="0"
                         aria-valuemax="100">
                    </div>
                    <p>&nbsp;</p>
                    <canvas id="budgetChart" class="avalonCanvas"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
