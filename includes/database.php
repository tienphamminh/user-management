<?php

if (!defined('_INCODE')) die('Access Denied...');

// Prepares and executes an SQL statement with placeholders
function query($sql, $data = [], $getStatement = false)
{
    global $dbh;
    $isSuccess = false;

    try {
        $sth = $dbh->prepare($sql); // $sth: statement handle

        if (empty($data)) {
            $isSuccess = $sth->execute();
        } else {
            $isSuccess = $sth->execute($data);
        }

        if ($isSuccess && $getStatement) {
            return $sth;
        }
    } catch (PDOException $e) {
        require_once 'modules/error/db-error.php';
        exit;
    }

    return $isSuccess;
}

function insert($tableName, $dataInsert)
{
    $keyArr = array_keys($dataInsert);
    $fieldStr = implode(', ', $keyArr);
    $valueStr = ':' . implode(', :', $keyArr);

    $sql = 'INSERT INTO ' . $tableName . '(' . $fieldStr . ') VALUES(' . $valueStr . ')';

    return query($sql, $dataInsert);
}

function update($tableName, $dataUpdate, $condition = '', $dataCondition = [])
{
    $keyArr = array_keys($dataUpdate);
    $updateStr = '';
    foreach ($keyArr as $key) {
        $updateStr .= $key . '=:' . $key . ', ';
    }
    // Strip commas from the end of $updateStr
    $updateStr = rtrim($updateStr, ', ');

    if (!empty($condition)) {
        $sql = 'UPDATE ' . $tableName . ' SET ' . $updateStr . ' WHERE ' . $condition;
        $dataUpdate = array_merge($dataUpdate, $dataCondition);
    } else {
        $sql = 'UPDATE ' . $tableName . ' SET ' . $updateStr;
    }

    return query($sql, $dataUpdate);
}

function delete($tableName, $condition = '', $dataCondition = [])
{
    if (!empty($condition)) {
        $sql = 'DELETE FROM ' . $tableName . ' WHERE ' . $condition;
    } else {
        $sql = 'DELETE FROM ' . $tableName;
    }

    return query($sql, $dataCondition);
}

// Fetch all rows returned by a PDO statement object
function getAllRows($sql, $data)
{
    $statement = query($sql, $data, true);
    if (is_object($statement)) {
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    return false;
}

// Fetch first row returned by a PDO statement object
function getFirstRow($sql, $data)
{
    $statement = query($sql, $data, true);
    if (is_object($statement)) {
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    return false;
}


function selectAllRows($tableName, $fieldStr = '*', $condition = '', $dataCondition = [])
{
    if (!empty($condition)) {
        $sql = 'SELECT ' . $fieldStr . ' FROM ' . $tableName . '  WHERE ' . $condition;
    } else {
        $sql = 'SELECT ' . $fieldStr . ' FROM ' . $tableName;
    }

    return getAllRows($sql, $dataCondition);
}

function selectFirstRow($tableName, $fieldStr = '*', $condition = '', $dataCondition = [])
{
    if (!empty($condition)) {
        $sql = 'SELECT ' . $fieldStr . ' FROM ' . $tableName . '  WHERE ' . $condition;
    } else {
        $sql = 'SELECT ' . $fieldStr . ' FROM ' . $tableName;
    }

    return getFirstRow($sql, $dataCondition);
}

// Returns the number of rows affected by the last SQL statement
function getNumberOfRows($sql, $data)
{
    $statement = query($sql, $data, true);
    if (is_object($statement)) {
        return $statement->rowCount();
    }

    return false;
}

