<?php
/*
|---------------------------------------------------------------
| Builder
| Definition:
| which allows constructing complex objects step by step and
| doesn’t require products to have a common interface. 
| That makes it possible to produce different products using the same construction process.
|
| Author:
| RefactoringGuru
|---------------------------------------------------------------
*/


interface SQLQueryBuilder
{
    public function select(string $table, array $fields): SQLQueryBuilder;
    public function where(string $field, string $value, string $operator = '='): SQLQueryBuilder;
    public function limit(int $start, int $offset): SQLQueryBuilder;
    public function getSQL(): string;
}

class MysqlQueryBuilder implements SQLQueryBuilder
{   
    protected $query;
    protected function reset(): void
    {
        $this->query = new \stdClass;
    }

    public function select(string $table, array $fields): SQLQueryBuilder
    {
        $this->reset();
        $this->query->base = 'select ' .  implode(', ', $fields) . ' from ' . $table;
        $this->query->type = 'select';
        return $this;
    }

    public function where(string $field, string $value, string $operator = '='): SQLQueryBuilder
    {
        if( ! in_array($this->query->type, ['select', 'update'])) {
            throw new \Exception("WHERE can only be added to SELECT OR UPDATE");
        }
        $this->query->where[] = $field . ' ' . $operator . ' ' . '"' . $value . '"';
        return $this;
    }

    public function limit(int $start, int $offset): SQLQueryBuilder
    {
        if( ! in_array($this->query->type, ['select'])) {
            throw new \Exception("LIMIT can only be added to SELECT");
        }
        $this->query->limit = ' limit ' . $start . ', ' . $offset;
        return $this;
    }

    public function getSQL(): string
    {
        $query = $this->query;
        $sql = $query->base;
        if( ! empty($query->where)) {
            $sql .= ' where ' . implode(' and ', $query->where);
        }
        if( isset($query->limit)) {
            $sql .= $query->limit;
        }
        $sql .= ';';
        return $sql;
    }
}

class PostgresQueryBuilder extends MysqlQueryBuilder 
{
    /**
     * Among other things, PostgreSQL has slightly different LIMIT syntax.
     */
    public function limit(int $start, int $offset): SQLQueryBuilder
    {
        parent::limit($start, $offset);
        $this->query->limit = ' limit ' . $start . ' offset ' . $offset;
        return $this;
    }
    // + tons of other overrides...
}

function clientCode(SQLQueryBuilder $queryBuilder)
{
    $query = $queryBuilder
        ->select("users", ["name", "email", "password"])
        ->where("age", 18, ">")
        ->where("age", 38, "<")
        ->limit(10, 20)
        ->getSQL();
    echo $query;
}

echo "Testing MySQL query builder: <br/>";
clientCode(new MysqlQueryBuilder);

echo "<br/><br/>";

echo "Testing PostgresSQL query builder: <br/>";
clientCode(new PostgresQueryBuilder);
 
