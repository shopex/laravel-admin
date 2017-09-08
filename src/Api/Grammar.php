<?php
namespace Shopex\LubanAdmin\Api;

// use Illuminate\Database\Schema\Grammars\Grammar as QueryGrammar;
use Illuminate\Database\Query\Grammars\MySqlGrammar;
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
	
} // END class 