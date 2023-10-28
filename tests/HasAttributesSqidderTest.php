<?php

use Illuminate\Database\Eloquent\Model;
use StevenFox\LaravelSqids\ModelSqidders\ModelSqidder;
use StevenFox\LaravelSqids\Traits\HasAttributesSqidder;

it('has a sqidder method that returns a ModelSqidder by default', function () {
    $model = new HasAttributesSqidderModel();

    expect($model->sqidder())->toBeInstanceOf(ModelSqidder::class);
});

test('the sqidder method can be overridden to provide a custom model sqidder', function () {
    $model = new CustomSqidderBySqidderMethodModel();

    expect($model->sqidder())->toBeInstanceOf(HasAttributesSqidderTestCustomSqidder::class);
});

it('has a sqidderClass method that permits overriding the sqidder type', function () {
    $model = new CustomSqidderByClassNameSqidModel();

    expect($model->sqidder())->toBeInstanceOf(HasAttributesSqidderTestCustomSqidder::class);
});

class HasAttributesSqidderModel extends Model
{
    use HasAttributesSqidder;
}

class CustomSqidderByClassNameSqidModel extends Model
{
    use HasAttributesSqidder;

    protected function sqidderClass(): string
    {
        return HasAttributesSqidderTestCustomSqidder::class;
    }
}

class CustomSqidderBySqidderMethodModel extends Model
{
    use HasAttributesSqidder;

    public function sqidder(): ModelSqidder
    {
        return new HasAttributesSqidderTestCustomSqidder($this);
    }
}

class HasAttributesSqidderTestCustomSqidder extends ModelSqidder
{
}
