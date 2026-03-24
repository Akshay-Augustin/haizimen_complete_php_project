<?php elseif ($role === 'caretaker'): ?>
    <div class="card">
        <h2>Caretaker Dashboard</h2>
        <div class="grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-id-badge"></i></div>
                <h3>My Profile</h3>
                <p>View and manage your caretaker profile details.</p>
                <a class="btn" href="caretaker_profile.php">Open</a>
            </div>

            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <h3>Booking Requests</h3>
                <p>See requests from parents and manage them.</p>
                <a class="btn" href="caretaker_requests.php">Open</a>
            </div>

            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-clock"></i></div>
                <h3>Availability</h3>
                <p>Update your working hours and availability.</p>
                <a class="btn" href="caretaker_availability.php">Open</a>
            </div>
        </div>
    </div>