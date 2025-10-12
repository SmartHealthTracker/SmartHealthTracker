@extends('layout.master')

@section('title','Challenges')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Challenges</h2>
<a href="{{ route('challenges.create') }}" class="btn btn-success btn-sm d-flex align-items-center justify-content-center" 
   style="padding: 0.25rem 0.5rem; font-size: 0.75rem; border-width: 1px;">
    <i class="mdi mdi-plus me-1" style="font-size: 0.8rem;"></i> Add Challenge
</a>

</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Participations</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($challenges as $challenge)
        <tr>
            <td>{{ $challenge->id }}</td>
            <td>{{ $challenge->name }}</td>
            <td>{{ $challenge->description }}</td>
            <td>{{ $challenge->start_date }}</td>
            <td>{{ $challenge->end_date }}</td>
            <td>{{ $challenge->participations->count() }}</td>
            <td>
                <a href="{{ route('challenges.edit', $challenge->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('challenges.destroy', $challenge->id) }}" method="POST" style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this challenge?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
