/*==================== HIDE NAVBAR ====================*/
let prevScrollpos = window.pageYOffset;
window.onscroll = function() {
    let currentScrollPos = window.pageYOffset;
    if (prevScrollpos > currentScrollPos) {
        document.getElementById("header").style.top = "0";
    } else {
        document.getElementById("header").style.top = "-60px";
    }
    prevScrollpos = currentScrollPos;
};

/*==================== SHOW NAVBAR ====================*/
const showMenu = (headerToggle, navbarId) => {
    const toggleBtn = document.getElementById(headerToggle),
        nav = document.getElementById(navbarId);

    if (headerToggle && navbarId) {
        toggleBtn.addEventListener("click", () => {
            nav.classList.toggle("show-menu");
            toggleBtn.classList.toggle("bx-x");
        });
    }
};
showMenu("header-toggle", "navbar");

/*==================== OPEN/CLOSE NAVBAR Listes amis ====================*/
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
  }

  function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
  }


/*==================== LINK ACTIVE ====================*/
const linkColor = document.querySelectorAll(".nav__link");

function colorLink() {
    linkColor.forEach((l) => l.classList.remove("active"));
    this.classList.add("active");
}

linkColor.forEach((l) => l.addEventListener("click", colorLink));

/*==================== DARK LIGHT THEME ====================*/
const themeButton = document.getElementById("header-mode");
const darkTheme = "dark-theme";
const iconTheme = "bxs-sun";

const selectedTheme = localStorage.getItem("selected-theme");
const selectedIcon = localStorage.getItem("selected-icon");

const getCurrentTheme = () =>
    document.body.classList.contains(darkTheme) ? "dark" : "light";
const getCurrentIcon = () =>
    themeButton.classList.contains(iconTheme) ? "bxs-moon" : "bxs-sun";

if (selectedTheme) {
    document.body.classList[selectedTheme === "dark" ? "add" : "remove"](
        darkTheme
    );
    themeButton.classList[selectedIcon === "bxs-moon" ? "add" : "remove"](
        iconTheme
    );
}

themeButton.addEventListener("click", () => {
    document.body.classList.toggle(darkTheme);
    themeButton.classList.toggle(iconTheme);
    localStorage.setItem("selected-theme", getCurrentTheme());
    localStorage.setItem("selected-icon", getCurrentIcon());
});

/*==================== DYSLEXIE THEME ====================*/
const dyslexieButton = document.getElementById("dyslexie-mode");
const dyslexieTheme = "dyslexie-theme";
const normalTheme = "normalTheme";

const selectTheme = localStorage.getItem("dyslexie");

const getTheme = () =>
    document.body.classList.contains(dyslexieTheme) ? "dyslexie" : "normal";

if (selectTheme) {
    document.body.classList[selectTheme === "dyslexie" ? "add" : "remove"](
        dyslexieTheme
    );
}

dyslexieButton.addEventListener("click", () => {
    document.body.classList.toggle(dyslexieTheme);
    localStorage.setItem("dyslexie", getTheme());
});

/*==================== DALTONIE THEME ====================*/
const daltonieButton = document.getElementById("daltonie-mode");
const daltonieTheme = "daltonie-theme";
const normTheme = "normTheme";

const selTheme = localStorage.getItem("daltonie");

const Theme = () =>
    document.body.classList.contains(daltonieTheme) ? "daltonie" : "norm";

if (selTheme) {
    document.body.classList[selTheme === "daltonie" ? "add" : "remove"](
        daltonieTheme
    );
}

daltonieButton.addEventListener("click", () => {
    document.body.classList.toggle(daltonieTheme);
    localStorage.setItem("daltonie", Theme());
});
/*==================== DELETE ILLUSTRATION ====================*/
window.onload = () => {
    let links = document.querySelectorAll("[data-delete]");

    for (link of links) {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            if (confirm("Voulez-vous supprimer cette illustration ?")) {
                fetch(this.getAttribute("href"), {
                        method: "DELETE",
                        headers: {
                            "X-requested-With": "XMLHttpRequest",
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({ _token: this.dataset.token }),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            this.parentElement.remove();
                        } else {
                            alert(data.error);
                        }
                    })
                    .catch((e) => alert(e));
            }
        });
    }
};

/*==================== COMMENT REPLY ====================*/

window.onload = () => {
    
    document.querySelectorAll("[data-reply]").forEach((element) => {
        element.addEventListener("click", function() {
            document.querySelector("#comment_parentid").value = this.dataset.id;
            console.log(this.dataset.id)
            
        });
    });
};

/*========================= COMMENT EDIT ===========================*/

/*==================== ACTIVE ====================*/

window.onload = () => {
    let activer = document.querySelectorAll("[type=checkbox]")
    for (let bouton of activer) {
        bouton.addEventListener("click", function() {
            let xmlhttp = new XMLHttpRequest;

            xmlhttp.open("get", `/admin/article/activer/${this.dataset.id}`)
            xmlhttp.send()
        })
    }
}

/*======================ScrollToTop Button ============================= */


// let scrollTop = document.querySelectorAll(".scrollToTop");

// scrollTop[0].addEventListener('click', function() {
//     window.scrollTo(0,0);
// });
// window.addEventListener('scroll', function(e) {
//     // console.log(window.scrollY)
//     if(window.scrollY > 120){
//         scrollTop[0].style.right = "20px" ;
//     } else{
//         scrollTop[0].style.right = "-50px";
//     }
// });