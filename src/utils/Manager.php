<?php

namespace App\Utils;

class Manager
{
    protected $db;
    protected $table;
    protected $class;


    public function __construct($table, $class)
    {
        // Initialize the database connection and set the table and class name
        $this->db = DB::getInstance();
        $this->table = $table;
        $this->class = $class;
    }

    /**
     * A boilerplate method to list all records from a table
     */
    public function listAll()
    {
        $sql = "SELECT * FROM " . $this->table;
        $rows = $this->db->select($sql);
        $objects = array();
        foreach ($rows as $row) {
            $object = new $this->class();
            foreach ($row as $key => $value) {
                $method = 'set' . ucfirst($key);
                if (method_exists($object, $method)) {
                    $object->$method($value);
                }
            }
            $objects[] = $object;
        }

        return $objects;
    }
}
