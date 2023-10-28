<?php

use Illuminate\Database\Eloquent\Model;
use StevenFox\LaravelSqids\ModelSqidders\ModelSqidder;
use StevenFox\LaravelSqids\Traits\EncodesModelAttributesToSqid;
use StevenFox\LaravelSqids\Traits\ResolvesRouteBindingWithSqid;

test('route binding', function () {
    // TODO: split into multiple tests
    $user = SimpleModel::unguarded(fn () => new SimpleModel(['id' => 1]));
    $query = $user->resolveRouteBindingQuery($user, 'Uk');
    expect($query->toRawSql())->toBe('select * from "models" where "id" = 1');

    $user = SimpleModel::unguarded(fn () => new SimpleModel(['id' => 1]));
    $query = $user->resolveRouteBindingQuery($user, 'Uk', 'baz'); // custom field
    expect($query->toRawSql())->toBe('select * from "models" where "baz" = 1');

    $user = SqidderModel::unguarded(fn () => new SqidderModel(['foo' => 1]));
    $query = $user->resolveRouteBindingQuery($user, 'Uk');
    expect($query->toRawSql())->toBe('select * from "models" where "foo" = 1');

    $user = ComplexSqidderModel::unguarded(fn () => new ComplexSqidderModel(['foo' => 1, 'bar' => 2]));
    $query = $user->resolveRouteBindingQuery($user, 'XMbT');
    expect($query->toRawSql())->toBe('select * from "models" where "foo" = 1 and "bar" = 2');

    $user = ComplexSqidderModel::unguarded(fn () => new ComplexSqidderModel(['foo' => 1, 'bar' => 2]));
    $query = $user->resolveRouteBindingQuery($user, 'XMbT', 'baz'); // custom field does nothing
    expect($query->toRawSql())->toBe('select * from "models" where "foo" = 1 and "bar" = 2');
});

class SimpleModel extends Model
{
    use ResolvesRouteBindingWithSqid;

    protected $table = 'models';

    protected $guarded = [];
}

class SqidderModel extends Model
{
    use EncodesModelAttributesToSqid;
    use ResolvesRouteBindingWithSqid;

    protected $table = 'models';

    public function getRouteKeyName()
    {
        return 'foo';
    }
}

class ComplexSqidderModel extends Model
{
    use EncodesModelAttributesToSqid;
    use ResolvesRouteBindingWithSqid;

    protected $table = 'models';

    protected function sqidderClass(): string
    {
        return ComplexModelSqidder::class;
    }
}

class ComplexModelSqidder extends ModelSqidder
{
    protected function attributeNamesUsedForSqid(): array
    {
        return [
            'foo',
            'bar',
        ];
    }
}
