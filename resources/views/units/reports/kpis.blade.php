<div class="row g-3">
    <div class="col-md-2 col-6">
        <div class="card text-center shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Total Units</h6>
                <h3 id="total_units">0</h3>
                <i class="bi bi-building fs-3 text-primary"></i>
            </div>
        </div>
    </div>

    <div class="col-md-2 col-6">
        <div class="card text-center shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Available Units</h6>
                <h3 id="available_units">0</h3>
                <i class="bi bi-house-door fs-3 text-success"></i>
            </div>
        </div>
    </div>

    <div class="col-md-2 col-6">
        <div class="card text-center shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Sold Units</h6>
                <h3 id="sold_units">0</h3>
                <i class="bi bi-check-circle fs-3 text-info"></i>
            </div>
        </div>
    </div>

    <div class="col-md-2 col-6">
        <div class="card text-center shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Reserved Units</h6>
                <h3 id="reserved_units">0</h3>
                <i class="bi bi-hourglass-split fs-3 text-warning"></i>
            </div>
        </div>
    </div>

    <div class="col-md-2 col-6">
        <div class="card text-center shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Deleted Units</h6>
                <h3 id="deleted_units">0</h3>
                <i class="bi bi-trash fs-3 text-danger"></i>
            </div>
        </div>
    </div>

    <div class="col-md-2 col-6">
        <div class="card text-center shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Total Projects</h6>
                <h3 id="total_projects">0</h3>
                <i class="bi bi-diagram-3 fs-3 text-secondary"></i>
            </div>
        </div>
    </div>
</div>
<style>
    /* Card style */
    .card {
        border-radius: 1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        color: #fff;
        overflow: hidden;
        position: relative;
        padding: 1rem;
    }

    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    /* Soft Gradient backgrounds for each card */
    .col-md-2:nth-child(1) .card {
        background: linear-gradient(135deg, #7b8cf7, #a29bfc);
    }

    .col-md-2:nth-child(2) .card {
        background: linear-gradient(135deg, #6ed3cf, #4fb6b6);
    }

    .col-md-2:nth-child(3) .card {
        background: linear-gradient(135deg, #f8a07c, #f6c9b4);
    }

    .col-md-2:nth-child(4) .card {
        background: linear-gradient(135deg, #fbd786, #f6e58d);
    }

    .col-md-2:nth-child(5) .card {
        background: linear-gradient(135deg, #ff9999, #ffb3b3);
    }

    .col-md-2:nth-child(6) .card {
        background: linear-gradient(135deg, #81ecec, #a4e4f2);
    }

    /* Icon animation */
    .card i {
        transition: transform 0.5s ease;
        font-size: 2rem;
    }

    .card:hover i {
        transform: rotate(15deg) scale(1.2);
    }

    /* Number animation */
    h3 {
        font-size: 1.8rem;
    }

    /* Text styling */
    .card h6 {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch("{{ route('units.reports.kpis') }}")
            .then(res => res.json())
            .then(data => {
                function animateValue(id, start, end, duration) {
                    let obj = document.getElementById(id);
                    let startTimestamp = null;
                    const step = (timestamp) => {
                        if (!startTimestamp) startTimestamp = timestamp;
                        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                        obj.innerText = Math.floor(progress * (end - start) + start);
                        if (progress < 1) {
                            window.requestAnimationFrame(step);
                        }
                    };
                    window.requestAnimationFrame(step);
                }

                animateValue('total_units', 0, data.total_units, 1000);
                animateValue('available_units', 0, data.available_units, 1000);
                animateValue('sold_units', 0, data.sold_units, 1000);
                animateValue('reserved_units', 0, data.reserved_units, 1000);
                animateValue('deleted_units', 0, data.deleted_units || 0, 1000);
                animateValue('total_projects', 0, data.total_projects, 1000);
            });
    });
</script>
