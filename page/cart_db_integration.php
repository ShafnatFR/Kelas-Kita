<?php
// Functions for cart database integration

/**
 * Syncs the session cart with database cart when user logs in
 * @param int $user_id User ID
 * @param mysqli $conn Database connection
 */
function syncCartOnLogin($user_id, $conn) {
    // 1. Get user's cart from database
    $db_cart = [];
    $sql = "SELECT uc.course_id, k.nama_kursus, k.harga, k.gambar, kat.nama_kategori 
            FROM user_cart uc 
            JOIN kursus k ON uc.course_id = k.id 
            LEFT JOIN kategori_kursus kat ON k.kategori_id = kat.id 
            WHERE uc.user_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $db_cart[] = [
            'id' => $row['course_id'],
            'name' => $row['nama_kursus'],
            'price' => $row['harga'],
            'quantity' => 1, // Courses typically have quantity of 1
            'image' => $row['gambar'],
            'category' => $row['nama_kategori']
        ];
    }
    
    // 2. Merge with session cart (prioritize session items if duplicates)
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Create a lookup of course IDs in session cart
    $session_course_ids = [];
    foreach ($_SESSION['cart'] as $item) {
        $session_course_ids[] = $item['id'];
    }
    
    // Add database items that are not in session
    foreach ($db_cart as $db_item) {
        if (!in_array($db_item['id'], $session_course_ids)) {
            $_SESSION['cart'][] = $db_item;
        }
    }
    
    // 3. Update database to match session
    updateDatabaseCart($user_id, $conn);
}

/**
 * Updates the database cart to match session cart
 * @param int $user_id User ID
 * @param mysqli $conn Database connection
 */
function updateDatabaseCart($user_id, $conn) {
    // Only proceed if user is logged in
    if (!$user_id) return;
    
    // 1. Clear existing cart
    $sql = "DELETE FROM user_cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    // 2. Insert current session cart items to database
    if (!empty($_SESSION['cart'])) {
        $sql = "INSERT INTO user_cart (user_id, course_id, price, added_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        
        foreach ($_SESSION['cart'] as $item) {
            $stmt->bind_param("iid", $user_id, $item['id'], $item['price']);
            $stmt->execute();
        }
    }
}

/**
 * Adds item to both session cart and database if user is logged in
 * @param array $item Item details
 * @param int $user_id User ID
 * @param mysqli $conn Database connection
 * @return bool Success status
 */
function addToCart($item, $user_id, $conn) {
    // 1. Check if item already exists in cart
    $exists = false;
    
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $index => $cart_item) {
            if ($cart_item['id'] == $item['id']) {
                $exists = true;
                break;
            }
        }
    } else {
        $_SESSION['cart'] = [];
    }
    
    // 2. Add to session cart if not already there
    if (!$exists) {
        $_SESSION['cart'][] = $item;
        
        // 3. Add to database if user is logged in
        if ($user_id) {
            $sql = "INSERT INTO user_cart (user_id, course_id, price, added_at) 
                    VALUES (?, ?, ?, NOW()) 
                    ON DUPLICATE KEY UPDATE price = ?, added_at = NOW()";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iidi", $user_id, $item['id'], $item['price'], $item['price']);
            $stmt->execute();
        }
        
        return true;
    }
    
    return false; // Item already in cart
}

/**
 * Removes item from cart (both session and database)
 * @param int $item_id Course ID to remove
 * @param int $user_id User ID
 * @param mysqli $conn Database connection
 */
function removeFromCart($item_id, $user_id, $conn) {
    // 1. Remove from session
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['id'] == $item_id) {
                array_splice($_SESSION['cart'], $index, 1);
                break;
            }
        }
    }
    
    // 2. Remove from database if user is logged in
    if ($user_id) {
        $sql = "DELETE FROM user_cart WHERE user_id = ? AND course_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $item_id);
        $stmt->execute();
    }
}

/**
 * Clears the entire cart (both session and database)
 * @param int $user_id User ID
 * @param mysqli $conn Database connection
 */
function clearCart($user_id, $conn) {
    // 1. Clear session cart
    $_SESSION['cart'] = [];
    
    // 2. Clear database cart if user is logged in
    if ($user_id) {
        $sql = "DELETE FROM user_cart WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }
}

/**
 * Gets the current cart items (preferably from session)
 * @param int $user_id User ID (optional, for backup if session empty)
 * @param mysqli $conn Database connection
 * @return array Cart items
 */
function getCartItems($user_id = null, $conn = null) {
    // Return session cart if available
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        return $_SESSION['cart'];
    }
    
    // If session cart is empty but user is logged in, try getting from database
    if ($user_id && $conn) {
        $cart = [];
        $sql = "SELECT uc.course_id, k.nama_kursus, k.harga, k.gambar, kat.nama_kategori 
                FROM user_cart uc 
                JOIN kursus k ON uc.course_id = k.id 
                LEFT JOIN kategori_kursus kat ON k.kategori_id = kat.id 
                WHERE uc.user_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $cart[] = [
                'id' => $row['course_id'],
                'name' => $row['nama_kursus'],
                'price' => $row['harga'],
                'quantity' => 1,
                'image' => $row['gambar'],
                'category' => $row['nama_kategori']
            ];
        }
        
        // Update session with database cart
        $_SESSION['cart'] = $cart;
        return $cart;
    }
    
    // Default: empty cart
    return [];
}
