<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\KycDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{
    /**
     * Show KYC management page
     */
    public function index()
    {
        $user = Auth::user();
        $kycDocuments = $user->kycDocuments()->latest()->get();
        
        return view('profile.kyc.index', compact('user', 'kycDocuments'));
    }

    /**
     * Show KYC upload form
     */
    public function create()
    {
        $user = Auth::user();
        return view('profile.kyc.create', compact('user'));
    }

    /**
     * Store KYC document
     */
    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:aadhar,pan,driving_license,passport',
            'document_number' => 'required|string|max:50|unique:kyc_documents,document_number',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            // Handle document upload
            $documentPath = $request->file('document_file')->store('kyc_documents', 'public');

            // Create KYC document
            KycDocument::create([
                'user_id' => Auth::id(),
                'document_type' => $request->document_type,
                'document_number' => $request->document_number,
                'document_file_path' => $documentPath,
                'status' => 'pending',
            ]);

            return redirect()->route('profile.kyc.index')
                ->with('success', 'KYC document uploaded successfully! Please wait for admin verification.');

        } catch (\Exception $e) {
            \Log::error('Error uploading KYC document: ' . $e->getMessage());
            return back()->with('error', 'Failed to upload KYC document. Please try again.');
        }
    }

    /**
     * Show KYC document details
     */
    public function show(KycDocument $kycDocument)
    {
        // Ensure user can only view their own documents
        if ($kycDocument->user_id !== Auth::id()) {
            abort(403);
        }

        return view('profile.kyc.show', compact('kycDocument'));
    }

    /**
     * Download KYC document
     */
    public function download(KycDocument $kycDocument)
    {
        // Ensure user can only download their own documents
        if ($kycDocument->user_id !== Auth::id()) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($kycDocument->document_file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($kycDocument->document_file_path);
    }

    /**
     * Delete KYC document (only if pending)
     */
    public function destroy(KycDocument $kycDocument)
    {
        // Ensure user can only delete their own documents
        if ($kycDocument->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow deletion if status is pending
        if ($kycDocument->status !== 'pending') {
            return back()->with('error', 'Cannot delete verified or rejected documents.');
        }

        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($kycDocument->document_file_path)) {
                Storage::disk('public')->delete($kycDocument->document_file_path);
            }

            // Delete record
            $kycDocument->delete();

            return redirect()->route('profile.kyc.index')
                ->with('success', 'KYC document deleted successfully.');

        } catch (\Exception $e) {
            \Log::error('Error deleting KYC document: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete KYC document. Please try again.');
        }
    }
}
