
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

    const lecturer = lecturerData[0];
    if (lecturer && lecturer.name) {
        greetingText += ', ' + lecturer.name;
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
    const lecturer = lecturerData[0];

    const profileImg = document.querySelector('.Profile img');
    if (profileImg && lecturer.pic) {
        profileImg.src = 'img/' + lecturer.pic;
        profileImg.alt = lecturer.name + "'s Profile Picture";
    } else {
        console.error("Student Profile Pic cannot be load");
    }

    setGreeting();
    goToSlide(0);
    autoSlide = startAutoSlide();
    renderSchedule(scheduleData, holidayData);
});

function getTodayName() {
    const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    const today = new Date();
    return days[today.getDay()];
}

function renderSchedule(schedule) {
    const list = document.getElementById('schedule-list');
    list.innerHTML = ''; 

    const todayName = "Tuesday";

    const todaysClasses = schedule.filter(item => item.Day === todayName);

    if (todaysClasses.length === 0) {
        list.innerHTML = '<li>No classes scheduled for today.</li>';
        return;
    }

    todaysClasses.forEach(item => {
        const slotNumber = item.Slot.charAt(1);
        const slotTimes = {
            1: "8:30 AM - 10:30 AM",
            2: "10:45 AM - 12:45 PM",
            3: "1:30 PM - 3:30 PM", 
            4: "3:45 PM - 5:45 PM"
        };
        const time = slotTimes[slotNumber] || "Time not available";

        const li = document.createElement('li');
        li.innerHTML = `<strong>${item.ModuleName}</strong> <br> (${item.ClassID}) <br> ${time}`;
        list.appendChild(li);
    });
}
