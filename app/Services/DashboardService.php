<?php

namespace App\Services;

use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use App\Models\UserTask;

class DashboardService
{
    /*
     * Fetch all Tasks */
    public function getTasks()
    {
        return Task::all()->count();
    }

    public function getCheckinTasks()
    {
        return Task::where('status', 'Check-in')->count();
    }

    public function getTreatmentTasks()
    {
        return Task::where('status', 'Treatment')->count();
    }
    public function getReferralTasks()
    {
        return Task::where('status', 'Referral')->count();
    }

    public function getCheckOutTasks()
    {
        return Task::where('status', 'Check-out')->count();
    }
    /*
     * Fetch Tasks by Status */

    /*
     * Fetch Tasks by Percentage */
    public function getTaskByPercentage($value)
    {
        $tasks = Task::all()->count();

        $percentage = $value > 0 ? $value / $tasks * 100 : 0;

        return round($percentage, 1);
    }

    /*
     *
     *     User Data
     *
     */

    /*
     * Fetch all Users */
    public function getUsers()
    {
        return User::all()->count();
    }

    /*
     * Fetch Users with tasks */
    public function getUsersWithTasks()
    {
        return UserTask::whereNull("end_time")
            ->select("user_id")
            ->distinct()
            ->get()
            ->count();
    }

    /*
     * Fetch Users with tasks */
    public function getUsersWithTasksPercentage()
    {
        $withTasks = $this->getUsersWithTasks();

        $totalUsers = $this->getUsers();

        $percentage = $withTasks > 0 ? $withTasks / $totalUsers * 100 : 0;

        return round($percentage, 1);
    }
}
