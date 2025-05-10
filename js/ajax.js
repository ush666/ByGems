document.addEventListener('DOMContentLoaded', function() {
    // Add click event to all "Add to Cart" buttons
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const button = this;
            const serviceId = button.getAttribute('data-service-id');
            const price = button.getAttribute('data-price');
            const originalText = button.innerHTML;

            // Validate data attributes before sending
            if (!serviceId || !price) {
                showButtonError(button, 'Invalid product data', originalText);
                return;
            }

            // Show loading state
            button.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Adding...';
            button.disabled = true;

            try {
                const response = await fetch('../backend/add_to_cart.php', {
                    method: 'POST',
                    credentials: 'include', // Send cookies for session
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        service_id: serviceId,
                        price: price,
                        quantity: 1,
                        status: "active"
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    // Handle HTTP errors (401, 400, 500, etc.)
                    const errorMsg = data.message || `Server error: ${response.status}`;
                    throw new Error(errorMsg);
                }

                if (!data.success) {
                    throw new Error(data.message || 'Failed to add to cart');
                }

                // Success case
                showButtonSuccess(button, originalText);
                
                // Update cart counter if available
                if (typeof updateCartCount === 'function') {
                    updateCartCount();
                }

                // Show success SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: data.message || 'Item added to cart',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Refresh the page after 5 seconds
                    setTimeout(() => {
                        location.reload();
                    }, 5000); // Refresh after 5 seconds
                });

            } catch (error) {
                console.error('Add to cart error:', error);
                
                // Special handling for session timeout
                if (error.message.includes('login')) {
                    if (confirm('Your session expired. Would you like to login now?')) {
                        window.location.href = '../login/customer_login.php?redirect=' + encodeURIComponent(window.location.pathname);
                    }
                    return;
                }

                showButtonError(button, error.message, originalText);
                
                // Show error SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'Failed to add to cart',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Helper functions
    function showButtonSuccess(button, originalText) {
        button.innerHTML = '<i class="bi bi-check-circle"></i> Added!';
        button.classList.add('text-success');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
            button.classList.remove('text-success');
            window.location.href = window.location.href;
        }, 2000);
    }

    function showButtonError(button, errorMsg, originalText) {
        button.innerHTML = '<i class="bi bi-exclamation-circle"></i> Error';
        button.classList.add('text-danger');
        console.error('Cart error:', errorMsg);
        
        // Only alert for important errors (not 401 which we handle separately)
        if (!errorMsg.includes('login')) {
            alert(errorMsg);
        }
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
            button.classList.remove('text-danger');
        }, 2000);
    }
});
