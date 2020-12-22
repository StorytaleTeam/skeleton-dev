<?php

namespace Storytale\SkeletonDev\PortAdapters\Secondary\Order\Querying\Aura;

use Aura\SqlQuery\QueryFactory;
use Storytale\Contracts\DataBase\Sql\PdoConnection;
use Storytale\SkeletonDev\Application\Query\TestOrder\SearchOrderService;

class AuraSearchOrderService implements SearchOrderService
{
    /** @var PdoConnection */
    private PdoConnection $pdoConnection;

    /** @var QueryFactory */
    private QueryFactory $queryFactory;

    public function __construct(PdoConnection $pdoConnection, QueryFactory $queryFactory)
    {
        $this->pdoConnection = $pdoConnection;
        $this->queryFactory = $queryFactory;
    }

    public function find(int $id)
    {
        $select = $this->queryFactory
            ->newSelect()
            ->cols([
                'o.id',
                'o.customer_id AS customerId',
                'o.product_id as productId',
                'o.created_date AS createdDate',
                'o.sold_date as soldDate',
            ])
            ->from('orders AS o')
            ->where('o.id=:id')
            ->bindValue('id', $id);

        $response = $this->executeStatement($select->getStatement(), $select->getBindValues());
        $response = count($response) === 0 ? null : $response[0];

        return $response;
    }

    private function executeStatement($queryStatement, $parameters)
    {
        $sth = $this->pdoConnection->getConnection()->prepare($queryStatement);
        $sth->execute($parameters);
        $result = [];
        while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }
}