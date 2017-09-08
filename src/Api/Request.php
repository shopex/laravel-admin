<?php
namespace Shopex\LubanAdmin\Api;

use Illuminate\Database\Query\Builder;
use Shopex\Luban\Facades\Luban; 
class Request extends Builder
{
    /**
     * Insert a new record and get the value of the primary key.
     *
     * @param  array   $values
     * @param  string  $sequence
     * @return int
     */
    public function insertGetId(array $values, $sequence = null)
    {
        unset($values['updated_at']);
        unset($values['created_at']);
        $result = Luban::s($this->service)->{$this->from.'_new'}($values);
        return $result;
        
        $sql = $this->grammar->compileInsertGetId($this, $values, $sequence);

        $values = $this->cleanBindings($values);

        return $this->processor->processInsertGetId($this, $sql, $values, $sequence);
    }
    /**
     * Insert a new record into the database.
     *
     * @param  array  $values
     * @return bool
     */
    public function insert(array $values)
    {
        // Since every insert gets treated like a batch insert, we will make sure the
        // bindings are structured in a way that is convenient when building these
        // inserts statements by verifying these elements are actually an array.
        if (empty($values)) {
            return true;
        }

        if (! is_array(reset($values))) {
            $values = [$values];
        }

        // Here, we will sort the insert keys for every record so that each insert is
        // in the same order for the record. We need to make sure this is the case
        // so there are not any errors or problems when inserting these records.
        else {
            foreach ($values as $key => $value) {
                ksort($value);

                $values[$key] = $value;
            }
        }
        unset($values['updated_at']);
        unset($values['created_at']);
        $result = Luban::s($this->service)->{$this->from.'_new'}($values);
        return $result;
        // Finally, we will run this query against the database connection and return
        // the results. We will need to also flatten these bindings before running
        // the query so they are all in one huge, flattened array for execution.
        // return $this->connection->insert(
        //     $this->grammar->compileInsert($this, $values),
        //     $this->cleanBindings(Arr::flatten($values, 1))
        // );
    }
    /**
     * Update a record in the database.
     *
     * @param  array  $values
     * @return int
     */
    public function update(array $values)
    {
        unset($values['updated_at']);
        $id = $this->getKeyWhere();
        if ($id < 1) {
            return false;
        }
        $values['id'] = $id;
        $result = Luban::s($this->service)->{$this->from.'_update'}($values);
        return $result;
        // $sql = $this->grammar->compileUpdate($this, $values);
        // return $this->connection->update($sql, $this->cleanBindings(
        //     $this->grammar->prepareBindingsForUpdate($this->bindings, $values)
        // ));
    }
    public function getKeyWhere()
    {
        foreach ($this->wheres as $key => $value) {
            if ($value['type'] == "Basic" && $value['column'] == 'id') {
                return $value['value'];
            }
        }
        return 0;
    }
    /**
     * Delete a record from the database.
     *
     * @param  mixed  $id
     * @return int
     */
    public function delete($id = null)
    {
        $id = $this->getKeyWhere();
        // If an ID is passed to the method, we will set the where clause to check the
        // ID to let developers to simply and quickly remove a single row from this
        // database without manually specifying the "where" clauses on the query.
        if (is_null($id)) {
            return false;
        }
        $values['id'] = $id;
        $result = Luban::s($this->service)->{$this->from.'_remove'}($values);
        return $result;
        // return $this->connection->delete(
        //     $this->grammar->compileDelete($this), $this->getBindings()
        // );
    }
	/**
     * Set the service which the query is targeting.
     *
     * @param  string  $service
     * @return $this
     */
    public function service($service)
    {
        $this->service = $service;

        return $this;
    }
	/**
     * Run the query as a "select" statement against the connection.
     *
     * @return array
     */
    protected function runSelect()
    {
    	$sql = $this->grammar->getSqlComponents($this);
    	if ($sql['wheres']) {
    		$sql['wheres'] = $this->showQuery($sql['wheres'], $this->getBindings());
    	}
        if (isset($sql['aggregate']) && $sql['aggregate']) {
            $sql['columns'] = $sql['aggregate'];
            unset($sql['aggregate']);
        }
        $replaces = ['select','from','where','limit','offset','order by','`'];
        foreach ($sql as $key => $value) {
            $query[$key] = trim(str_replace($replaces, "", $value));
        }
        $result = Luban::s($this->service)->{$this->from.'_find'}($query);
        return $result;
     //    return $this->connection->select(
     //        $this->toSql(), $this->getBindings(), ! $this->useWritePdo
     //    );
    }
    public function showQuery($query, $params)
    {
        $keys = $values = [];
        # build a regular expression for each parameter
        foreach ($params as $key=>$value) {
            if (is_string($key)){
                $keys[] = '/:'.$key.'/';
            }else{
                $keys[] = '/[?]/';
            }
            if(is_numeric($value)){
                $values[] = intval($value);
            }else{
                $values[] = '"'.$value .'"';
            }
        }
        
        $query = preg_replace($keys, $values, $query, 1, $count);
        return $query;
    }
} // END class ApiRequest