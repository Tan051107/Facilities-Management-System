
document.addEventListener('DOMContentLoaded', () => {
    const lecturer = lecturerData[0];

    const profileImg = document.querySelector('.Profile img');
    if (profileImg && lecturer.pic) {
        profileImg.src = 'img/' + lecturer.pic;
        profileImg.alt = lecturer.name + "'s Profile Picture";
    }

    if (lecturer.pic && lecturer.pic !== "") {
        const profileImg = document.getElementById("profileImage");
        profileImg.src = 'img/' + lecturer.pic;
        profileImg.alt = lecturer.name + "'s Profile Picture";
    }

    const profileNameEmail = document.querySelector('.profile-pic .name-email');
    if (profileNameEmail) {
        profileNameEmail.innerHTML = `<strong>${lecturer.name}</strong><br><small>${lecturer.email}</small>`;
    }

    const profileDetails = document.querySelector('.profile-details');
    if (profileDetails) {
        profileDetails.innerHTML = `
            <h2>Lecturer Details</h2>
            <p><strong>Lecturer ID:</strong> ${lecturer.id}</p>
            <p><strong>Job Title:</strong> ${lecturer.job}</p>
            <p><strong>Department:</strong> ${lecturer.dep}</p>
        `;

    } else {
        console.error("Lecturer data is not defined.");
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

        fetch('UpdateProfileLecturer.php', {
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
