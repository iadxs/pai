<?php
require_once('database.php');

class Test
{
    public $id;
    public $name;
    public $value;

    public function __construct(
        $name = "",
        $value = "",
        $id = ""
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Returns record from DB as an instance of Test object
     * @param int $id of a record
     * @return Test|false returns object of type Test or false on failure
     */
    public static function getInstanceById(int $id)
    {
        $db = Database::getInstance();
        $object = new self();
        $columns = array_keys(get_object_vars($object));
        $sql = 'SELECT ' . implode(', ', $columns) . ' FROM test WHERE id=' . (int)$id . ' LIMIT 1;';
        $result = $db->query($sql);
        $dane = $db->fetchSingleRow($result);

        if (empty($dane)) {
            return false;
        }
        foreach ($columns as $column) {
            $object->$column = $dane[$column];
        }

        return $object;
    }

    /**
     * Creates or updates record in database
     * @return boolean true when the query is successful, it returns false otherwise
     */
    public function save()
    {
        $db = Database::getInstance();

        $properties = get_object_vars($this);
        if ($this->id == '') {
            unset($properties['id']);
        }
        $columnNames = array_keys($properties);

        if ($this->id == '') {
            $sql = 'INSERT INTO `test` (`' . implode('`,`', $columnNames) . '`) VALUES("' . implode('","', $properties) . '");';
        } else {
            $sql = "UPDATE `test` SET ";
            $conditions = [];
            foreach($properties as $name => $value) {
                $conditions[] = "`$name` = '$value'";
            }
            $sql .= implode(', ', $conditions);
            $sql .= " WHERE `id` = $this->id;";
        }
        $result = $db->query($sql);

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * returns all records as an array of objects
     * @param array $filter filtering table field_name => searched_value
     * @param array $columns get only listed columns, all if empty
     * @return array $data
     */
    public static function getAll($filter = [], $columns = []): array
    {
        $db = Database::getInstance();
        $columns = !empty($columns) ? $columns : array_keys(get_class_vars(self::class));
        $data = [];

        if ($filter == []) {
            $sql = "SELECT `" . implode('`, `', $columns) . "`  FROM `test`";
        } else {
            $conditions = [];
            $sql = "SELECT `" . implode('`, `', $columns) . "` FROM `test` WHERE 1 ";
            foreach ($filter as $key => $wartosc) {
                $conditions[] = "`$key` = '$wartosc'";
            }
            if ($conditions) {
                $sql .= "AND " . implode(' AND ', $conditions);
            }
        }

        $result = $db->query($sql);
        $records = $db->fetchByAssoc($result);

        foreach ($records as $record) {
            $object = new self();
            foreach ($columns as $name) {
                $object->$name = $record[$name];
            }

            $data[] = $object;
        }
        return $data;
    }
}
