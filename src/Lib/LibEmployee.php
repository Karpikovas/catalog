<?php


namespace App\Lib;


class LibEmployee
{
  private $Db;

  public function __construct(LibDB $Db)
  {
    $this->Db = $Db;
  }

  public function getEmployees($subdivision) {
    $params = [];
    if ($subdivision) {
      $params = [
          $subdivision
      ];
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
        left join EmployeeInSubdivision on Employee.id = EmployeeInSubdivision.id_employee
        inner join Subdivision on Subdivision.id = EmployeeInSubdivision.id_subdivison and EmployeeInSubdivision.id_subdivison = ?
        left join EmployeeHasPost on Employee.id = EmployeeHasPost.id_employee
        left join Post on Post.id = EmployeeHasPost.id_post
        ORDER BY surname;', $params);
    } else {
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
        left join EmployeeInSubdivision on Employee.id = EmployeeInSubdivision.id_employee
        left join Subdivision on Subdivision.id = EmployeeInSubdivision.id_subdivison
        left join EmployeeHasPost on Employee.id = EmployeeHasPost.id_employee
        left join Post on Post.id = EmployeeHasPost.id_post
        ORDER BY surname;', $params);
    }

  }

  public function updateEmployeePhoto(string $id, ?string $photo) {
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

  public function addEmployee(?string $surname, ?string $name, ?string $patronymic,
                                     ?string $birthday, ?string $salary, ?string $rate)
  {
    $params = [
        $surname,
        $name,
        $patronymic,
        $birthday,
        $salary,
        $rate
    ];

    $this->Db->exec('
        INSERT INTO Employee (
            surname,
            name,
            patronymic,
            birthday,
            salary,
            rate
        )
         VALUES (?, ?, ?, ?, ?, ?);',
        $params
    );
    return $this->Db->lastID();
  }

  public function addEmployeeSubdivisionByID(string $id, ?string $subdivision)
  {
    $params = [
        $subdivision,
        $id
    ];
    return $this->Db->exec('
        INSERT INTO EmployeeInSubdivision (
            id_subdivison,
            id_employee
        )
        VALUES (?, ?);',
        $params
    );
  }

  public function checkEmployeeSubdivisionByID(string $id)
  {
    $params = [
        $id
    ];
    return $this->Db->select('
        SELECT id_subdivison FROM EmployeeInSubdivision WHERE id_employee=?',
        $params
    );
  }

  public function addEmployeePostByID(string $id, ?string $post)
  {
    $params = [
        $post,
        $id
    ];
    return $this->Db->exec('
        INSERT INTO EmployeeHasPost ( 
            id_post,
            id_employee
        )
        VALUES (?, ?);',
        $params
    );
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
        UPDATE Employee SET 
            surname=?,
            name=?,
            patronymic=?,
            birthday=?,
            salary=?,
            rate=?
         WHERE id=?;',
        $params
    );
  }

  public function updateEmployeeSubdivisionByID(string $id, ?string $subdivision)
  {
    $params = [
        $subdivision,
        $id
    ];
    return $this->Db->exec('
        UPDATE EmployeeInSubdivision SET 
            id_subdivison=?
         WHERE id_employee=?;',
        $params
    );
  }
  public function updateEmployeePostByID(string $id, ?string $post)
  {
    $params = [
        $post,
        $id
    ];
    return $this->Db->exec('
        UPDATE EmployeeHasPost SET 
            id_post=?
         WHERE id_employee=?;',
        $params
    );
  }
  public function getPhotoPathByID(string $ID) {
    $params = [
        $ID
    ];

    return $this->Db->select('select photo from Employee where id = ?', $params);
  }

  public function deleteEmployeeByID(?string $ID)
  {
    $params = [
        $ID
    ];
    return $this->Db->exec('DELETE FROM Employee WHERE id = ?;', $params);
  }
}
