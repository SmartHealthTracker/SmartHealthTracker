@extends('layout.master')

@section('content')
<div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Ajouter une habitude</h4>
            <p class="card-description">
                Sélectionnez le type via le menu déroulant et la durée. L'icône se choisira automatiquement selon le type.
            </p>

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

                <!-- Nom de l'habitude -->
                <div class="form-group">
                    <label for="name">Nom de l'habitude</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Ex: Faire du sport" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <!-- Sélecteur pour le type -->
                <div class="form-group">
                    <label for="type">Type d'habitude</label>
                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="">Choisir le type</option>
                        <option value="sleep" @if(old('type')=='sleep') selected @endif>Sommeil</option>
                        <option value="sport" @if(old('type')=='sport') selected @endif>Sport</option>
                        <option value="study" @if(old('type')=='study') selected @endif>Révision</option>
                        <option value="reading" @if(old('type')=='reading') selected @endif>Lecture</option>
                        <option value="nutrition" @if(old('type')=='nutrition') selected @endif>Nutrition</option>
                    </select>
                    <div id="selected-type" class="mt-2 font-weight-bold"></div>
                    @error('type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <!-- Durée (uniquement si applicable) -->
                <div class="form-group" id="duration-group">
                    <label for="duration">Durée (minutes)</label>
                    <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" placeholder="Ex: 60" min="5" max="600" value="{{ old('duration') }}">
                    <small class="text-muted">Entre 5 min et 600 min (10h). Laisser vide pour les habitudes comme la nutrition.</small>
                    @error('duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <!-- Prévisualisation de l'icône -->
                <div class="form-group" id="icon-preview-group" style="display:none;">
                    <label>Icône sélectionnée</label><br>
                    <img id="icon-preview" src="" alt="Icône" style="width:40px;height:40px;">
                </div>

                <!-- Heure prévue -->
                <div class="form-group">
                    <label for="schedule_time">Heure prévue</label>
                    <input type="time" class="form-control" id="schedule_time" name="schedule_time" value="{{ old('schedule_time') }}">
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description (optionnel)</label>
                    <textarea class="form-control" id="description" name="description" rows="2">{{ old('description') }}</textarea>
                </div>


                <button type="submit" class="btn btn-success">Ajouter</button>
                <a href="{{ route('habits.index') }}" class="btn btn-light">Annuler</a>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const defaultIcons = {
        sleep: 'https://cdn-icons-png.flaticon.com/512/1680/1680899.png',
        sport: 'https://cdn-icons-png.flaticon.com/512/1041/1041916.png',
        study: 'https://cdn-icons-png.flaticon.com/512/3135/3135768.png',
        reading: 'https://cdn-icons-png.flaticon.com/512/167/167755.png',
        nutrition: 'https://cdn-icons-png.flaticon.com/512/135/135763.png'
    };

    const typeSelect = document.getElementById('type');
    const iconInput = document.getElementById('icon');
    const durationGroup = document.getElementById('duration-group');
    const durationInput = document.getElementById('duration');
    const selectedTypeDiv = document.getElementById('selected-type');
    const iconPreviewGroup = document.getElementById('icon-preview-group');
    const iconPreview = document.getElementById('icon-preview');

    // Affichage du type sélectionné
    function updateSelectedType() {
        const selectedType = typeSelect.value;
        selectedTypeDiv.textContent = selectedType ? 'Type sélectionné : ' + typeSelect.options[typeSelect.selectedIndex].text : '';
        iconInput.value = defaultIcons[selectedType] || '';
        if (iconInput.value) {
            iconPreview.src = iconInput.value;
            iconPreviewGroup.style.display = '';
        } else {
            iconPreviewGroup.style.display = 'none';
        }
        if (selectedType === 'nutrition') {
            durationGroup.style.display = 'none';
            durationInput.value = '';
        } else {
            durationGroup.style.display = '';
        }
    }
    typeSelect.addEventListener('change', updateSelectedType);
    updateSelectedType();

    // Contrôle JS côté client avant soumission
    document.getElementById('habit-form').addEventListener('submit', function(e) {
        let valid = true;
        // Nom obligatoire
        const name = document.getElementById('name');
        if (!name.value.trim()) {
            name.classList.add('is-invalid');
            valid = false;
        } else {
            name.classList.remove('is-invalid');
        }
        // Type obligatoire
        if (!typeSelect.value) {
            typeSelect.classList.add('is-invalid');
            valid = false;
        } else {
            typeSelect.classList.remove('is-invalid');
        }
        // Durée obligatoire sauf nutrition, min 5 max 600
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
@endsection
