<!-- resources/views/pages/activities/edit.blade.php -->
@extends('layout.master')
@section('title', 'Modifier Activité')
@section('content')
<div class="row">
  <div class="col-12">
    <h1>Modifier l'Activité</h1>
    <form action="{{ route('activities.update', $activity->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="form-group">
        <label for="name">Nom</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ $activity->name }}" required>
      </div>
      <div class="form-group">
        <label for="calories_per_hour">Calories par Heure</label>
        <input type="number" name="calories_per_hour" id="calories_per_hour" class="form-control" value="{{ $activity->calories_per_hour }}" required min="0">
      </div>
      <button type="submit" class="btn btn-primary">Mettre à Jour</button>
    </form>
  </div>
</div>
@endsection