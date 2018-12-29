@extends('mainLayout')
@section('content')
    <h2>Banktransacties</h2>
    <div class="table-responsive">
        {{ $transactions->links() }}
        <table class="table table-striped table-sm table-bordered table-hover table-condensed" style="font-size: 12px;">
            <thead>
            <tr>
                <th>Datum</th>
                <th>Bedrag</th>
                <th>Omschrijving</th>
            </tr>
            </thead>
            <tbody>
            @foreach($transactions as $t)
                <tr>
                    <td>{{ $t->transaction_date }}</td>
                    <td width="7%">
                        @if($t->amount>0)
                            <span class="text-success">
                                {{ $t->currencies->symbol }} {{ number_format($t->amount, 2, ',', '.') }}
                            </span>
                        @else
                            <span class="text-danger">
                                {{ $t->currencies->symbol }} {{ number_format($t->amount, 2, ',', '.') }}
                            </span>
                        @endif
                    </td>
                    <td style="font-size: 11px;">{{ $t->description }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $transactions->links() }}
    </div>
@endsection
