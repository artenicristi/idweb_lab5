<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="col-2">
        <i class="fa fa-face-smile mr-1"></i>
        <a class="navbar-brand" href="{{route('home')}}">
            Réclam
            <sub>dev</sub>
            <sup><small>test</small></sup>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="collapse navbar-collapse col-10" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{route('reclamations.index')}}">
                    <i class="fa fa-gears"></i>
                    Réclamations
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('pilotage.export')}}">
                    <i class="fa fa-compass"></i>
                    Pilotage
                </a>
            </li>
        </ul>
    </div>
</nav>
