<?php
// cart_db_integration.php

function getCartItems($id_user, $conn) {
    $cart_items = [];
    
    // Get cart items with complete course data
    $stmt = $conn->prepare("
        SELECT c.id_kelas, c.tgl_keranjang, 
               k.nama_kelas, k.harga, k.kategori, k.profil_kelas as gambar, k.description as deskripsi
        FROM tb_keranjang c 
        JOIN tb_kelas k ON c.id_kelas = k.id_kelas 
        WHERE c.id_user = ?
    ");
    
    if ($stmt) {
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            // Try different possible column names for course title
            $course_name = '';
            if (!empty($row['judul_kelas'])) {
                $course_name = $row['judul_kelas'];
            } elseif (!empty($row['nama_kelas'])) {
                $course_name = $row['nama_kelas'];
            } else {
                $course_name = 'Kursus Tanpa Judul';
            }
            
            $cart_items[] = [
                'id' => $row['id_kelas'],
                'name' => $course_name,
                'price' => $row['harga'],
                'tgl_keranjang' => $row['tgl_keranjang'],
                'category' => $row['kategori'] ?? 'Umum',
                'image' => $row['gambar'] ?? '',
                'description' => $row['deskripsi'] ?? ''
            ];
        }
        $stmt->close();
    }
    
    return $cart_items;
}

function addToCart($id_kelas, $id_user, $conn) {
    // Check if item already exists in cart
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM tb_keranjang WHERE id_user = ? AND id_kelas = ?");
    if ($stmt) {
        $stmt->bind_param("ii", $id_user, $id_kelas);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            // Item already in cart, do nothing or update date
            // Optionally update tgl_keranjang here if needed
        } else {
            // Add new item
            $insert_stmt = $conn->prepare("INSERT INTO tb_keranjang (id_user, id_kelas, tgl_keranjang) VALUES (?, ?, CURDATE())");
            if ($insert_stmt) {
                $insert_stmt->bind_param("ii", $id_user, $id_kelas);
                $insert_stmt->execute();
                $insert_stmt->close();
            }
        }
        $stmt->close();
    }
    
    // Update session cart
    if (isset($_SESSION['cart'])) {
        // Check if item exists in session cart
        $found = false;
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['id'] == $id_kelas) {
                $found = true;
                break;
            }
        }
        
        // If not found, get course data and add to session
        if (!$found) {
            $course_stmt = $conn->prepare("SELECT nama_kelas, harga, kategori, profil_kelas as gambar, description as deskripsi FROM tb_kelas WHERE id_kelas = ?");
            if ($course_stmt) {
                $course_stmt->bind_param("i", $id_kelas);
                $course_stmt->execute();
                $course_result = $course_stmt->get_result();
                
                if ($course_row = $course_result->fetch_assoc()) {
                    // Try different possible column names for course title
                    $course_name = '';
                    if (!empty($course_row['judul_kelas'])) {
                        $course_name = $course_row['judul_kelas'];
                    } elseif (!empty($course_row['nama_kelas'])) {
                        $course_name = $course_row['nama_kelas'];
                    } else {
                        $course_name = 'Kursus Tanpa Judul';
                    }
                    
                    $_SESSION['cart'][] = [
                        'id' => $id_kelas,
                        'name' => $course_name,
                        'price' => $course_row['harga'],
                        'category' => $course_row['kategori'] ?? 'Umum',
                        'image' => $course_row['gambar'] ?? ''
                    ];
                }
                $course_stmt->close();
            }
        }
    }
}

function removeFromCart($id_kelas, $id_user, $conn) {
    // Remove from database
    if ($id_user) {
        // Check if cart item is referenced by any transaction
        $check_stmt = $conn->prepare("SELECT COUNT(*) as count FROM tb_transaksi t JOIN tb_keranjang k ON t.id_keranjang = k.id_keranjang WHERE k.id_user = ? AND k.id_kelas = ?");
        if ($check_stmt) {
            $check_stmt->bind_param("ii", $id_user, $id_kelas);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            $row = $result->fetch_assoc();
            $check_stmt->close();
            
            if ($row['count'] > 0) {
                // Cart item is referenced by transaction, do not delete
                return false; // Indicate failure to delete
            }
        }
        
        $stmt = $conn->prepare("DELETE FROM tb_keranjang WHERE id_user = ? AND id_kelas = ?");
        if ($stmt) {
            $stmt->bind_param("ii", $id_user, $id_kelas);
            $stmt->execute();
            $stmt->close();
        }
    }
    
    // Remove from session
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['id'] == $id_kelas) {
                unset($_SESSION['cart'][$index]);
                $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
                break;
            }
        }
    }
    return true; // Indicate successful deletion
}

function clearCart($id_user, $conn) {
    // Clear database cart
    if ($id_user) {
        $stmt = $conn->prepare("DELETE FROM tb_keranjang WHERE id_user = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $stmt->close();
        }
    }
    
    // Clear session cart
    $_SESSION['cart'] = [];
}

function updateDatabaseCart($id_user, $conn) {
    // Clear existing cart in database
    $stmt = $conn->prepare("DELETE FROM tb_keranjang WHERE id_user = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $stmt->close();
    }
    
    // Insert current session cart to database
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $insert_stmt = $conn->prepare("INSERT INTO tb_keranjang (id_user, id_kelas, tgl_keranjang) VALUES (?, ?, CURDATE())");
        if ($insert_stmt) {
            foreach ($_SESSION['cart'] as $item) {
                $insert_stmt->bind_param("ii", $id_user, $item['id']);
                $insert_stmt->execute();
            }
            $insert_stmt->close();
        }
    }
}

function getCartCount($id_user = null, $conn = null) {
    $count = 0;
    
    // If user is logged in and database connection is available
    if ($id_user && $conn) {
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM tb_keranjang WHERE id_user = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $count = $row['total'] ?? 0;
            }
            $stmt->close();
        }
    } else {
        // Fall back to session cart
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $count += 1;
            }
        }
    }
    
    return $count;
}

function syncCartToSession($id_user, $conn) {
    // Get cart items from database and sync to session
    $_SESSION['cart'] = getCartItems($id_user, $conn);
}

function syncCartToDatabase($id_user, $conn) {
    // Sync session cart to database
    updateDatabaseCart($id_user, $conn);
}
?>