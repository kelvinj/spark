<?php

namespace Laravel\Spark;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

trait InteractsWithSparkHooks
{
    /**
     * Get the response from a custom validator callback.
     *
     * @param  callable|string  $callback
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $arguments
     * @return void
     */
    public function callCustomValidator($callback, Request $request, array $arguments = [])
    {
        if (is_string($callback)) {
            list($class, $method) = explode('@', $callback);

            $callback = [app($class), $method];
        }

        $validator = call_user_func_array($callback, array_merge([$request], $arguments));

        $validator = $validator instanceof ValidatorContract
                        ? $validator
                        : Validator::make($request->all(), $validator);

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
    }

    /**
     * Call a custom Spark updater callback.
     *
     * @param  callable|string  $callback
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $arguments
     * @return mixed
     */
    public function callCustomUpdater($callback, Request $request, array $arguments = [])
    {
        if (is_string($callback)) {
            list($class, $method) = explode('@', $callback);

            $callback = [app($class), $method];
        }

        return call_user_func_array($callback, array_merge([$request], $arguments));
    }
}
