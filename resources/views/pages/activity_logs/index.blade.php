@extends('layout.master')
@section('title', 'Journaux d’Activités')
@section('content')
<div class="row">
  <div class="col-12">
    <a href="{{ route('activity_logs.create') }}" class="btn btn-primary mb-3">Ajouter Journal</a>
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Activité</th>
          <th>Utilisateur</th>
          <th>Durée (min)</th>
          <th>Calories Brûlées</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($logs as $log)
        <tr>
          <td>{{ $log->activity->name }}</td>
          <td>{{ $log->user->name }}</td>
          <td>{{ $log->duration }}</td>
          <td>{{ $log->calories_burned }}</td>
          <td>{{ $log->date }}</td>
          <td>
            <a href="{{ route('activity_logs.edit', $log->id) }}" class="btn btn-sm btn-warning">Modifier</a>
            <form action="{{ route('activity_logs.destroy', $log->id) }}" method="POST" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer?')">Supprimer</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection