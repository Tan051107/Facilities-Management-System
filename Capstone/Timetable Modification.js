
    const dateInput = document.getElementById("datePicker");
    const today = new Date();
    dateInput.min = today.toISOString().split("T")[0];

    let holidayDates = [];

    fetch('get_holidayInfo.php')
        .then(response => response.json())
        .then(data => {
            holidayDates = data;
            console.log("Fetched holidays:", holidayDates);
        })
        .catch(error => {
            console.error("Failed to fetch holiday data", error);
        });

    dateInput.addEventListener("input", function () {
        const selectedDate = new Date(this.value);
        const day = selectedDate.getDay();

        if (day === 0 || day === 6) {
            alert("Weekends are not selectable. Please choose a weekday.");
            this.value = "";
            document.getElementById("roomSection").style.display = "none";
            return;
        }

        if (holidayDates.includes(this.value)) {
            alert("Selected date is a holiday. Please choose another date.");
            this.value = "";
            document.getElementById("roomSection").style.display = "none";
        }
    });

    const timeSelect = document.getElementById("timeSelect");
    const roomSection = document.getElementById("roomSection");
    const roomOptions = document.getElementById("roomOptions");

    function displayRooms() {
        const dateVal = dateInput.value;
        const timeVal = timeSelect.value;

        console.log('displayRooms called with:', { date: dateVal, time: timeVal });

        if (dateVal && timeVal) {
            roomOptions.innerHTML = "Loading...";
            roomSection.style.display = "block";

            const formData = new FormData();
            formData.append('date', dateVal);
            formData.append('time', timeVal);

            console.log('Fetching rooms for:', { date: dateVal, time: timeVal });

            fetch('get_available_rooms.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                console.log('Raw response:', text); 
                return JSON.parse(text); 
            })
            .then(data => {
                console.log('Room data received:', data);
                roomOptions.innerHTML = "";
                if (data.success && data.rooms && data.rooms.length > 0) {
                    data.rooms.forEach(room => {
                        const btn = document.createElement("button");
                        btn.innerText = room;
                        btn.className = 'room-button';
                        btn.onclick = function() {
                            document.querySelectorAll('#roomOptions button').forEach(b => b.classList.remove('selected'));
                            btn.classList.add('selected');
                            console.log('Room selected:', room);
                        };
                        roomOptions.appendChild(btn);
                    });
                } else {
                    roomOptions.innerHTML = "No rooms available for selected date and time.";
                    console.log('No rooms available:', data);
                }
            })
            .catch(error => {
                console.error('Error fetching rooms:', error);
                roomOptions.innerHTML = "Error loading available rooms. Please try again.";
            });
        } else {
            console.log('Date or time not selected');
            roomSection.style.display = "none";
        }
    }

    const style = document.createElement('style');
    style.textContent = `
        .room-button {
            margin: 5px;
            padding: 8px 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            background: #f8f8f8;
        }
        .room-button:hover {
            background: #e8e8e8;
        }
        .room-button.selected {
            background: #007bff;
            color: white;
            border-color: #0056b3;
        }
    `;
    document.head.appendChild(style);

    dateInput.addEventListener("change", displayRooms);
    timeSelect.addEventListener("change", displayRooms);

    document.querySelector('.update-button').addEventListener('click', function() {
        const selectedDate = document.getElementById('datePicker').value;
        const selectedTime = document.getElementById('timeSelect').value;
        const selectedRoom = document.querySelector('#roomOptions button.selected')?.innerText;
        const courseCode = document.getElementById('courseId').value;
        const originalDate = document.getElementById('originalDate').value;

        if (!selectedDate || !selectedTime || !selectedRoom) {
            alert('Please select a date, time, and room before updating.');
            return;
        }

        const [year, month, day] = selectedDate.split('-');
        const formattedDate = `${year}-${month}-${day}`;

        const selectedDateObj = new Date(selectedDate);
        const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const selectedDay = daysOfWeek[selectedDateObj.getDay()];

        const Intake_Code = '<?php echo $IntakeCode; ?>';

        console.log('Sending data:', {
            Intake_Code,
            courseCode,
            originalDate,
            newDate: formattedDate,
            newDay: selectedDay,
            time: selectedTime,
            room: selectedRoom
        });

        const formData = new FormData();
        formData.append('courseCode', courseCode);
        formData.append('originalDate', originalDate);
        formData.append('newDate', formattedDate);
        formData.append('newDay', selectedDay);
        formData.append('newTime', selectedTime);
        formData.append('newRoom', selectedRoom);
        formData.append('IntakeCode', Intake_Code);

        fetch('update_timetable.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            console.log('Raw update response:', text);
            return JSON.parse(text);
        })
        .then(data => {
            if (data.success) {
                alert('Timetable updated successfully!');
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = data.redirect;

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'intake';
                input.value = data.intake;

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            } else {
                alert('Failed to update timetable: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            alert('An error occurred while updating the timetable.');
        });
    });

document.querySelector('.date-wrapper').addEventListener('click', function (e) {
    const input = document.getElementById('datePicker');
    if (document.activeElement !== input) {
        if (typeof input.showPicker === 'function') {
            input.showPicker(); 
        } else {
            input.focus();
        }
    }
});