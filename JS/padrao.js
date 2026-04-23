document.getElementById("userMenu").addEventListener("click", function (e) {
    e.stopPropagation(); 
    this.classList.toggle("active");
});

document.addEventListener("click", function () {
    document.getElementById("userMenu").classList.remove("active");
});