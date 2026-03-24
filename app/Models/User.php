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
            (role, first_name, last_name, gender, dob, certificate_path, blood_group, height, weight, mother_name, father_name, address, email, phone, username, password_hash)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $stmt->bind_param(
            'ssssssssssssssss',
            $data['role'],
            $data['firstname'],
            $data['lastname'],
            $data['gender'],
            $data['DOB'],
            $data['uploadcertificate'],
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
}