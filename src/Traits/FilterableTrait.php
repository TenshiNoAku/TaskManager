<?php

namespace App\Traits;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;

trait FilterableTrait
{
    private function getFiltersProperty(QueryBuilder $queryBuilder): array
    {
        $properties = $this->getClassMetadata()->getFieldNames();

        $assocProperties = array_intersect_key($this->getClassMetadata()->getAssociationMappings(),array_flip($queryBuilder->getAllAliases()));
        $ar = array();
        foreach ($assocProperties as $propertyName => $reflectionProperty) {
            $assocNames = $this->getEntityManager()->getClassMetadata($reflectionProperty->targetEntity)->getFieldNames();
            foreach ($assocNames as $assocName) {
                $ar[] = "$propertyName.$assocName" ;
            }
        }
        $properties = array_merge($properties,$ar);

        return $properties;
    }

    public function addOrdering(QueryBuilder $queryBuilder, array $orders): QueryBuilder
    {
        $properties = $this->getFiltersProperty($queryBuilder);
        $criteria = Criteria::create()->orderBy(array_intersect_key($orders ,array_flip($properties)));
        $query = $queryBuilder->addCriteria($criteria);
        return $query;
    }

    public function addFiltering(QueryBuilder $queryBuilder, array $filter): QueryBuilder

    {
        $properties = $this->getFiltersProperty($queryBuilder);
        $criteria = Criteria::create();
        if (isset($filter['eq'])) {
            $eqArray = $filter['eq'];
            $eqArray = array_intersect_key($eqArray,array_flip($properties));

            array_map(function (string $property, string $value) use ($criteria){
                $expr = new Comparison($property, Comparison::EQ, $value);
                $criteria->andWhere($expr);
            },array_keys($eqArray),$eqArray);
        }
         if (isset($filter['neq'])) {
            $neqArray = $filter['neq'];
            $neqArray = array_intersect_key($neqArray,array_flip($properties));
            array_map(function (string $property, string $value) use ($criteria){
                $expr = new Comparison($property, Comparison::NEQ, $value);
                $criteria->andWhere($expr);
            },array_keys($neqArray),$neqArray);
        }
        $queryBuilder->addCriteria($criteria);
        return $queryBuilder;
    }


}