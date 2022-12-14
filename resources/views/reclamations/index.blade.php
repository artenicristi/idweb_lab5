@extends('layout.default')

@section('titre')
    Réclamations
@endsection

@section('headcontent')
    <div class="my-4 pb-1 pt-1 border-bottom text-white">
        <div class="row">
            <div class="col-9">
                <h2>@yield('titre')</h2>
            </div>
            <div class="col-3 text-right align-self-center create-btn">
                <a href="{{ route('reclamations.create') }}" class="btn btn-sm btn-primary align-self-center" title="ajouter une reclamation">
                    <i class="fas fa-circle-plus"></i>
                    créer une réclamation
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="sticky-top mb-4 sticky-search">
        <div class="rounded py-2 px-3 px-md-4 my-2 search-bar">
            <form method="get" action="{{route('reclamations.index')}}">
                <div class="row">
                    <div class="col-12 col-sm-3 col-md-3 col-xl-2">
                        <label for="recherche_reference" class="m-0"><strong>référence</strong></label>
                        <input id="recherche_reference" class="form-control form-control-sm" placeholder=""
                               name="reference_courte" value="{{request('reference_courte', '')}}">
                    </div>

                    <div class="col-12 col-sm-3 col-md-3 col-xl-2">
                        <label for="recherche_type" class="m-0"><strong>type</strong></label>
                        <select class="form-control custom-select custom-select-sm" name="type" id="recherche_type">
                            @if(request('type') !== '')
                                <option value="">tous</option>
                            @endif
                            @foreach(\App\Models\Reclamation::$types as $typeSlug => $typeLabel)
                                @if ($typeSlug !== '')
                                    <option class="bg_type_reclamation-{{$typeSlug}}" value="{{$typeSlug}}"
                                            @if($typeSlug === request('type')) selected @endif>{{$typeLabel}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3 col-xl-4">
                        <label for="recherche_coordonnees" class="m-0"><strong>coordonnées</strong></label>
                        <input id="recherche_coordonnees" class="form-control form-control-sm"
                               placeholder="nom, téléphone ou adresse email" name="coordonnees" value="">
                    </div>

                    <div class="col-12 col-sm-6 col-md-3 col-xl-4 text-right align-self-end">
                        <a href="{{route('reclamations.index')}}" class="btn btn-secondary btn-sm mr-1" title="remettre à zero le formulaire">
                            <i class="fas fa-delete-left"></i> RàZ
                        </a>
                        <button type="submit" class="btn btn-info btn-sm mr-1" title="rechercher">
                            <i class="fas fa-magnifying-glass"></i> rechercher
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
            <th>créé le</th>
            <th>actions</th>
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
                <td class="text-left">
                    <span class="badge px-2 badge-pill bg_type_reclamation-{{$reclamation->type}}">&nbsp</span>
                    {{\App\Models\Reclamation::$types[$reclamation->type]}}
                </td>
                <td class="text-left description">{{$reclamation->description}}</td>
                <td>{{$reclamation->created_at}}</td>

                <td>
                    <div class="row justify-content-center action-buttons">
                        <a href="{{ route('reclamations.edit', ['reclamation' => $reclamation->id]) }}"
                           class="btn mr-2 text-warning" id="show-btn">
                            <i class="fa fa-pen"></i>
                        </a>

                        <form method="POST"
                              action="{{route('reclamations.destroy', ['reclamation' => $reclamation->id])}}"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>

                    </div>
                </td>
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

@push('scripts')
@endpush

