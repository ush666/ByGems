document.addEventListener('DOMContentLoaded', function() {
    // Add to cart functionality with duration options
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', async function() {
            const serviceId = this.dataset.serviceId;
            const basePrice = parseFloat(this.dataset.basePrice);
            let duration = '';
            let priceModifier = 0;
            
            // Get selected duration option if available
            const durationSelect = this.closest('.card').querySelector('.duration-select');
            if (durationSelect) {
                const selectedOption = durationSelect.options[durationSelect.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    duration = selectedOption.value;
                    priceModifier = parseFloat(selectedOption.dataset.priceModifier) || 0;
                } else {
                    alert('Please select a duration option');
                    return;
                }
            }
            
            const finalPrice = basePrice + priceModifier;
            
            try {
                const response = await fetch('../backend/add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        service_id: serviceId,
                        quantity: 1, // Default quantity
                        duration: duration,
                        price_modifier: priceModifier,
                        final_price: finalPrice
                    }),
                    credentials: 'include'
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Show success message
                    const toast = new bootstrap.Toast(document.getElementById('cartToast'));
                    document.getElementById('toastMessage').textContent = data.message;
                    toast.show();
                    
                    // Update cart count
                    if (data.cart_count !== undefined) {
                        document.getElementById('cartCount').textContent = data.cart_count;
                    }
                } else {
                    alert(data.message || 'Failed to add to cart');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to add to cart');
            }
        });
    });
});