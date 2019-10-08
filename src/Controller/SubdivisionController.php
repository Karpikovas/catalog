<?php


namespace App\Controller;


use App\Lib\LibSubdivision;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubdivisionController extends AbstractController
{
  public function getSubdivisions(LibSubdivision $subdivision) {
    $subdivisions = $subdivision->getSubdivisions();
    return $this->json(['status' => "OK", 'message' => [], 'data' => $subdivisions]);
  }
}