@extends('layout.master')

@section('title', 'Challenge Details')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm p-4">
        <h3 class="mb-3 text-primary">{{ $challenge->name }}</h3>

        <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($challenge->start_date)->format('d M Y') }}</p>
        <p><strong>End Date:</strong> {{ \Carbon\Carbon::parse($challenge->end_date)->format('d M Y') }}</p>

        <hr>

        <h5 class="mb-3">Participants</h5>
        @if($challenge->participations->count() > 0)
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Age</th>
                        <th>Weight</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($challenge->participations as $index => $participation)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $participation->user->name ?? 'N/A' }}</td>
                            <td>{{ $participation->age }}</td>
                            <td>{{ $participation->weight }}</td>
                            <td>
                                <span class="badge 
                                    @if($participation->status === 'approved') bg-success
                                    @elseif($participation->status === 'pending') bg-warning
                                    @else bg-danger @endif">
                                    {{ ucfirst($participation->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted">No participants yet for this challenge.</p>
        @endif

        <div class="mt-4">
            <a href="{{ route('challenges.index') }}" class="btn btn-secondary">← Back to Challenges</a>
        </div>
    </div>
</div>
@endsection
