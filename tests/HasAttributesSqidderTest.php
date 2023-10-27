<?php

use Illuminate\Database\Eloquent\Model;
use StevenFox\LaravelSqids\ModelSqidders\ModelSqidder;
use StevenFox\LaravelSqids\Traits\EncodesModelAttributesToSqid;

it('has a sqidder method that returns a ModelSqidder by default', function () {
    $model = new SqidModel();

    expect($model->sqidder())->toBeInstanceOf(ModelSqidder::class);
});

test('the sqidder method can be overridden to provide a custom model sqidder', function () {
    $model = new CustomSqidderBySqidderMethodModel();

    expect($model->sqidder())->toBeInstanceOf(CustomModelSqidder::class);
});

it('has a sqidderClass method that permits overriding the sqidder type', function () {
    $model = new CustomSqidderByClassNameSqidModel();

    expect($model->sqidder())->toBeInstanceOf(CustomModelSqidder::class);
});

class SqidModel extends Model
{
    use EncodesModelAttributesToSqid;
}

class CustomSqidderByClassNameSqidModel extends Model
{
    use EncodesModelAttributesToSqid;

    protected function sqidderClass(): string
    {
        return CustomModelSqidder::class;
    }
}

class CustomSqidderBySqidderMethodModel extends Model
{
    use EncodesModelAttributesToSqid;

    public function sqidder(): ModelSqidder
    {
        return new CustomModelSqidder($this);
    }
}

class CustomModelSqidder extends ModelSqidder
{
}
