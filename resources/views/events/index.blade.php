{{-- resources/views/calendar/index.blade.php --}}
@extends('layouts.master')
@section('title')
    Calendar
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        #external-events .fc-event {
            margin: 0 0 10px 0;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        #external-events .fc-event:hover {
            opacity: 0.8;
            transform: translateY(-2px);
        }

        #calendar {
            max-width: 100%;
            margin: 0 auto;
        }

        .fc-event {
            cursor: pointer;
        }

        .upcoming-event-item {
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .upcoming-event-item:hover {
            transform: translateX(-3px);
        }

        /* Flatpickr custom styling */
        .flatpickr-input {
            background-color: white !important;
        }

        .flatpickr-calendar {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Apps
        @endslot
        @slot('title')
            Calendar
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-3">
            <div class="card mb-3">
                <div class="card-body">
                    <button class="btn btn-primary w-100 mb-3" id="btn-new-event">
                        <i class="mdi mdi-plus"></i> Create New Event
                    </button>

                    <div id="external-events">
                        <p class="text-muted mb-2">Quick Templates</p>
                        <div class="fc-event bg-soft-success text-success" data-class="bg-soft-success">
                            <i class="mdi mdi-calendar-check me-1"></i>New Event Planning
                        </div>
                        <div class="fc-event bg-soft-info text-info" data-class="bg-soft-info">
                            <i class="mdi mdi-account-group me-1"></i>Meeting
                        </div>
                        <div class="fc-event bg-soft-warning text-warning" data-class="bg-soft-warning">
                            <i class="mdi mdi-file-chart me-1"></i>Generating Reports
                        </div>
                        <div class="fc-event bg-soft-danger text-danger" data-class="bg-soft-danger">
                            <i class="mdi mdi-palette me-1"></i>Create New Theme
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">Upcoming Events</h6>
                    <div id="upcoming-event-list">
                        <div class="text-center text-muted py-3">
                            <i class="mdi mdi-calendar-blank mdi-24px d-block mb-2"></i>
                            <small>No upcoming events</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9">
            <div class="card">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Modal -->
    <div class="modal fade" id="event-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Add New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="form-event">
                        <div class="mb-3">
                            <label class="form-label">Event Name <span class="text-danger">*</span></label>
                            <input type="text" id="event-title" class="form-control" placeholder="Enter event name"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select id="event-category" class="form-select">
                                <option value="bg-soft-success">Success</option>
                                <option value="bg-soft-info">Info</option>
                                <option value="bg-soft-warning">Warning</option>
                                <option value="bg-soft-danger">Danger</option>
                                <option value="bg-soft-primary">Primary</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date & Time <span class="text-danger">*</span></label>
                                <input type="text" id="event-start-date" class="form-control"
                                    placeholder="Select date & time" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Date & Time</label>
                                <input type="text" id="event-end-date" class="form-control"
                                    placeholder="Select date & time">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-map-marker"></i></span>
                                <input type="text" id="event-location" class="form-control" placeholder="Event location">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea id="event-description" class="form-control" rows="3" placeholder="Event details..."></textarea>
                        </div>

                        <input type="hidden" id="eventid">

                        <div class="mt-4 d-flex justify-content-between">
                            <button type="button" class="btn btn-danger" id="btn-delete-event" style="display:none;">
                                <i class="mdi mdi-delete"></i> Delete
                            </button>
                            <div class="ms-auto">
                                <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save"></i> Save Event
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const upcomingList = document.getElementById('upcoming-event-list');
            const eventModal = new bootstrap.Modal(document.getElementById('event-modal'));

            // Initialize Flatpickr for start date
            const startDatePicker = flatpickr("#event-start-date", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                defaultHour: 9,
                defaultMinute: 0,
                minuteIncrement: 15,
                onChange: function(selectedDates, dateStr, instance) {
                    // Update end date minimum to start date
                    endDatePicker.set('minDate', dateStr);
                }
            });

            // Initialize Flatpickr for end date
            const endDatePicker = flatpickr("#event-end-date", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                defaultHour: 10,
                defaultMinute: 0,
                minuteIncrement: 15
            });

            // Initialize FullCalendar
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                editable: true,
                droppable: true,
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },

                events: '/calendar/events',

                // Add color based on category
                eventDidMount: function(info) {
                    const category = info.event.extendedProps.category || 'bg-soft-primary';
                    const el = info.el;

                    // Remove default background
                    el.style.backgroundColor = 'transparent';
                    el.style.borderColor = 'transparent';

                    // Apply category class
                    el.classList.add(category);

                    // Add color mapping
                    const colorMap = {
                        'bg-soft-success': '#28a745',
                        'bg-soft-info': '#17a2b8',
                        'bg-soft-warning': '#ffc107',
                        'bg-soft-danger': '#dc3545',
                        'bg-soft-primary': '#007bff'
                    };

                    const borderColor = colorMap[category] || '#007bff';
                    el.style.borderLeft = `4px solid ${borderColor}`;

                    updateUpcomingEvents();
                },
                // Click on existing event
                eventClick: function(info) {
                    const event = info.event;

                    document.getElementById('modal-title').innerText = 'Edit Event';
                    document.getElementById('event-title').value = event.title;
                    document.getElementById('event-description').value = event.extendedProps
                        .description || '';
                    document.getElementById('event-location').value = event.extendedProps.location ||
                        '';
                    document.getElementById('event-category').value = event.extendedProps.category ||
                        'bg-soft-success';

                    // Set dates using Flatpickr
                    startDatePicker.setDate(event.start);

                    if (event.end) {
                        endDatePicker.setDate(event.end);
                    } else {
                        endDatePicker.clear();
                    }

                    document.getElementById('eventid').value = event.id;
                    document.getElementById('btn-delete-event').style.display = 'inline-block';

                    eventModal.show();
                },

                // Select date range
                select: function(info) {
                    document.getElementById('modal-title').innerText = 'Add New Event';
                    document.getElementById('form-event').reset();

                    // Set dates using Flatpickr
                    startDatePicker.setDate(info.start);

                    if (info.end) {
                        const endDate = new Date(info.end);
                        endDate.setMinutes(endDate.getMinutes() - 1);
                        endDatePicker.setDate(endDate);
                    }

                    document.getElementById('eventid').value = '';
                    document.getElementById('btn-delete-event').style.display = 'none';

                    eventModal.show();
                },

                // Drop external event
                drop: function(info) {
                    const draggedTitle = info.draggedEl.innerText.trim().replace(/^\s*[^\w\s]+\s*/, '');
                    const eventData = {
                        title: draggedTitle,
                        start: info.dateStr,
                        category: info.draggedEl.getAttribute('data-class') || 'bg-soft-primary'
                    };

                    saveEvent(eventData);
                },

                // Drag and drop existing event
                eventDrop: function(info) {
                    updateEventDate(info.event);
                },

                // Resize event
                eventResize: function(info) {
                    updateEventDate(info.event);
                },
            });

            calendar.render();

            // Update upcoming events list
            function updateUpcomingEvents() {
                fetch('/calendar/events')
                    .then(res => res.json())
                    .then(events => {
                        const now = new Date();
                        const upcoming = events
                            .filter(ev => new Date(ev.start) >= now)
                            .sort((a, b) => new Date(a.start) - new Date(b.start))
                            .slice(0, 5);

                        if (upcoming.length === 0) {
                            upcomingList.innerHTML = `
                                <div class="text-center text-muted py-3">
                                    <i class="mdi mdi-calendar-blank mdi-24px d-block mb-2"></i>
                                    <small>No upcoming events</small>
                                </div>
                            `;
                            return;
                        }

                        upcomingList.innerHTML = upcoming.map(ev => {
                            const eventDate = new Date(ev.start);
                            const dateStr = eventDate.toLocaleDateString('en-US', {
                                month: 'short',
                                day: 'numeric',
                                year: 'numeric'
                            });
                            const timeStr = eventDate.toLocaleTimeString('en-US', {
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            return `
                                <div class="upcoming-event-item mb-2 p-2 ${ev.category} rounded">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-0 text-dark">${ev.title}</h6>
                                            <small class="text-muted">
                                                <i class="mdi mdi-calendar"></i> ${dateStr}
                                                <i class="mdi mdi-clock-outline ms-2"></i> ${timeStr}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                    });
            }

            // Save event (create or update)
            function saveEvent(eventData, eventId = null) {
                const url = eventId ? `/calendar/${eventId}` : '/calendar';
                const method = eventId ? 'PUT' : 'POST';

                fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(eventData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        calendar.refetchEvents();
                        updateUpcomingEvents();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error saving event. Please try again.');
                    });
            }

            // Update event date after drag/resize
            function updateEventDate(event) {
                const eventData = {
                    title: event.title,
                    start: event.start.toISOString(),
                    end: event.end ? event.end.toISOString() : null,
                    category: event.extendedProps.category,
                    location: event.extendedProps.location,
                    description: event.extendedProps.description
                };

                saveEvent(eventData, event.id);
            }

            // Form submit handler
            document.getElementById('form-event').addEventListener('submit', function(e) {
                e.preventDefault();

                const eventId = document.getElementById('eventid').value;
                const startValue = document.getElementById('event-start-date').value;
                const endValue = document.getElementById('event-end-date').value;

                const eventData = {
                    title: document.getElementById('event-title').value,
                    start: startValue,
                    end: endValue || null,
                    category: document.getElementById('event-category').value,
                    location: document.getElementById('event-location').value,
                    description: document.getElementById('event-description').value
                };

                saveEvent(eventData, eventId);
                eventModal.hide();
            });

            // Delete event with SweetAlert
            document.getElementById('btn-delete-event').addEventListener('click', function() {
                const eventId = document.getElementById('eventid').value;

                if (!eventId) return;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This event will be deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/calendar/${eventId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content
                                }
                            })
                            .then(() => {
                                calendar.refetchEvents();
                                updateUpcomingEvents();
                                eventModal.hide();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Event has been deleted.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error deleting event. Please try again.'
                                });
                            });
                    }
                });
            });


            // New event button
            document.getElementById('btn-new-event').addEventListener('click', function() {
                document.getElementById('modal-title').innerText = 'Add New Event';
                document.getElementById('form-event').reset();
                document.getElementById('eventid').value = '';
                document.getElementById('btn-delete-event').style.display = 'none';

                // Set default start time to now using Flatpickr
                startDatePicker.setDate(new Date());
                endDatePicker.clear();

                eventModal.show();
            });

            // Make external events clickable
            const externalEvents = document.querySelectorAll('#external-events .fc-event');
            externalEvents.forEach(event => {
                event.addEventListener('click', function() {
                    document.getElementById('modal-title').innerText = 'Add New Event';
                    document.getElementById('form-event').reset();

                    // Extract title without icon
                    const fullText = this.innerText.trim();
                    const cleanTitle = fullText.replace(/^\s*[^\w\s]+\s*/, '');

                    document.getElementById('event-title').value = cleanTitle;
                    document.getElementById('event-category').value = this.getAttribute(
                        'data-class') || 'bg-soft-primary';
                    document.getElementById('eventid').value = '';
                    document.getElementById('btn-delete-event').style.display = 'none';

                    startDatePicker.setDate(new Date());
                    endDatePicker.clear();

                    eventModal.show();
                });
            });

            // Initial load of upcoming events
            updateUpcomingEvents();
        });
    </script>
@endsection
