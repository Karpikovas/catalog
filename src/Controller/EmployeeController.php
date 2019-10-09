<?php


namespace App\Controller;


use App\Lib\LibEmployee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

class EmployeeController extends AbstractController
{
  public function getEmployees(LibEmployee $employee) {
    $employees = $employee->getEmployees();
    return $this->json(['status' => "OK", 'message' => [], 'data' => $employees]);
  }

  public function uploadImage(Request $request, $ID, ParameterBagInterface $params, LibEmployee $employee) {
    $fileDir = $params->get('FILE_DIRECTORY');

    $originalFilename = $_FILES['path']['name'];

    $type = pathinfo($originalFilename, PATHINFO_EXTENSION);
    $tmp_path = $_FILES['path']['tmp_name'];

    $safeFileName = explode('/',$tmp_path);
    $safeFileName = uniqid().array_pop($safeFileName);

    $photo = $safeFileName.'.'.$type;

    list($width, $height) = getimagesize($tmp_path);

    if ($width >= 168 && $height >= 168) {
      $size = $width > $height ? $height : $width;
      $image = new \Imagick($tmp_path);
      $image->cropImage($size, $size, ($width - $size) / 2, ($height - $size) / 2);
      $image->adaptiveResizeImage(168, 168);
      $image->writeImage($tmp_path);
    }

    move_uploaded_file($tmp_path, $fileDir.'/'.$photo);

    $employee->addEmployeePhoto($ID, $photo);

    return $this->json(['status' => "OK", 'message' => [], 'data' => []]);
  }

  public function downloadImage($ID, ParameterBagInterface $params, LibEmployee $employee)
  {

    $fileDir = $params->get('FILE_DIRECTORY');
    $path = $employee->getPhotoPathByID($ID);


    if ($path[0]["photo"]) {
      $file = $fileDir.'/'.$path[0]["photo"];
    } else {
      $file = $fileDir.'/no_photo.png';
    }

    $response = new BinaryFileResponse($file);
    return $response;

  }
}