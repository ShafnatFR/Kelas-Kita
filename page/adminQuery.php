<?php
class AdminQuery {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    /**
     * Get total count dari table tertentu
     */
    public function getTotalCount($table, $condition = '') {
        try {
            $sql = "SELECT COUNT(*) as total FROM $table";
            if (!empty($condition)) {
                $sql .= " WHERE $condition";
            }
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            
            return $data['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Error in getTotalCount: " . $e->getMessage());
            return 0;
        }
    }

    // BY - Shaf

    public function getCountReportUser(){
        
    }

    /**
     * Get semua data user untuk dashboard
     */
    public function getAllUsers() {
        try {
            $sql = "
                SELECT id_user, 
                       CASE 
                           WHEN first_name IS NOT NULL AND last_name IS NOT NULL 
                           THEN CONCAT(first_name, ' ', last_name)
                           ELSE username
                       END AS fullname, 
                       username,
                       email,
                       role,
                       status,
                       created_at
                FROM tb_user
                ORDER BY id_user ASC
            ";
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing user statement: " . $this->conn->error);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getAllUsers: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get semua data kelas
     */
    public function getAllKelas() {
        try {
            $sql = "
                SELECT id_kelas, 
                       nama_kelas, 
                       deskripsi, 
                       harga,
                       created_at
                FROM tb_kelas
                ORDER BY id_kelas ASC
            ";
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing kelas statement: " . $this->conn->error);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error in getAllKelas: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get total transaksi yang completed
     */
    public function getTotalTransaksi() {
        try {
            $sql = "
                SELECT COALESCE(SUM(k.harga), 0) AS total_transaksi
                FROM tb_kelas k
                LEFT JOIN tb_keranjang kk ON kk.id_kelas = k.id_kelas
                LEFT JOIN tb_transaksi tk ON tk.id_keranjang = kk.id_keranjang
                WHERE tk.status = 'Completed'
            ";
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                // Fallback query jika join bermasalah
                return 0;
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            
            return $data['total_transaksi'] ?? 0;
        } catch (Exception $e) {
            error_log("Error in getTotalTransaksi: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get statistik lengkap untuk dashboard
     */
    public function getDashboardStats() {
        return [
            'total_users' => $this->getTotalCount('tb_user'),
            'total_kelas' => $this->getTotalCount('tb_kelas'),
            'total_materi' => $this->getTotalCount('tb_materi'),
            'total_transaksi' => $this->getTotalTransaksi(),
            'total_user_active' => $this->getTotalCount('tb_user', "status = 'active'"),
            'total_user_inactive' => $this->getTotalCount('tb_user', "status = 'inactive'"),
            'total_mentor' => $this->getTotalCount('tb_user', "role = 'mentor'"),
            'total_student' => $this->getTotalCount('tb_user', "role = 'student'")
        ];
    }
    
    /**
     * Get user berdasarkan ID
     */
    public function getUserById($id) {
        try {
            $sql = "
                SELECT id_user, username, first_name, last_name, email, role, status
                FROM tb_user 
                WHERE id_user = ?
            ";
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }
            
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error in getUserById: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update user
     */
    public function updateUser($id, $data) {
        try {
            $sql = "
                UPDATE tb_user 
                SET username = ?, first_name = ?, last_name = ?, email = ?, role = ?, status = ?
                WHERE id_user = ?
            ";
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }
            
            $stmt->bind_param("ssssssi", 
                $data['username'], 
                $data['first_name'], 
                $data['last_name'], 
                $data['email'], 
                $data['role'], 
                $data['status'], 
                $id
            );
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in updateUser: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete user
     */
    public function deleteUser($id) {
        try {
            $sql = "DELETE FROM tb_user WHERE id_user = ?";
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }
            
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error in deleteUser: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Search users dengan filter
     */
    public function searchUsers($search = '', $role = '', $status = '') {
        try {
            $sql = "
                SELECT id_user, 
                       CASE 
                           WHEN first_name IS NOT NULL AND last_name IS NOT NULL 
                           THEN CONCAT(first_name, ' ', last_name)
                           ELSE username
                       END AS fullname, 
                       username, email, role, status
                FROM tb_user
                WHERE 1=1
            ";
            
            $params = [];
            $types = '';
            
            if (!empty($search)) {
                $sql .= " AND (username LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
                $searchParam = "%$search%";
                $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
                $types .= 'ssss';
            }
            
            if (!empty($role)) {
                $sql .= " AND role = ?";
                $params[] = $role;
                $types .= 's';
            }
            
            if (!empty($status)) {
                $sql .= " AND status = ?";
                $params[] = $status;
                $types .= 's';
            }
            
            $sql .= " ORDER BY id_user ASC";
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $this->conn->error);
            }
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error in searchUsers: " . $e->getMessage());
            return [];
        }
    }
}
?>