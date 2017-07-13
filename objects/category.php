<?php
class Category{
 
    // database connection and table name
    private $conn;
    private $table_name = "categories";
 
    // object properties
    public $id;
    public $name;
    public $description;
    public $created;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // used by select drop-down list
    public function readAll(){
        //select all data
        $query = "SELECT
                    id, name, description, created
                FROM
                    " . $this->table_name . "
                ORDER BY
                    name";
 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
 
        return $stmt;
    }

    // used by select drop-down list
    public function read(){
     
        //select all data
        $query = "SELECT
                    id, name, description, created
                FROM
                    " . $this->table_name . "
                ORDER BY
                    name";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
     
        return $stmt;
    }

    // create category
    function create(){
     
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    name=:name, description=:description, created=:created";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));        
        $this->description=htmlspecialchars(strip_tags($this->description));        
        $this->created=htmlspecialchars(strip_tags($this->created));
     
        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":created", $this->created);
     
        // execute query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    // used when filling up the update category form
    function readOne(){
     
        // query to read single record
        /*$query = "SELECT
                    c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN
                        categories c
                            ON p.category_id = c.id
                WHERE
                    p.id = ?
                LIMIT
                    0,1";*/
        //select all data
        $query = "SELECT
                    id, name, description, created
                FROM
                    " . $this->table_name . " c
                WHERE
                    c.id = ?
                LIMIT
                    0,1";

        // prepare query statement
        $stmt = $this->conn->prepare( $query );
     
        // bind id of category to be updated
        $stmt->bindParam(1, $this->id);
     
        // execute query
        $stmt->execute();
     
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        // set values to object properties
        $this->name = $row['name'];
        $this->description = $row['description'];
        $this->created = $row['created'];
    }

    // update the product
    function update(){
     
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    name = :name,
                    description = :description
                WHERE
                    id = :id";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->description=htmlspecialchars(strip_tags($this->description));
        
        // bind new values
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
     
        // execute the query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    // delete the category
    function delete(){
     
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
     
        // bind id of record to delete
        $stmt->bindParam(1, $this->id);
     
        // execute query
        if($stmt->execute()){
            return true;
        }
     
        return false;
         
    }

    // search products
    function search($keywords){
     
        // select all query
        $query = "SELECT
                    c.id, c.name, c.description, c.created
                FROM
                    " . $this->table_name . " c                    
                WHERE
                    c.name LIKE ? OR c.created LIKE ?
                ORDER BY
                    c.created DESC";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
     
        // bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

}
?>