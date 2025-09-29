@extends('layout.master')

@section('content')
<!-- Hero Section -->
<section class="hero bg-light py-5" style="background-image: url('{{ url('assets/images/health-hero.jpg') }}'); background-size: cover; background-position: center;">
    <div class="container text-center text-white">
        <h1 class="display-4 fw-bold">Smart Health Tracker</h1>
        <p class="lead">Améliorez vos habitudes de vie, suivez votre bien-être et restez en bonne santé au quotidien.</p>
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg mt-3">Commencer maintenant</a>
    </div>
</section>

<!-- Features Section -->
<section class="features py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="feature-card p-4 shadow-sm rounded">
                    <img src="{{ url('assets/images/track.png') }}" alt="Suivi" class="mb-3" style="height: 80px;">
                    <h4 class="fw-bold">Suivi quotidien</h4>
                    <p>Enregistrez vos activités, sommeil et alimentation pour mieux comprendre vos habitudes.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card p-4 shadow-sm rounded">
                    <img src="{{ url('assets/images/insights.png') }}" alt="Insights" class="mb-3" style="height: 80px;">
                    <h4 class="fw-bold">Analyse & Conseils</h4>
                    <p>Recevez des recommandations personnalisées pour améliorer votre santé et votre bien-être.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card p-4 shadow-sm rounded">
                    <img src="{{ url('assets/images/community.png') }}" alt="Community" class="mb-3" style="height: 80px;">
                    <h4 class="fw-bold">Communauté</h4>
                    <p>Partagez vos progrès et découvrez les conseils de vos pairs pour rester motivé.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta py-5 bg-primary text-white text-center">
    <div class="container">
        <h2 class="fw-bold">Prêt à améliorer votre quotidien ?</h2>
        <p>Inscrivez-vous dès maintenant et commencez à suivre vos habitudes de vie.</p>
        <a href="{{ route('register') }}" class="btn btn-light btn-lg mt-3">S'inscrire</a>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white py-4">
    <div class="container text-center">
        <p>&copy; {{ date('Y') }} Smart Health Tracker. Tous droits réservés.</p>
    </div>
</footer>
@endsection
