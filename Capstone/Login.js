
const logregBox = document.querySelector('.logreg-box');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');

registerLink.addEventListener('click', () => {
    logregBox.classList.add('active');
});

loginLink.addEventListener('click', () => {
    logregBox.classList.remove('active');
});

function ShowPassword(checkbox, inputConfigs) {
    const isChecked = checkbox.checked;
    inputConfigs.forEach(({ inputId, iconContainerId }) => {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconContainerId);

        if (isChecked) {
            input.type = 'text';
            icon.classList.remove('bxs-lock-alt');
            icon.classList.add('bxs-lock-open-alt');
        } else {
            input.type = 'password';
            icon.classList.remove('bxs-lock-open-alt');
            icon.classList.add('bxs-lock-alt');
        }
    });
}

