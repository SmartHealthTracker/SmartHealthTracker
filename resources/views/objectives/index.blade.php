@extends('layout.master')

@section('content')
<div class="row">
    <div class="col-lg-4 grid-margin">
        <div class="d-flex flex-column">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">Create Objective</h4>
                <p class="card-description">Plan a new objective with a specific date and time.</p>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        Please fix the errors below.
                    </div>
                @endif

                <form id="objective-form" method="POST" action="{{ route('objectives.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="objective-title">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="objective-title" name="title" value="{{ old('title') }}">
                        <small id="objective-title-error" class="text-danger" style="display:none"></small>
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="objective-description">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="objective-description" name="description" rows="4" placeholder="Optional details...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="objective-start">Start date &amp; time</label>
                        <input type="datetime-local" class="form-control @error('start_at') is-invalid @enderror" id="objective-start" name="start_at" value="{{ old('start_at', now()->format('Y-m-d\TH:i')) }}">
                        <small id="objective-start-error" class="text-danger" style="display:none"></small>
                        @error('start_at')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="objective-end">End date &amp; time</label>
                        <input type="datetime-local" class="form-control @error('end_at') is-invalid @enderror" id="objective-end" name="end_at" value="{{ old('end_at') }}">
                        @error('end_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">If left blank, the calendar shows a 1 hour duration.</small>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Save Objective</button>
                </form>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Recent Objectives</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>When</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($objectives as $objective)
                                <tr>
                                    <td>{{ $objective->title }}</td>
                                    <td>{{ $objective->start_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('objectives.destroy', $objective) }}" onsubmit="return confirm('Delete this objective?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-link text-danger p-0">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted">No objectives yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Daily Motivation</h5>
                <p class="card-description">Pull a fresh quote to kickstart your objectives.</p>
                <div id="quote-card" class="border rounded p-3 bg-light mb-3" style="display:none"></div>
                <div id="quote-empty" class="text-muted small">Tap the button below to retrieve today's motivation.</div>
                <button id="quote-refresh" class="btn btn-outline-primary btn-sm">Show Today's Quote</button>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Weather-based Suggestions</h5>
                <p class="card-description">Use local weather to adapt today’s objectives.</p>
                <form id="weather-form" class="mb-3">
                    <div class="form-group">
                        <label for="weather-city">City</label>
                        <div class="input-group">
                            <input type="text" id="weather-city" class="form-control" placeholder="e.g. Tunis" value="{{ old('city', 'Tunis') }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="submit">Check</button>
                            </div>
                        </div>
                        <small class="text-muted">Powered by Open-Meteo (no API key required).</small>
                        <div id="weather-city-error" class="text-danger mt-1" style="display:none"></div>
                    </div>
                </form>
                <div id="weather-result" class="border rounded p-3 bg-light" style="display:none"></div>
                <div id="weather-empty" class="text-muted small">Enter a city to receive activity and hydration suggestions.</div>
            </div>
        </div>
        </div>
    </div>
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-1">Objectives Calendar</h4>
                        <p class="card-description mb-0">Drag objectives to reschedule them instantly.</p>
                    </div>
                    <div class="text-muted small">
                        Tip: Click an objective to view its description.
                    </div>
                </div>
                <div id="objectives-calendar" class="calendar-container"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css">
<style>
.calendar-container {
    min-height: 650px;
}
</style>
@endpush

@push('custom-scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('objectives-calendar');
    if (!calendarEl) {
        return;
    }

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        editable: true,
        eventDurationEditable: true,
        selectable: false,
        events: {
            url: '{{ route('objectives.events') }}',
            failure: function () {
                calendarEl.innerHTML = '<div class="alert alert-warning">Unable to load objectives. Please try again later.</div>';
            }
        },
        eventDrop: function (info) {
            updateObjectiveDates(info);
        },
        eventResize: function (info) {
            updateObjectiveDates(info);
        },
        eventClick: function (info) {
            var props = info.event.extendedProps || {};
            var description = props.description ? props.description : 'No additional details.';
            if (window.Swal) {
                Swal.fire({
                    title: info.event.title,
                    text: description,
                    icon: 'info'
                });
            } else {
                alert(info.event.title + '\n' + description);
            }
        }
    });

    calendar.render();

    var form = document.getElementById('objective-form');
    if (form) {
        form.addEventListener('submit', function (event) {
            var titleInput = document.getElementById('objective-title');
            var startInput = document.getElementById('objective-start');
            var validationMap = [
                { input: titleInput, messageEl: document.getElementById('objective-title-error'), message: 'Title is required.' },
                { input: startInput, messageEl: document.getElementById('objective-start-error'), message: 'Start date & time is required.' }
            ];

            var hasError = false;
            validationMap.forEach(function (item) {
                if (!item.input.value.trim()) {
                    item.messageEl.textContent = item.message;
                    item.messageEl.style.display = 'block';
                    item.input.classList.add('is-invalid');
                    hasError = true;
                } else {
                    item.messageEl.textContent = '';
                    item.messageEl.style.display = 'none';
                    item.input.classList.remove('is-invalid');
                }
            });

            if (hasError) {
                event.preventDefault();
            }
        });

        ['objective-title', 'objective-start', 'objective-end'].forEach(function (id) {
            var input = document.getElementById(id);
            if (!input) {
                return;
            }
            input.addEventListener('input', function () {
                this.classList.remove('is-invalid');
                var map = {
                    'objective-title': document.getElementById('objective-title-error'),
                    'objective-start': document.getElementById('objective-start-error')
                };
                if (map[id]) {
                    map[id].style.display = 'none';
                    map[id].textContent = '';
                }
            });
        });
    }

    function updateObjectiveDates(changeInfo) {
        var event = changeInfo.event;
        var payload = {
            start_at: event.start.toISOString(),
            end_at: event.end ? event.end.toISOString() : null
        };

        fetch('/objectives/' + event.id, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Failed to update objective');
            }
            return response.json();
        })
        .then(function () {
            if (window.toastr) {
                toastr.success('Objective updated');
            }
        })
        .catch(function (error) {
            console.error(error);
            if (window.Swal) {
                Swal.fire('Update failed', 'Could not reschedule objective.', 'error');
            }
            changeInfo.revert();
        });
    }

    var weatherForm = document.getElementById('weather-form');
    var weatherResult = document.getElementById('weather-result');
    var weatherEmpty = document.getElementById('weather-empty');
    var weatherError = document.getElementById('weather-city-error');

    var quoteRefresh = document.getElementById('quote-refresh');
    var quoteCard = document.getElementById('quote-card');
    var quoteEmpty = document.getElementById('quote-empty');

    if (quoteRefresh) {
        var loadQuote = function () {
            quoteRefresh.disabled = true;
            quoteRefresh.textContent = 'Loading...';
            quoteEmpty.style.display = 'none';
            quoteCard.style.display = 'block';
            quoteCard.innerHTML = '<div class="spinner-border spinner-border-sm text-primary mr-2" role="status"></div> Fetching your motivational quote...';

            fetch('{{ route('motivation.quote') }}')
                .then(function (response) {
                    if (!response.ok) {
                        throw response;
                    }
                    return response.json();
                })
                .then(function (payload) {
                    if (!payload.success) {
                        throw payload;
                    }

                    var quote = payload.data;
                    quoteCard.innerHTML = `
                        <blockquote class="mb-2">“${quote.quote}”</blockquote>
                        <div class="text-right font-italic">— ${quote.author}</div>
                    `;
                })
                .catch(function (error) {
                    var message = 'Unable to load a quote right now. Try again later.';
                    if (error && error.json) {
                        error.json().then(function (payload) {
                            message = payload.message || message;
                            quoteCard.innerHTML = `<div class="text-danger">${message}</div>`;
                        }).catch(function () {
                            quoteCard.innerHTML = `<div class="text-danger">${message}</div>`;
                        });
                    } else {
                        quoteCard.innerHTML = `<div class="text-danger">${message}</div>`;
                    }
                })
                .finally(function () {
                    quoteRefresh.disabled = false;
                    quoteRefresh.textContent = 'Refresh Quote';
                });
        };

        quoteRefresh.addEventListener('click', function () {
            loadQuote();
        });

        // auto-load on page render to keep card populated
        loadQuote();
    }

    if (weatherForm) {
        weatherForm.addEventListener('submit', function (event) {
            event.preventDefault();
            weatherError.style.display = 'none';
            weatherError.textContent = '';

            var cityInput = document.getElementById('weather-city');
            var city = cityInput.value.trim();
            if (!city) {
                cityInput.classList.add('is-invalid');
                weatherError.textContent = 'Please enter a city.';
                weatherError.style.display = 'block';
                return;
            }

            cityInput.classList.remove('is-invalid');
            weatherResult.style.display = 'block';
            weatherResult.innerHTML = '<div class="spinner-border spinner-border-sm text-primary mr-2" role="status"></div> Loading weather insights...';
            weatherEmpty.style.display = 'none';

            fetch(`{{ route('weather.suggestions') }}?city=${encodeURIComponent(city)}`)
                .then(function (response) {
                    if (!response.ok) {
                        throw response;
                    }
                    return response.json();
                })
                .then(function (payload) {
                    if (!payload.success) {
                        throw payload;
                    }

                    var data = payload.data;
                    var location = data.location;
                    var current = data.current;
                    var suggestions = data.suggestions;

                    var temperature = current.temperature ?? 'N/A';
                    var apparent = current.apparent_temperature ?? 'N/A';
                    var humidity = current.humidity ?? 'N/A';
                    var precipitation = current.precipitation ?? 'N/A';
                    var wind = current.wind_speed ?? 'N/A';

                    weatherResult.innerHTML = `
                        <h6 class="mb-2">${location.name}, ${location.country}</h6>
                        <p class="mb-1"><strong>Temperature:</strong> ${temperature}&deg;C (feels like ${apparent}&deg;C)</p>
                        <p class="mb-1"><strong>Humidity:</strong> ${humidity}% &middot; <strong>Precipitation:</strong> ${precipitation} mm &middot; <strong>Wind:</strong> ${wind} km/h</p>
                        <div class="mt-3">
                            <p class="mb-2 font-weight-bold">Activity Tip</p>
                            <p class="text-body">${suggestions.activity}</p>
                            <p class="mb-2 font-weight-bold">Hydration Tip</p>
                            <p class="text-body">${suggestions.hydration}</p>
                        </div>
                    `;
                })
                .catch(function (error) {
                    var message = 'Unable to retrieve weather right now. Please try later.';
                    if (error.json) {
                        error.json().then(function (errPayload) {
                            message = errPayload.message || message;
                            weatherResult.innerHTML = `<div class="text-danger">${message}</div>`;
                        }).catch(function () {
                            weatherResult.innerHTML = `<div class="text-danger">${message}</div>`;
                        });
                    } else if (error && error.message) {
                        weatherResult.innerHTML = `<div class="text-danger">${error.message}</div>`;
                    } else {
                        weatherResult.innerHTML = `<div class="text-danger">${message}</div>`;
                    }
                });
        });
    }
});
</script>
@endpush
