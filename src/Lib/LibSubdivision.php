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

  public function addSubdivision($name, $description) {
    $params = [
        $name,
        $description
    ];
    $this->Db->exec('
        INSERT INTO Subdivision (
            name,
            description
        )
         VALUES (?, ?);',
        $params
    );
  }
  public function updateSubdivisionByID($ID, $name, $description) {
    $params = [
        $name,
        $description,
        $ID
    ];
    $this->Db->exec('
        UPDATE Subdivision SET
            name=?,
            description=?
        WHERE id=?;',
        $params
    );
  }

  public function deleteSubdivisionByID(?string $ID)
  {
    $params = [
        $ID
    ];
    return $this->Db->exec('DELETE FROM Subdivision WHERE id=?;', $params);
  }

  public function getPosts() {
    $params = [];
    return $this->Db->select('
        SELECT * FROM Post;', $params);
  }
  public function getSubdivisionIDByName($name) {
    $params = [
        $name
    ];
    return $this->Db->select('
        SELECT id FROM Subdivision where name=?;', $params);
  }
  public function getPostIDByName($name) {
    $params = [
        $name
    ];
    return $this->Db->select('
        SELECT id FROM Post where name=?;', $params);
  }
}
