
document.addEventListener('DOMContentLoaded', () => {
    const admin = adminData[0];

    const profileImg = document.querySelector('.Profile img');
    if (profileImg && admin.pic) {
        profileImg.src = 'img/' + admin.pic;
        profileImg.alt = admin.name + "'s Profile Picture";
    }

    if (admin.pic && admin.pic !== "") {
        const profileImg = document.getElementById("profileImage");
        profileImg.src = 'img/' + admin.pic;
        profileImg.alt = admin.name + "'s Profile Picture";
    }

    const profileNameEmail = document.querySelector('.profile-pic .name-email');
    if (profileNameEmail) {
        profileNameEmail.innerHTML = `<strong>${admin.name}</strong><br><small>${admin.email}</small>`;
    }

    const profileDetails = document.querySelector('.profile-details');
    if (profileDetails) {
        profileDetails.innerHTML = `
            <h2>Admin Details</h2>
            <p><strong>Admin ID:</strong> ${admin.id}</p>
        `;
    } else {
        console.error("Admin data is not defined.");
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

        fetch('UpdateProfileAdmin.php', {
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
