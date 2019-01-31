<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('old_password', function ($attribute, $value, $parameters, $validator) {
            return Hash::check($value, current($parameters));
        });

        Validator::extend('not_supported', function ($attribute, $value, $parameters, $validator) {
            if (!$value || $value == 'N') {
                return true;
            }
        });

        Validator::extend('time_before', function ($attribute, $value, $parameters, $validator) {
            if (date('H:i', strtotime($value)) <= date('H:i', strtotime($parameters[0]))) {
                return true;
            }
        });

        Validator::extend('time_after', function ($attribute, $value, $parameters, $validator) {
            if (date('H:i', strtotime($value)) > date('H:i', strtotime($parameters[0]))) {
                return true;
            }
        });

        Validator::extend('greater_than_value', function ($attribute, $value, $parameters, $validator) {
            return ($value > $parameters[0]);
        });

        Validator::replacer('time_before', function($message, $attribute, $rule, $parameters) {
            return str_replace(':value', $parameters[0], $message);
        });

        Validator::replacer('time_after', function($message, $attribute, $rule, $parameters) {
            return str_replace(':value', $parameters[0], $message);
        });

        Validator::replacer('greater_than_value', function($message, $attribute, $rule, $parameters) {
            return str_replace(':value', $parameters[0], $message);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
