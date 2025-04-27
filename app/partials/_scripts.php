<?php
/**
 * Common scripts for all pages
 */
?>
<!-- Bootstrap 5 JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Sui Integration -->
<script src="./js/sui-integration.js?v=<?php echo time(); ?>" type="module"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Theme Toggle -->
<script>
    $(document).ready(function() {
        // Check for saved theme preference or respect OS preference
        const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)");
        const currentTheme = localStorage.getItem("theme");
        
        if (currentTheme == "dark" || (!currentTheme && prefersDarkScheme.matches)) {
            document.body.classList.add("dark-theme");
            $("#theme-toggle").prop('checked', true);
        }
        
        // Theme switch event
        $("#theme-toggle").change(function() {
            if ($(this).is(":checked")) {
                document.body.classList.add("dark-theme");
                localStorage.setItem("theme", "dark");
            } else {
                document.body.classList.remove("dark-theme");
                localStorage.setItem("theme", "light");
            }
        });
        
        // Process placeholder images with data-prompt attributes
        $('img[data-prompt]').each(function() {
            const prompt = $(this).data('prompt');
            if (prompt && !$(this).attr('src').startsWith('http')) {
                // Set a loading placeholder
                $(this).attr('src', 'https://placehold.co/600x400/9c27b0/ffffff?text=Loading+Image');
                
                // In a real app, you would make an API call to generate the image
                // For now, we'll use placeholder.com to simulate loading
                const width = $(this).parent().width();
                const height = $(this).parent().height() || 400;
                
                setTimeout(() => {
                    // Simulate image generation completion
                    $(this).attr('src', `https://placehold.co/${width}x${height}/4abe8a/ffffff?text=Generated+Image`);
                }, 1500);
            }
        });
    });
</script>