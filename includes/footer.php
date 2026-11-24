    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <span class="brand-icon">♻️</span> PreLoved Marketplace
                </div>
                <p class="footer-text">&copy; 2026 PreLoved Marketplace. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Auto-dismiss flash messages
        const flash = document.getElementById('flashAlert');
        if (flash) {
            setTimeout(() => {
                flash.style.opacity = '0';
                flash.style.transform = 'translateY(-10px)';
                setTimeout(() => flash.remove(), 300);
            }, 3000);
        }

        // Mobile nav toggle
        const navToggle = document.getElementById('navToggle');
        const navLinks = document.getElementById('navLinks');
        if (navToggle) {
            navToggle.addEventListener('click', () => {
                navLinks.classList.toggle('active');
                navToggle.classList.toggle('active');
            });
        }

        // Confirm delete
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (!confirm('Apakah Anda yakin ingin menghapus?')) {
                    e.preventDefault();
                }
            });
        });

        // Image preview on upload
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        if (imageInput && imagePreview) {
            imageInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        imagePreview.src = ev.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
</body>
</html>
