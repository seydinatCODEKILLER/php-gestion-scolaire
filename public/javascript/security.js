document.addEventListener("DOMContentLoaded", () => {
  showNotification();
  const sidebarDeviceButton = document.getElementById("sidebar-device");
  const sidebarClose = document.getElementById("sidebar-close");
  sidebarDeviceButton.addEventListener("click", openSidebar);
  sidebarClose.addEventListener("click", closeSidebar);
});

function showNotification() {
  const alertRole = document.getElementById("alerter");
  setTimeout(
    () => alertRole.classList.remove("opacity-0", "translate-y-2"),
    10
  );

  setTimeout(() => {
    alertRole.classList.add("opacity-0", "translate-y-2");
    setTimeout(() => alertRole.remove(), 300);
  }, 3000);
}

function openSidebar() {
  const sidebar = document.getElementById("sidebar");
  sidebar.classList.remove("-translate-x-full");
}

function closeSidebar() {
  const sidebar = document.getElementById("sidebar");
  sidebar.classList.add("-translate-x-full");
}
