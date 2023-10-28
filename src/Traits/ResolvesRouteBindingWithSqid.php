<?php

namespace StevenFox\LaravelSqids\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use StevenFox\LaravelSqids\Exceptions\CannotDecodeSqidToAttributesArray;

trait ResolvesRouteBindingWithSqid
{
    use MakesEncodedAndDecodedSqidInstances;

    public function resolveRouteBindingQuery($query, $value, $field = null): Model|Builder
    {
        if (in_array(HasAttributesSqidder::class, class_uses_recursive($this), true)) {
            return $this->resolveRouteBindingQueryForModelThatEncodesAttributesToSqid($value, $query);
        }

        $decodedSqid = $this->makeEncodedSqid($value)->decodeOrFail();

        if (count($decodedSqid->numbers()) !== 1) {
            throw CannotDecodeSqidToAttributesArray::numbersCountDoesNotMatchExpected($value);
        }

        return $query->where($field ?? $this->getRouteKeyName(), $decodedSqid->toInt());
    }

    private function resolveRouteBindingQueryForModelThatEncodesAttributesToSqid($value, $query): Model|Builder
    {
        /** @var HasAttributesSqidder $this */
        foreach ($this->sqidder()->decodeToAttributesArray($value) as $attribute => $number) {
            $query = $query->where($attribute, $number);
        }

        return $query;
    }
}
