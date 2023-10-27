<?php

namespace StevenFox\LaravelSqids\ModelSqidders;

use Illuminate\Database\Eloquent\Model;
use StevenFox\LaravelSqids\Exceptions\CannotDecodeSqidToAttributesArray;
use StevenFox\LaravelSqids\Sqids\DecodedSqid;
use StevenFox\LaravelSqids\Sqids\EncodedSqid;
use StevenFox\LaravelSqids\Traits\MakesEncodedAndDecodedSqidInstances;

/**
 * @template TModel of Model
 * @template TEncodedSqid of EncodedSqid
 * @template TDecodedSqid of DecodedSqid
 */
class ModelSqidder
{
    use MakesEncodedAndDecodedSqidInstances;

    public function __construct(protected Model $model)
    {
    }

    /**
     * @return TEncodedSqid
     */
    public function encode(): EncodedSqid
    {
        return $this
            ->makeDecodedSqid($this->sqidNumbersFromAttributes())
            ->encode();
    }

    /**
     * @return TDecodedSqid
     */
    public function decode(string $sqid): DecodedSqid
    {
        return $this
            ->makeEncodedSqid($sqid)
            ->decode();
    }

    /**
     * @return TDecodedSqid
     */
    public function decodeOrFail(string $sqid): DecodedSqid
    {
        return $this
            ->makeEncodedSqid($sqid)
            ->decodeOrFail();
    }

    /**
     * @return TModel
     */
    public function decodeAndFill(string $sqid): Model
    {
        return $this->model->fill($this->decodeToAttributesArray($sqid));
    }

    /**
     * @return TModel
     */
    public function decodeAndForceFill(string $sqid): Model
    {
        return $this->model->forceFill($this->decodeToAttributesArray($sqid));
    }

    /**
     * Decode the sqid into an associative array of
     * attributes (as keys) and numbers (as values).
     *
     * @return array<string, int>
     */
    public function decodeToAttributesArray(string $sqid): array
    {
        $attributesForSqid = $this->attributeNamesUsedForSqid();
        $decodedNumbers = $this->decodeOrFail($sqid)->numbers();

        if (count($decodedNumbers) !== count($attributesForSqid)) {
            throw CannotDecodeSqidToAttributesArray::numbersCountDoesNotMatchExpected($sqid);
        }

        return collect($attributesForSqid)
            ->combine($decodedNumbers)
            ->all();
    }

    /**
     * @return int[]
     */
    protected function sqidNumbersFromAttributes(): array
    {
        $numbers = [];

        foreach ($this->attributeNamesUsedForSqid() as $attributeName) {
            $numbers[] = (int) $this->model->{$attributeName};
        }

        return $numbers;
    }

    /**
     * @return string[]
     */
    protected function attributeNamesUsedForSqid(): array
    {
        return [
            $this->model->getRouteKeyName(),
        ];
    }
}
