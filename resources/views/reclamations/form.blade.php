@extends('layout.default')

@php
    $isCreate = \Illuminate\Support\Facades\Request::url() == route('reclamations.create');
@endphp

@section('titre')
    {{$isCreate ? 'Créer une réclamation' : 'Modifier la réclamation #' . $reclamation->id}}
@endsection

@section('headcontent')
    <div class="mb-3 pb-1 pt-1 border-bottom">
        <div class="row">
            <div class="col-9">
                <h2>@yield('titre')</h2>
            </div>
            <div class="col-3 text-right align-self-center">
                <a href="{{route('reclamations.index')}}" class="btn btn-sm btn-secondary mr-1" title="annuler">
                    <i class="fas fa-ban"></i><span class="d-none d-lg-inline"> annuler</span>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <div class="row my-5">
        <div class="col-6 p-5 box fit-content reclamation"
             data-reclamation={{$reclamation->id ?? ''}}>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{$isCreate ? route('reclamations.store') : route('reclamations.update', ['reclamation' => $reclamation->id])}}"
                method="POST">
                @csrf
                @method($isCreate ? "POST" : "PUT")

                <h5>origine de la réclamation </h5>

                <div class="form-group row my-4">
                    <label for="nom" class="col-sm-2 col-form-label">nom</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom"
                               placeholder="nom" name="nom"
                               value="{{old('nom', $reclamation->nom ?? '')}}">
                        @error('nom')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="form-group row my-4">
                    <label for="telephone" class="col-sm-2 col-form-label">télephone</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone"
                               placeholder="télephone" name="telephone"
                               value="{{old('telephone', $reclamation->telephone ?? '')}}">
                        @error('telephone')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="form-group row my-4">
                    <label for="email" class="col-sm-2 col-form-label">email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                               placeholder="email" name="email"
                               value="{{old('email', $reclamation->email ?? '')}}">
                        @error('email')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <h5>objet de la réclamation </h5>
                <div class="form-group row my-3">
                    <label for="reference" class="col-sm-2 col-form-label">référence de l'annonce</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('reference') is-invalid @enderror" id="reference"
                               placeholder="référence courte" name="reference"
                               value="{{old('reference', $reclamation->reference ?? '')}}">
                        @error('reference')
                        <p class="text-danger">Le champ 'reference courte' est requis.</p>
                        @enderror
                    </div>
                </div>

                <div class="form-group row my-3">
                    <label for="type" class="col-sm-2 col-form-label">type de réclamation</label>
                    <div class="col-sm-10">
                        <select class="custom-select mr-sm-2 col @error('type') is-invalid @enderror" id="type" name="type">
                            @foreach(\App\Models\Reclamation::$types as $typeSlug => $typeLabel)
                                <option class="bg_type_reclamation-{{$typeSlug}}" value="{{ $typeSlug }}" @if($typeSlug === old('type', $reclamation->type ?? '')) selected @endif>
                                    {{ $typeLabel }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-group row my-3">
                    <label for="description" class="col-sm-2 col-form-label">description</label>
                    <div class="col-sm-10">
                        <textarea class="form-control col @error('description') is-invalid @enderror"
                                  id="description" rows="3"
                                  name="description">{{old('description', $reclamation->description ?? '')}}</textarea>
                        @error('description')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12 d-flex justify-content-end">
                        <a href="{{route('reclamations.index')}}" class="btn btn-sm btn-secondary mr-1" title="annuler">
                            <i class="fas fa-ban"></i><span class="d-none d-lg-inline"> annuler</span>
                        </a>
                        <button type="submit" class="btn btn-sm btn-primary mr-1 submit" title="enregistrer">
                            <i class="fas fa-check"></i>
                            <span class="d-none d-lg-inline">enregistrer</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-6 fit-content">
            <section class="comment">
                <div class="container py-2">
                    <div class="row d-flex justify-content-center">
                        <div class="col-12">
                            <div class="card text-dark">
                                <h4 class="p-3">Commentaires récents</h4>
                                <hr class="my-0" />
                                <div class="comments-list">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="comment">
                <div class="container p-3">
                    <div class="row d-flex justify-content-center">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-start align-items-center">
                                        <h5 class="fw-bold text-primary mb-1">Ajouter un commentaire</h5>
                                    </div>
                                </div>
                                <div class="card-footer py-3 border-0 bg-white">
                                    <div class="d-flex flex-start w-100">
                                        <div class="form-outline w-100">
                                            <textarea class="form-control" id="comment" rows="4" required="required" {{$isCreate ? "disabled" : ''}}></textarea>
                                            <p class="text-danger d-none">Le commentaire ne doit pas être vide</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 pt-1 text-right">
                                        <button type="button" class="btn btn-primary btn-sm post-btn" {{$isCreate ? "disabled" : ''}}>
                                            <i class="fas fa-circle-plus"></i>
                                            Poster
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
@endsection

@push('scripts')
    <script src="{{asset('scripts/comments.js')}}"></script>
@endpush
