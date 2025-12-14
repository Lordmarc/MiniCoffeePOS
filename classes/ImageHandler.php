<?php

class ImageHandler {

  private $uploadDir;

  public function __construct($uploadDir = null)
  {
    if($uploadDir == null)
    {
      $uploadDir = __DIR__ . '/../upload/';
    }

    $this->uploadDir = rtrim($uploadDir, '/') . '/';

    if(!file_exists($this->uploadDir))
    {
      mkdir($this->uploadDir, 0755, true);
    }
  }

  public function upload($file)
  {
    if(empty($file['name']))
    {
      return null;
    }

    $allowedTypes = [
      "image/jpeg",
      "image/png",
      "image/webp"
    ];

    if(!in_array($file['type'], $allowedTypes))
    {
      throw new Exception("Only JPEG, PNG, and WEBP are allowed.");
    }

    if($file['size'] > 3 * 1024 * 1024)
    {
      throw new Exception("Max file size if 3MB");
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = uniqid() . '.' . $ext;
    $targetPath = $this->uploadDir . $newName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
          // return relative path for JSON/db
          return 'upload/' . $newName;
      }

      return false; // upload failed
  }
}