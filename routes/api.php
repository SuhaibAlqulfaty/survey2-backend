<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SurveyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/metrics', [DashboardController::class, 'metrics']);
    Route::get('/dashboard/charts', [DashboardController::class, 'charts']);
    Route::get('/dashboard/recent-campaigns', [DashboardController::class, 'recentCampaigns']);
    
    // Survey routes
    Route::apiResource('surveys', SurveyController::class);
    
    // Channel Orchestration routes
    Route::get('/channels/stats', function () {
        return response()->json([
            'email' => ['sent' => 12450, 'delivered' => 11890, 'opened' => 8234, 'status' => 'active'],
            'sms' => ['sent' => 8920, 'delivered' => 8756, 'opened' => 6543, 'status' => 'active'],
            'whatsapp' => ['sent' => 5670, 'delivered' => 5234, 'opened' => 4123, 'status' => 'inactive'],
            'voice' => ['sent' => 2340, 'delivered' => 2156, 'opened' => 1890, 'status' => 'active'],
        ]);
    });
    
    // Campaign Manager routes
    Route::get('/campaigns/stats', function () {
        return response()->json([
            'total' => 4,
            'active' => 2,
            'completed' => 1,
            'scheduled' => 1,
        ]);
    });
    
    Route::get('/campaigns', function () {
        return response()->json([
            [
                'id' => 1,
                'name' => 'Customer Satisfaction Q2 2024',
                'description' => 'Quarterly customer satisfaction survey for all premium customers',
                'target_audience' => 'Premium Customers',
                'responses' => '1234 / 5000',
                'response_rate' => '68.4%',
                'status' => 'Active',
                'last_activity' => '2 hours ago',
                'date_range' => '2024-04-01 - 2024-06-30',
                'channels' => ['Email', 'SMS'],
                'progress' => 68,
            ],
            [
                'id' => 2,
                'name' => 'Market Research Study',
                'description' => 'Research on market trends and customer preferences',
                'target_audience' => 'Target Market',
                'responses' => '1,350 / 3,000',
                'response_rate' => '45%',
                'status' => 'Active',
                'last_activity' => '5 hours ago',
                'date_range' => '2024-05-01 - 2024-05-31',
                'channels' => ['Email', 'SMS'],
                'progress' => 45,
            ],
        ]);
    });
    
    // Billing & Usage routes
    Route::get('/billing/usage', function () {
        return response()->json([
            'current_balance' => 1247.50,
            'current_balance_change' => '+$150 this month',
            'credits_used' => 17550,
            'credits_used_change' => '+12% vs last month',
            'monthly_spend' => 2632.10,
            'monthly_spend_change' => '-8% vs last month',
            'cost_per_response' => 0.18,
            'cost_per_response_change' => '-$0.02 vs last month',
            'plan' => [
                'name' => 'Pro Plan',
                'price' => 299,
                'credits_remaining' => 32450,
                'next_billing' => 'Jul 1',
                'auto_renewal' => true,
            ],
        ]);
    });
    
    // Integration Hub routes
    Route::get('/integrations/stats', function () {
        return response()->json([
            'total' => 8,
            'connected' => 4,
            'data_synced' => '45.2K',
            'api_calls' => '12.8K',
        ]);
    });
    
    Route::get('/integrations', function () {
        return response()->json([
            [
                'id' => 1,
                'name' => 'Salesforce',
                'type' => 'CRM',
                'description' => 'Sync survey responses with your Salesforce contacts and leads',
                'status' => 'connected',
                'last_sync' => '2 hours ago',
                'features' => ['Contact Sync', 'Lead Scoring', 'Automated Workflows'],
            ],
            [
                'id' => 2,
                'name' => 'Mailchimp',
                'type' => 'Email Marketing',
                'description' => 'Send surveys through Mailchimp campaigns and sync subscriber data',
                'status' => 'connected',
                'last_sync' => '1 day ago',
                'features' => ['Campaign Integration', 'Subscriber Sync', 'Segmentation'],
            ],
            [
                'id' => 3,
                'name' => 'Slack',
                'type' => 'Communication',
                'description' => 'Get real-time notifications about survey responses in Slack',
                'status' => 'connected',
                'last_sync' => '30 minutes ago',
                'features' => ['Real-time Notifications', 'Channel Integration', 'Custom Alerts'],
            ],
        ]);
    });
    
    // Admin Settings routes
    Route::get('/admin/users/stats', function () {
        return response()->json([
            'total' => 5,
            'active' => 4,
            'admins' => 1,
            'api_keys' => 3,
        ]);
    });
    
    Route::get('/admin/users', function () {
        return response()->json([
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john.doe@acmecorp.com',
                'role' => 'Admin',
                'status' => 'Active',
                'last_login' => '2 hours ago',
            ],
            [
                'id' => 2,
                'name' => 'Sarah Johnson',
                'email' => 'sarah.j@acmecorp.com',
                'role' => 'Manager',
                'status' => 'Active',
                'last_login' => '1 day ago',
            ],
            [
                'id' => 3,
                'name' => 'Mike Davis',
                'email' => 'mike.d@acmecorp.com',
                'role' => 'User',
                'status' => 'Active',
                'last_login' => '3 days ago',
            ],
            [
                'id' => 4,
                'name' => 'Emily Brown',
                'email' => 'emily.b@acmecorp.com',
                'role' => 'User',
                'status' => 'Inactive',
                'last_login' => '2 weeks ago',
            ],
            [
                'id' => 5,
                'name' => 'David Wilson',
                'email' => 'david.w@acmecorp.com',
                'role' => 'Manager',
                'status' => 'Active',
                'last_login' => '5 hours ago',
            ],
        ]);
    });
    
    // Consent & Privacy routes
    Route::get('/privacy/stats', function () {
        return response()->json([
            'consents_collected' => 21370,
            'active_consents' => 19245,
            'data_requests' => 3,
            'audit_events' => 4,
        ]);
    });
    
    // Analytics routes
    Route::get('/analytics/overview', function () {
        return response()->json([
            'total_responses' => 45230,
            'completion_rate' => 78.5,
            'average_time' => '4m 32s',
            'satisfaction_score' => 4.2,
        ]);
    });
    
    // Contacts & Audience routes
    Route::get('/contacts/stats', function () {
        return response()->json([
            'total_contacts' => 45230,
            'active_contacts' => 42150,
            'segments' => 12,
            'recent_imports' => 3,
        ]);
    });
    
    Route::get('/contacts', function () {
        return response()->json([
            [
                'id' => 1,
                'name' => 'Alice Johnson',
                'email' => 'alice@example.com',
                'phone' => '+1 (555) 123-4567',
                'status' => 'Active',
                'segments' => ['Premium', 'Engaged'],
                'last_response' => '2 days ago',
                'consent_status' => 'Granted',
            ],
            [
                'id' => 2,
                'name' => 'Bob Smith',
                'email' => 'bob@example.com',
                'phone' => '+1 (555) 987-6543',
                'status' => 'Active',
                'segments' => ['Basic', 'New'],
                'last_response' => '1 week ago',
                'consent_status' => 'Granted',
            ],
            [
                'id' => 3,
                'name' => 'Carol Davis',
                'email' => 'carol@example.com',
                'phone' => '+1 (555) 456-7890',
                'status' => 'Inactive',
                'segments' => ['Premium'],
                'last_response' => '1 month ago',
                'consent_status' => 'Pending',
            ],
        ]);
    });
});
