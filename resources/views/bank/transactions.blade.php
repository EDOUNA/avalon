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
                        }
                    },
                    error: function (jqXhr, textStatus, errorThrown) {
                        console.log(textStatus);
                    }
                });
            }, 1000);
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
                    <td style="width: 15%;">
                        <div class="spinner-border" role="status" style="display: none;"
                             id="loaderElement_{{ $t->id }}">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div id="updateElement_"{{ $t->id }}>
                            <select name="category" id="select_{{ $t->id }}" class="selectable" style="width: 75%;">
                                <option></option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->description }}</option>
                                @endforeach
                            </select>
                            <a href="javascript:void(0);" onclick="updateCategory({{ $t->id }})"
                               class="btn btn-primary btn-sm"><i class="fas fa-check-circle"></i></a>
                        </div>
                    </td>
                    <td style="font-size: 11px;">
                        {{ $t->description }}
                        <div class="clearfix"></div>
                        <div class="btn-group-sm" style="margin-bottom: -1px;">
                            <a href="#" class="btn btn-primary">x</a>
                            <a href="#" class="btn btn-primary">x</a>
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
