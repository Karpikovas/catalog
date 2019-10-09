<?php


namespace App\Lib;


class LibEmployee
{
  private $Db;

  public function __construct(LibDB $Db)
  {
    $this->Db = $Db;
  }

  public function getEmployees() {
    $params = [];
    return $this->Db->select('
        SELECT 
          Employee.id, 
            surname, 
            Employee.name,
            patronymic, 
            DATE(birthday) as birthday, 
            photo,
            salary,
            rate, 
            Subdivision.name as subdivision,
            Post.name as post
        FROM catalog.Employee
        inner join EmployeeInSubdivision on Employee.id = EmployeeInSubdivision.id_employee
        inner join Subdivision on Subdivision.id = EmployeeInSubdivision.id_subdivison
        inner join EmployeeHasPost on Employee.id = EmployeeHasPost.id_employee
        inner join Post on Post.id = EmployeeHasPost.id_post
        ORDER BY surname;', $params);
  }

  public function addEmployeePhoto(string $id, ?string $photo) {
    $params = [
        $photo,
        $id
    ];
    return $this->Db->exec('
        UPDATE Employee SET 
            photo=? 
         WHERE id=?;',
        $params
    );
  }

  public function getPhotoPathByID(string $ID) {
    $params = [
        $ID
    ];

    return $this->Db->select('select photo from Employee where id = ?', $params);
  }

  public function updateEmployeeByID(string $id, ?string $surname, ?string $name, ?string $patronymic,
                                     ?string $birthday, ?string $salary, ?string $rate): bool
  {
    $params = [
        $surname,
        $name,
        $patronymic,
        $birthday,
        $salary,
        $rate,
        $id
    ];

    return $this->Db->exec('
        UPDATE Track SET 
            comment=? 
         WHERE id=?;',
        $params
    );
  }
}