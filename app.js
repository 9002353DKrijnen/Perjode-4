/* Story is done in  Dutch. The code is written in english and so are the comments. 
We will use a onclick event to open the box that displays a cool story! */

let storyBox = document.getElementById("storyBox");

storyBox.onclick = function () {
    // check firstly if the overlay already exists, so we won't display it twice or ruin the website
    let existingOverlay = document.getElementById("storyOverlay");

    if (!existingOverlay) {
        let overlay = document.createElement("div");
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



        let story = document.createElement("div");
        story.textContent = "Jij en je vrienden zijn gevallen door een luik in een museum. Jullie zijn beland in een ouder deel van het museum. Onderdeel van deze museum is Napoleon! Vind samen met je maatje alle antwoorden tot de vragen en ontsnap uit de museum!";
        overlay.appendChild(story);
        document.body.appendChild(overlay);

        overlay.onclick = () => overlay.remove();


    }

}