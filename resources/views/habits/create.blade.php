@extends('layout.master')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 grid-margin">
        <div class="card habit-card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="card-title mb-1">Ajouter une habitude</h4>
                        <p class="card-description mb-0 text-muted">
                            Personnalisez votre nouvelle routine en choisissant un type, une icône et des détails adaptés.
                        </p>
                    </div>
                    <div class="habit-icon-preview" id="panel-icon-preview">
                        <i class="mdi mdi-heart-pulse"></i>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="habit-form" action="{{ route('habits.store') }}" method="POST" novalidate>
                    @csrf
                    <input type="hidden" name="icon" id="icon" value="{{ old('icon') }}">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Nom de l'habitude</label>
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" placeholder="Ex : Yoga du matin" value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="schedule_time">Heure prévue</label>
                                <input type="time" class="form-control form-control-lg" id="schedule_time" name="schedule_time" value="{{ old('schedule_time') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="d-block">Choisissez un type</label>
                        <div class="type-grid" id="type-grid">
                            <button type="button" class="type-card" data-type="sleep" data-icon="mdi mdi-weather-night" data-color="#5e72e4">
                                <span class="type-icon"><i class="mdi mdi-weather-night"></i></span>
                                <span class="type-label">Sommeil</span>
                            </button>
                            <button type="button" class="type-card" data-type="sport" data-icon="mdi mdi-dumbbell" data-color="#ff5f6d">
                                <span class="type-icon"><i class="mdi mdi-dumbbell"></i></span>
                                <span class="type-label">Sport</span>
                            </button>
                            <button type="button" class="type-card" data-type="study" data-icon="mdi mdi-book-open-page-variant" data-color="#ffb547">
                                <span class="type-icon"><i class="mdi mdi-book-open-page-variant"></i></span>
                                <span class="type-label">Révision</span>
                            </button>
                            <button type="button" class="type-card" data-type="reading" data-icon="mdi mdi-book" data-color="#48c78e">
                                <span class="type-icon"><i class="mdi mdi-book"></i></span>
                                <span class="type-label">Lecture</span>
                            </button>
                            <button type="button" class="type-card" data-type="nutrition" data-icon="mdi mdi-silverware-fork-knife" data-color="#17a2b8">
                                <span class="type-icon"><i class="mdi mdi-silverware-fork-knife"></i></span>
                                <span class="type-label">Nutrition</span>
                            </button>
                        </div>
                        <select class="d-none @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Choisir le type</option>
                            <option value="sleep" @if(old('type')=='sleep') selected @endif>Sommeil</option>
                            <option value="sport" @if(old('type')=='sport') selected @endif>Sport</option>
                            <option value="study" @if(old('type')=='study') selected @endif>Révision</option>
                            <option value="reading" @if(old('type')=='reading') selected @endif>Lecture</option>
                            <option value="nutrition" @if(old('type')=='nutrition') selected @endif>Nutrition</option>
                        </select>
                        @error('type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group" id="duration-group">
                                <label for="duration">Durée (minutes)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" placeholder="Ex : 45" min="5" max="600" value="{{ old('duration') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">min</span>
                                    </div>
                                </div>
                                <small class="text-muted">Entre 5 et 600 minutes. Optionnel pour la nutrition.</small>
                                @error('duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Description (optionnel)</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Ajoutez un objectif ou une note">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('habits.index') }}" class="btn btn-light btn-lg">Annuler</a>
                        <button type="submit" class="btn btn-gradient btn-lg">Enregistrer l'habitude</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.habit-card { border: none; border-radius: 18px; }
.habit-icon-preview { width: 64px; height: 64px; border-radius: 18px; background: linear-gradient(135deg,#4f8af3,#6ad4c5); display:flex; align-items:center; justify-content:center; color:#fff; font-size:2rem; box-shadow:0 8px 15px rgba(79,138,243,0.3); }
.habit-icon-preview i { font-size:2rem; }
.type-grid { display:flex; flex-wrap:wrap; gap:12px; }
.type-card { flex:1 1 calc(50% - 12px); min-width:140px; border:1px solid #e0e0e0; border-radius:14px; padding:14px; background:#fafbff; display:flex; align-items:center; gap:12px; transition:all .2s ease; color:#4a4a4a; font-weight:600; }
.type-card .type-icon { font-size:1.5rem; }
.type-card.active { border-color:var(--type-accent, #4f8af3); background:#eef4ff; box-shadow:0 6px 14px rgba(79,138,243,0.2); color:#1f3c88; }
.type-card.active .type-icon { color:var(--type-accent, #4f8af3); }
.type-card:hover { transform:translateY(-2px); }
.btn-gradient { background:linear-gradient(135deg,#4f8af3,#6ad4c5); color:#fff; border:none; padding-left:26px; padding-right:26px; }
.btn-gradient:hover { color:#fff; box-shadow:0 10px 20px rgba(79,138,243,0.25); }
@media(max-width: 768px){
    .type-card{flex:1 1 100%;}
}
</style>
@endpush

@push('custom-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const iconInput = document.getElementById('icon');
    const durationGroup = document.getElementById('duration-group');
    const durationInput = document.getElementById('duration');
    const typeCards = document.querySelectorAll('.type-card');
    const panelPreview = document.getElementById('panel-icon-preview');

    const typeMeta = {};
    typeCards.forEach(card => {
        typeMeta[card.dataset.type] = {
            icon: card.dataset.icon,
            color: card.dataset.color
        };
        const iconSpan = card.querySelector('.type-icon');
        if (iconSpan) {
            iconSpan.style.color = card.dataset.color;
        }
    });

    function renderPreview(icon, color) {
        if (!panelPreview) return;
        if (icon && icon.startsWith('mdi')) {
            panelPreview.innerHTML = `<i class="${icon}" style="color:${color || '#4f8af3'}"></i>`;
        } else if (icon && (icon.startsWith('http://') || icon.startsWith('https://'))) {
            panelPreview.innerHTML = `<img src="${icon}" alt="icon" style="width:48px;height:48px;border-radius:14px;">`;
        } else {
            panelPreview.innerHTML = '<i class="mdi mdi-heart-pulse"></i>';
        }
    }

    function updateSelectedType() {
        const selectedType = typeSelect.value;
        const meta = typeMeta[selectedType] || {};

        if (meta.icon) {
            iconInput.value = meta.icon;
        }

        durationGroup.style.display = '';

        typeCards.forEach(card => {
            const isActive = card.dataset.type === selectedType;
            card.classList.toggle('active', isActive);
            card.style.setProperty('--type-accent', isActive ? (meta.color || card.dataset.color) : card.dataset.color);
        });

        const previewIcon = meta.icon || iconInput.value;
        renderPreview(previewIcon, meta.color || '#4f8af3');
    }

    typeSelect.addEventListener('change', updateSelectedType);

    typeCards.forEach(card => {
        card.addEventListener('click', () => {
            typeSelect.value = card.dataset.type;
            updateSelectedType();
        });
    });

    if (!typeSelect.value && iconInput.value) {
        renderPreview(iconInput.value, '#4f8af3');
    }

    updateSelectedType();

    document.getElementById('habit-form').addEventListener('submit', function(e) {
        let valid = true;
        const name = document.getElementById('name');
        if (!name.value.trim()) {
            name.classList.add('is-invalid');
            valid = false;
        } else {
            name.classList.remove('is-invalid');
        }

        if (!typeSelect.value) {
            typeSelect.classList.add('is-invalid');
            valid = false;
        } else {
            typeSelect.classList.remove('is-invalid');
        }

        if (typeSelect.value !== 'nutrition') {
            const val = parseInt(durationInput.value, 10);
            if (!val || val < 5 || val > 600) {
                durationInput.classList.add('is-invalid');
                valid = false;
            } else {
                durationInput.classList.remove('is-invalid');
            }
        } else {
            durationInput.classList.remove('is-invalid');
        }

        if (!valid) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
