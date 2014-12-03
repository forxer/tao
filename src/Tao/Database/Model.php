<?php
namespace Tao\Database;

use Tao\Application;
use Tao\Html\Modifiers;

abstract class Model
{
    protected $app;

    protected $table;

    protected $alias;

    protected $columns;

    protected $primaryKey = 'id';

    protected $foreignKeys = [];

    protected $searchWordsColumn;

    protected $searchIndexableColumns = [];

    /**
     * Constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->init();
    }

    /**
     * Initialize the model.
     */
    abstract function init();

    /**
     * Indicate if a given entry exists.
     *
     * @param mixed $primaryKey
     * @param string $column
     * @return boolean
     */
    public function has($primaryKey, $column = null)
    {
        if (null === $column) {
            $column = $this->getPrimaryKey();
        }

        return (boolean)$this->app['db']->fetchColumn(
            'SELECT COUNT(:column) FROM ' . $this->getTable() . ' WHERE '.$this->getPrimaryKey().' = :pk',
            [
                'column' => $column,
                'pk' => $primaryKey
            ]
        );
    }

    /**
     * Insert an entry.
     *
     * @param array $data
     */
    public function insert(array $data)
    {
        $this->normalizeDataColumns($data);

        $this->setSearchIndexableData($data);

        return $this->app['db']->insert($this->getTable(), $data);
    }

    /**
     * Update an entry.
     *
     * @param array $data
     * @param mixed $primaryKey
     */
    public function update(array $data, $primaryKey)
    {
        $this->normalizeDataColumns($data);

        $this->setSearchIndexableData($data);

        return $this->app['db']->update($this->getTable(), $data, [ $this->getPrimaryKey() => $primaryKey]);
    }

    /**
     * Delete an entry.
     *
     * @param mixed $primaryKey
     */
    public function delete($primaryKey)
    {
        return $this->app['db']->delete($this->getTable(), [ $this->getPrimaryKey() => $primaryKey]);
    }

    public function reIndexAll($bOnlyEmpty = false)
    {
        $qb = $this->app['qb']
            ->selectSearchIndexable($this)
        ;

        if ($bOnlyEmpty)
        {
            $qb->andWhere($this->getAlias().'.'.$this->getSearchWordsColumn().' IS NULL');
            $qb->orWhere($this->getAlias().'.'.$this->getSearchWordsColumn().' = \'\'');
        }

        $all = $qb->execute()->fetchAll();

        foreach ($all as $columns) {
            $this->update($columns, $columns[$this->getPrimaryKey()]);
        }

        return count($all);
    }

    public function autoJoin($model)
    {
        return;
    }

    /**
     * Set the table name. Should be called by init() method.
     *
     * @param string $sTableName
     */
    public function setTable($sTableName)
    {
        $this->table = $sTableName;
    }

    /**
     * Get the table name.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set the table alias. Should be called by init() method.
     *
     * @param string $sTableAlias
     */
    public function setAlias($sTableAlias)
    {
        $this->alias = $sTableAlias;
    }

    /**
     * Get the table alias.
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set the columns names. Should be called by init() method.
     *
     * @return string
     */
    public function setColumns(array $aColumns)
    {
        $this->columns = $aColumns;
    }

    /**
     * Get the columns names.
     *
     * @param boolean $bWithoutForeignKey Return with or without foreign keys.
     * @param boolean $bAliased Return or not aliased columns names.
     * @param boolean $bPrefixed Return or not prefixed columns names.
     * @return array
     */
    public function getColumns($bWithoutForeignKey = true, $bAliased = true, $bPrefixed = false)
    {
        $aColumns = $this->columns;

        if ($bWithoutForeignKey) {
            $aColumns = $this->removeForeignKeyColumns($aColumns);
        }

        if ($bAliased) {
            $aColumns = $this->aliaseColumns($aColumns, $this->getAlias());
        }

        if ($bPrefixed) {
            $aColumns = $this->prefixeColumns($aColumns, $this->getAlias());
        }

        return $aColumns;
    }

    /**
     * Set the table primary key. Should be called by init() method.
     *
     * @param string $sPrimaryKey
     */
    public function setPrimaryKey($sPrimaryKey)
    {
        $this->primaryKey = $sPrimaryKey;
    }

    /**
     * Get the table primary key.
     *
     * @param boolean $bAliased Return or not aliased primary key.
     * @return string
     */
    public function getPrimaryKey($bAliased = false)
    {
        return $bAliased
            ? $this->getAlias() . '.' . $this->primaryKey
            : $this->primaryKey;
    }

    /**
     * Define wich columns are foreign keys.
     *
     * @param array $foreignKeys
     */
    public function setForeignKeys(array $foreignKeys)
    {
        $this->foreignKeys = $foreignKeys;
    }

    /**
     * Return foreign keys.
     *
     * @return array
     */
    public function getForeignKeys()
    {
        return $this->foreignKeys;
    }

    /**
     * Return name of foreign key for other models.
     *
     * @return string
     */
    public function getOwnExternalForeignKey()
    {
        return $this->getTable() . '_' . $this->getPrimaryKey();
    }

    public function hasForeignKey($sForeignKey)
    {
        return in_array($sForeignKey, $this->foreignKeys);
    }

    public function setSearchWordsColumn($sSearchWordsColumn)
    {
        $this->searchWordsColumn = $sSearchWordsColumn;
    }

    public function getSearchWordsColumn()
    {
        return $this->searchWordsColumn;
    }

    public function setSearchIndexableColumns(array $aSearchIndexableColumns)
    {
        $this->searchIndexableColumns = $aSearchIndexableColumns;
    }

    public function getSearchIndexableColumns()
    {
        return $this->searchIndexableColumns;
    }

    public function setSearchIndex($sSearchWordsColumn, array $aSearchIndexableColumns)
    {
        $this->setSearchWordsColumn($sSearchWordsColumn);
        $this->setSearchIndexableColumns($aSearchIndexableColumns);
    }

    public function isSearchIndexable()
    {
        return (!empty($this->searchWordsColumn) && !empty($this->searchIndexableColumns));
    }

    public function removeForeignKeyColumns(array $aColumns)
    {
        $aForeignKeys = $this->getForeignKeys();

        foreach ($aColumns as $k => $v)
        {
            if (in_array($v, $aForeignKeys)) {
                unset($aColumns[$k]);
            }
        }

        return $aColumns;
    }

    public function aliaseColumns(array $aColumns, $sAlias)
    {
        array_walk($aColumns, function(&$value, $key) use ($sAlias) {
            $value = $sAlias . '.' . $value;
        });

        return $aColumns;
    }

    public function prefixeColumns(array $aColumns, $sAlias)
    {
        array_walk($aColumns, function(&$value, $key) use ($sAlias) {
            $value .= ' AS ' . $sAlias . '_' . str_replace($sAlias.'.', '', $value);
        });

        return $aColumns;
    }

    protected function normalizeDataColumns(array &$data)
    {
        $columns = $this->getColumns(false, false, false);

        foreach (array_keys($data) as $column)
        {
            if (!in_array($column, $columns)) {
                unset($data[$column]);
            }
        }
    }

    protected function setSearchIndexableData(array &$data, $locale = 'fr')
    {
        if ($this->isSearchIndexable() && (!isset($data[$this->getSearchWordsColumn()]) || empty($data[$this->getSearchWordsColumn()])))
        {
            $words = '';

            foreach ($data as $column => $values)
            {
                if (in_array($column, $this->getSearchIndexableColumns())) {
                    $words .= $values.' ';
                }
            }

            $words = Modifiers::toIndexes($words);

            $aStopWords = $this->app['stopwords']->get($locale);

            $data[$this->getSearchWordsColumn()] = implode(' ', array_diff($words, $aStopWords));
        }
    }
}
