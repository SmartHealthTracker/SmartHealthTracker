@extends('layout.master')

@section('content')
<div class="col-lg-6 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Ajouter une habitude</h4>
            <p class="card-description">
                Sélectionnez le type via le menu déroulant et la durée. L'icône se choisira automatiquement selon le type.
            </p>

            <form action="{{ route('habits.store') }}" method="POST">
                @csrf

                <!-- Nom de l'habitude -->
                <div class="form-group">
                    <label for="name">Nom de l'habitude</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Ex: Faire du sport" required>
                </div>

                <!-- Sélecteur pour le type -->
                <div class="form-group">
                    <label for="type">Type d'habitude</label>
                    <select class="form-control" id="type" name="type" required>
                        <option value="">Choisir le type</option>
                        <option value="sleep">Sommeil</option>
                        <option value="sport">Sport</option>
                        <option value="study">Révision</option>
                        <option value="reading">Lecture</option>
                        <option value="nutrition">Nutrition</option>
                    </select>
                    <div id="selected-type" class="mt-2 font-weight-bold"></div>
                </div>

                <!-- Durée (uniquement si applicable) -->
                <div class="form-group" id="duration-group">
                    <label for="duration">Durée (minutes)</label>
                    <input type="number" class="form-control" id="duration" name="duration" placeholder="Ex: 60" min="1">
                    <small class="text-muted">Laisser vide pour les habitudes comme la nutrition.</small>
                </div>

                <!-- Prévisualisation de l'icône -->
                <div class="form-group" id="icon-preview-group" style="display:none;">
                    <label>Icône sélectionnée</label><br>
                    <img id="icon-preview" src="" alt="Icône" style="width:40px;height:40px;">
                </div>

                <!-- Heure prévue -->
                <div class="form-group">
                    <label for="schedule_time">Heure prévue</label>
                    <input type="time" class="form-control" id="schedule_time" name="schedule_time">
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description (optionnel)</label>
                    <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                </div>

                <!-- Champ caché pour icône -->
                <input type="hidden" name="icon" id="icon">

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

    typeSelect.addEventListener('change', function() {
        const selectedType = this.value;

        iconInput.value = defaultIcons[selectedType] || '';
        selectedTypeDiv.textContent = selectedType ? 'Type sélectionné : ' + this.options[this.selectedIndex].text : '';

        if (selectedType === 'nutrition') {
            durationGroup.style.display = 'none';
            durationInput.value = '';
        } else {
            durationGroup.style.display = '';
        }

        if (iconInput.value) {
            iconPreview.src = iconInput.value;
            iconPreviewGroup.style.display = '';
        } else {
            iconPreviewGroup.style.display = 'none';
        }
    });
});
</script>
@endsection
