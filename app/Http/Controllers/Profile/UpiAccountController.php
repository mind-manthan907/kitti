<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\UpiAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpiAccountController extends Controller
{
    /**
     * Show UPI accounts management page
     */
    public function index()
    {
        $user = Auth::user();
        $upiAccounts = $user->upiAccounts()->latest()->get();
        
        return view('profile.upi-accounts.index', compact('user', 'upiAccounts'));
    }

    /**
     * Show UPI account creation form
     */
    public function create()
    {
        $user = Auth::user();
        return view('profile.upi-accounts.create', compact('user'));
    }

    /**
     * Store UPI account
     */
    public function store(Request $request)
    {
        $request->validate([
            'upi_id' => 'required|string|max:100|unique:upi_accounts,upi_id',
            'account_holder_name' => 'required|string|max:100',
            'is_primary' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            // If this is primary, unset other primary accounts
            if ($request->boolean('is_primary')) {
                UpiAccount::where('user_id', Auth::id())
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }

            // Create UPI account
            UpiAccount::create([
                'user_id' => Auth::id(),
                'upi_id' => $request->upi_id,
                'account_holder_name' => $request->account_holder_name,
                'is_primary' => $request->boolean('is_primary'),
            ]);

            DB::commit();

            return redirect()->route('profile.upi-accounts.index')
                ->with('success', 'UPI account added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error adding UPI account: ' . $e->getMessage());
            return back()->with('error', 'Failed to add UPI account. Please try again.');
        }
    }

    /**
     * Show UPI account edit form
     */
    public function edit(UpiAccount $upiAccount)
    {
        // Ensure user can only edit their own accounts
        if ($upiAccount->user_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();
        return view('profile.upi-accounts.edit', compact('user', 'upiAccount'));
    }

    /**
     * Update UPI account
     */
    public function update(Request $request, UpiAccount $upiAccount)
    {
        // Ensure user can only update their own accounts
        if ($upiAccount->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'upi_id' => 'required|string|max:100|unique:upi_accounts,upi_id,' . $upiAccount->id,
            'account_holder_name' => 'required|string|max:100',
            'is_primary' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            // If this is primary, unset other primary accounts
            if ($request->boolean('is_primary')) {
                UpiAccount::where('user_id', Auth::id())
                    ->where('is_primary', true)
                    ->where('id', '!=', $upiAccount->id)
                    ->update(['is_primary' => false]);
            }

            // Update UPI account
            $upiAccount->update([
                'upi_id' => $request->upi_id,
                'account_holder_name' => $request->account_holder_name,
                'is_primary' => $request->boolean('is_primary'),
            ]);

            DB::commit();

            return redirect()->route('profile.upi-accounts.index')
                ->with('success', 'UPI account updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating UPI account: ' . $e->getMessage());
            return back()->with('error', 'Failed to update UPI account. Please try again.');
        }
    }

    /**
     * Toggle UPI account status (activate/deactivate)
     */
    public function toggleStatus(UpiAccount $upiAccount)
    {
        // Ensure user can only toggle their own accounts
        if ($upiAccount->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $upiAccount->update([
                'is_active' => !$upiAccount->is_active
            ]);

            $status = $upiAccount->is_active ? 'activated' : 'deactivated';
            return back()->with('success', "UPI account {$status} successfully!");

        } catch (\Exception $e) {
            \Log::error('Error toggling UPI account status: ' . $e->getMessage());
            return back()->with('error', 'Failed to update UPI account status. Please try again.');
        }
    }

    /**
     * Set UPI account as primary
     */
    public function setPrimary(UpiAccount $upiAccount)
    {
        // Ensure user can only set their own accounts as primary
        if ($upiAccount->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::beginTransaction();

            // Unset other primary accounts
            UpiAccount::where('user_id', Auth::id())
                ->where('is_primary', true)
                ->update(['is_primary' => false]);

            // Set this account as primary
            $upiAccount->update(['is_primary' => true]);

            DB::commit();

            return back()->with('success', 'Primary UPI account updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error setting primary UPI account: ' . $e->getMessage());
            return back()->with('error', 'Failed to update primary UPI account. Please try again.');
        }
    }
}
