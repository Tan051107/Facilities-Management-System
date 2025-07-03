
document.addEventListener('DOMContentLoaded', () => {
    const student = studentData[0];

    const profileImg = document.querySelector('.Profile img');
    if (profileImg && student.pic) {
        profileImg.src = 'img/' + student.pic;
        profileImg.alt = student.name + "'s Profile Picture";
    }

    if (student.pic && student.pic !== "") {
        const profileImg = document.getElementById("profileImage");
        profileImg.src = 'img/' + student.pic;
        profileImg.alt = student.name + "'s Profile Picture";
    }

    const profileNameEmail = document.querySelector('.profile-pic .name-email');
    if (profileNameEmail) {
        profileNameEmail.innerHTML = `<strong>${student.name}</strong><br><small>${student.email}</small>`;
    }

    const profileDetails = document.querySelector('.profile-details');
    if (profileDetails) {
        profileDetails.innerHTML = `
            <h2>Student Details</h2>
            <p><strong>Student ID:</strong> ${student.id}</p>
            <p><strong>Course:</strong> ${student.program}</p>
            <p><strong>Date of Birth:</strong> ${student.dob ?? "N/A"}</p>
            <p><strong>Country:</strong> ${student.country}</p>
            <p><strong>Intake Code:</strong> ${student.intake}</p>
        `;

    } else {
        console.error("Student data is not defined.");
    }
});

document.getElementById('profileUpload').addEventListener('change', function () {
    const file = this.files[0];
    const errorMsg = document.getElementById('uploadError');
    if (file) {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!allowedTypes.includes(file.type)) {
            errorMsg.style.display = 'block';
            return;
        } else {
            errorMsg.style.display = 'none';
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('profileImage').src = e.target.result;
        };
        reader.readAsDataURL(file);

        const formData = new FormData();
        formData.append('profilePic', file);

        fetch('UpdateProfile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(result => {
            console.log('Upload success:', result);
            location.reload();
        })
        .catch(error => {
            console.error('Upload error:', error);
        });
    }
});
