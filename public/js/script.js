function toggleSidebar() {
    var sidebar = document.getElementById("sidebar");
    var mainContent = document.getElementById("main");
    sidebar.classList.toggle("collapsed");
    mainContent.classList.toggle("collapsed");
}

function toggleDropdown(event) {
    event.preventDefault();
    var dropdownMenu = event.currentTarget.nextElementSibling;
    if (dropdownMenu.style.display === "block") {
        dropdownMenu.style.display = "none";
    } else {
        dropdownMenu.style.display = "block";
    }
}
