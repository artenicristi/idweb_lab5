@extends('layout.default')

@section('titre', "Présentation de l'application Réclam")

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
        <div class="col-12">
            <h3>Utilité de l'application</h3>
            <p><small>
                    L'application réclamation permet de gérer les réclamations sur les annonces faites par les utilisateurs de nos sites.</small></p>
            <h3>Besoins</h3>
            <ul>
                <li>
                    en tant que site web, j'ai besoin de créer une nouvelle réclamation via api
                </li>
                <li>
                    en tant qu'utilisateur back-office, j'ai besoin de traiter les réclamation (workflow)
                </li>
            </ul>
            <p>
                <a href="{{route('home')}}" class="btn btn-primary" title="accéder à l'application">
                    accéder à l'application
                    <i class="fas fa-arrow-pointer"></i>
                </a>
            </p>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
