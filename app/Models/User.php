<?php

class User
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function usernameExists(string $username): bool
    {
        $stmt = $this->conn->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function createUser(array $data): int
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO users
            (role, first_name, last_name, gender, dob, certificate_path, qualification_certificate, blood_group, height, weight, mother_name, father_name, address, email, phone, username, password_hash)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $stmt->bind_param(
            'sssssssssssssssss',
            $data['role'],
            $data['firstname'],
            $data['lastname'],
            $data['gender'],
            $data['DOB'],
            $data['uploadcertificate'],
            $data['qualification_certificate'],
            $data['bloodgroup'],
            $data['height'],
            $data['weight'],
            $data['mothername'],
            $data['fathername'],
            $data['address'],
            $data['emailid'],
            $data['phonenumber'],
            $data['username'],
            $data['password_hash']
        );

        $stmt->execute();
        return (int)$stmt->insert_id;
    }

    public function createLegacyLogin(int $userId, string $username, string $passwordHash): void
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO login (user_id, Username, Password) VALUES (?, ?, ?)'
        );
        $stmt->bind_param('iss', $userId, $username, $passwordHash);
        $stmt->execute();
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user ?: null;
    }

    public function verifyLogin(string $username, string $password): ?array
    {
        $user = $this->findByUsername($username);

        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return null;
        }

        return $user;
    }

    // =========================
    // DOCTOR
    // =========================
    public function createDoctorProfile(int $userId, array $data): void
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO doctors 
            (user_id, doctor_name, department, qualification, hospital_id, email, phone)
            VALUES (?, ?, ?, ?, ?, ?, ?)'
        );

        $doctorName = trim($data['firstname'] . ' ' . $data['lastname']);

        $stmt->bind_param(
            'isssiss',
            $userId,
            $doctorName,
            $data['department'],
            $data['qualification'],
            $data['hospital_id'], // ✅ NEW
            $data['emailid'],
            $data['phonenumber']
        );

        $stmt->execute();
    }

    // =========================
    // HOSPITAL
    // =========================
    public function createHospitalProfile(int $userId, array $data): void
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO hospitals
            (user_id, hospital_name, registration_number, hospital_type, contact_person,
             email, phone, address, city, state, pincode, opening_time, closing_time, description)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $stmt->bind_param(
            'isssssssssssss',
            $userId,
            $data['hospital_name'],
            $data['registration_number'],
            $data['hospital_type'],
            $data['contact_person'],
            $data['emailid'],
            $data['phonenumber'],
            $data['address'],
            $data['city'],
            $data['state'],
            $data['pincode'],
            $data['hospital_opening_time'],
            $data['hospital_closing_time'],
            $data['hospital_description']
        );

        $stmt->execute();
    }

    // =========================
    // CARETAKER
    // =========================
    public function createCaretakerProfile(int $userId, array $data): void
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO caretakers
            (user_id, caretaker_name, experience_years, skills, availability, fee, preferred_location, email, phone)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $caretakerName = trim($data['firstname'] . ' ' . $data['lastname']);

        $stmt->bind_param(
            'issssssss',
            $userId,
            $caretakerName,
            $data['experience_years'],
            $data['skills'],
            $data['availability'],
            $data['fee'],
            $data['preferred_location'],
            $data['emailid'],
            $data['phonenumber']
        );

        $stmt->execute();
    }

    // =========================
    // DAYCARE
    // =========================
    public function createDaycareProfile(int $userId, array $data): void
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO daycares
            (user_id, center_name, manager_name, capacity, opening_time, closing_time, age_group_supported, facilities, description, email, phone, address)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $managerName = trim($data['firstname'] . ' ' . $data['lastname']);

        $stmt->bind_param(
            'ississssssss',
            $userId,
            $data['center_name'],
            $managerName,
            $data['capacity'],
            $data['opening_time'],
            $data['closing_time'],
            $data['age_group_supported'],
            $data['facilities'],
            $data['daycare_description'],
            $data['emailid'],
            $data['phonenumber'],
            $data['address']
        );

        $stmt->execute();
    }
}