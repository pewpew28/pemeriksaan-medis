<form id="online-payment-form" action="{{ route('payments.create', $examination->id) }}" method="POST"
        style="display: none;">
        @csrf
        <input type="hidden" name="examination_id" value="{{ $examination->id }}">
        <input type="hidden" name="amount" value="{{ $examination->serviceItem->price }}">
</form>

<!-- Loading indicator (optional) -->
<div id="payment-loading" style="text-align: center; padding: 20px;">
    <div class="spinner-border" role="status">
        <span class="sr-only">Memproses pembayaran...</span>
    </div>
    <p>Mengarahkan ke halaman pembayaran...</p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto submit form when page loads
    const form = document.getElementById('online-payment-form');
    const loadingDiv = document.getElementById('payment-loading');
    
    if (form) {
        // Show loading indicator
        if (loadingDiv) {
            loadingDiv.style.display = 'block';
        }
        
        // Submit form after a brief delay to allow page to render
        setTimeout(function() {
            form.submit();
        }, 500);
    }
});

// Alternative: Auto submit when specific condition is met (e.g., success message)
@if(session('success') && str_contains(session('success'), 'pembayaran online'))
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('online-payment-form');
        if (form) {
            setTimeout(function() {
                form.submit();
            }, 1000); // 1 second delay
        }
    });
@endif
</script>