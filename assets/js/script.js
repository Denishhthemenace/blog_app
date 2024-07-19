document.addEventListener('DOMContentLoaded', function() {
    
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    event.preventDefault();
                    alert(`${field.getAttribute('placeholder')} is required.`);
                }
            });
        });
    });

    const contentTextarea = document.querySelector('textarea[name="content"]');
    if (contentTextarea) {
        const charCount = document.createElement('div');
        charCount.id = 'charCount';
        contentTextarea.parentNode.insertBefore(charCount, contentTextarea.nextSibling);

        function updateCharCount() {
            const remaining = 1000 - contentTextarea.value.length;
            charCount.textContent = `${remaining} characters remaining`;
            if (remaining < 0) {
                charCount.style.color = 'red';
            } else {
                charCount.style.color = 'black';
            }
        }

        contentTextarea.addEventListener('input', updateCharCount);
        updateCharCount();
    }

    const deleteLinks = document.querySelectorAll('a[href^="delete_post.php"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            if (!confirm('Are you sure you want to delete this post?')) {
                event.preventDefault();
            }
        });
    });
});