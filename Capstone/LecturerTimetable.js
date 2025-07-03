
  document.addEventListener("DOMContentLoaded", function() {
      const weekInput = document.getElementById('selectedWeekInput');
      const weekButton = document.getElementById('selectedWeek');
      
      function getSurroundingWeeks() {
          const today = new Date();
          const mondayOffset = today.getDay() === 0 ? -6 : 1 - today.getDay();
          const currentMonday = new Date(today);
          currentMonday.setDate(today.getDate() + mondayOffset);

          const weeks = [];
          for (let i = -2; i <= 2; i++) {
              const weekStart = new Date(currentMonday);
              weekStart.setDate(currentMonday.getDate() + i * 7);
              const weekEnd = new Date(weekStart);
              weekEnd.setDate(weekStart.getDate() + 4);
              
              const key = formatDate(weekStart) + ' - ' + formatDate(weekEnd);
              weeks.push({
                  key,
                  start: weekStart,
                  end: weekEnd
              });
          }
          return weeks;
      }

      function formatDate(date) {
          const day = date.getDate();
          const month = date.toLocaleString('default', { month: 'short' });
          return `${day} ${month}`;
      }

      function populateWeekDropdown(selectedWeek) {
          const dropdown = document.getElementById("Dropdown");
          dropdown.innerHTML = ""; 
          
          const weeks = getSurroundingWeeks();
          weeks.forEach(week => {
              const option = document.createElement("a");
              option.href = "#";
              option.textContent = week.key;
              if (week.key === selectedWeek) {
                  option.style.fontWeight = "bold";
                  option.style.backgroundColor = "#f0f0f0";
              }
              option.onclick = (e) => {
                  e.preventDefault();
                  selectWeek(week.key);
              };
              dropdown.appendChild(option);
          });
      }

      function toggleWeekDropdown() {
          const dropdown = document.getElementById("Dropdown");
          dropdown.classList.toggle("show");
      }

      function selectWeek(weekKey) {
          weekInput.value = weekKey;
          weekButton.textContent = weekKey;
          sessionStorage.setItem('selectedWeek', weekKey);
          document.getElementById("Dropdown").classList.remove("show");
          document.getElementById("timetableForm").submit();
      }

      function selectDay(day) {
          const currentWeek = sessionStorage.getItem('selectedWeek') || weekButton.textContent;
          weekInput.value = currentWeek;
          document.getElementById("selectedDayInput").value = day;
          document.getElementById("timetableForm").submit();
      }

      const storedWeek = sessionStorage.getItem('selectedWeek') || weekInput.value;
      if (storedWeek) {
          weekButton.textContent = storedWeek;
          weekInput.value = storedWeek;
      }

      populateWeekDropdown(storedWeek);

      weekButton.onclick = toggleWeekDropdown;

      window.onclick = function(event) {
          if (!event.target.matches('.dropbtn')) {
              const dropdowns = document.getElementsByClassName("dropdown-content");
              for (const dropdown of dropdowns) {
                  if (dropdown.classList.contains('show')) {
                      dropdown.classList.remove('show');
                  }
              }
          }
      };

      window.selectWeek = selectWeek;
      window.selectDay = selectDay;
      window.toggleWeekDropdown = toggleWeekDropdown;
  });
