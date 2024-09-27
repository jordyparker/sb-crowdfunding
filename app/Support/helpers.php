<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

if (!function_exists('appGuard')) {
    /**
     * Returns the active waspito guard
     */
    function appGuard()
    {
        foreach (array_keys(config('auth.guards')) as $guard) {
            if (auth()->guard($guard)->check()) return $guard;
        }
        return null;
    }
}

if (!function_exists('generateUsername')) {
    /**
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string $username
     * @param string $except
     * @return string
     */
    function generateUsername(Model $model, $username, $except = null): string
    {
        $exists = $model::when($except, function ($q) use ($except) {
            $q->where('username', '!=', $except);
        })->where('username', $username)->exists();

        while ($exists) {
            return generateUsername($model, $username . random_int(10, 99), $except);
        }

        return $username;
    }
}

if (!function_exists('generateSlug')) {
    /**
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string $name
     * @param string $column
     * @return string
     */
    function generateSlug(Model $model, $name, $column = 'slug'): string
    {
        $slug = Str::slug($name);

        $exists = $model::where($column, $slug)->exists();

        while ($exists) {
            return generateSlug($model, $name . random_int(1000, 9999), $column);
        }

        return $slug;
    }
}

if (!function_exists('convertAmount')) {
    /**
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string $from
     * @param string $to
     * @param float $amount
     * @return string
     */
    function convertAmount(string $from, string $to, float $amount): float
    {
        if ($from === $to) {
            return $amount;
        }

        // TODO: convert amount to the target currency
        return $amount;
    }
}

