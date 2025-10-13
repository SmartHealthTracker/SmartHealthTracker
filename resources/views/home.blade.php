@extends('layout.app')

@section('title', 'Accueil')

@section('content')
<main>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-light fixed-top shadow-lg">
        <div class="container">
            <a class="navbar-brand mx-auto d-lg-none" href="#">
                Medic Care
                <strong class="d-block">Health Specialist</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#hero">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#timeline">Timeline</a></li>
                    <li class="nav-item"><a class="nav-link" href="#reviews">Testimonials</a></li>
                    <li class="nav-item"><a class="nav-link" href="#booking">Booking</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>

                    @auth
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link" style="border:none; background:none;">
                                Logout
                            </button>
                        </form>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO / CAROUSEL -->
    <section class="hero" id="hero">
        <div class="container mt-5 pt-5">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('assets/images/slider/portrait-successful-mid-adult-doctor-with-crossed-arms.jpg') }}" class="d-block w-100" alt="Doctor 1">
                        <div class="carousel-caption d-none d-md-block">
                            <h1>Welcome to Medic Care</h1>
                            <p>Your health, our priority.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('assets/images/slider/young-asian-female-dentist-white-coat-posing-clinic-equipment.jpg') }}" class="d-block w-100" alt="Doctor 2">
                        <div class="carousel-caption d-none d-md-block">
                            <h1>Welcome to Medic Care</h1>
                            <p>Your health, our priority.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('assets/images/slider/doctor-s-hand-holding-stethoscope-closeup.jpg') }}" class="d-block w-100" alt="Doctor 3">
                        <div class="carousel-caption d-none d-md-block">
                            <h1>Welcome to Medic Care</h1>
                            <p>Your health, our priority.</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <!-- ABOUT DOCTOR -->
    <section class="section-padding" id="doctor">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6 col-12">
                    <h2 class="mb-3">Meet Dr. Carson</h2>
                    <p>Protect yourself and others by wearing masks and washing hands frequently. Outdoor is safer than indoor for gatherings or holding events. People who get sick with Coronavirus disease (COVID-19) will experience mild to moderate symptoms and recover without special treatments.</p>
                    <p>You can feel free to use this CSS template for your medical profession or health care related websites.</p>
                </div>
                <div class="col-lg-6 col-md-6 col-12 text-center">
                    <div class="rounded-circle shadow-lg d-flex flex-column justify-content-center align-items-center"
                        style="width: 250px; height: 250px; margin: auto; background: #fff;">
                        <h1 class="text-primary display-4 fw-bold">12</h1>
                        <p class="mb-0 fw-semibold">Years<br>of Experiences</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- RESSOURCES -->
    <section class="section-padding bg-light" id="ressources">
        <div class="container">
            <h2 class="mb-4 text-center">Nos Ressources</h2>

            @if($resources->isEmpty())
                <p class="text-center text-muted">Aucune ressource disponible pour le moment.</p>
            @else
                <div class="row">
                    @foreach($resources as $resource)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm resource-card" data-id="{{ $resource->id }}">
                                {{-- Image --}}
                                <img src="{{ $resource->image ? asset('storage/images/' . $resource->image) : asset('assets/images/default-resource.jpg') }}" 
                                     class="card-img-top" 
                                     alt="{{ $resource->title }}"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $resource->title }}</h5>
                                    <p class="card-text text-muted">{{ Str::limit($resource->content, 100) }}</p>
                                </div>
                                <div class="card-footer bg-white">
                                    <form action="{{ route('resources.comment', $resource->id) }}" method="POST">
                                        @csrf
                                        <textarea name="commentaire" class="form-control" placeholder="Ajouter un commentaire..." rows="2"></textarea>
                                        <button type="submit" class="btn btn-sm btn-primary w-100 mt-2">Publier</button>
                                    </form>

                                    @if($resource->comments->isNotEmpty())
                                        <hr>
                                        <h6>Commentaires :</h6>
                                        <ul class="list-group list-group-flush">
                                            @foreach($resource->comments as $comment)
                                                <li class="list-group-item">
                                                    <strong>{{ $comment->user->name ?? 'Anonyme' }}:</strong> {{ $comment->content }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- RESSOURCES RECOMMANDÉES -->
    @if(Auth::check() && isset($recommendedResources) && $recommendedResources->isNotEmpty())
    <section class="section-padding bg-light" id="recommended">
        <div class="container">
            <h2 class="mb-4 text-center">Ressources Recommandées Pour Vous</h2>
                        
            <div class="recommended-slider d-flex overflow-auto gap-3 py-2">
                @foreach($recommendedResources as $rec)
                <div class="card shadow-sm flex-shrink-0" style="width: 250px;">
                    <img src="{{ $rec->image ? asset('storage/images/' . $rec->image) : asset('assets/images/default-resource.jpg') }}"
                         class="card-img-top" alt="{{ $rec->title }}" style="height:150px; object-fit:cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $rec->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($rec->content, 80) }}</p>
                        <a href="{{ route('resources.show', $rec->id) }}" class="btn btn-primary btn-sm w-100">Voir</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- ABOUT -->
    <section class="section-padding" id="about">
        <div class="container">
            <h2>About Us</h2>
            <p>Medic Care is a leading healthcare provider with years of experience in medical services.</p>
        </div>
    </section>

    <!-- TIMELINE -->
    <section class="section-padding pb-0" id="timeline">
        <div class="container">
            <h2>Our Timeline</h2>
            <p>Important milestones and achievements.</p>
        </div>
    </section>

    <!-- REVIEWS -->
    <section class="section-padding pb-0" id="reviews">
        <div class="container">
            <h2>Testimonials</h2>
            <p>What our patients say about us.</p>
        </div>
    </section>

    <!-- BOOKING -->
    <section class="section-padding" id="booking">
        <div class="container">
            <h2>Book an Appointment</h2>
            <form action="#" method="post">
                <div class="row">
                    <div class="col-lg-6 col-12 mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Full name" required>
                    </div>
                    <div class="col-lg-6 col-12 mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="col-lg-6 col-12 mb-3">
                        <input type="tel" name="phone" class="form-control" placeholder="Phone">
                    </div>
                    <div class="col-lg-6 col-12 mb-3">
                        <input type="date" name="date" class="form-control">
                    </div>
                    <div class="col-12 mb-3">
                        <textarea name="message" rows="4" class="form-control" placeholder="Message"></textarea>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">Book Now</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

</main>

<!-- FOOTER -->
<footer class="site-footer section-padding" id="contact">
    <div class="container text-center">
        <p>&copy; 2025 Medic Care. Designed by TemplateMo.</p>
    </div>
</footer>
@endsection

@push('styles')
<style>
    /* Slider horizontal façon bande annonce */
    .recommended-slider {
        scroll-behavior: smooth;
    }

    .recommended-slider::-webkit-scrollbar {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ScrollSpy
        const scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#navbarNav',
            offset: 100
        });

        // IntersectionObserver pour enregistrer les vues des ressources
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.dataset.id;

                    fetch(`/resource/view/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    }).then(response => response.json())
                      .then(data => console.log('Vue enregistrée', data))
                      .catch(err => console.error(err));

                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('.resource-card').forEach(card => observer.observe(card));

        // Auto-scroll slider recommandé
        const slider = document.querySelector('.recommended-slider');
        if(slider) {
            let scrollAmount = 0;
            const scrollStep = 1;
            function autoScroll() {
                scrollAmount += scrollStep;
                if (scrollAmount >= slider.scrollWidth - slider.clientWidth) scrollAmount = 0;
                slider.scrollTo({ left: scrollAmount, behavior: 'smooth' });
                requestAnimationFrame(autoScroll);
            }
            autoScroll();
        }
    });
</script>
@endpush
