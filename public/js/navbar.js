let btnMenu = document.querySelector(".btn-menu");
let closeBtn = document.querySelector(".close-btn");
let arrow = document.querySelectorAll(".arrow");

btnMenu.addEventListener("click", () => {
    document.querySelector(".side-bar").classList.toggle('active');
    document.querySelector(".btn-menu").style.visibility = "hidden";
})

closeBtn.addEventListener("click", () => {
    document.querySelector(".side-bar").classList.toggle('active');
    document.querySelector(".btn-menu").style.visibility = "visible";
})

for (let i = 0; i < arrow.length; i++)
{
    arrow[i].addEventListener("click", (e) => {
        let arrowParent = e.target.parentElement.parentElement;
        arrowParent.classList.toggle("showSubMenu");
    })
}