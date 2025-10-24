<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Health Tracker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #4f8af3;
            --secondary: #6ad4c5;
            --dark: #11142d;
            --text-muted: #6c7a91;
            --bg-soft: #f5f7ff;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--bg-soft);
            color: var(--dark);
        }

        a { text-decoration: none; }

        .landing-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 6vw;
        }

        .brand {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark);
        }

        .nav-links {
            display: flex;
            gap: 24px;
        }

        .nav-links a {
            color: var(--text-muted);
            font-weight: 500;
        }

        .nav-actions {
            display: flex;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border-radius: 999px;
            font-weight: 600;
            transition: all .2s ease;
        }

        .btn-outline {
            border: 1px solid rgba(17, 20, 45, 0.1);
            color: var(--dark);
            background: transparent;
        }

        .btn-fill {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            border: none;
            box-shadow: 0 10px 25px rgba(79, 138, 243, .25);
        }

        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .btn-fill:hover { transform: translateY(-2px); }

        .hero {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            padding: 50px 6vw 30px;
            gap: 40px;
        }

        .hero-text { flex: 1 1 420px; }

        .hero-title {
            font-size: clamp(2.4rem, 4vw, 3.4rem);
            margin-bottom: 20px;
        }

        .hero-subtitle {
            color: var(--text-muted);
            font-size: 1.05rem;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        .hero-actions { display: flex; gap: 16px; flex-wrap: wrap; }

        .hero-visual {
            flex: 1 1 420px;
            position: relative;
        }

        .hero-card {
            position: relative;
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(17, 20, 45, .15);
        }

        .hero-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .floating-metric {
            position: absolute;
            top: 24px;
            left: 24px;
            background: rgba(255, 255, 255, .9);
            backdrop-filter: blur(6px);
            border-radius: 16px;
            padding: 16px 20px;
            box-shadow: 0 12px 30px rgba(17, 20, 45, .1);
        }

        .floating-metric strong { display: block; font-size: 1.2rem; }
        .floating-metric span { font-size: .9rem; color: var(--text-muted); }

        .features {
            padding: 60px 6vw 40px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 32px;
        }

        .section-header h2 { margin: 0; font-size: 2rem; }
        .section-header p { color: var(--text-muted); max-width: 540px; }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
        }

        .feature-card {
            background: #fff;
            border-radius: 24px;
            padding: 26px;
            box-shadow: 0 20px 40px rgba(17, 20, 45, 0.08);
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .feature-icon {
            width: 54px;
            height: 54px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #fff;
        }

        .feature-card:nth-child(1) .feature-icon { background: #4f8af3; }
        .feature-card:nth-child(2) .feature-icon { background: #6ad4c5; }
        .feature-card:nth-child(3) .feature-icon { background: #ffb547; }
        .feature-card:nth-child(4) .feature-icon { background: #ff6f91; }

        .resources-section,
        .recommended-section {
            padding: 60px 6vw;
        }

        .resources-section h2,
        .recommended-section h2 { margin-bottom: 12px; }

        .resources-section p.description,
        .recommended-section p.description { color: var(--text-muted); margin-bottom: 32px; }

        .resource-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
        }

        .resource-card {
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 12px 30px rgba(17, 20, 45, .08);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .resource-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(17,20,45,0.12); }

        .resource-card img {
            width: 100%;
            height: 170px;
            object-fit: cover;
        }

        .resource-body { padding: 20px 22px; flex: 1; }
        .resource-body h3 { margin: 0 0 10px; font-size: 1.1rem; }
        .resource-body p { margin: 0; color: var(--text-muted); font-size: .95rem; }

        .resource-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 14px;
            font-size: .85rem;
            color: var(--text-muted);
        }

        .resource-category {
            display: inline-block;
            background: rgba(79,138,243,.12);
            color: #1f3c88;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: .75rem;
            margin-bottom: 12px;
        }

        .comment-box {
            border-top: 1px solid rgba(17,20,45,0.08);
            padding: 16px 22px 22px;
        }

        .comment-box textarea {
            width: 100%;
            border-radius: 12px;
            border: 1px solid rgba(17,20,45,0.12);
            padding: 10px 12px;
            font-family: inherit;
            resize: vertical;
            min-height: 72px;
        }

        .comment-box button {
            margin-top: 12px;
            width: 100%;
            border: none;
            border-radius: 999px;
            padding: 10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            font-weight: 600;
        }

        .recommended-slider {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding-bottom: 6px;
            scroll-snap-type: x mandatory;
        }

        .recommended-slider::-webkit-scrollbar { display: none; }

        .recommended-card {
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 12px 24px rgba(17, 20, 45, .08);
            flex: 0 0 260px;
            scroll-snap-align: start;
            overflow: hidden;
        }

        .recommended-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .recommended-card .content { padding: 18px 20px; }

        .workout-section {
            margin: 60px 6vw;
            padding: 48px;
            border-radius: 28px;
            background: linear-gradient(135deg, rgba(79,138,243,0.12), rgba(106,212,197,0.12));
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .workout-section h3 { font-size: 2rem; margin-bottom: 12px; }
        .workout-section p { color: var(--text-muted); max-width: 600px; }

        .cta-section {
            margin: 60px 6vw;
            background: linear-gradient(135deg, rgba(79,138,243,0.12), rgba(106,212,197,0.12));
            border-radius: 28px;
            padding: 48px;
            text-align: center;
        }

        .cta-section h3 { font-size: 2rem; margin-bottom: 12px; }
        .cta-section p { color: var(--text-muted); margin-bottom: 26px; }

        footer {
            text-align: center;
            padding: 24px 0 40px;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        @media (max-width: 992px) {
            .hero { padding-top: 20px; }
            .workout-section { padding: 32px; }
        }

        @media (max-width: 768px) {
            .landing-nav { flex-direction: column; gap: 16px; }
            .nav-links { flex-wrap: wrap; justify-content: center; }
            .nav-actions { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <nav class="landing-nav">
        <a href="{{ route('home') }}" class="brand">SmartHealthTracker</a>
        <div class="nav-links">
            <a href="#resources">Resources</a>
            <a href="#features">Features</a>
            <a href="#workout">Workout</a>
            <a href="{{ route('chatbot.index') }}">AI Coach</a>
        </div>
        <div class="nav-actions">
            @guest
                <a class="btn btn-outline" href="{{ route('login') }}">Log in</a>
                <a class="btn btn-fill" href="{{ route('register.form') }}">Sign up</a>
            @else
                <a class="btn btn-outline" href="{{ route('health.index') }}">Sign up</a>
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn btn-fill" style="border:none; cursor:pointer;">Log out</button>
                </form>
            @endguest
        </div>
    </nav>

    <section class="hero">
        <div class="hero-text">
            <h1 class="hero-title">Elevate your health and fitness routine</h1>
            <p class="hero-subtitle">
                Plan your workouts, track your recovery, receive personalized tips, and stay motivated every single day.
            </p>
            <div class="hero-actions">
                <a href="{{ route('habits.create') }}" class="btn btn-fill">Create a habit</a>
                <a href="{{ route('health.index') }}" class="btn btn-outline">View health dashboard</a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-card">
                <img src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&w=1200&q=80" alt="Fitness training">
                <div class="floating-metric">
                    <strong>+24%</strong>
                    <span>consistency gain in 30 days</span>
                </div>
            </div>
        </div>
    </section>

    <section class="features" id="features">
        <div class="section-header">
            <h2>Everything you need to succeed</h2>
            <p>Smart Health Tracker combines habit coaching, nutrition analytics, motivational insights, and performance reporting in one elegant platform.</p>
        </div>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-activity"></i></div>
                <h4>Training tracker</h4>
                <p>Log your sessions, monitor fatigue and track improvements across strength and cardio disciplines.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-water"></i></div>
                <h4>Wellness habits</h4>
                <p>Hydration, sleep, mindfulness and nutrition habits all in sync with your objectives.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-bar-chart"></i></div>
                <h4>Predictive reports</h4>
                <p>Visualize trends, forecast your progress and keep an eye on key health indicators.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-chat-dots"></i></div>
                <h4>Motivational AI</h4>
                <p>Daily quotes, weather-based recommendations and personalized reminders keep you focused.</p>
            </div>
        </div>
    </section>

    <section class="resources-section" id="resources">
        <div class="section-header">
            <div>
                <h2>Nos ressources</h2>
                <p class="description">Explore curated articles, guides and insights created by our health experts and community coaches.</p>
            </div>
        </div>
        @if(isset($resources) && $resources->isNotEmpty())
            <div class="resource-grid">
                @foreach($resources as $resource)
                    <article class="resource-card" data-id="{{ $resource->id }}">
                        <img src="{{ $resource->image ? asset('storage/images/' . $resource->image) : asset('assets/images/default-resource.jpg') }}" alt="{{ $resource->title }}">
                        <div class="resource-body">
                            @if(!empty($resource->category))
                                <span class="resource-category">{{ $resource->category }}</span>
                            @endif
                            <h3>{{ $resource->title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($resource->content), 120) }}</p>
                            <div class="resource-meta">
                                <span><i class="bi bi-calendar-event"></i> {{ optional($resource->created_at)->diffForHumans() }}</span>
                                <span><i class="bi bi-chat"></i> {{ $resource->comments->count() ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="comment-box">
                            @auth
                                <form action="{{ route('resources.comment', $resource->id) }}" method="POST">
                                    @csrf
                                    <textarea name="commentaire" placeholder="Add a quick note..."></textarea>
                                    <button type="submit">Publish comment</button>
                                </form>
                                @if($resource->comments->isNotEmpty())
                                    <div class="existing-comments">
                                        @foreach($resource->comments->take(2) as $comment)
                                            <div class="comment-item">
                                                <strong>{{ optional($comment->user)->name ?? 'Utilisateur' }}</strong>
                                                <span class="comment-date">{{ optional($comment->created_at)->diffForHumans() }}</span>
                                                <p>{{ $comment->content }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <p class="text-muted" style="margin:0;">Connectez-vous pour laisser un commentaire.</p>
                            @endauth
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="text-muted">No resources available yet. Check back soon!</p>
        @endif
    </section>

    @auth
        @if(isset($recommendedResources) && $recommendedResources->isNotEmpty())
            <section class="recommended-section" id="recommended">
                <h2>Ressources recommandées</h2>
                <p class="description">Basées sur vos lectures récentes et vos objectifs déclarés.</p>
                <div class="recommended-slider">
                    @foreach($recommendedResources as $rec)
                        <div class="recommended-card resource-card" data-id="{{ $rec->id }}">
                            <img src="{{ $rec->image ? asset('storage/images/' . $rec->image) : asset('assets/images/default-resource.jpg') }}" alt="{{ $rec->title }}">
                            <div class="content">
                                <h4 style="margin:0 0 8px;">{{ $rec->title }}</h4>
                                <p style="margin:0; color:var(--text-muted); font-size:.9rem;">{{ \Illuminate\Support\Str::limit(strip_tags($rec->content), 90) }}</p>
                                <div class="comment-count"><i class="bi bi-chat"></i> {{ $rec->comments->count() }} commentaires</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    @endauth

    <section class="workout-section" id="workout">
        <div>
            <h3>Need a fresh workout plan?</h3>
            <p>Tell us your objectives, available equipment and training frequency. Our generator will craft a balanced program including warm-up, main sets and recovery tips.</p>
        </div>
        <a href="{{ url('/workout') }}" class="btn btn-fill" style="padding:14px 32px;">Generate my plan</a>
    </section>

    <section class="cta-section">
        <h3>Start your transformation today</h3>
        <p>Create your free account and join a community that chooses consistency over excuses.</p>
        <a href="{{ url('/user-pages/register') }}" class="btn btn-fill">Sign up now</a>
    </section>

    <footer>
        &copy; {{ date('Y') }} SmartHealthTracker — wellness, performance and balance.
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.resource-card');
            if (cards.length) {
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
                            }).catch(err => console.error(err));
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.5 });

                cards.forEach(card => observer.observe(card));
            }

            const slider = document.querySelector('.recommended-slider');
            if (slider && slider.children.length > 2) {
                let scrollAmount = 0;
                const scrollStep = 1.2;
                function autoScroll() {
                    scrollAmount += scrollStep;
                    if (scrollAmount >= slider.scrollWidth - slider.clientWidth) {
                        scrollAmount = 0;
                    }
                    slider.scrollTo({ left: scrollAmount, behavior: 'smooth' });
                    requestAnimationFrame(autoScroll);
                }
                autoScroll();
            }
        });
    </script>
</body>
</html>
