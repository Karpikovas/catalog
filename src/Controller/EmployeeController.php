<?php


namespace App\Controller;


use App\Lib\LibEmployee;
use App\Lib\LibSubdivision;
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

    $employee->updateEmployeePhoto($ID, $photo);

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

  public function updateEmployeeInfo(Request $request, $ID, LibEmployee $employee, LibSubdivision $sub)
  {
    $surname = $request->request->get('surname');
    $name = $request->request->get('name');
    $patronymic = $request->request->get('patronymic');
    $birthday = $request->request->get('birthday');
    $salary = $request->request->get('salary');
    $rate = $request->request->get('rate');

    $subdivison = $request->request->get('subdivison');
    $post = $request->request->get('post');

    if ($subdivison) {
      $subdivison = array_shift($sub->getSubdivisionIDByName($subdivison)[0]);
      if (count($employee->checkEmployeeSubdivisionByID($ID)) > 0) {
        $employee->updateEmployeeSubdivisionByID($ID, $subdivison);
      } else {
        $employee->addEmployeeSubdivisionByID($ID, $subdivison);
      }

    }
    if ($post) {
      $post = array_shift($sub->getPostIDByName($post)[0]);
      $employee->updateEmployeePostByID($ID, $post);
    }

    //$employee->updateEmployeeByID($ID, $surname, $name, $patronymic, $birthday, $salary, $rate);

    return $this->json(['status' => "OK", 'message' => [], 'data' => [$employee->checkEmployeeSubdivisionByID($ID)]]);
  }

  public function deleteEmployee(Request $request, $ID, ParameterBagInterface $params, LibEmployee $employee) {
    $fileDir = $params->get('FILE_DIRECTORY');
    $photo = $employee->getPhotoPathByID($ID);


    if ($photo[0]["photo"]) {
      $file = $fileDir.'/'.$photo[0]["photo"];
      unlink($file);
    }

    $employee->deleteEmployeeByID($ID);
    return $this->json(['status' => "OK", 'message' => [], 'data' => []]);
  }

  public function addEmployee(Request $request, LibEmployee $employee, LibSubdivision $sub) {
    $surname = $request->request->get('surname');
    $name = $request->request->get('name');
    $patronymic = $request->request->get('patronymic');
    $birthday = $request->request->get('birthday');
    $salary = (int)$request->request->get('salary');
    $rate = (float)$request->request->get('rate');
    $subdivison = $request->request->get('subdivison');
    $post = $request->request->get('post');

    $id = $employee->addEmployee($surname, $name, $patronymic, $birthday, $salary, $rate);

    if ($subdivison) {
      $subdivison = array_shift($sub->getSubdivisionIDByName($subdivison)[0]);
      $employee->addEmployeeSubdivisionByID($id, $subdivison);
    }
    if ($post) {
      $post = array_shift($sub->getPostIDByName($post)[0]);
      $employee->addEmployeePostByID($id, $post);
    }
    return $this->json(['status' => "OK", 'message' => [], 'data' => [$id]]);
  }
}
