<?php
namespace Shopex\LubanAdmin\Grpc;

// use Illuminate\Database\Schema\Grammars\Grammar as QueryGrammar;
use Illuminate\Database\Query\Grammars\MySqlGrammar;
use Shopex\Luban\Sdf\SearchFilter;
use Shopex\Luban\Sdf\SearchFilter_Op;
use Illuminate\Database\Query\Builder;
class Grammar extends MySqlGrammar
{
	/**
     * Compile the components necessary for a select clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return array
     */
	public function getSqlComponents($query){
		return $this->compileComponents($query);
	}
	/**
     * Get an array of all the where clauses for the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return array
     */
    protected function compileWheresToArray($query)
    {

        $return = collect($query->wheres)->map(function ($where) use ($query) {
        	$sf = new SearchFilter();
        	$sf->setColumn($where['column']);
        	switch ($where['type']) {
        		case 'Basic':
        			$sf->setOperator($this->operatorMap($where['operator']));
        			$sf->setValue($where['value']);
        			break;
        		case 'between':
        			if ($where['not']) {
        				$sf->setOperator($this->operatorMap('notbetween'));
        			}else{
        				$sf->setOperator($this->operatorMap('between'));
        			}
        			$sf->setValue($where['value'][0]);
        			$sf->setOption($where['value'][1]);
        			break;
        		case 'In':
        			$sf->setOperator($this->operatorMap('in'));
        			$sf->setValue(implode(',', $where['values']));
        			break;
        		default:
        			$sf->setOperator(SearchFilter_Op::NEQUAL);
        			$sf->setValue($where['value']);
        			break;
        	}
        	return $sf;
        })->all();
        return $return;
        
    }
    /**
     * Format the where clause statements into one string.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $sql
     * @return string
     */
    protected function concatenateWhereClauses($query, $sql)
    {
    	return $sql;
        $conjunction = $query instanceof JoinClause ? 'on' : 'where';
        return $conjunction.' '.$this->removeLeadingBoolean(implode(' ', $sql));
    }
    /**
     * Compile the query orders to an array.
     *
     * @param  \Illuminate\Database\Query\Builder
     * @param  array  $orders
     * @return array
     */
    protected function compileOrdersToArray(Builder $query, $orders)
    {
        return array_map(function ($order) {
            return ! isset($order['sql'])
                        ? $this->wrap($order['column']).' '.$order['direction']
                        : $order['sql'];
        }, $orders);
    }
     /**
     * Compile the "limit" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  int  $limit
     * @return string
     */
    protected function compileLimit(Builder $query, $limit)
    {
        return (int) $limit;
    }
     /**
     * Compile the "order by" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $orders
     * @return string
     */
    protected function compileOrders(Builder $query, $orders)
    {
        if (! empty($orders)) {
            return implode(', ', $this->compileOrdersToArray($query, $orders));
        }

        return '';
    }
      /**
     * Compile the "select *" portion of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $columns
     * @return string|null
     */
    protected function compileColumns(Builder $query, $columns)
    {
        // If the query is actually performing an aggregating select, we will let that
        // compiler handle the building of the select clauses, as it will need some
        // more syntax that is best handled by that function to keep things neat.
        if (! is_null($query->aggregate)) {
            return;
        }

        $select = "";//$query->distinct ? 'select distinct ' : 'select ';

        return $select.$this->columnize($columns);
    }

    /**
     * Compile the "offset" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  int  $offset
     * @return string
     */
    protected function compileOffset(Builder $query, $offset)
    {
        return (int) $offset;
    }

    public function operatorMap($op){
    	$data = [
    		">"=>SearchFilter_Op::THAN,      
			"<"=>SearchFilter_Op::LTHAN,     
			"="=>SearchFilter_Op::NEQUAL,    
			"="=>SearchFilter_Op::TEQUAL,    
			"<>"=>SearchFilter_Op::NOEQUAL,   
			"<="=>SearchFilter_Op::STHAN,     
			">="=>SearchFilter_Op::BTHAN,     
			"like"=>SearchFilter_Op::HAS,         
			"notlike"=>SearchFilter_Op::NOHAS,     
			"between"=>SearchFilter_Op::BETWEEN,   
			// "notbetween"=>SearchFilter_Op::NOTBETWEEN,
			"in"=>SearchFilter_Op::IN,        
			"noint"=>SearchFilter_Op::NOTIN,     
			"isnull"=>SearchFilter_Op::ISNULL,    
			"isnotnull"=>SearchFilter_Op::ISNOTNULL, 
    	];

    	return array_get($data,$op,SearchFilter_Op::NEQUAL);
    }
} // END class 