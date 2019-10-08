<?php


namespace App\Controller;


use App\Lib\LibEmployee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeeController extends AbstractController
{
  public function getEmployees(LibEmployee $employee) {
    $employees = $employee->getEmployees();
    return $this->json(['status' => "OK", 'message' => [], 'data' => $employees]);
  }
}