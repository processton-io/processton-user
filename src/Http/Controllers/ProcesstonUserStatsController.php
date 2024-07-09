<?php

namespace Processton\ProcesstonUser\Http\Controllers;
use Illuminate\Support\Carbon;
use Processton\ProcesstonDataTable\ProcesstonDataTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Processton\ProcesstonStatsCard\ProcesstonStatsCard;
use Processton\ProcesstonUser\Models\Session;

class ProcesstonUserStatsController
{
    public function usersCount() : JsonResponse
    {
        $model = config('auth.providers.users.model');
        $data = $model::count();

        return response()->json([
            'data' => ProcesstonStatsCard::generateStatsCardData('Users', $data)
        ]);
    }
    public function newUsersCount(): JsonResponse
    {
        $model = config('auth.providers.users.model');
        $data = $model::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        return response()->json([
            'data' => ProcesstonStatsCard::generateStatsCardData('This month', $data)
        ]);
    }


    public function sessionsCount(): JsonResponse
    {
        $data = Session::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        return response()->json([
            'data' => ProcesstonStatsCard::generateStatsCardData('Sessions', $data)
        ]);
    }


    public function pendingValidations(): JsonResponse
    {
        $model = config('auth.providers.users.model');
        $data = $model::where('email_verified_at', null)
            ->count();

        return response()->json([
            'data' => ProcesstonStatsCard::generateStatsCardData('Pending validations', $data)
        ]);
    }


    public function sessionsDuration(): JsonResponse
    {
        $data = 0;

        return response()->json([
            'data' => ProcesstonStatsCard::generateStatsCardData('Duration', $data)
        ]);
    }
}
