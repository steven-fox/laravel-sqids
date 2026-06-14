<?php

namespace StevenFox\LaravelSqids\Traits;

use Illuminate\Database\Eloquent\Model;
use StevenFox\LaravelSqids\ModelSqidders\ModelSqidder;

/**
 * @phpstan-require-extends Model
 */
trait HasAttributesSqidder
{
    /**
     * @return ModelSqidder<static>
     */
    public function sqidder(): ModelSqidder
    {
        /** @var class-string<ModelSqidder> $class */
        $class = $this->sqidderClass();

        return new $class($this);
    }

    /**
     * @return class-string<ModelSqidder>
     */
    protected function sqidderClass(): string
    {
        return ModelSqidder::class;
    }
}
