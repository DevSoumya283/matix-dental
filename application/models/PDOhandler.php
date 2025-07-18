<?php

Class PDOhandler extends MY_Model
{
    public function query($sql, $params, $action = 'fetchAll')
    {

        Debugger::debug($sql, 'running SQL ' . $action, false, 'PDO');
        Debugger::debug($params, 'with params', false, 'PDO');

        try {
            $stmt = $this->db->conn_id->prepare($sql);
            $stmt->execute($params);
            if($action){
                $result = $stmt->$action(PDO::FETCH_OBJ);
            }
        } catch (Exception $e){
            $this->debug($e->getMessage());
        }
        Debugger::debug($results, 'Results', false, 'PDO');
        return $result;
    }

    private function debug($item, $name = '', $backtrace = false)
    {
        Debugger::debug($item, $name, $backtrace, 'PDO');
    }
}
