
function filterTable() {
const input = document.getElementById("searchInput");
const filter = input.value.toUpperCase();
const rows = document.querySelectorAll(".table-body .table-row");

rows.forEach(row => {
  const intakeCell = row.querySelector(".column");
  if (intakeCell) {
  const txtValue = intakeCell.textContent || intakeCell.innerText;
  if (txtValue.toUpperCase().indexOf(filter) > -1) {
      row.style.display = "";
  } else {
      row.style.display = "none";
  }
  }
  });
}