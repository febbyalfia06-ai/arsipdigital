    </div> <!-- end container -->
</div> <!-- end main-content -->

<!-- SweetAlert2 UI/UX -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("click", function(e) {
    let target = e.target.closest('[onclick*="confirm("]');
    if (target && !target.dataset.swalBypassed) {
        let onclickStr = target.getAttribute('onclick');
        if (onclickStr && onclickStr.includes('confirm(')) {
            e.preventDefault();
            e.stopImmediatePropagation();
            
            const match = onclickStr.match(/confirm\((['"])(.*?)\1\)/);
            const message = match ? match[2] : 'Apakah Anda yakin ingin melanjutkan?';
            
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#ef4444',
                confirmButtonText: '<i class="fas fa-check"></i> Oke',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'swal2-custom-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (target.tagName.toLowerCase() === 'a') {
                        window.location.href = target.href;
                    } else {
                        const form = target.closest('form');
                        if (form) {
                            if (target.name && !form.querySelector('input[name="' + target.name + '"]')) {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = target.name;
                                input.value = target.value;
                                form.appendChild(input);
                            }
                            form.submit();
                        } else {
                            target.dataset.swalBypassed = "true";
                            target.click();
                            delete target.dataset.swalBypassed;
                        }
                    }
                }
            });
        }
    }
}, true);
</script>
</body>
</html>
