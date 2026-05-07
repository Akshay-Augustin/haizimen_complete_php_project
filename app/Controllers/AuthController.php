<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Helpers/helpers.php';

class AuthController
{
    private mysqli $conn;
    private User $userModel;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
        $this->userModel = new User($conn);
    }

    public function registerUser(): array
    {
        $errors = [];

        $data = [
            'role' => trim($_POST['role'] ?? ''),
            'firstname' => trim($_POST['firstname'] ?? ''),
            'lastname' => trim($_POST['lastname'] ?? ''),
            'gender' => trim($_POST['gender'] ?? ''),
            'DOB' => trim($_POST['DOB'] ?? ''),
            'bloodgroup' => trim($_POST['bloodgroup'] ?? ''),
            'height' => trim($_POST['height'] ?? ''),
            'weight' => trim($_POST['weight'] ?? ''),
            'mothername' => trim($_POST['mothername'] ?? ''),
            'fathername' => trim($_POST['fathername'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'emailid' => trim($_POST['emailid'] ?? ''),
            'phonenumber' => trim($_POST['phonenumber'] ?? ''),
            'username' => trim($_POST['username'] ?? ''),
            'uploadcertificate' => '',
            'qualification_certificate' => '',

            // doctor
            'department' => trim($_POST['department'] ?? ''),
            'qualification' => trim($_POST['qualification'] ?? ''),
            'clinic_name' => trim($_POST['clinic_name'] ?? ''),
            'hospital_id' => (int)($_POST['hospital_id'] ?? 0),

            // hospital
            'hospital_name' => trim($_POST['hospital_name'] ?? ''),
            'registration_number' => trim($_POST['registration_number'] ?? ''),
            'hospital_type' => trim($_POST['hospital_type'] ?? ''),
            'contact_person' => trim($_POST['contact_person'] ?? ''),
            'hospital_opening_time' => trim($_POST['hospital_opening_time'] ?? ''),
            'hospital_closing_time' => trim($_POST['hospital_closing_time'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'state' => trim($_POST['state'] ?? ''),
            'pincode' => trim($_POST['pincode'] ?? ''),
            'hospital_description' => trim($_POST['hospital_description'] ?? ''),

            // caretaker
            'experience_years' => trim($_POST['experience_years'] ?? ''),
            'skills' => trim($_POST['skills'] ?? ''),
            'availability' => trim($_POST['availability'] ?? ''),
            'fee' => trim($_POST['fee'] ?? ''),
            'preferred_location' => trim($_POST['preferred_location'] ?? ''),

            // daycare
            'center_name' => trim($_POST['center_name'] ?? ''),
            'capacity' => trim($_POST['capacity'] ?? ''),
            'opening_time' => trim($_POST['opening_time'] ?? ''),
            'closing_time' => trim($_POST['closing_time'] ?? ''),
            'age_group_supported' => trim($_POST['age_group_supported'] ?? ''),
            'facilities' => trim($_POST['facilities'] ?? ''),
            'daycare_description' => trim($_POST['daycare_description'] ?? ''),
        ];

        $password = $_POST['password'] ?? '';

        /*
         * Daycare does not show personal fields in the form.
         * But users table still requires first_name, last_name, gender, dob.
         * So we provide safe defaults here.
         */
        if ($data['role'] === 'daycare') {
            if ($data['firstname'] === '') {
                $data['firstname'] = $data['center_name'] !== '' ? $data['center_name'] : 'Daycare';
            }
            if ($data['lastname'] === '') {
                $data['lastname'] = 'Center';
            }
            if ($data['gender'] === '') {
                $data['gender'] = 'others';
            }
            if ($data['DOB'] === '') {
                $data['DOB'] = '2000-01-01';
            }
        }

        /*
         * Hospital also does not show personal fields in the form.
         * So we provide safe defaults here.
         */
        if ($data['role'] === 'hospital') {
            if ($data['firstname'] === '') {
                $data['firstname'] = $data['hospital_name'] !== '' ? $data['hospital_name'] : 'Hospital';
            }
            if ($data['lastname'] === '') {
                $data['lastname'] = 'Admin';
            }
            if ($data['gender'] === '') {
                $data['gender'] = 'others';
            }
            if ($data['DOB'] === '') {
                $data['DOB'] = '2000-01-01';
            }
        }

        remember_old_input($data);

        $allowedRoles = ['parent', 'doctor', 'hospital', 'caretaker', 'daycare'];

        if (!in_array($data['role'], $allowedRoles, true)) {
            $errors[] = 'Please select a valid role.';
        }

        if (!filter_var($data['emailid'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required.';
        }

        if ($data['username'] === '') {
            $errors[] = 'Username is required.';
        }

        if (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        if ($data['phonenumber'] === '') {
            $errors[] = 'Phone number is required.';
        }

        // role-based validation for roles that require personal fields
        if (!in_array($data['role'], ['daycare', 'hospital'], true)) {
            if ($data['firstname'] === '') {
                $errors[] = 'First name is required.';
            }

            if ($data['lastname'] === '') {
                $errors[] = 'Last name is required.';
            }

            if (!in_array($data['gender'], ['male', 'female', 'others'], true)) {
                $errors[] = 'Gender is invalid.';
            }

            if ($data['DOB'] === '') {
                $errors[] = 'Date of birth is required.';
            }
        }

        if (!empty($_FILES['qualification_certificate']['name'])) {
            $upload = $this->handleCertificateUpload($_FILES['qualification_certificate']);
            if (!empty($upload['error'])) {
                $errors[] = $upload['error'];
            } else {
                $data['qualification_certificate'] = $upload['path'];
            }
        }

        if ($data['role'] === 'doctor' && $data['department'] === '') {
            $errors[] = 'Department is required for doctor registration.';
        }

        if ($data['role'] === 'doctor' && $data['hospital_id'] <= 0) {
            $errors[] = 'Hospital selection is required for doctor registration.';
        }

        if ($data['role'] === 'hospital' && $data['hospital_name'] === '') {
            $errors[] = 'Hospital name is required for hospital registration.';
        }

        if ($data['role'] === 'hospital' && $data['registration_number'] === '') {
            $errors[] = 'Registration number is required for hospital registration.';
        }

        if ($data['role'] === 'hospital' && $data['contact_person'] === '') {
            $errors[] = 'Contact person is required for hospital registration.';
        }

        if ($data['role'] === 'caretaker' && $data['experience_years'] === '') {
            $errors[] = 'Experience is required for caretaker registration.';
        }

        if ($data['role'] === 'daycare' && $data['center_name'] === '') {
            $errors[] = 'Center name is required for daycare registration.';
        }

        if ($this->userModel->usernameExists($data['username'])) {
            $errors[] = 'Username already exists.';
        }

        if ($this->userModel->emailExists($data['emailid'])) {
            $errors[] = 'Email already exists.';
        }

        if (!empty($_FILES['uploadcertificate']['name'])) {
            $upload = $this->handleCertificateUpload($_FILES['uploadcertificate']);
            if (!empty($upload['error'])) {
                $errors[] = $upload['error'];
            } else {
                $data['uploadcertificate'] = $upload['path'];
            }
        }

        if ($errors) {
            return ['success' => false, 'errors' => $errors];
        }

        $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);

        $userId = $this->userModel->createUser($data);

        if ($data['role'] === 'doctor') {
            $this->userModel->createDoctorProfile($userId, $data);
        }

        if ($data['role'] === 'hospital') {
            $this->userModel->createHospitalProfile($userId, $data);
        }

        if ($data['role'] === 'caretaker') {
            $this->userModel->createCaretakerProfile($userId, $data);
        }

        if ($data['role'] === 'daycare') {
            $this->userModel->createDaycareProfile($userId, $data);
        }

        $this->userModel->createLegacyLogin($userId, $data['username'], $data['password_hash']);

        clear_old_input();
        flash_set('success', 'Registration completed successfully.');

        return ['success' => true];
    }

    public function registerParent(): array
    {
        $_POST['role'] = 'parent';
        return $this->registerUser();
    }

    public function login(): array
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            return ['success' => false, 'errors' => ['Username and password are required.']];
        }

        $user = $this->userModel->verifyLogin($username, $password);

        if (!$user) {
            return ['success' => false, 'errors' => ['Invalid username or password.']];
        }

        $_SESSION['auth'] = [
            'id' => $user['id'],
            'role' => $user['role'],
            'name' => trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')),
            'username' => $user['username'],
            'email' => $user['email'],
        ];

        flash_set('success', 'Login successful.');

        $redirect = 'dashboard.php';
        if ($user['role'] === 'admin') {
            $redirect = 'admin_dashboard.php';
        }

        return [
            'success' => true,
            'redirect' => $redirect
        ];
    }

    public function logout(): void
    {
        unset($_SESSION['auth']);
        flash_set('Logged out successfully.');
    }

    private function handleCertificateUpload(array $file): array
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return ['error' => 'Certificate upload failed.'];
        }

        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed, true)) {
            return ['error' => 'Certificate must be jpg, jpeg, png, or pdf.'];
        }

        if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
            return ['error' => 'Certificate file is too large.'];
        }

        $dir = __DIR__ . '/../../public/uploads/certificates/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $name = uniqid('cert_', true) . '.' . $ext;
        $target = $dir . $name;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            return ['error' => 'Could not save uploaded certificate.'];
        }

        return ['path' => 'public/uploads/certificates/' . $name];
    }
}