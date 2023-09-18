<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    /*
     * Get Task data */
    public function tasks(DashboardService $service)
    {


        // Fetch Data
        return response([
            "tasks" => $service->getTasks(),
            "checkin" => $service->getCheckinTasks(),
            "treatment" => $service->getTreatmentTasks(),
            "referrals" => $service->getReferralTasks(),
            "checkout" => $service->getCheckOutTasks()
        ], 200);
    }

    /*
     * Get User data */
    public function users(DashboardService $service)
    {
        $withTasksPercentage = $service->getUsersWithTasksPercentage();

        // Fetch Data
        return response([
            "users" => $service->getUsers(),
            "withTasks" => [
                "total" => $service->getUsersWithTasks(),
                "percent" => $withTasksPercentage,
            ],
            "withoutTasks" => [
                "percent" => (100 - $withTasksPercentage),
            ],
        ], 200);
    }
}
