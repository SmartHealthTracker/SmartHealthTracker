@extends('layout.master')
@section('title', 'Modifier Journal d’Activité')
@section('content')
<div class="row">
  <div class="col-12">
    <h1>Modifier le Journal d’Activité</h1>
    <form action="{{ route('activity_logs.update', $activity_log->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="form-group">
        <label for="activity_id">Activité</label>
        <select name="activity_id" id="activity_id" class="form-control" required>
          @foreach($activities as $activity)
            <option value="{{ $activity->id }}" {{ $activity_log->activity_id == $activity->id ? 'selected' : '' }}>{{ $activity->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label for="user_id">Utilisateur</label>
        <select name="user_id" id="user_id" class="form-control" required>
          @foreach($users as $user)
            <option value="{{ $user->id }}" {{ $activity_log->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label for="duration">Durée (minutes)</label>
        <input type="number" name="duration" id="duration" class="form-control" value="{{ $activity_log->duration }}" required min="1">
      </div>
      <div class="form-group">
        <label for="date">Date</label>
        <input type="date" name="date" id="date" class="form-control" value="{{ $activity_log->date }}" required>
      </div>
      <button type="submit" class="btn btn-primary">Mettre à Jour</button>
    </form>
  </div>
</div>
@endsection