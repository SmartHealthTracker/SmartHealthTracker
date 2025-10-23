<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenge Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-calendar {
            table-layout: fixed;
        }
        .calendar-event {
            font-size: 0.75rem;
            cursor: pointer;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-bottom: 2px;
            padding: 2px 4px;
            border-radius: 3px;
            color: white;
        }
        .calendar-event:hover {
            opacity: 0.8;
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }
        .calendar-day {
            height: 150px;
            vertical-align: top;
        }
        .other-month {
            background-color: #f8f9fa;
            color: #6c757d;
        }
        .today {
            background-color: #e8f4f8;
            border: 2px solid #0d6efd;
        }
        .navbar {
            margin-bottom: 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .calendar-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .month-navigation {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
        }
        .challenge-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        .challenge-card:hover {
            transform: translateY(-5px);
        }
        .calendar-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .day-number {
            font-weight: bold;
            font-size: 1.1em;
        }
        .weekday-header {
            background: #2c3e50;
            color: white;
            font-weight: 600;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <!-- Simple Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <strong>📅 Challenge Calendar</strong>
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="javascript:history.back()">
                    ← Back to Previous Page
                </a>
                <a class="nav-link text-white" href="/challenges">View All Challenges</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <!-- Calendar Header -->
                <div class="calendar-header text-center p-4 mb-4">
                    <h1 class="display-6 fw-bold mb-2">Challenge Calendar</h1>
                    <p class="lead mb-0">Track your fitness challenges and activities</p>
                </div>

                <!-- Month Navigation -->
                <div class="month-navigation mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            @php
                                $prevMonth = $month - 1;
                                $prevYear = $year;
                                if ($prevMonth == 0) {
                                    $prevMonth = 12;
                                    $prevYear = $year - 1;
                                }
                                
                                $nextMonth = $month + 1;
                                $nextYear = $year;
                                if ($nextMonth == 13) {
                                    $nextMonth = 1;
                                    $nextYear = $year + 1;
                                }
                            @endphp
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('challenges.calendar', ['month' => $prevMonth, 'year' => $prevYear]) }}" 
                                   class="btn btn-outline-primary btn-lg">
                                    ← {{ DateTime::createFromFormat('!m', $prevMonth)->format('F') }}
                                </a>
                                
                                <h2 class="text-center mb-0 fw-bold text-dark">
                                    {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}
                                </h2>
                                
                                <a href="{{ route('challenges.calendar', ['month' => $nextMonth, 'year' => $nextYear]) }}" 
                                   class="btn btn-outline-primary btn-lg">
                                    {{ DateTime::createFromFormat('!m', $nextMonth)->format('F') }} →
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label for="month" class="form-label fw-semibold">Month</label>
                                    <select name="month" id="month" class="form-select">
                                        @for($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                
                                <div class="col-6">
                                    <label for="year" class="form-label fw-semibold">Year</label>
                                    <select name="year" id="year" class="form-select">
                                        @for($y = date('Y') - 1; $y <= date('Y') + 2; $y++)
                                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Calendar -->
                <div class="calendar-container">
                    <div class="table-responsive">
                        <table class="table table-bordered table-calendar mb-0">
                            <thead>
                                <tr>
                                    <th width="14.28%" class="weekday-header">Sunday</th>
                                    <th width="14.28%" class="weekday-header">Monday</th>
                                    <th width="14.28%" class="weekday-header">Tuesday</th>
                                    <th width="14.28%" class="weekday-header">Wednesday</th>
                                    <th width="14.28%" class="weekday-header">Thursday</th>
                                    <th width="14.28%" class="weekday-header">Friday</th>
                                    <th width="14.28%" class="weekday-header">Saturday</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($calendarData as $week)
                                    <tr>
                                        @foreach($week as $day)
                                            @php
                                                $dayClass = '';
                                                if (!$day['isCurrentMonth']) {
                                                    $dayClass = 'other-month';
                                                }
                                                if ($day['isToday']) {
                                                    $dayClass = 'today';
                                                }
                                            @endphp
                                            <td class="calendar-day {{ $dayClass }} position-relative">
                                                <div class="p-2 h-100 d-flex flex-column">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="day-number {{ $day['isToday'] ? 'badge bg-primary' : '' }}">
                                                            {{ $day['date']->format('j') }}
                                                        </span>
                                                        @if($day['isToday'])
                                                            <small class="text-primary fw-bold">Today</small>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="calendar-events flex-grow-1">
                                                        @foreach($day['challenges'] as $challenge)
                                                            @php
                                                                // Generate consistent color based on challenge ID
                                                                $colors = [
                                                                    '#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6',
                                                                    '#1abc9c', '#d35400', '#c0392b', '#16a085', '#8e44ad',
                                                                    '#27ae60', '#2980b9', '#8e44ad', '#2c3e50', '#f1c40f'
                                                                ];
                                                                $color = $colors[$challenge->id % count($colors)];
                                                            @endphp
                                                            <div class="calendar-event" 
                                                                 style="background-color: {{ $color }};"
                                                                 data-bs-toggle="tooltip" 
                                                                 data-bs-placement="top"
                                                                 title="{{ $challenge->name }}&#10;{{ \Carbon\Carbon::parse($challenge->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($challenge->end_date)->format('M j') }}&#10;{{ $challenge->participations->count() }} participations">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-grow-1 text-truncate">
                                                                        <strong>{{ $challenge->name }}</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Active Challenges Summary -->
                @if($challenges->count() > 0)
                    <div class="card mt-4 border-0 shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-trophy me-2"></i>
                                Active Challenges for {{ DateTime::createFromFormat('!m', $month)->format('F Y') }}
                                <span class="badge bg-light text-primary ms-2">{{ $challenges->count() }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($challenges as $challenge)
                                    @php
                                        $colors = [
                                            '#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6',
                                            '#1abc9c', '#d35400', '#c0392b', '#16a085', '#8e44ad'
                                        ];
                                        $color = $colors[$challenge->id % count($colors)];
                                    @endphp
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card challenge-card h-100">
                                            <div class="card-header text-white" style="background-color: {{ $color }};">
                                                <h6 class="card-title mb-0">{{ $challenge->name }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text text-muted small">
                                                    @if(strlen($challenge->description) > 100)
                                                        {{ substr($challenge->description, 0, 100) }}...
                                                    @else
                                                        {{ $challenge->description ?: 'No description provided.' }}
                                                    @endif
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ \Carbon\Carbon::parse($challenge->start_date)->format('M j, Y') }} - 
                                                        {{ \Carbon\Carbon::parse($challenge->end_date)->format('M j, Y') }}
                                                    </small>
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-users me-1"></i>
                                                        {{ $challenge->participations->count() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info mt-4 text-center">
                        <h5 class="alert-heading">No Challenges Found</h5>
                        <p class="mb-0">There are no challenges scheduled for {{ DateTime::createFromFormat('!m', $month)->format('F Y') }}.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Challenge Calendar. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
        
        // Auto-submit form when month/year changes
        document.getElementById('month').addEventListener('change', function() {
            window.location.href = "{{ route('challenges.calendar') }}?month=" + this.value + "&year=" + document.getElementById('year').value;
        });
        
        document.getElementById('year').addEventListener('change', function() {
            window.location.href = "{{ route('challenges.calendar') }}?month=" + document.getElementById('month').value + "&year=" + this.value;
        });

        // Add click event to calendar events to show more details
        document.querySelectorAll('.calendar-event').forEach(function(event) {
            event.addEventListener('click', function() {
                // You can add functionality to show challenge details when clicked
                console.log('Challenge clicked:', this.getAttribute('title'));
            });
        });
    </script>
</body>
</html>