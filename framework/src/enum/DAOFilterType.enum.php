<?php

enum DAOFilterType {
    case EQUALS;
    case NOT_EQUALS;
    case GREATER_THAN;
    case LESS_THAN;
    case GREATER_THAN_EQUALS;
    case LESS_THAN_EQUALS;
    case LIKE;
    case IN;
    case NOT_IN;

    function generateSqlTerm(mixed $key, string $field, mixed $filterValue): string {
        $sql = "";
        switch($this) {
            case self::EQUALS:
                if($filterValue === null) {
                    $sql .= "`{$field}` IS NULL";
                } else {
                    $sql .= "`{$field}` = :{$key}";
                }
                break;
            case self::NOT_EQUALS:
                if($filterValue === null) {
                    $sql .= "`{$field}` IS NOT NULL";
                } else {
                    $sql .= "`{$field}` != :{$key}";
                }
                break;
            case self::GREATER_THAN:
                $sql = "`{$field}` > :{$key}";
                break;
            case self::LESS_THAN:
                $sql = "`{$field}` < :{$key}";
                break;
            case self::GREATER_THAN_EQUALS:
                $sql = "`{$field}` >= :{$key}";
                break;
            case self::LESS_THAN_EQUALS:
                $sql = "`{$field}` <= :{$key}";
                break;
            case self::LIKE:
                $sql = "`{$field}` LIKE :{$key}";
                break;
            case self::IN:
                if(is_array($filterValue)) {
                    if(in_array(null, $filterValue)) {
                        $sql .= "(`{$field}` IS NULL OR ";
                    }

                    $sql .= "`{$field}` IN (";
                    foreach($filterValue as $filterValueKey => $filterValueValue) {
                        if($filterValueValue !== null) {
                            $sql .= ":{$key}_{$filterValueKey}, ";
                        }
                    }
                    $sql = substr($sql, 0, -2);
                    $sql .= ")";

                    if(in_array(null, $filterValue)) {
                        $sql .= ")";
                    }
                } else {
                    $sql .= "`{$field}` IN (:{$key})";
                }
                break;
            case self::NOT_IN:
                if(is_array($filterValue)) {
                    if(in_array(null, $filterValue)) {
                        $sql .= "(`{$field}` IS NOT NULL OR ";
                    }

                    $sql .= "`{$field}` NOT IN (";
                    foreach($filterValue as $filterValueKey => $filterValueValue) {
                        if($filterValueValue !== null) {
                            $sql .= ":{$key}_{$filterValueKey}, ";
                        }
                    }
                    $sql = substr($sql, 0, -2);
                    $sql .= ")";

                    if(in_array(null, $filterValue)) {
                        $sql .= ")";
                    }
                } else {
                    $sql .= "`{$field}` NOT IN (:{$key})";
                }
                break;
        }

        return $sql;
    }

    function bindQueryParameters(PDOStatement $stmt, mixed $key, string $field, mixed $filterValue): void {
        switch($this) {
            case self::EQUALS:
            case self::NOT_EQUALS:
            case self::GREATER_THAN:
            case self::LESS_THAN:
            case self::GREATER_THAN_EQUALS:
            case self::LESS_THAN_EQUALS:
            case self::LIKE:
                if($filterValue !== null) {
                    if($filterValue instanceof DateTime || $filterValue instanceof DateTimeImmutable) {
                        $date = $filterValue->format(DateTimeInterface::RFC3339_EXTENDED);
                        $stmt->bindValue(":{$key}", $date, PDO::PARAM_STR);
                    } else if(is_bool($filterValue)) {
                        $stmt->bindValue(":{$key}", $filterValue, PDO::PARAM_BOOL);
                    } else if(is_int($filterValue)) {
                        $stmt->bindValue(":{$key}", $filterValue, PDO::PARAM_INT);
                    } else {
                        $stmt->bindValue(":{$key}", $filterValue, PDO::PARAM_STR);
                    }
                }
                break;
            case self::IN:
            case self::NOT_IN:
                if(is_array($filterValue)) {
                    foreach($filterValue as $filterValueKey => $filterValueValue) {
                        if($filterValueValue !== null) {
                            if($filterValueValue instanceof DateTime || $filterValue instanceof DateTimeImmutable) {
                                $date = $filterValueValue->format(DateTimeInterface::RFC3339_EXTENDED);
                                $stmt->bindValue(":{$key}_{$filterValueKey}", $date, PDO::PARAM_STR);
                            } else if(is_bool($filterValueValue)) {
                                $stmt->bindValue(":{$key}_{$filterValueKey}", $filterValueValue, PDO::PARAM_BOOL);
                            } else if(is_int($filterValueValue)) {
                                $stmt->bindValue(":{$key}_{$filterValueKey}", $filterValueValue, PDO::PARAM_INT);
                            } else {
                                $stmt->bindValue(":{$key}_{$filterValueKey}", $filterValueValue, PDO::PARAM_STR);
                            }
                        }
                    }
                } else {
                    if($filterValue instanceof DateTime || $filterValue instanceof DateTimeImmutable) {
                        $date = $filterValue->format(DateTimeInterface::RFC3339_EXTENDED);
                        $stmt->bindValue(":{$key}", $date, PDO::PARAM_STR);
                    } else if(is_bool($filterValue)) {
                        $stmt->bindValue(":{$key}", $filterValue, PDO::PARAM_BOOL);
                    } else if(is_int($filterValue)) {
                        $stmt->bindValue(":{$key}", $filterValue, PDO::PARAM_INT);
                    } else {
                        $stmt->bindValue(":{$key}", $filterValue, PDO::PARAM_STR);
                    }
                }
                break;
        }
    }
}
