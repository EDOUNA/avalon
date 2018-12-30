@extends('mainLayout')
@section('content')
    <script type="text/javascript">

    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".selectable").select2({
                    placeholder: 'Selecteer categorie',
                }
            );

            $("#tableTransaction").fadeIn('fast');

            updateProgress();
        });

        /**
         *
         * @param transactionID
         * @returns {boolean}
         */
        function updateCategory(transactionID) {
            let selectedCategory = $("#select_" + transactionID).val();

            if (selectedCategory == "") {
                alert('Selecteer wel een categorie!');
                return false;
            }

            $("div#updateElement_" + transactionID).hide();
            $("#loaderElement_" + transactionID).fadeIn('fast');

            window.setTimeout(function () {
                $.ajax({
                    url: "{{ url('bank/transactions/updateCategory') }}",
                    dataType: 'json',
                    type: 'post',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        "transaction": transactionID,
                        "category": selectedCategory,
                        "_token": "{{ csrf_token() }}"
                    }),
                    processData: false,
                    success: function (data, textStatus, jQxhr) {
                        if (textStatus === "success") {
                            $("#tr_" + transactionID).fadeOut('slow');
                            // Rerun to update the page
                            updateProgress();
                        }
                    },
                    error: function (jqXhr, textStatus, errorThrown) {
                        console.log(textStatus);
                    }
                });
            }, 1000);

        }

        /**
         * @param transactionID
         */
        function toggleEdit(transactionID) {
            $("#editTable_" + transactionID).slideToggle();
        }

        function updateProgress() {
            $.ajax({
                url: "{{ url('bank/transactions/api/getCategorizedScore') }}",
                dataType: 'json',
                type: 'get',
                contentType: 'application/json',
                processData: false,
                success: function (data, textStatus, jQxhr) {
                    let progressPercentage = ((data.total / data.uncategorized * 100) - 100).toFixed(2);
                    $("#categoryProgress").css("width", progressPercentage).attr('aria-valuenow', Math.round(progressPercentage));
                    $("#categoryProgress").text(progressPercentage + "% | no category = " + data.uncategorized);
                },
                error: function (jqXhr, textStatus, errorThrown) {
                    console.log(textStatus);
                }
            });
        }
    </script>
    <h2>Banktransacties</h2>
    <div class="table-responsive">
        <div>
            {{ $transactions->links() }}
            @if(!request()->has('uncategorized'))
                <a href="{{ url('bank/transactions?uncategorized=true') }}" class="btn btn-primary">Geen categorie</a>
            @else
                <a href="{{ url('bank/transactions') }}" class="btn btn-primary">Met categorie</a>
            @endif
            <div class="float-right" style="width: 50%;">
                <div class="progress">
                    <div class="progress-bar" id="categoryProgress" role="progressbar"
                         aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <table class="table table-striped table-sm table-bordered table-hover table-condensed"
               style="font-size: 12px; display:none;" id="tableTransaction">
            <thead>
            <tr>
                <th>Datum</th>
                <th>Bedrag</th>
                <th>Categorie</th>
                <th>Omschrijving</th>
                <th>Bankrekening</th>
            </tr>
            </thead>
            <tbody>
            @foreach($transactions as $t)
                <tr style="height: 100px;" id="tr_{{ $t->id }}">
                    <td style="width: 5%;">{{ date('d-m-Y', strtotime($t->transaction_date)) }}</td>
                    <td style="width: 5%;">
                        <span class="{{ $t->spanClass }}">
                            {{ $t->currencies->symbol }} {{ number_format($t->amount, 2, ',', '.') }}
                        </span>
                    </td>
                    <td style="width: 10%;">

                    </td>
                    <td style="font-size: 11px;" class="hidden-sm">
                        <table class="table">
                            {{ $t->description }}
                        </table>
                        <table class="table">
                            <div class="clearfix"></div>
                            <div class="btn-group btn-group-sm">
                                <a href="javascript:void(0);" onclick="toggleEdit({{ $t->id }})"
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a href="{{ url('bank/transactions/find/'.$t->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search"></i>
                                </a>
                                <a href="{{ url('bank/transactions/deactivate/'.$t->id) }}"
                                   class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </table>
                        <div id="editTable_{{ $t->id }}" style="display: none;">
                            <table class="table table-striped table-sm table-bordered table-hover table-condensed">
                                <tr>
                                    <td>
                                        <div class="spinner-border" role="status" style="display: none;"
                                             id="loaderElement_{{ $t->id }}">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <div id="updateElement_"{{ $t->id }}>
                                            <select name="category" id="select_{{ $t->id }}" class="selectable"
                                                    style="width: 75%;">
                                                <option></option>
                                                @foreach($categories as $c)
                                                    <option value="{{ $c->id }}">{{ $c->description }}</option>
                                                @endforeach
                                            </select>
                                            <a href="javascript:void(0);" onclick="updateCategory({{ $t->id }})"
                                               class="btn btn-primary btn-sm"><i class="fas fa-check-circle"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td>
                        {{ $t->bankaccounts->account_number }}<br/>
                        <span style="font-size: 10px;">({{ $t->bankaccounts->description }})</span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $transactions->links() }}
    </div>
@endsection
