<?php
include("connect.php");
require_once 'app/Controllers/AuthController.php';

$errors = [];
$hospitalRows = [];

$hospitalQuery = $conn->query("SELECT id, hospital_name FROM hospitals ORDER BY hospital_name ASC");
if ($hospitalQuery) {
    while ($row = $hospitalQuery->fetch_assoc()) {
        $hospitalRows[] = $row;
    }
}

if (isset($_POST['submit'])) {
    $controller = new AuthController($conn);
    $result = $controller->registerUser();

    if ($result['success']) {
        header('Location: login.php');
        exit;
    }

    $errors = $result['errors'] ?? ['Registration failed.'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Register - Haizimen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            color: #1b2b3a;
            background:
                linear-gradient(rgba(234, 244, 255, 0.85), rgba(214, 236, 255, 0.9)),
                url('https://images.unsplash.com/photo-1516627145497-ae6968895b74?auto=format&fit=crop&w=1600&q=80') no-repeat center center fixed;
            background-size: cover;
        }
        .page-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
        }
        .container {
            width: 620px;
            max-width: 100%;
            background: rgba(255, 255, 255, 0.96);
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.05);
        }
        h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 8px;
            color: #1b6ec2;
        }
        .subtext {
            text-align: center;
            color: #4a5c6b;
            font-size: 14px;
            margin-bottom: 18px;
        }
        table { width: 100%; }
        td {
            padding: 6px 4px;
            font-size: 13px;
            vertical-align: top;
            color: #4a5c6b;
        }
        select,
        textarea,
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        input[type="number"],
        input[type="time"],
        input[type="file"] {
            width: 100%;
            padding: 9px 10px;
            border-radius: 6px;
            border: 1px solid #cfe3f7;
            font-size: 13px;
            box-sizing: border-box;
            background: #f4f9ff;
            color: #1b2b3a;
            font-family: Arial, sans-serif;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        input[type="file"] { padding: 7px; }
        input[type="radio"] { margin-right: 4px; }
        .btn {
            width: 100%;
            padding: 11px;
            background: #1b6ec2;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            margin-top: 8px;
        }
        .btn:hover { background: #155a9c; }
        .login-link {
            text-align: center;
            font-size: 13px;
            margin-top: 12px;
            color: #4a5c6b;
        }
        a {
            text-decoration: none;
            color: #1b6ec2;
            font-weight: bold;
        }
        .alert {
            background: #ffecec;
            color: #a94442;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 13px;
        }
        .back-home {
            display: block;
            text-align: center;
            margin-top: 14px;
            font-size: 13px;
        }
        .doctor-only,
        .caretaker-only,
        .daycare-only,
        .hospital-only,
        .personal-only,
        .parent-only,
        .parent-extra-only {
            display: none;
        }
    </style>
</head>
<body>
<div class="page-wrap">
    <div class="container">
        <h2>Register</h2>
        <div class="subtext">Create your Haizimen account</div>

        <?php if (!empty($errors)): ?>
            <div class="alert">
                <?php foreach ($errors as $error): ?>
                    <div><?php echo e($error); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>Role</td>
                    <td>
                        <select name="role" id="role" required>
                            <option value="">Select Role</option>
                            <option value="parent" <?php echo old('role') === 'parent' ? 'selected' : ''; ?>>Parent</option>
                            <option value="doctor" <?php echo old('role') === 'doctor' ? 'selected' : ''; ?>>Doctor</option>
                            <option value="hospital" <?php echo old('role') === 'hospital' ? 'selected' : ''; ?>>Hospital</option>
                            <option value="caretaker" <?php echo old('role') === 'caretaker' ? 'selected' : ''; ?>>Caretaker</option>
                            <option value="daycare" <?php echo old('role') === 'daycare' ? 'selected' : ''; ?>>Daycare</option>
                        </select>
                    </td>
                </tr>

                <!-- Doctor -->
                <tr class="doctor-only">
                    <td>Department</td>
                    <td><input type="text" id="department" name="department" value="Pediatrics" readonly></td>
                </tr>

                <tr class="doctor-only">
                    <td>Qualification</td>
                    <td><input type="text" id="qualification" name="qualification" value="<?php echo e(old('qualification')); ?>"></td>
                </tr>

                <tr class="doctor-only">
                    <td>Select Hospital</td>
                    <td>
                        <select id="hospital_id" name="hospital_id">
                            <option value="">Choose Hospital</option>
                            <?php foreach ($hospitalRows as $hospital): ?>
                                <option value="<?php echo (int)$hospital['id']; ?>" <?php echo old('hospital_id') == $hospital['id'] ? 'selected' : ''; ?>>
                                    <?php echo e($hospital['hospital_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <!-- Hospital -->
                <tr class="hospital-only">
                    <td>Hospital Name</td>
                    <td><input type="text" id="hospital_name" name="hospital_name" value="<?php echo e(old('hospital_name')); ?>"></td>
                </tr>

                <tr class="hospital-only">
                    <td>Registration Number</td>
                    <td><input type="text" id="registration_number" name="registration_number" value="<?php echo e(old('registration_number')); ?>"></td>
                </tr>

                <tr class="hospital-only">
                    <td>Hospital Type</td>
                    <td>
                        <select id="hospital_type" name="hospital_type">
                            <option value="">Choose Type</option>
                            <option value="General">General</option>
                            <option value="Children">Children</option>
                            <option value="Multispeciality">Multispeciality</option>
                            <option value="Clinic">Clinic</option>
                            <option value="Government">Government</option>
                            <option value="Private">Private</option>
                        </select>
                    </td>
                </tr>

                <tr class="hospital-only">
                    <td>Contact Person</td>
                    <td><input type="text" id="contact_person" name="contact_person" value="<?php echo e(old('contact_person')); ?>"></td>
                </tr>

                <tr class="hospital-only">
                    <td>Opening Time</td>
                    <td><input type="time" id="hospital_opening_time" name="hospital_opening_time" value="<?php echo e(old('hospital_opening_time')); ?>"></td>
                </tr>

                <tr class="hospital-only">
                    <td>Closing Time</td>
                    <td><input type="time" id="hospital_closing_time" name="hospital_closing_time" value="<?php echo e(old('hospital_closing_time')); ?>"></td>
                </tr>

                <tr class="hospital-only">
                    <td>City</td>
                    <td><input type="text" id="city" name="city" value="<?php echo e(old('city')); ?>"></td>
                </tr>

                <tr class="hospital-only">
                    <td>State</td>
                    <td><input type="text" id="state" name="state" value="<?php echo e(old('state')); ?>"></td>
                </tr>

                <tr class="hospital-only">
                    <td>Pincode</td>
                    <td><input type="text" id="pincode" name="pincode" value="<?php echo e(old('pincode')); ?>"></td>
                </tr>

                <tr class="hospital-only">
                    <td>Description</td>
                    <td><textarea id="hospital_description" name="hospital_description"><?php echo e(old('hospital_description')); ?></textarea></td>
                </tr>

                <!-- Caretaker -->
                <tr class="caretaker-only">
                    <td>Experience (Years)</td>
                    <td><input type="text" id="experience_years" name="experience_years" value="<?php echo e(old('experience_years')); ?>"></td>
                </tr>

                <tr class="caretaker-only">
                    <td>Skills</td>
                    <td><textarea id="skills" name="skills"><?php echo e(old('skills')); ?></textarea></td>
                </tr>

                <tr class="caretaker-only">
                    <td>Availability</td>
                    <td><input type="text" id="availability" name="availability" value="<?php echo e(old('availability')); ?>" placeholder="Full Time / Part Time / Weekend"></td>
                </tr>

                <tr class="caretaker-only">
                    <td>Fee</td>
                    <td><input type="text" id="fee" name="fee" value="<?php echo e(old('fee')); ?>" placeholder="e.g. 500 per day"></td>
                </tr>

                <tr class="caretaker-only">
                    <td>Preferred Location</td>
                    <td><input type="text" id="preferred_location" name="preferred_location" value="<?php echo e(old('preferred_location')); ?>"></td>
                </tr>

                <!-- Daycare -->
                <tr class="daycare-only">
                    <td>Center Name</td>
                    <td><input type="text" id="center_name" name="center_name" value="<?php echo e(old('center_name')); ?>"></td>
                </tr>

                <tr class="daycare-only">
                    <td>Capacity</td>
                    <td><input type="number" id="capacity" name="capacity" value="<?php echo e(old('capacity')); ?>" min="0"></td>
                </tr>

                <tr class="daycare-only">
                    <td>Opening Time</td>
                    <td><input type="time" id="opening_time" name="opening_time" value="<?php echo e(old('opening_time')); ?>"></td>
                </tr>

                <tr class="daycare-only">
                    <td>Closing Time</td>
                    <td><input type="time" id="closing_time" name="closing_time" value="<?php echo e(old('closing_time')); ?>"></td>
                </tr>

                <tr class="daycare-only">
                    <td>Age Group Supported</td>
                    <td><input type="text" id="age_group_supported" name="age_group_supported" value="<?php echo e(old('age_group_supported')); ?>" placeholder="e.g. 2-6 years"></td>
                </tr>

                <tr class="daycare-only">
                    <td>Facilities</td>
                    <td><textarea id="facilities" name="facilities"><?php echo e(old('facilities')); ?></textarea></td>
                </tr>

                <tr class="daycare-only">
                    <td>Description</td>
                    <td><textarea id="daycare_description" name="daycare_description"><?php echo e(old('daycare_description')); ?></textarea></td>
                </tr>

                <!-- Personal -->
                <tr class="personal-only">
                    <td>First Name</td>
                    <td><input type="text" id="firstname" name="firstname" value="<?php echo e(old('firstname')); ?>"></td>
                </tr>

                <tr class="personal-only">
                    <td>Last Name</td>
                    <td><input type="text" id="lastname" name="lastname" value="<?php echo e(old('lastname')); ?>"></td>
                </tr>

                <tr class="personal-only">
                    <td>Gender</td>
                    <td>
                        <input type="radio" name="gender" value="male" <?php echo old('gender') === 'male' ? 'checked' : ''; ?>> Male
                        <input type="radio" name="gender" value="female" <?php echo old('gender') === 'female' ? 'checked' : ''; ?>> Female
                        <input type="radio" name="gender" value="others" <?php echo old('gender') === 'others' ? 'checked' : ''; ?>> Other
                    </td>
                </tr>

                <tr class="personal-only">
                    <td>DOB</td>
                    <td><input type="date" id="DOB" name="DOB" value="<?php echo e(old('DOB')); ?>"></td>
                </tr>

                <tr class="parent-only">
                    <td>Birth Certificate</td>
                    <td><input type="file" name="uploadcertificate"></td>
                </tr>

                <tr class="parent-only">
                    <td>Blood Group</td>
                    <td><input type="text" name="bloodgroup" value="<?php echo e(old('bloodgroup')); ?>"></td>
                </tr>

                <tr class="doctor-only caretaker-only hospital-only">
                    <td>Qualification / License Certificate</td>
                    <td><input type="file" name="qualification_certificate"></td>
                </tr>

                <tr class="parent-extra-only">
                    <td>Height</td>
                    <td><input type="text" name="height" value="<?php echo e(old('height')); ?>"></td>
                </tr>

                <tr class="parent-extra-only">
                    <td>Weight</td>
                    <td><input type="text" name="weight" value="<?php echo e(old('weight')); ?>"></td>
                </tr>

                <tr class="parent-extra-only">
                    <td>Mother Name</td>
                    <td><input type="text" name="mothername" value="<?php echo e(old('mothername')); ?>"></td>
                </tr>

                <tr class="parent-extra-only">
                    <td>Father Name</td>
                    <td><input type="text" name="fathername" value="<?php echo e(old('fathername')); ?>"></td>
                </tr>

                <tr>
                    <td>Address</td>
                    <td><input type="text" name="address" value="<?php echo e(old('address')); ?>"></td>
                </tr>

                <tr>
                    <td>Email</td>
                    <td><input type="email" name="emailid" value="<?php echo e(old('emailid')); ?>" required></td>
                </tr>

                <tr>
                    <td>Phone</td>
                    <td><input type="text" name="phonenumber" value="<?php echo e(old('phonenumber')); ?>" required></td>
                </tr>

                <tr>
                    <td>Username</td>
                    <td><input type="text" name="username" value="<?php echo e(old('username')); ?>" required></td>
                </tr>

                <tr>
                    <td>Password</td>
                    <td><input type="password" name="password" required></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Register" class="btn">
                    </td>
                </tr>
            </table>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Login</a>
        </div>

        <a class="back-home" href="index.php">Back to Home</a>
    </div>
</div>

<script>
function toggleRoleFields() {
    const role = document.getElementById('role')?.value || '';

    const doctorFields = document.querySelectorAll('.doctor-only');
    const caretakerFields = document.querySelectorAll('.caretaker-only');
    const daycareFields = document.querySelectorAll('.daycare-only');
    const hospitalFields = document.querySelectorAll('.hospital-only');
    const personalFields = document.querySelectorAll('.personal-only');
    const parentExtraFields = document.querySelectorAll('.parent-extra-only');
    const parentOnly = document.querySelectorAll('.parent-only');

    parentOnly.forEach(field => field.style.display = 'none');
    doctorFields.forEach(field => field.style.display = 'none');
    caretakerFields.forEach(field => field.style.display = 'none');
    daycareFields.forEach(field => field.style.display = 'none');
    hospitalFields.forEach(field => field.style.display = 'none');
    personalFields.forEach(field => field.style.display = 'none');
    parentExtraFields.forEach(field => field.style.display = 'none');

    if (role === 'parent') {
        personalFields.forEach(f => f.style.display = 'table-row');
        parentOnly.forEach(f => f.style.display = 'table-row');
    }

    if (role === 'doctor') {
        personalFields.forEach(f => f.style.display = 'table-row');
        doctorFields.forEach(f => f.style.display = 'table-row');
    }

    if (role === 'caretaker') {
        personalFields.forEach(f => f.style.display = 'table-row');
        caretakerFields.forEach(f => f.style.display = 'table-row');
    }

    if (role === 'daycare') {
        daycareFields.forEach(field => field.style.display = 'table-row');
    }

    if (role === 'hospital') {
        hospitalFields.forEach(field => field.style.display = 'table-row');
    }

    const dept = document.getElementById('department');
    const hospitalId = document.getElementById('hospital_id');
    const hospitalName = document.getElementById('hospital_name');
    const centerName = document.getElementById('center_name');
    const firstname = document.getElementById('firstname');
    const lastname = document.getElementById('lastname');
    const dob = document.getElementById('DOB');

    if (dept) dept.required = role === 'doctor';
    if (hospitalId) hospitalId.required = role === 'doctor';
    if (hospitalName) hospitalName.required = role === 'hospital';
    if (centerName) centerName.required = role === 'daycare';

    if (firstname) firstname.required = role === 'parent' || role === 'doctor' || role === 'caretaker';
    if (lastname) lastname.required = role === 'parent' || role === 'doctor' || role === 'caretaker';
    if (dob) dob.required = role === 'parent' || role === 'doctor' || role === 'caretaker';
}

document.addEventListener('DOMContentLoaded', function () {
    const role = document.getElementById('role');
    if (role) {
        role.addEventListener('change', toggleRoleFields);
        toggleRoleFields();
    }
});
</script>
</body>
</html>