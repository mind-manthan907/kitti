<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvestmentPlan;
use Illuminate\Support\Facades\Validator;
use App\Models\SystemConfig;

class InvestmentPlanController extends Controller
{
    /**
     * Display a listing of investment plans
     */

    public function landingPage()
    {
        $company_name = SystemConfig::getValue('company_name', 'KITTI Investment Platform');
        $company_address = SystemConfig::getValue('company_address', 'Mumbai, Maharashtra, India');

        $plans = InvestmentPlan::orderBy('amount')->paginate(10);
        return view('welcome', compact('plans','company_name','company_address'));
    }

    public function index()
    {
        $plans = InvestmentPlan::orderBy('amount')->paginate(10);

        return view('admin.investment_plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new investment plan
     */
    public function create()
    {
        return view('admin.investment_plans.create');
    }

    /**
     * Store a newly created investment plan
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:investment_plans',
            'amount' => 'required|numeric|min:1000',
            'duration_months' => 'required|integer|min:1|max:120',
            'emi_months' => 'required|integer|min:1|max:12',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string|max:1000',
            'min_duration_months' => 'required|integer|min:1|max:120',
            'max_duration_months' => 'required|integer|min:1|max:120|gte:min_duration_months',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            InvestmentPlan::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Investment plan created successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Exception caught: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create investment plan. Please try again.'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified investment plan
     */
    public function edit(InvestmentPlan $investmentPlan)
    {
        return view('admin.investment_plans.edit', compact('investmentPlan'));
    }

    /**
     * Update the specified investment plan
     */
    public function update(Request $request, InvestmentPlan $investmentPlan)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:investment_plans,name,' . $investmentPlan->id,
            'amount' => 'required|numeric|min:1000',
            'duration_months' => 'required|integer|min:1|max:120',
            'emi_months' => 'required|integer|min:1|max:12',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string|max:1000',
            'min_duration_months' => 'required|integer|min:1|max:120',
            'max_duration_months' => 'required|integer|min:1|max:120|gte:min_duration_months',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $investmentPlan->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Investment plan updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update investment plan. Please try again.'
            ], 500);
        }
    }

    /**
     * Toggle the active status of an investment plan
     */
    public function toggleStatus(InvestmentPlan $investmentPlan)
    {
        try {
            $investmentPlan->update([
                'is_active' => !$investmentPlan->is_active
            ]);

            $status = $investmentPlan->is_active ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => "Investment plan {$status} successfully!",
                'is_active' => $investmentPlan->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update plan status. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove the specified investment plan
     */
    public function destroy(InvestmentPlan $investmentPlan)
    {
        try {
            // Check if plan is being used
            if ($investmentPlan->kittiRegistrations()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete plan as it is being used by existing registrations.'
                ], 422);
            }

            $investmentPlan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Investment plan deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete investment plan. Please try again.'
            ], 500);
        }
    }
}
