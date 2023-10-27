<?php

use Illuminate\Database\Eloquent\Model;
use StevenFox\LaravelSqids\ModelSqidders\ModelSqidder;
use StevenFox\LaravelSqids\Sqids\EncodedSqid;
use StevenFox\LaravelSqids\Traits\EncodesModelAttributesToSqid;

it('has a sqid attribute that provides the encoded sqid as a string', function () {
    $model = new SqidModel(['id' => 1]);

    expect($model->sqid)->toBe('Uk');
});

it('the sqid attribute will use a custom model sqidder', function () {
    $model = new CustomSqidderModel(['id' => 1]);

    expect($model->sqid)->toBe('foobar');
});

it('will append the sqid on the model by default', function () {
    $model = new SqidModel();

    expect($model->hasAppended('sqid'))->toBeTrue();
});

it('appending the sqid can be overridden', function () {
    $model = new NonAppendingModel();

    expect($model->hasAppended('sqid'))->toBeFalse();
});

class SqidModel extends Model
{
    use EncodesModelAttributesToSqid;

    protected $guarded = [];
}

class CustomSqidderModel extends Model
{
    use EncodesModelAttributesToSqid;

    protected $guarded = [];

    protected function sqidderClass(): string
    {
        return CustomModelSqidder::class;
    }
}

class CustomModelSqidder extends ModelSqidder
{
    public function encode(): EncodedSqid
    {
        return new EncodedSqid('foobar');
    }
}

class NonAppendingModel extends Model
{
    use EncodesModelAttributesToSqid;

    protected function shouldAppendSqid(): bool
    {
        return false;
    }
}
