<?php

namespace App\Models;

use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @method static get()
 * @method static insert(array $array)
 * @method static upsert(array $array, array $uniqueKeys, array $valuesToReplace)
 * @method static insertOrIgnore(array $array)
 * @method static where(string|array $key, string $operator = '', int | string | null $value = null))
 * @method static select(array | string $array)
 * @method static find(int $id)
 * @method static whereNull(string $column)
 * @method static whereNotNull(string $column)
 * @method static whereIn(string $column, array|Closure $values)
 * @method static create(array $data)
 * @method static whereHas(string $key, Closure $closure)
 * @method static firstOrCreate(array $findConditions, array $saveArray)
 * @method static updateOrCreate(array $findConditions, array $updateArray)
 * @method static updateOrInsert(array $findCondition, array $updateArray)
 * @method static insertUsing(string[] $array, Builder $select)
 * @method static latest(string $column = 'id')
 * @method static whereRaw(string $conditions, array $bindings = [])
 * @method static whereDate(string $column, string|Carbon $date)
 * @method static count()
 * @method static findOrFail($id)
 * @method static join(string $string, string $string1, string $string2 = '', string $string3 = '')
 * @method static leftJoin(string $string, string $string1, string $string2 = '', string $string3 = '')
 * @method static firstOrNew(array $array)
 * @method static inRandomOrder()
 * @method static Builder has(string $string)

 */
class BaseModel extends Model
{

}
