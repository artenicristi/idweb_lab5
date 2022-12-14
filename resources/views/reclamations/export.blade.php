@extends('layout.default')

@section('titre')
    export des réclamations
@endsection

@section('headcontent')
    <div class="my-4 pb-1 pt-1 border-bottom text-white">
        <div class="row">
            <div class="col-9">
                <h2>@yield('titre')</h2>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="sticky-top mb-3 sticky-search">
        <div class="rounded py-2 px-3 px-md-4 my-2 search-bar">
            <form method="get" action="{{route('pilotage.export')}}" class="m-0">
                <div class="row">
                    <div class="input-group input-group-sm col-sm-6 col-lg-4 col-xl-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="date_debut">du</label>
                        </div>
                        <input type="date" class="form-control form-control-sm" name="date_debut" id="date_debut" value="{{request('date_debut')}}">
                    </div>
                    <div class="input-group input-group-sm col-sm-6 col-lg-4 col-xl-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="date_fin">au</label>
                        </div>
                        <input type="date" class="form-control form-control-sm" name="date_fin" id="date_fin" value="{{request('date_fin')}}">
                    </div>
                    <div class="col-lg-4 mt-2 mt-lg-0 text-right text-lg-left">
                        <button type="submit" class="btn btn-info btn-sm mr-1" title="rechercher">
                            <i class="fas fa-list"></i> afficher
                        </button>
                        <button type="submit" name="download_csv" value="1" class="btn btn-secondary btn-sm" title="télécharger le CSV">
                            <i class="fas fa-file-csv"></i>  télécharger le CSV
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <table class="table text-white" id="product-table">
        <thead class="thead-dark">
        <tr class="text-center">
            <th>id</th>
            <th>nom</th>
            <th>téléphone</th>
            <th>email</th>
            <th>référence</th>
            <th>type</th>
            <th>description</th>
            <th>created_at</th>
            <th>updated_at</th>
        </tr>
        </thead>
        <tbody>

        @foreach($reclamations as $reclamation)
            <tr class="text-center">
                <td>{{$reclamation->id}}</td>
                <td>{{$reclamation->nom}}</td>
                <td>{{$reclamation->telephone}}</td>
                <td>{{$reclamation->email}}</td>
                <td>{{$reclamation->reference}}</td>
                <td class="text-left">{{$reclamation->type}}</td>
                <td class="text-left description">{{$reclamation->description}}</td>
                <td>{{$reclamation->created_at}}</td>
                <td>{{$reclamation->updated_at}}</td>

            </tr>
        @endforeach

        </tbody>
    </table>

    <div class="container-fluid">
        <div class="row justify-content-start mt-2 mb-5">
            {{$reclamations->links()}}
        </div>
    </div>

@endsection
