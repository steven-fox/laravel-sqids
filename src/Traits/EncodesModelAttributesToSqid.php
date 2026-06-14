<?php

namespace StevenFox\LaravelSqids\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @phpstan-require-extends Model
 */
trait EncodesModelAttributesToSqid
{
    use HasAttributesSqidder;

    protected function initializeEncodesModelAttributesToSqid(): void
    {
        if ($this->shouldAppendSqid()) {
            $this->append('sqid');
        }
    }

    protected function shouldAppendSqid(): bool
    {
        return true;
    }

    /** @return Attribute<string, never> */
    protected function sqid(): Attribute
    {
        return Attribute::get(function (): string {
            return $this->sqidder()->encode()->id();
        });
    }
}
