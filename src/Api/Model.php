<?php
namespace Shopex\LubanAdmin\Api;

use Illuminate\Database\Eloquent\Model as OrmModel;

class Model extends OrmModel
{
	public function getService(){
		return $this->service;
	}
    /**
     * Get the table qualified key name.
     *
     * @return string
     */
    public function getQualifiedKeyName()
    {
        return $this->getKeyName();
    }
	/**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();
        $connection->setQueryGrammar(new Grammar); 
        return new Request(
            $connection, $connection->getQueryGrammar(), $connection->getPostProcessor()
        );
    }
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }
    /**
     * Get a new query builder that doesn't have any global scopes.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newQueryWithoutScopes()
    {
        $builder = $this->newEloquentBuilder($this->newBaseQueryBuilder());

        // Once we have the query builders, we will set the model instances so the
        // builder can easily access any information it may need from the model
        // while it is constructing and executing various queries against it.
        return $builder->setModel($this)
        			->setService($this)
                    ->with($this->with)
                    ->withCount($this->withCount);
    }
} // END class Api