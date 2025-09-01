<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Redirecting to PayU...</title>
</head>
<body>
    <p>Please wait, you are being redirected to PayU...</p>

    <form id="payuForm" action="{{ $action }}" method="post">
        {{-- ⚠️ Do NOT use @csrf because this form is going to PayU’s server, not your Laravel app --}}
        <input type="hidden" name="key" value="{{ $MERCHANT_KEY }}" />
        <input type="hidden" name="txnid" value="{{ $txnid }}" />
        <input type="hidden" name="amount" value="{{ $amount }}" />
        <input type="hidden" name="productinfo" value="{{ $productinfo }}" />
        <input type="hidden" name="firstname" value="{{ $firstname }}" />
        <input type="hidden" name="email" value="{{ $email }}" />
        <input type="hidden" name="surl" value="{{ $surl }}" />
        <input type="hidden" name="furl" value="{{ $furl }}" />
        <input type="hidden" name="hash" value="{{ $hash }}" />
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // 🚀 Automatically submit the form
            document.getElementById("payuForm").submit();
        });
    </script>
</body>
</html>
