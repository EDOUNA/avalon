@extends('mainLayout')
@section('content')
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
        <table class="table table-striped table-sm table-bordered table-hover table-condensed" style="font-size: 12px;">
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
                <tr style="height: 100px;">
                    <td style="width: 5%;">{{ date('d-m-Y', strtotime($t->transaction_date)) }}</td>
                    <td style="width: 5%;">
                        <span class="{{ $t->spanClass }}">
                            {{ $t->currencies->symbol }} {{ number_format($t->amount, 2, ',', '.') }}
                        </span>
                    </td>
                    <td>
                        
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
