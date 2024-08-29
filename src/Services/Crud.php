<?php

namespace Usermp\LaravelGenerator\Services;

use Usermp\LaravelGenerator\Services\Constants;
use Usermp\LaravelGenerator\Services\Response;
use Illuminate\Http\JsonResponse;

class Crud
{
    public static function index($model, $customSetting = false): JsonResponse
    {
        try {
            $resource = $customSetting ? $model : (request()->has("page") ? $model->filter()->orderBy('id', 'DESC')->paginate(15) : $model->filter()->orderBy('id', 'DESC')->get());
            return Response::success(Constants::SUCCESS,$resource);
        } catch (\Exception $exception) {
            \Sentry\captureException($exception);
            return Response::error(env("APP_DEBUG") ? $exception->getMessage() : Constants::ERROR);
        }
    }

    public static function store(array $fields, $model): JsonResponse
    {
        try {
            $response = $model::create($fields);
            return Response::success(Constants::SUCCESS, $response,201);
        } catch (\Exception $exception) {
            \Sentry\captureException($exception);
            return Response::error(env("APP_DEBUG") ? $exception->getMessage() : Constants::ERROR_STORE);
        }
    }

    public static function show($model, $customSetting = null): JsonResponse
    {
        try {
            $resource = func_num_args() > 1 ? $customSetting : $model;
            return Response::success(Constants::SUCCESS, $resource);
        } catch (\Exception $exception) {
            \Sentry\captureException($exception);
            return Response::error(env("APP_DEBUG") ? $exception->getMessage() : Constants::ERROR);
        }
    }

    public static function update(array $fields, $model): JsonResponse
    {
        try {
            $model->update($fields);
            return Response::success(Constants::SUCCESS_UPDATE, $model);
        } catch (\Exception $exception) {
            \Sentry\captureException($exception);
            return Response::error(env("APP_DEBUG") ? $exception->getMessage() : Constants::ERROR_UPDATE);
        }
    }

    public static function destroy($model): JsonResponse
    {
        try {
            $model->delete();
            return Response::success(Constants::SUCCESS_DELETE);
        } catch (\Exception $exception) {
            \Sentry\captureException($exception);
            return Response::error(env("APP_DEBUG") ? $exception->getMessage() : Constants::ERROR_DELETE);
        }
    }
}
