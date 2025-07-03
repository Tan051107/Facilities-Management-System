
function SearchBook(bookid) {
    const bookId = bookid;
    const url = `https://www.worldcat.org/search?q=${bookId}`;
    window.open(url, '_blank');
}

document.addEventListener("DOMContentLoaded", function () {
    const body = document.body;
    const savedTheme = localStorage.getItem("theme") || "light";

    setTheme(savedTheme);

    function setTheme(theme){
        if (theme === "dark") {
            body.classList.remove("lightmode");
            body.classList.add("darkmode");
        } else {
            body.classList.remove("darkmode");
            body.classList.add("lightmode");
        }
        localStorage.setItem("theme", theme);
    }
})


