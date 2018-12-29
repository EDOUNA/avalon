@extends('mainLayout')
@section('content')
    <h2>Categorieën</h2>
    <div class="pull-left">
        <a href="{{ url('bank/categories/create') }}" class="btn btn-primary">Nieuwe categorie</a>
    </div>
    <hr>
    @if (count($categories)>0)
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered table-hover table-condensed">
                @foreach($categories as $c)
                    <tr>
                        <td>{{ $c->description }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    @else
        <div class="alert alert-info">Er zijn geen categorieën. Maak een nieuwe categorie aan.</div>
    @endif
@endsection
