document.addEventListener("DOMContentLoaded", () => {
  showNotification();
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
