<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\KittiRegistration;
use App\Models\SystemConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Process payment gateway payment
     */
    public function gateway(PaymentTransaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return redirect()->route('registration.payment', $transaction->registration_id)
                ->with('error', 'Invalid transaction status');
        }

        // Check if payment gateway is enabled
        if (!SystemConfig::getValue('payment_gateway_enabled', true)) {
            return redirect()->route('registration.payment', $transaction->registration_id)
                ->with('error', 'Payment gateway is currently disabled');
        }

        // Initialize payment gateway
        $gatewayData = $this->initializeGatewayPayment($transaction);

        return view('payment.gateway', compact('transaction', 'gatewayData'));
    }

    /**
     * Process UPI payment
     */
    public function upi(PaymentTransaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return redirect()->route('registration.payment', $transaction->registration_id)
                ->with('error', 'Invalid transaction status');
        }

        // Check if UPI is enabled
        if (!SystemConfig::getValue('upi_enabled', true)) {
            return redirect()->route('registration.payment', $transaction->registration_id)
                ->with('error', 'UPI payments are currently disabled');
        }

        // Generate UPI payment data
        $upiData = $this->generateUpiPaymentData($transaction);

        return view('payment.upi', compact('transaction', 'upiData'));
    }

    /**
     * Process QR code payment
     */
    public function qr(PaymentTransaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return redirect()->route('registration.payment', $transaction->registration_id)
                ->with('error', 'Invalid transaction status');
        }

        // Check if QR payments are enabled
        if (!SystemConfig::getValue('qr_enabled', true)) {
            return redirect()->route('registration.payment', $transaction->registration_id)
                ->with('error', 'QR payments are currently disabled');
        }

        // Generate QR code data
        $qrData = $this->generateQrPaymentData($transaction);

        return view('payment.qr', compact('transaction', 'qrData'));
    }

    /**
     * Payment gateway callback
     */
    public function gatewayCallback(Request $request)
    {
        $transactionId = $request->input('transaction_id');
        $transaction = PaymentTransaction::findOrFail($transactionId);

        // Verify payment gateway response
        $isValid = $this->verifyGatewayResponse($request, $transaction);

        if ($isValid) {
            // Update transaction
            $transaction->update([
                'status' => 'success',
                'gateway_transaction_id' => $request->input('gateway_transaction_id'),
                'gateway_response' => $request->all(),
                'payment_completed_at' => now(),
            ]);

            // Update registration status
            $registration = $transaction->registration;
            $registration->update(['status' => 'payment_verified']);

            // Set auto-confirm time
            $autoConfirmHours = SystemConfig::getValue('auto_confirm_hours', 24);
            $registration->update([
                'auto_confirm_at' => now()->addHours($autoConfirmHours)
            ]);

            return redirect()->route('registration.payment.success', ['transaction_id' => $transaction->id]);
        } else {
            // Update transaction as failed
            $transaction->update([
                'status' => 'failed',
                'error_message' => 'Payment gateway verification failed',
                'gateway_response' => $request->all(),
            ]);

            return redirect()->route('registration.payment.failure', ['transaction_id' => $transaction->id]);
        }
    }

    /**
     * UPI payment callback
     */
    public function upiCallback(Request $request)
    {
        $transactionId = $request->input('transaction_id');
        $transaction = PaymentTransaction::findOrFail($transactionId);

        // Verify UPI payment
        $isValid = $this->verifyUpiPayment($request, $transaction);

        if ($isValid) {
            // Update transaction
            $transaction->update([
                'status' => 'success',
                'upi_transaction_id' => $request->input('upi_transaction_id'),
                'upi_app' => $request->input('upi_app'),
                'payment_completed_at' => now(),
            ]);

            // Update registration status
            $registration = $transaction->registration;
            $registration->update(['status' => 'payment_verified']);

            // Set auto-confirm time
            $autoConfirmHours = SystemConfig::getValue('auto_confirm_hours', 24);
            $registration->update([
                'auto_confirm_at' => now()->addHours($autoConfirmHours)
            ]);

            return redirect()->route('registration.payment.success', ['transaction_id' => $transaction->id]);
        } else {
            // Update transaction as failed
            $transaction->update([
                'status' => 'failed',
                'error_message' => 'UPI payment verification failed',
            ]);

            return redirect()->route('registration.payment.failure', ['transaction_id' => $transaction->id]);
        }
    }

    /**
     * QR payment callback
     */
    public function qrCallback(Request $request)
    {
        $transactionId = $request->input('transaction_id');
        $transaction = PaymentTransaction::findOrFail($transactionId);

        // Verify QR payment
        $isValid = $this->verifyQrPayment($request, $transaction);

        if ($isValid) {
            // Update transaction
            $transaction->update([
                'status' => 'success',
                'payment_completed_at' => now(),
            ]);

            // Update registration status
            $registration = $transaction->registration;
            $registration->update(['status' => 'payment_verified']);

            // Set auto-confirm time
            $autoConfirmHours = SystemConfig::getValue('auto_confirm_hours', 24);
            $registration->update([
                'auto_confirm_at' => now()->addHours($autoConfirmHours)
            ]);

            return redirect()->route('registration.payment.success', ['transaction_id' => $transaction->id]);
        } else {
            // Update transaction as failed
            $transaction->update([
                'status' => 'failed',
                'error_message' => 'QR payment verification failed',
            ]);

            return redirect()->route('registration.payment.failure', ['transaction_id' => $transaction->id]);
        }
    }

    /**
     * Initialize gateway payment
     */
    private function initializeGatewayPayment(PaymentTransaction $transaction): array
    {
        // This would integrate with actual payment gateway APIs
        // For now, we'll return mock data
        
        $gatewayConfig = [
            'merchant_id' => SystemConfig::getValue('gateway_merchant_id', 'TEST_MERCHANT'),
            'api_key' => SystemConfig::getValue('gateway_api_key', 'test_key'),
            'callback_url' => route('payment.gateway.callback'),
        ];

        return [
            'merchant_id' => $gatewayConfig['merchant_id'],
            'transaction_id' => $transaction->transaction_reference,
            'amount' => $transaction->amount,
            'currency' => 'INR',
            'description' => 'KITTI Investment - ' . $transaction->registration->plan_amount,
            'customer_email' => $transaction->registration->email,
            'customer_phone' => $transaction->registration->mobile,
            'callback_url' => $gatewayConfig['callback_url'],
            'return_url' => route('registration.payment.success', ['transaction_id' => $transaction->id]),
            'cancel_url' => route('registration.payment.failure', ['transaction_id' => $transaction->id]),
        ];
    }

    /**
     * Generate UPI payment data
     */
    private function generateUpiPaymentData(PaymentTransaction $transaction): array
    {
        $upiId = SystemConfig::getValue('company_upi_id', 'kitti@paytm');
        
        return [
            'upi_id' => $upiId,
            'amount' => $transaction->amount,
            'transaction_id' => $transaction->transaction_reference,
            'description' => 'KITTI Investment - ' . $transaction->registration->plan_amount,
            'customer_name' => $transaction->registration->full_name,
            'customer_email' => $transaction->registration->email,
            'customer_phone' => $transaction->registration->mobile,
        ];
    }

    /**
     * Generate QR payment data
     */
    private function generateQrPaymentData(PaymentTransaction $transaction): array
    {
        $upiId = SystemConfig::getValue('company_upi_id', 'kitti@paytm');
        
        // Generate UPI QR code data
        $qrData = "upi://pay?pa={$upiId}&pn=KITTI Investment&tn={$transaction->transaction_reference}&am={$transaction->amount}&cu=INR";
        
        return [
            'qr_code' => $qrData,
            'upi_id' => $upiId,
            'amount' => $transaction->amount,
            'transaction_id' => $transaction->transaction_reference,
            'description' => 'KITTI Investment - ' . $transaction->registration->plan_amount,
        ];
    }

    /**
     * Verify gateway response
     */
    private function verifyGatewayResponse(Request $request, PaymentTransaction $transaction): bool
    {
        // This would verify the payment gateway response
        // For now, we'll assume success if status is 'success'
        
        $status = $request->input('status');
        $gatewayTransactionId = $request->input('gateway_transaction_id');
        
        return $status === 'success' && !empty($gatewayTransactionId);
    }

    /**
     * Verify UPI payment
     */
    private function verifyUpiPayment(Request $request, PaymentTransaction $transaction): bool
    {
        // This would verify UPI payment with bank/UPI provider
        // For now, we'll assume success if UPI transaction ID is provided
        
        $upiTransactionId = $request->input('upi_transaction_id');
        
        return !empty($upiTransactionId);
    }

    /**
     * Verify QR payment
     */
    private function verifyQrPayment(Request $request, PaymentTransaction $transaction): bool
    {
        // This would verify QR payment
        // For now, we'll assume success if verification data is provided
        
        $verificationData = $request->input('verification_data');
        
        return !empty($verificationData);
    }

    /**
     * Check payment status
     */
    public function checkStatus(PaymentTransaction $transaction)
    {
        // This would check payment status with the payment provider
        // For now, we'll return the current status
        
        return response()->json([
            'transaction_id' => $transaction->id,
            'status' => $transaction->status,
            'amount' => $transaction->amount,
            'payment_method' => $transaction->payment_method,
            'created_at' => $transaction->created_at,
            'completed_at' => $transaction->payment_completed_at,
        ]);
    }

    /**
     * Retry failed payment
     */
    public function retryPayment(PaymentTransaction $transaction)
    {
        if ($transaction->status !== 'failed') {
            return response()->json([
                'success' => false,
                'message' => 'Only failed payments can be retried'
            ], 400);
        }

        // Reset transaction for retry
        $transaction->update([
            'status' => 'pending',
            'error_message' => null,
            'retry_count' => $transaction->retry_count + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment retry initiated',
            'redirect_url' => $this->getPaymentRedirectUrl($transaction->payment_method, $transaction)
        ]);
    }

    /**
     * Get payment redirect URL
     */
    private function getPaymentRedirectUrl(string $method, PaymentTransaction $transaction): string
    {
        return match($method) {
            'gateway' => route('payment.gateway', $transaction->id),
            'upi' => route('payment.upi', $transaction->id),
            'qr' => route('payment.qr', $transaction->id),
            default => route('registration.payment', $transaction->registration_id),
        };
    }
}
