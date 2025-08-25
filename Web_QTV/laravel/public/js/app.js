// HTD Face Recognition System - Simplified JavaScript

$(document).ready(function() {
    
    // Initialize all functions
    initMobileSidebar();
    initSearchFunctionality();
    initFormValidations();
    initDeleteConfirmation();
    
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
});

// Global variables
let searchTimeout;

function initMobileSidebar() {
    // Add mobile menu button for small screens
    if (window.innerWidth <= 768) {
        const mobileMenuBtn = `
            <button class="btn btn-primary d-md-none position-fixed" 
                    style="top: 20px; left: 20px; z-index: 1051;"
                    id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>
        `;
        $('body').prepend(mobileMenuBtn);
        
        // Handle mobile menu toggle
        $('#mobileMenuToggle').on('click', function() {
            $('.sidebar').toggleClass('show');
        });
        
        // Close sidebar when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.sidebar, #mobileMenuToggle').length) {
                $('.sidebar').removeClass('show');
            }
        });
    }
}

function initSearchFunctionality() {
    // Real-time search
    $('input[type="search"], input[placeholder*="Tìm"]').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        const targetTable = $(this).closest('.card').find('table tbody');
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterTableRows(targetTable, searchTerm);
        }, 300);
    });
}

function filterTableRows(table, searchTerm) {
    table.find('tr').each(function() {
        const rowText = $(this).text().toLowerCase();
        if (rowText.includes(searchTerm)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

function initFormValidations() {
    // Form validation
    $('form').on('submit', function(e) {
        const form = this;
        let isValid = true;
        
        // Check required fields
        $(form).find('[required]').each(function() {
            if (!$(this).val().trim()) {
                e.preventDefault();
                isValid = false;
                showFieldError($(this), 'Trường này là bắt buộc');
            } else {
                hideFieldError($(this));
            }
        });
        
        // Email validation
        $(form).find('input[type="email"]').each(function() {
            const email = $(this).val();
            if (email && !isValidEmail(email)) {
                e.preventDefault();
                isValid = false;
                showFieldError($(this), 'Email không hợp lệ');
            }
        });
        
        if (isValid) {
            showFormSubmitting($(form));
        }
    });
}

function showFieldError(field, message) {
    field.addClass('is-invalid');
    field.next('.invalid-feedback').remove();
    field.after(`<div class="invalid-feedback">${message}</div>`);
}

function hideFieldError(field) {
    field.removeClass('is-invalid');
    field.next('.invalid-feedback').remove();
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showFormSubmitting(form) {
    const submitBtn = form.find('button[type="submit"]');
    const originalText = submitBtn.html();
    
    submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...')
            .prop('disabled', true);
    
    // Reset button after 3 seconds (for demo)
    setTimeout(() => {
        submitBtn.html(originalText).prop('disabled', false);
    }, 3000);
}

function initDeleteConfirmation() {
    // Delete confirmation
    $(document).on('click', '.btn-danger', function(e) {
        const buttonText = $(this).text().toLowerCase();
        if (buttonText.includes('xóa') || buttonText.includes('delete')) {
            e.preventDefault();
            if (confirm('Bạn có chắc chắn muốn xóa?')) {
                $(this).closest('tr').fadeOut(300, function() {
                    $(this).remove();
                });
            }
        }
    });
}

// Handle window resize
$(window).on('resize', function() {
    if (window.innerWidth > 768) {
        $('.sidebar').removeClass('show');
        $('#mobileMenuToggle').hide();
    } else if ($('#mobileMenuToggle').length === 0) {
        initMobileSidebar();
    }
});

// Utility functions
function showNotification(message, type = 'success') {
    const notification = `
        <div class="notification position-fixed top-0 end-0 m-3 alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('body').append(notification);
    
    setTimeout(() => {
        $('.notification').fadeOut(300, function() {
            $(this).remove();
        });
    }, 5000);
}
