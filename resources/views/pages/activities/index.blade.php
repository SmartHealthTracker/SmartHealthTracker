@extends('layout.master')
@section('title', 'Activités')
@section('content')
<div class="row">
  <div class="col-12">
    <a href="{{ route('activities.create') }}" class="btn btn-primary mb-3">Ajouter Activité</a>
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Calories par Heure</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($activities as $activity)
        <tr>
          <td>{{ $activity->name }}</td>
          <td>{{ $activity->calories_per_hour }}</td>
          <td>
            <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-sm btn-warning">Modifier</a>
            <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" style="display:inline;">
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
