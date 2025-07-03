
const isInIframe = window !== window.parent;

const swiper = new Swiper('.wrapper', {
    loop: true,
    spaceBetween: 30,

    autoplay: isInIframe ? false : {
        delay: 5000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
    },

    pagination: {
        el: '.swiper-pagination',
        clickable: true,
        dynamicBullets: true,
    },

    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },

    breakpoints: {
        0: {
            slidesPerView: 1,
        },
        768: {
            slidesPerView: 2,
        },
        1024: {
            slidesPerView: 3,
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const radios = document.getElementsByName('bookStatus');

    for (const radio of radios) {
        radio.addEventListener('change', function() {
            document.getElementById('selectedValue').value = this.value;
            document.getElementById('bookForm').submit();
        });
    }
});

function filterBooks() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const dropdown = document.getElementById('dropdown');
    dropdown.innerHTML = ''; 
    const bookCards = document.querySelectorAll('.card');
    const uniqueTitles = new Set();
    let hasResults = false;

    bookCards.forEach(card => {
        const title = card.querySelector('.card-title').textContent.toLowerCase();
        if (title.startsWith(filter) && filter.length > 0 && !uniqueTitles.has(title)) {
            uniqueTitles.add(title); 
            const bookTitle = document.createElement('div');
            bookTitle.textContent = card.querySelector('.card-title').textContent;
            bookTitle.onclick = function () {
                window.location.href = "Book Details.php?bookId=" + card.dataset.bookid;
            };
            dropdown.appendChild(bookTitle);
            hasResults = true;
        }
    });

    dropdown.style.display = hasResults ? 'block' : 'none';
}

 const statusRadios = document.querySelectorAll('input[name="bookStatus"]');

  function updateLabels() {
    statusRadios.forEach(radio => {
      const label = document.querySelector(`label[for="${radio.id}"]`);
    });
  }

  statusRadios.forEach(radio => {
    radio.addEventListener('change', updateLabels);
  });

  updateLabels();