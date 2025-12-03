<?php

class Auth {
  private $file;

  public function __construct($file = null)
  {
    if ($file === null)
    {
      $file = __DIR__ . '/../data/users.json';
    }

    $this->file = $file;

    if(!file_exists($this->file))
    {
      file_put_contents($this->file, json_encode([]));
    }
  }

  private function loadUsers()
  {
    $json = file_get_contents($this->file);
    return json_decode($json, true);
  }

  private function saveUsers($users)
  {
    file_put_contents($this->file, json_encode($users, JSON_PRETTY_PRINT));
  }

  public function registerUser(User $user)
{
    $users = $this->loadUsers();

    // Check email duplicate
    foreach ($users as $u) {
        if ($u['email'] === $user->getEmail()) {
            $_SESSION['error'] = "Email already taken.";
            header("Location: register.php");
            exit();
        }
    }

    // Hash password
    $hashed = password_hash($user->getPassword(), PASSWORD_DEFAULT);

    // Add new user
    $users[] = [
        'id'       => uniqid(),
        'email'    => $user->getEmail(),
        'password' => $hashed,
        'role'     => $user->getRole() ?? 'staff'
    ];

    // Save JSON
    $this->saveUsers($users);

    // Redirect success
    header("Location: login.php");
    exit();
}


  public function loginUser($email, $password)
  {
    $users = $this->loadUsers();

    foreach($users as $user)
    {
      if($user['email'] == $email)
      {
        if(password_verify($password, $user['password'])){
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_email'] = $user['email'];

        if($_SESSION['role'] === 'admin')
        {
          header("Location: admin/dashboard.php");
          exit();
        }

        header('Location: index.php');
        exit();

        }else{
          $_SESSION['error'] = "Incorrect password";
          header("Location: login.php");
          exit();
        }

      }

    }
      $_SESSION['error'] = "User not found.";
      header("Location: login.php");
      exit();
  }

  public function logout()
  {
    session_start();
    session_unset();
    session_destroy();

    header("Location: ../login.php");
    exit();
  }

}