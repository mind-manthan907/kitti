<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'document_number',
        'document_file_path',
        'status',
        'rejection_reason',
        'admin_notes',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /**
     * Get the user that owns the KYC document
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who verified the document
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Check if document is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if document is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if document is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get document type display name
     */
    public function getDocumentTypeDisplayAttribute()
    {
        return match($this->document_type) {
            'aadhar' => 'Aadhar Card',
            'pan' => 'PAN Card',
            'driving_license' => 'Driving License',
            'passport' => 'Passport',
            default => ucfirst($this->document_type),
        };
    }
}
