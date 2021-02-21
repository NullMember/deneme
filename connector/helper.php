<?php

$lastError = 0;

function addUser(mysqli $conn, string $name, string $surname, string $email, string $pass){
    global $lastError;

    $name = stripslashes($name);
    $name = mysqli_real_escape_string($conn, $name);
    $surname = stripslashes($surname);
    $surname = mysqli_real_escape_string($conn, $surname);
    $email = stripslashes($email);
    $email = mysqli_real_escape_string($conn, $email);
    $pass = stripslashes($pass);
    $pass = mysqli_real_escape_string($conn, $pass);

    $hash = hash('sha256', $pass);

    $stmt = new mysqli_stmt($conn);
    $stmt->prepare("INSERT INTO users (name, surname, email, pass) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $name, $surname, $email, $hash);
    if(!$stmt->execute()){
        $lastError = $stmt->errno;
        return;
    }

    $lastError = false;
    return;
}

function removeUser(mysqli $conn, string $email){
    global $lastError;

    $email = stripslashes($email);
    $email = mysqli_real_escape_string($conn, $email);

    $stmt = new mysqli_stmt($conn);
    $stmt->prepare("DELETE FROM users WHERE email=?");
    $stmt->bind_param('s', $email);
    if(!$stmt->execute()){
        $lastError = $stmt->errno;
        return;
    }

    $lastError = false;
    return;
}

function getUserID(mysqli $conn, string $email, string $pass){
    global $lastError;

    $email = stripslashes($email);
    $email = mysqli_real_escape_string($conn, $email);
    $pass = stripslashes($pass);
    $pass = mysqli_real_escape_string($conn, $pass);

    $phash = hash('sha256', $pass);

    $stmt = new mysqli_stmt($conn);
    $stmt->prepare("SELECT userid, pass FROM users WHERE email=?");
    $stmt->bind_param('s', $email);
    if(!$stmt->execute()){
        $lastError = $stmt->errno;
        return;
    }
    $stmt->bind_result($userid, $hash);
    if(!$stmt->fetch()){
        $lastError = $stmt->errno;
        return;
    }

    if(strcmp($hash, $phash) == 0){
        $lastError = false;
        return $userid;
    }
    else{
        $lastError = true;
        return;
    }
}

function getUser(mysqli $conn, int $userid){
    global $lastError;

    $stmt = new mysqli_stmt($conn);
    $stmt->prepare("SELECT name, surname, email, register_date, validated, validate_hash, is_admin FROM users WHERE userid=?");
    $stmt->bind_param('s', $userid);
    if(!$stmt->execute()){
        $lastError = $stmt->errno;
        return;
    }

    $stmt->bind_result($name, $surname, $email, $register_date, $validated, $validate_hash, $is_admin);
    if(!$stmt->fetch()){
        $lastError = $stmt->errno;
        return;
    }

    $lastError = false;
    return ["name" => $name, "surname" => $surname, "email" => $email, "register_date" => $register_date, "validated" => $validated, "validate_hash" => $validate_hash, "is_admin" => $is_admin];
}

function validateUser(mysqli $conn, string $email, string $validate_hash){
    global $lastError;

    $stmt = new mysqli_stmt($conn);
    $stmt->prepare("SELECT validate_hash FROM users WHERE email=?");
    $stmt->bind_param('s', $email);
    if(!$stmt->execute()){
        $lastError = $stmt->errno;
        return;
    }

    $stmt->bind_result($validate);
    if(!$stmt->fetch()){
        $lastError = $stmt->errno;
        return;
    }

    if(strcmp($validate_hash, $validate) == 0){
        $stmt->prepare("UPDATE users SET validated=1 WHERE email=?");
        $stmt->bind_param('s', $email);
        if(!$stmt->execute()){
            $lastError = $stmt->errno;
            return;
        }
    }
    else{
        $lastError = true;
        return;
    }
    $lastError = false;
    return;
}

function isValidated(mysqli $conn, string $email){
    global $lastError;

    $stmt = new mysqli_stmt($conn);
    $stmt->prepare("SELECT validated FROM users WHERE email=?");
    $stmt->bind_param('s', $email);
    if(!$stmt->execute()){
        $lastError = $stmt->errno;
        return;
    }

    $stmt->bind_result($isvalidated);
    if(!$stmt->fetch()){
        $lastError = $stmt->errno;
        return;
    }

    $lastError = false;
    return $isvalidated;
}

function lastError(){
    global $lastError;
    return $lastError;
}

function isSuccesfull(){
    global $lastError;
    return !$lastError;
}

?>