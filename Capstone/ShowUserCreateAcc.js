function generateUniqueID(role) {
    fetch('generateUniqueID.php')
        .then(response => response.json())
        .then(data => {
            const id = data.newID;
            console.log("Fetched ID:", data)
            if (role === 'admin') {
                document.getElementById('admin-id').value = id;
                document.getElementById('admin-email').value = id+"@mail.umt.edu.my"
            } else if (role === 'student') {
                document.getElementById('student-id').value = id;
                document.getElementById('student-email').value = id+"@mail.umt.edu.my"
            } else if (role === 'lecturer') {
                document.getElementById('lecturer-id').value = id;
                document.getElementById('lecturer-email').value = id+"@mail.umt.edu.my"
            }
        })
        .catch(error => console.error("Fetch error:", error));;
}

function showAdminAcc(){
    const admin_form = document.getElementById("admin-create-acc-section");
    const student_form = document.getElementById("student-create-acc-section");
    const lecturer_form = document.getElementById("lecturer-create-acc-section");
    const invalid_messages = document.getElementsByClassName("invalid-message");
    const inputs = document.querySelectorAll('input:not([readonly])');
    for (let i =0;i<invalid_messages.length;i++){
        invalid_messages[i].textContent="";
    }
    for (let i =0;i<inputs.length;i++){
        inputs[i].value="";
    }
    admin_form.style.display = "block";
    student_form.style.display = "none";
    lecturer_form.style.display = "none";
    generateUniqueID('admin');

}

function showStudentAcc(){
    const admin_form = document.getElementById("admin-create-acc-section");
    const student_form = document.getElementById("student-create-acc-section");
    const lecturer_form = document.getElementById("lecturer-create-acc-section");
    const invalid_messages = document.getElementsByClassName("invalid-message");
    const inputs = document.querySelectorAll('input:not([readonly])');
    for (let i =0;i<invalid_messages.length;i++){
        invalid_messages[i].textContent="";
    }
    for (let i =0;i<inputs.length;i++){
        inputs[i].value="";
    }
    admin_form.style.display = "none";
    student_form.style.display = "block";
    lecturer_form.style.display = "none";
    generateUniqueID('student');

}


function showLecturerAcc(){
    const admin_form = document.getElementById("admin-create-acc-section");
    const student_form = document.getElementById("student-create-acc-section");
    const lecturer_form = document.getElementById("lecturer-create-acc-section");
    const invalid_messages = document.getElementsByClassName("invalid-message");
    const inputs = document.querySelectorAll('input:not([readonly])');
    for (let i =0;i<invalid_messages.length;i++){
        invalid_messages[i].textContent="";
    }
    for (let i =0;i<inputs.length;i++){
        inputs[i].value="";
    }
    admin_form.style.display = "none";
    student_form.style.display = "none";
    lecturer_form.style.display = "block";
    generateUniqueID('lecturer');

}






