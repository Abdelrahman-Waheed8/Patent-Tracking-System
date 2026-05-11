const cards = document.querySelectorAll(".card");
const sections = document.querySelectorAll(".section-card");
cards.forEach(card => {
    card.addEventListener("click", () => {
        const target = card.dataset.target;
        sections.forEach(section => {
            section.classList.remove("active");
        });
        document.getElementById(target).classList.add("active");
        document.getElementById(target).scrollIntoView({
            behavior: "smooth"
        });
    });

});
function goBack() {
    window.location.href = "../ip_counsel/ipcounsel.php";
}