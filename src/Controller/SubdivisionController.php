<?php


namespace App\Controller;


use App\Lib\LibSubdivision;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SubdivisionController extends AbstractController
{
  public function getSubdivisionsAndPosts(LibSubdivision $subdivision) {
    $subdivisions = $subdivision->getSubdivisions();
    $posts = $subdivision->getPosts();
    return $this->json(['status' => "OK", 'message' => [], 'data' => ['subdivisions' => $subdivisions, 'posts' => $posts]]);
  }

  public function addSubdivision(Request $request,  LibSubdivision $sub) {
    $name = $request->request->get('name');
    $description = $request->request->get('description');

    $id = $sub->addSubdivision($name, $description);

    return $this->json(['status' => "OK", 'message' => [], 'data' => [$id]]);
  }

  public function updateSubdivision(Request $request,  $ID, LibSubdivision $sub) {
    $name = $request->request->get('name');
    $description = $request->request->get('description');

    $sub->updateSubdivisionByID($ID, $name, $description);

    return $this->json(['status' => "OK", 'message' => [], 'data' => []]);
  }

  public function deleteSubdivision($ID, LibSubdivision $sub) {
    $sub->deleteSubdivisionByID($ID);

    return $this->json(['status' => "OK", 'message' => [], 'data' => []]);
  }

}
