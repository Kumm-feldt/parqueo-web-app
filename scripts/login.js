document.addEventListener('DOMContentLoaded', function() {
    const signUpButton = document.querySelector('.sign-up');
    const signInButton = document.querySelector('.sign-in');
    const container = document.getElementById('container');
    const signUpContainer = document.querySelector('.sign-up-container');
    const signInContainer = document.querySelector('.sign-in-container');

    signUpButton.addEventListener('click', function(e) {
        e.preventDefault();
        signInContainer.style.display = 'none';
        signUpContainer.style.display = 'block';
    });

    signInButton.addEventListener('click', function(e) {
        e.preventDefault();
        signUpContainer.style.display = 'none';
        signInContainer.style.display = 'block';
    });
});
