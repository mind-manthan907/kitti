<!-- Razorpay Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var options = {
            key: "{{ config('services.razorpay.key') }}", // from config/services.php
            amount: "{{ $razorpayOrder['amount'] }}",     // amount in paise
            currency: "INR",
            name: "Kitti Investment",
            description: "Plan Payment",
            order_id: "{{ $razorpayOrder['id'] }}",       // Razorpay Order ID

            handler: function (response) {
                fetch("{{ route('registration.payment.success') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_signature: response.razorpay_signature
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect_url;
                    } else {
                        alert("Payment verification failed!");
                    }
                })
                .catch(err => {
                    console.error("Payment success callback error:", err);
                });
            },

            theme: {
                color: "#6366f1"
            },

            modal: {
                ondismiss: function () {
                    // Redirect if user closes modal without paying
                    window.location.href = "{{ route('registration.preview', $registration->id) }}";
                }
            }
        };

        var rzp1 = new Razorpay(options);

        // ✅ Auto-open modal when page loads
        rzp1.open();

        // ✅ Also allow manual button click (fallback)
        var btn = document.getElementById('pay-button');
        if (btn) {
            btn.onclick = function (e) {
                e.preventDefault();
                rzp1.open();
            };
        }
    });
</script>