<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{
    /**
     * Display a listing of surveys for the authenticated user's organization.
     */
    public function index()
    {
        $surveys = Survey::where('organization_id', Auth::user()->organization_id)
            ->with(['questions', 'responses'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $surveys
        ]);
    }

    /**
     * Store a newly created survey.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.title' => 'required|string|max:255',
            'questions.*.type' => 'required|in:text,multiple_choice,single_choice,rating,date,long_text,image_upload',
            'questions.*.required' => 'boolean',
            'questions.*.options' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $survey = Survey::create([
                'title' => $request->title,
                'description' => $request->description,
                'organization_id' => Auth::user()->organization_id,
                'created_by' => Auth::id(),
                'status' => 'draft',
                'settings' => [
                    'allow_anonymous' => true,
                    'collect_email' => false,
                    'show_progress' => true,
                    'randomize_questions' => false
                ]
            ]);

            // Create questions
            foreach ($request->questions as $index => $questionData) {
                Question::create([
                    'survey_id' => $survey->id,
                    'title' => $questionData['title'],
                    'type' => $questionData['type'],
                    'required' => $questionData['required'] ?? false,
                    'options' => $questionData['options'] ?? null,
                    'order' => $index + 1,
                    'settings' => []
                ]);
            }

            $survey->load(['questions', 'responses']);

            return response()->json([
                'success' => true,
                'message' => 'Survey created successfully',
                'data' => $survey
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create survey',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified survey.
     */
    public function show($id)
    {
        $survey = Survey::where('organization_id', Auth::user()->organization_id)
            ->with(['questions', 'responses'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $survey
        ]);
    }

    /**
     * Update the specified survey.
     */
    public function update(Request $request, $id)
    {
        $survey = Survey::where('organization_id', Auth::user()->organization_id)
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:draft,active,paused,completed',
            'questions' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $survey->update($request->only(['title', 'description', 'status']));

            // Update questions if provided
            if ($request->has('questions')) {
                // Delete existing questions
                $survey->questions()->delete();
                
                // Create new questions
                foreach ($request->questions as $index => $questionData) {
                    Question::create([
                        'survey_id' => $survey->id,
                        'title' => $questionData['title'],
                        'type' => $questionData['type'],
                        'required' => $questionData['required'] ?? false,
                        'options' => $questionData['options'] ?? null,
                        'order' => $index + 1,
                        'settings' => []
                    ]);
                }
            }

            $survey->load(['questions', 'responses']);

            return response()->json([
                'success' => true,
                'message' => 'Survey updated successfully',
                'data' => $survey
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update survey',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified survey.
     */
    public function destroy($id)
    {
        $survey = Survey::where('organization_id', Auth::user()->organization_id)
            ->findOrFail($id);

        try {
            $survey->delete();

            return response()->json([
                'success' => true,
                'message' => 'Survey deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete survey',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get survey statistics.
     */
    public function stats()
    {
        $organizationId = Auth::user()->organization_id;
        
        $stats = [
            'total_surveys' => Survey::where('organization_id', $organizationId)->count(),
            'active_surveys' => Survey::where('organization_id', $organizationId)->where('status', 'active')->count(),
            'draft_surveys' => Survey::where('organization_id', $organizationId)->where('status', 'draft')->count(),
            'completed_surveys' => Survey::where('organization_id', $organizationId)->where('status', 'completed')->count(),
            'total_responses' => \DB::table('responses')
                ->join('surveys', 'responses.survey_id', '=', 'surveys.id')
                ->where('surveys.organization_id', $organizationId)
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Publish a survey (make it active).
     */
    public function publish($id)
    {
        $survey = Survey::where('organization_id', Auth::user()->organization_id)
            ->findOrFail($id);

        $survey->update(['status' => 'active']);

        return response()->json([
            'success' => true,
            'message' => 'Survey published successfully',
            'data' => $survey
        ]);
    }

    /**
     * Pause a survey.
     */
    public function pause($id)
    {
        $survey = Survey::where('organization_id', Auth::user()->organization_id)
            ->findOrFail($id);

        $survey->update(['status' => 'paused']);

        return response()->json([
            'success' => true,
            'message' => 'Survey paused successfully',
            'data' => $survey
        ]);
    }
}
