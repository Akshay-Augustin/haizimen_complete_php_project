<?php elseif ($role === 'daycare'): ?>
    <div class="card">
        <h2>Daycare Dashboard</h2>
        <div class="grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-school"></i></div>
                <h3>Center Profile</h3>
                <p>Manage daycare profile, timings, and facilities.</p>
                <a class="btn" href="daycare_profile.php">Open</a>
            </div>

            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-user-group"></i></div>
                <h3>Enrollment Requests</h3>
                <p>See parent requests for child enrollment.</p>
                <a class="btn" href="daycare_requests.php">Open</a>
            </div>

            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-building-circle-check"></i></div>
                <h3>Capacity Details</h3>
                <p>Track available seats and daycare capacity.</p>
                <a class="btn" href="daycare_capacity.php">Open</a>
            </div>
        </div>
    </div>