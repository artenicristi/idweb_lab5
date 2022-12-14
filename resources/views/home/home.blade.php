@extends('layout.default')

@section('titre')
    Salut root !
@endsection

@section('headcontent')
    <div class="mb-3 pb-1 pt-1 border-bottom">
        <div class="row">
            <div class="col">
                <h2>@yield('titre')</h2>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col col-12">
            <p><small>Vous êtes sur la page d'accueil de l'application.</small></p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-9 col-md-6 col-xl-4">
            <div class="card">
                <div class="card border-gray-500">
                    <div class="card-header pt-2 pb-0 bg-gray">
                        <h6>
                            <i class="fa fa-chart-bar"></i>
                            informations sur les réclamations
                        </h6>
                    </div>
                </div>
                <table class="table table-sm table-borderless mt-2">
                    <tbody>
                        <tr>
                            <td style="width: 30%" class="text-right text-muted">
                                à traiter :
                            </td>
                            <td>923</td>
                        </tr>
                        <tr>
                            <td class="text-right text-muted">
                                en attente :
                            </td>
                            <td>45</td>
                        </tr>
                    </tbody>
                </table>
                <div class="card-body">
                    <p class="card-text text-right">
                        <a href="{{route('reclamations.index')}}" class="btn btn-sm btn-secondary" title="liste des réclamations">
                            <i class="fa fa-list"></i>
                            réclamations
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
