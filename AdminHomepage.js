
function setGreeting() {
    const greetingElement = document.querySelector('.greeting');
    const now = new Date();
    const hour = now.getHours();
    let greetingText = '';

    if (hour < 12) {
        greetingText = 'Good Morning';
    } else if (hour < 18) {
        greetingText = 'Good Afternoon';
    } else {
        greetingText = 'Good Evening';
    }

    const admin = adminData[0];
    if (admin && admin.name) {
        greetingText += ', ' + admin.name;
    }

    if (greetingElement) {
        greetingElement.textContent = greetingText;
    }
}

const buttons = document.querySelectorAll('.button');
const slider = document.querySelector('.wrapper-holder');
let currentIndex = 0;
let autoSlide;

function goToSlide(index) {
    currentIndex = index;
    slider.style.transform = `translateX(-${index * 100}%)`;
    updateActiveButton();
}

function updateActiveButton() {
    buttons.forEach(btn => btn.classList.remove('active'));
    buttons[currentIndex].classList.add('active');
}

buttons.forEach(button => {
    button.addEventListener('click', () => {
        clearInterval(autoSlide); 
        goToSlide(Number(button.getAttribute('data-slide')));
        autoSlide = startAutoSlide();
    });
});

function startAutoSlide() {
    return setInterval(() => {
        currentIndex = (currentIndex + 1) % buttons.length;
        goToSlide(currentIndex);
    }, 5000);
}

document.addEventListener('DOMContentLoaded', () => {
    const admin = adminData[0];
    const profileImg = document.querySelector('.Profile img');
    if (profileImg && admin.pic) {
        profileImg.src = 'img/' + admin.pic;
        profileImg.alt = admin.name + "'s Profile Picture";
    } else {
        console.error("Admin Profile Pic cannot be load");
    }

    setGreeting();
    goToSlide(0);
    autoSlide = startAutoSlide();
    renderFeedbackList(feedbackData);
});

const feedbackList = document.getElementById('feedback-list');

function renderFeedbackList(data) {
    feedbackList.innerHTML = '';
    data.slice(0, 3).forEach(item => { 
        const li = document.createElement('li');
        li.innerHTML = `
            <a href="#" class="feedback-link">
            <strong>${item.type}</strong> (${item.userid})<br>
            <small> On ${new Date(item.Date).toLocaleString()}</small>
        `;
        feedbackList.appendChild(li);
    });
}