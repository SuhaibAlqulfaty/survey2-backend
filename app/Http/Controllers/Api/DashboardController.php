<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function metrics(Request $request)
    {
        $organizationId = $request->user()->organization_id;

        // Mock data for now - in production, these would be real database queries
        return response()->json([
            'completed_responses' => 8920,
            'completed_responses_change' => '+8% from last month',
            'active_contacts' => 45230,
            'active_contacts_change' => '+156 this week',
            'survey_views' => 12450,
            'survey_views_change' => '-2% from last month',
            'response_rate' => 68.5,
            'response_rate_change' => '+5.2% from last month',
        ]);
    }

    public function charts(Request $request)
    {
        return response()->json([
            'response_by_channel' => [
                ['name' => 'Email', 'value' => 45, 'color' => '#3b82f6'],
                ['name' => 'SMS', 'value' => 30, 'color' => '#10b981'],
                ['name' => 'WhatsApp', 'value' => 20, 'color' => '#f59e0b'],
                ['name' => 'Voice', 'value' => 5, 'color' => '#ef4444'],
            ],
            'response_trend' => [
                ['month' => 'Jan', 'responses' => 4000],
                ['month' => 'Feb', 'responses' => 3000],
                ['month' => 'Mar', 'responses' => 5000],
                ['month' => 'Apr', 'responses' => 4500],
                ['month' => 'May', 'responses' => 6000],
                ['month' => 'Jun', 'responses' => 9000],
            ],
        ]);
    }

    public function recentCampaigns(Request $request)
    {
        return response()->json([
            [
                'id' => 1,
                'name' => 'Customer Satisfaction Q2 2024',
                'responses' => '1234 / 5000 responses',
                'response_rate' => '78%',
                'status' => 'Active',
                'progress' => 78,
            ],
            [
                'id' => 2,
                'name' => 'Product Feedback Survey',
                'responses' => '892 / 1000 responses',
                'response_rate' => '100%',
                'status' => 'Completed',
                'progress' => 100,
            ],
            [
                'id' => 3,
                'name' => 'Employee Engagement',
                'responses' => '0 / 2500 responses',
                'response_rate' => '0%',
                'status' => 'Scheduled',
                'progress' => 0,
            ],
        ]);
    }
}
