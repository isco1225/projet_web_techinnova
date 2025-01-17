/* notifications.js */
document.addEventListener("DOMContentLoaded", function () {
    const markReadButtons = document.querySelectorAll(".mark-read");
    markReadButtons.forEach(button => {
        button.addEventListener("click", function () {
            const notification = this.parentElement;
            notification.classList.remove("unread");
            notification.classList.add("read");
            this.remove();
        });
    });
});
