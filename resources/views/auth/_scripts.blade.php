<script>
    function togglePw(id, btn) {
        const input = document.getElementById(id);
        const eyeOpen = btn.querySelector('.eo');
        const eyeClosed = btn.querySelector('.ec');

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.style.display = 'none';
            eyeClosed.style.display = 'block';
        } else {
            input.type = 'password';
            eyeOpen.style.display = 'block';
            eyeClosed.style.display = 'none';
        }
    }
</script>
