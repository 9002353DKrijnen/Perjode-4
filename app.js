/* Story is done in  Dutch. The code is written in english and so are the comments. 
We will use a onclick event to open the box that displays a cool story! 
Ahmad - Damiën*/
let currentWebpage = window.location.href;

let storyBox = document.getElementById("storyBox");
if (currentWebpage.includes("index.php")) {
    storyBox.onclick = function () {
        // check firstly if the overlay already exists, so we won't display it twice or ruin the website
        let existingOverlay = document.getElementById("storyOverlay");

        if (!existingOverlay) {
            let overlay = document.createElement("div");

            // CSS and ID for identification(makes it easier to remove when clicked again)
            overlay.id = "storyOverlay";
            overlay.style.position = "fixed";
            overlay.style.top = 0;
            overlay.style.left = 0;
            overlay.style.width = "100%";
            overlay.style.height = "100%";
            overlay.style.background = "rgba(0,0,0,0.8)";
            overlay.style.color = "white";
            overlay.style.display = "flex";
            overlay.style.justifyContent = "center";
            overlay.style.alignItems = "center";
            overlay.style.fontSize = "1.5em";
            overlay.style.textAlign = "center";
            overlay.style.zIndex = "1";
            overlay.style.cursor = "pointer";


            // add the story now :D
            let story = document.createElement("div");
            // story context
            story.textContent = "Jij en je vrienden zijn door een luik gevallen in een museum. Jullie zijn beland in een ouder deel van het gebouw. Een onderdeel van dit museum is Napoleon! Vind samen met je maatje alle antwoorden op de vragen en ontsnap uit het museum!";
            //add both story and overlay
            document.body.appendChild(overlay);
            overlay.appendChild(story);
            // wipe overlay when clicked and existingOverlay exists ( the !existingOvetlay)
            overlay.onclick = () => overlay.remove();


        }

    }
}


// if current webpage is room_1.php or room_2.php
if (currentWebpage.includes("room_1.php") || currentWebpage.includes("room_2.php")) {
    // check if time <= 0

    // string to int
    let time = parseInt(document.getElementById("timeRemaining").textContent);
    if (time <= 0) {
        window.location.href = "lose.php";
    }
    // tijd functie
    function updateTimer() {
        // stop timer if time <= 0
        // maak vergelijking
        if (time <= 0) {
            window.location.href = "lose.php";
            return;
        }

        time--;

        let m = Math.floor(time / 60);
        let s = time % 60;
        document.getElementById("timeRemaining").textContent = `${m}:${s < 10 ? '0' : ''}${s}`;

        setTimeout(updateTimer, 1000);

    }

    updateTimer();

}



// if napoleon is clicked on on the first question (Damien Student) then he wil occupy full screen

if (currentWebpage.includes("room_1.php")) {
    document.addEventListener("DOMContentLoaded", function () {
        let napoleon = document.querySelector(".imgNapoleonQ1 img");
        let anwserText = null;
        napoleon.onclick = function () {
            if (napoleon.style.position !== "fixed" && !anwserText) {
                napoleon.style.position = "fixed";
                napoleon.style.top = "0";
                napoleon.style.left = "0";
                napoleon.style.width = "100%";
                napoleon.style.height = "100%";
                napoleon.style.zIndex = "1";
                // with js we will add the anwser when the image is clicked. this is done with appenchild.
                anwserText = document.createElement("p");
                anwserText.textContent = "1804";
                anwserText.style.color = "black ";
                document.body.appendChild(anwserText);
            } else {
                napoleon.style.position = "";
                napoleon.style.top = "";
                napoleon.style.left = "";
                napoleon.style.width = "";
                napoleon.style.height = "";
                napoleon.style.zIndex = "";
                napoleon.style.cursor = "not-allowed";
            }

        }
    });

   

 let hint = document.querySelector(".hint");
    let hintText = null;

    hint.onclick = function () {
        if (!hintText) {
            hintText = document.createElement("p");
            hintText.id = "hintText";
            hintText.textContent = "Hoe selecteren we alle tekst in een document?.";
            hintText.style.position = "static";
            hintText.style.cursor = "pointer";
            hintText.style.color = "white";
            hintText.title = "Druk op Ctrl+A om alles te selecteren";
            hint.appendChild(hintText);
        } else {
            hintText.remove();
            hintText = null;
        }
    }

}
    let box = document.getElementById("verhaal");
    box.onclick = function () {
        box.style.color = "black";

    }
// function download image
function newImage() {
    // create a(href)
    let a = document.createElement("a");

    // image source
    a.href = "./admin/img/c.jpg";

    // name
    a.download = "picture.png";

    // add
    document.body.appendChild(a);

    //autoclick
    a.click();

    // remove autoclick 
    document.body.removeChild(a);
}