fetch('https://restcountries.com/v3.1/all?fields=name')
    .then(response => response.json())
    .then(data => {
    const datalist = document.getElementById('countries');

    const sorted = data.sort((a, b) =>
        a.name.common.localeCompare(b.name.common)
    );

    sorted.forEach(country => {
        const option = document.createElement('option');
        option.value = country.name.common;
        datalist.appendChild(option);
    });
    })
    .catch(error => {
    console.error('Error fetching country list:', error);
    });



