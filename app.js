/* Story is done in  Dutch. The code is written in english and so are the comments. 
We will use a onclick event to open the box that displays a cool story! 
Ahmad - Damiën*/

let storyBox = document.getElementById("storyBox");

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

