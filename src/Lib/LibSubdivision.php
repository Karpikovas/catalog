<?php


namespace App\Lib;


class LibSubdivision
{
  private $Db;

  public function __construct(LibDB $Db)
  {
    $this->Db = $Db;
  }

  public function getSubdivisions() {
    $params = [];
    return $this->Db->select('
        SELECT * FROM Subdivision;', $params);
  }
}