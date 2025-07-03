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

const create_lecturer_acc_form = document.getElementById("create-lecturer-acc-form");


create_lecturer_acc_form.addEventListener("submit", function (e) {
    e.preventDefault();

    const name = document.getElementById("lecturer-name").value;
    const email = document.getElementById("lecturer-email").value;
    const id = document.getElementById("lecturer-id").value;
    const password = document.getElementById("lecturer-password").value;
    const confirmPassword = document.getElementById("lecturer-confirmPassword").value;

    let isValid = true;

    document.getElementById("lecturer-name-error").textContent = "";
    document.getElementById("lecturer-email-error").textContent = "";
    document.getElementById("lecturer-id-error").textContent = "";
    document.getElementById("lecturer-password-error").textContent = "";
    document.getElementById("lecturer-confirmPassword-error").textContent = "";

    const nameRegex = /^(?=.*[A-Za-z])[A-Za-z\s]+$/;
    if (!nameRegex.test(name)) {
        document.getElementById("lecturer-name-error").textContent = 
            "Please enter a valid name";
        isValid = false;
    }

    const emailRegex = /^([a-zA-Z0-9._%+-]+)@([a-zA-Z0-9.-]+)\.([a-zA-Z]{2,3})$/;
    if (!emailRegex.test(email)) {
        document.getElementById("lecturer-email-error").textContent = 
            "Please enter a valid email address";
        isValid = false;
    }

    const idRegex = /^UMT\d{5}$/;
    if (!idRegex.test(id)) {
        document.getElementById("lecturer-id-error").textContent = 
            "Please enter a valid ID";
        isValid = false;
    }

    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d])[A-Za-z\d\S]{8,12}$/;
    if (!passwordRegex.test(password)) {
        document.getElementById("lecturer-password-error").textContent = 
            "Password length must be between 8 to 12. Password should contain at least one uppercase letter, lowercase letter, digit and special symbol";
        isValid = false;
    }

    if (confirmPassword!==password) {
        document.getElementById("lecturer-confirmPassword-error").textContent = 
            "Please confirm password correctly";
        isValid = false;
    }

    if (isValid) {
        const formData = new FormData(create_lecturer_acc_form);

        fetch("validateCreateLecturerAcc.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            const dataTrim = data.trim();
            if (dataTrim === "success") {
                alert("Account is created successfully!");
                create_lecturer_acc_form.reset();
                generateUniqueID('lecturer')

            } else{
                document.getElementById("lecturer-email-error").textContent = dataTrim;
            }
        })
        .catch(error => {
            alert("Error occured while creating account");
        });
    }
});