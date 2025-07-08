document.addEventListener('DOMContentLoaded', function() {
    // Manejar errores de login
    const urlParams = new URLSearchParams(window.location.search);
    const loginError = urlParams.get('login_error');
    
    if (loginError) {
        // Mostrar modal de login con error
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
        
        // Mostrar mensaje de error
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger';
        errorDiv.textContent = decodeURIComponent(loginError);
        document.querySelector('#loginModal .modal-body').prepend(errorDiv);
    }
    
    // Manejar transición entre modales
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
        button.addEventListener('click', function() {
            const targetModal = this.getAttribute('data-bs-target');
            const currentModal = this.closest('.modal');
            
            if (currentModal) {
                const currentModalInstance = bootstrap.Modal.getInstance(currentModal);
                currentModalInstance.hide();
                
                setTimeout(() => {
                    const nextModal = new bootstrap.Modal(document.querySelector(targetModal));
                    nextModal.show();
                }, 500);
            }
        });
    });
});