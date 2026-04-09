const emailVerify = document.getElementById("emailVerified-btn");

if (emailVerify) {
    emailVerify.addEventListener("click", () => {
        window.location.href = "/forgot-password";
    });
}
