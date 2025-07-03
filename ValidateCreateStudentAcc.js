function generateUniqueID(role) {
    fetch('generateUniqueID.php')
        .then(response => response.json())
        .then(data => {
            const id = data.newID;
            console.log("Fetched ID:", data)
            if (role === 'admin') {
                document.getElementById('admin-id').value = id;
                document.getElementById('admin-email').value = id+"@mail.umt.edu.my";
            } else if (role === 'student') {
                document.getElementById('student-id').value = id;
                document.getElementById('student-email').value = id+"@mail.umt.edu.my";
            } else if (role === 'lecturer') {
                document.getElementById('lecturer-id').value = id;
                document.getElementById('lecturer-email').value = id+"@mail.umt.edu.my";
            }
        })
        .catch(error => console.error("Fetch error:", error));;
}


const create_student_acc_form = document.getElementById("create-student-acc-form");


create_student_acc_form.addEventListener("submit", function (e) {
    e.preventDefault();

    const name = document.getElementById("student-name").value;
    const email = document.getElementById("student-email").value;
    const id = document.getElementById("student-id").value;
    const country = document.getElementById("student-country").value;
    const dob = document.getElementById("student-dob").value;
    const password = document.getElementById("student-password").value;
    const confirmPassword = document.getElementById("student-confirmPassword").value;

    let isValid = true;

    document.getElementById("student-name-error").textContent = "";
    document.getElementById("student-email-error").textContent = "";
    document.getElementById("student-id-error").textContent = "";
    document.getElementById("student-country-error").textContent = "";
    document.getElementById("student-dob-error").textContent = "";
    document.getElementById("student-password-error").textContent = "";
    document.getElementById("student-confirmPassword-error").textContent = "";

    const nameRegex = /^(?=.*[A-Za-z])[A-Za-z\s]+$/;
    if (!nameRegex.test(name)) {
        document.getElementById("student-name-error").textContent = 
            "Please enter a valid name";
        isValid = false;
    }

    const emailRegex = /^([a-zA-Z0-9._%+-]+)@([a-zA-Z0-9.-]+)\.([a-zA-Z]{2,3})$/;
    if (!emailRegex.test(email)) {
        document.getElementById("student-email-error").textContent = 
            "Please enter a valid email address";
        isValid = false;
    }

    const idRegex = /^UMT\d{5}$/;
    if (!idRegex.test(id)) {
        document.getElementById("student-id-error").textContent = 
            "Please enter a valid ID";
        isValid = false;
    }

    const curr_date = new Date();
    let dobDate = new Date(dob);

    if (dob === ""|| isNaN(dobDate.getTime())||dobDate > curr_date) {
        document.getElementById("student-dob-error").textContent = "Please select a valid date of birth"; // optional clear
        isValid = false;
    }

    function isValidCountry() {

        const countryList = document.getElementById("countries");

        for (let i = 0; i < countryList.options.length; i++) {
            if (countryList.options[i].value.toLowerCase() === country.toLowerCase()) {
                return true;
        }
    }
    return false;
    }

    if (!isValidCountry()){
        document.getElementById("student-country-error").textContent = "Please select a valid country";
        isValid = false;
    }

    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d])[A-Za-z\d\S]{8,12}$/;
    if (!passwordRegex.test(password)) {
        document.getElementById("student-password-error").textContent = 
            "Password length must be between 8 to 12. Password should contain at least one uppercase letter, lowercase letter, digit and special symbol";
        isValid = false;
    }

    if (confirmPassword!==password) {
        document.getElementById("student-confirmPassword-error").textContent = 
            "Please confirm password correctly";
        isValid = false;
    }

    if (isValid) {
        const formData = new FormData(create_student_acc_form);

        fetch("validateCreateStudentAcc.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            const dataTrim = data.trim();
            if (dataTrim === "success") {
                alert("Account is created successfully!");
                create_student_acc_form.reset();
                generateUniqueID('student')

            } else{
                document.getElementById("student-email-error").textContent = dataTrim;
            }
        })
        .catch(error => {
            alert("Error occured while creating account");
        });
    }
});