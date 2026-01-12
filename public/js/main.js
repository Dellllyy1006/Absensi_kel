/**
 * Absensi SMK - Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Mobile menu toggle (if needed)
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const sidebar = document.querySelector('.sidebar');
    
    if (mobileMenuBtn && sidebar) {
        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });
    }

    // Confirm dialogs for destructive actions
    const confirmButtons = document.querySelectorAll('[data-confirm]');
    confirmButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

    // Form validation
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                    
                    // Remove error class on input
                    field.addEventListener('input', function() {
                        this.classList.remove('error');
                    }, { once: true });
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang wajib diisi');
            }
        });
    });

    // Loading state for forms
    const submitForms = document.querySelectorAll('form:not([data-no-loading])');
    submitForms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                
                // Re-enable after 10 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 10000);
            }
        });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Toast notification helper
    window.showToast = function(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type}`;
        toast.innerHTML = `
            <span>${message}</span>
            <button type="button" class="alert-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        const container = document.querySelector('.main-content') || document.body;
        container.insertBefore(toast, container.firstChild);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    };

    // AJAX helper
    window.fetchJSON = async function(url, options = {}) {
        try {
            const response = await fetch(url, {
                ...options,
                headers: {
                    'Content-Type': 'application/json',
                    ...options.headers
                }
            });
            return await response.json();
        } catch (error) {
            console.error('Fetch error:', error);
            return { success: false, message: 'Terjadi kesalahan jaringan' };
        }
    };

    // Table row click handler (optional)
    const clickableRows = document.querySelectorAll('tr[data-href]');
    clickableRows.forEach(row => {
        row.style.cursor = 'pointer';
        row.addEventListener('click', function() {
            window.location.href = this.dataset.href;
        });
    });

    // Date input default value
    const dateInputs = document.querySelectorAll('input[type="date"]:not([value])');
    dateInputs.forEach(input => {
        const today = new Date().toISOString().split('T')[0];
        input.value = today;
    });

    // Print functionality
    window.printElement = function(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print</title>');
            printWindow.document.write('<link rel="stylesheet" href="' + window.location.origin + '/absensi_kel/public/css/style.css">');
            printWindow.document.write('</head><body>');
            printWindow.document.write(element.innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        }
    };
});

// Export helper
window.exportToCSV = function(tableId, filename = 'data.csv') {
    const table = document.getElementById(tableId);
    if (!table) return;

    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const rowData = [];
        cols.forEach(col => {
            // Remove commas and new lines from cell content
            let data = col.innerText.replace(/,/g, ' ').replace(/\n/g, ' ');
            rowData.push('"' + data + '"');
        });
        csv.push(rowData.join(','));
    });

    // Download CSV
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
};
