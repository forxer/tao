<?php
namespace Tao\Database;

use Doctrine\DBAL\Query\QueryBuilder as BaseQueryBuilder;

use Pagerfanta\Adapter\DoctrineDbalAdapter;
use Pagerfanta\Pagerfanta;
use Doctrine\DBAL\Query\Expression\CompositeExpression;

class QueryBuilder extends BaseQueryBuilder
{
	public function getPager(Model $model, $iMaxPerPage, $iCurrentPage, $callback = null)
	{
		if (null === $callback)
		{
			$callback = function(QueryBuilder $queryBuilder) use($model) {
				$queryBuilder
					->select('COUNT(DISTINCT ' . $model->getAlias() . '.' . $model->getPrimaryKey() . ') AS total_results')
					->setMaxResults(1);
			};
		}

		$adapter = new DoctrineDbalAdapter($this, $callback);

		$pager = (new Pagerfanta($adapter))
			->setMaxPerPage($iMaxPerPage)
			->setCurrentPage($iCurrentPage);

		return $pager;
	}

	public function selectModel(Model $model)
	{
		$this
			->select($model->getColumns())
			->from($model->getTable(), $model->getAlias());

		return $this;
	}

	public function fromModel(Model $model)
	{
		$this->from($model->getTable(), $model->getAlias());

		return $this;
	}

	public function searchInColumn(Model $model, $column, array $query, array $params = [])
	{
		if (empty($params['wordOperand'])) {
			$params['wordOperand'] = CompositeExpression::TYPE_AND;
		}
		elseif ($params['wordOperand'] !== CompositeExpression::TYPE_AND) {
			$params['wordOperand'] = CompositeExpression::TYPE_OR;
		}

		if (empty($params['globalOperand'])) {
			$params['globalOperand'] = CompositeExpression::TYPE_AND;
		}

		if (empty($params['searchPattern'])) {
			$params['searchPattern'] = '%%%s%%';
		}

		$exp = [];
		foreach ($query as $word)
		{
			$exp[] = $this->expr()
				->like(
					$model->getAlias().'.'.$column,
					$this->createNamedParameter(sprintf($params['searchPattern'], $word))
				);
		}

		$this->{$params['globalOperand'] === CompositeExpression::TYPE_AND ? 'andWhere' : 'orWhere'}(
			new CompositeExpression($params['wordOperand'], $exp)
		);

		return $this;
	}

	public function search(Model $model, array $query, $wordOperand = CompositeExpression::TYPE_AND, $globalOperand = CompositeExpression::TYPE_AND, $searchPattern = '%%%s%%')
	{
		$this->searchInColumn($model, $model->getSearchWordsColumn(), $query, [
			'wordOperand' => $wordOperand,
			'globalOperand' => $globalOperand,
			'searchPattern' => $searchPattern
		]);

		return $this;
	}

	public function selectSearchIndexable(Model $model, array $columns = null)
	{
		if (null === $columns)
		{
			$columns = $model->getSearchIndexableColumns();
			array_unshift($columns, $model->getPrimaryKey());
		}

		$this
			->select(
				$model->aliaseColumns($columns, $model->getAlias())
			)
			->from($model->getTable(), $model->getAlias());

		return $this;
	}

	public function countModel(Model $model)
	{
		$this
			->select('COUNT(DISTINCT ' . $model->getAlias() . '.' . $model->getPrimaryKey() . ')')
			->from($model->getTable(), $model->getAlias());

		return $this;
	}

	public function leftJoinModels(Model $modelA, Model $modelB, $bNoSelect = false)
	{
		return $this->joinModels($modelA, $modelB, 'left', $bNoSelect);
	}

	public function joinModels(Model $modelA, Model $modelB, $type = 'inner', $bNoSelect = false)
	{
		$type = in_array($type, ['inner', 'left', 'right']) ? $type : 'inner';

		if (!$bNoSelect)
		{
			$this
				->addSelect($modelB->getColumns(true, true, true));
		}

		switch ($type)
		{
			default:
			case 'inner':
				$this->innerJoin($modelA->getAlias(), $modelB->getTable(), $modelB->getAlias(),

					$modelA->getAlias() . '.' . $modelB->getOwnExternalForeignKey()
					. ' = '
					. $modelB->getAlias() . '.' . $modelB->getPrimaryKey()
				);
				break;

			case 'left':
				$this->leftJoin($modelA->getAlias(), $modelB->getTable(), $modelB->getAlias(),

					$modelA->getAlias() . '.' . $modelB->getOwnExternalForeignKey().
					' = '.
					$modelB->getAlias() . '.' . $modelB->getPrimaryKey()

				);
				break;

			case 'right':
				break;
		}

		$modelB->autoJoin($this);

		return $this;
	}

	public function findByPk(Model $model, $mPrimaryKey)
	{
		$this
			->where($model->getAlias() . '.' . $model->getPrimaryKey() . ' = :pk')
			->setParameter('pk', $mPrimaryKey)
		;

		return $this;
	}
}
