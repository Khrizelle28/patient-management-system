<script src="{{ asset('assets/js/vendor/jquery-3.6.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('js/demo/chart-bar-demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
<script src="{{ asset('js/scripts.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>

<script>
function togglePassword() {
    const passwordField = document.getElementById("form2Example22");
    const toggleIcon = document.getElementById("toggleEye");

    const isPassword = passwordField.type === "password";
    passwordField.type = isPassword ? "text" : "password";
    toggleIcon.src = isPassword ? "image/eye.svg" : "image/eye_hide.png";
    toggleIcon.alt = isPassword ? "Hide Password" : "Show Password";
}

function toggleEyeVisibility() {
    const passwordField = document.getElementById("form2Example22");
    const toggleIcon = document.getElementById("toggleEye");

    if (passwordField.value.trim() === "") {
        toggleIcon.style.display = "none";
    } else {
        toggleIcon.style.display = "block";
    }
}
</script>
