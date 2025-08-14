<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BankAccountController extends Controller
{
    /**
     * Show bank accounts management page
     */
    public function index()
    {
        $user = Auth::user();
        $bankAccounts = $user->bankAccounts()->latest()->get();
        
        return view('profile.bank-accounts.index', compact('user', 'bankAccounts'));
    }

    /**
     * Show bank account creation form
     */
    public function create()
    {
        $user = Auth::user();
        return view('profile.bank-accounts.create', compact('user'));
    }

    /**
     * Store bank account
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_holder_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50|unique:bank_accounts,account_number',
            'bank_name' => 'required|string|max:100',
            'ifsc_code' => 'required|string|max:20',
            'branch_name' => 'nullable|string|max:100',
            'is_primary' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            // If this is primary, unset other primary accounts
            if ($request->boolean('is_primary')) {
                BankAccount::where('user_id', Auth::id())
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }

            // Create bank account
            BankAccount::create([
                'user_id' => Auth::id(),
                'account_holder_name' => $request->account_holder_name,
                'account_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'ifsc_code' => $request->ifsc_code,
                'branch_name' => $request->branch_name,
                'is_primary' => $request->boolean('is_primary'),
            ]);

            DB::commit();

            return redirect()->route('user.profile.bank-accounts.index')
                ->with('success', 'Bank account added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error adding bank account: ' . $e->getMessage());
            return back()->with('error', 'Failed to add bank account. Please try again.');
        }
    }

    /**
     * Show bank account edit form
     */
    public function edit(BankAccount $bankAccount)
    {
        // Ensure user can only edit their own accounts
        if ($bankAccount->user_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();
        return view('profile.bank-accounts.edit', compact('user', 'bankAccount'));
    }

    /**
     * Update bank account
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        // Ensure user can only update their own accounts
        if ($bankAccount->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'account_holder_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50|unique:bank_accounts,account_number,' . $bankAccount->id,
            'bank_name' => 'required|string|max:100',
            'ifsc_code' => 'required|string|max:20',
            'branch_name' => 'nullable|string|max:100',
            'is_primary' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            // If this is primary, unset other primary accounts
            if ($request->boolean('is_primary')) {
                BankAccount::where('user_id', Auth::id())
                    ->where('is_primary', true)
                    ->where('id', '!=', $bankAccount->id)
                    ->update(['is_primary' => false]);
            }

            // Update bank account
            $bankAccount->update([
                'account_holder_name' => $request->account_holder_name,
                'account_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'ifsc_code' => $request->ifsc_code,
                'branch_name' => $request->branch_name,
                'is_primary' => $request->boolean('is_primary'),
            ]);

            DB::commit();

            return redirect()->route('user.profile.bank-accounts.index')
                ->with('success', 'Bank account updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating bank account: ' . $e->getMessage());
            return back()->with('error', 'Failed to update bank account. Please try again.');
        }
    }

    /**
     * Toggle bank account status (activate/deactivate)
     */
    public function toggleStatus(BankAccount $bankAccount)
    {
        // Ensure user can only toggle their own accounts
        if ($bankAccount->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $bankAccount->update([
                'is_active' => !$bankAccount->is_active
            ]);

            $status = $bankAccount->is_active ? 'activated' : 'deactivated';
            return back()->with('success', "Bank account {$status} successfully!");

        } catch (\Exception $e) {
            \Log::error('Error toggling bank account status: ' . $e->getMessage());
            return back()->with('error', 'Failed to update bank account status. Please try again.');
        }
    }

    /**
     * Set bank account as primary
     */
    public function setPrimary(BankAccount $bankAccount)
    {
        // Ensure user can only set their own accounts as primary
        if ($bankAccount->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::beginTransaction();

            // Unset other primary accounts
            BankAccount::where('user_id', Auth::id())
                ->where('is_primary', true)
                ->update(['is_primary' => false]);

            // Set this account as primary
            $bankAccount->update(['is_primary' => true]);

            DB::commit();

            return back()->with('success', 'Primary bank account updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error setting primary bank account: ' . $e->getMessage());
            return back()->with('error', 'Failed to update primary bank account. Please try again.');
        }
    }
}
