document.addEventListener("DOMContentLoaded", function () {
  registerEventListeners();
  activateMenu();
  registerFilterListeners();
  filterRooms();
});

//thumbnail logic
function registerEventListeners() {
  // var imgs = document.getElementsByClassName("img-thumbnail")

  // if (imgs !== null && imgs.length > 0) {
  //   for (var i = 0; i < imgs.length; i++) {
  //     var img = imgs[i]
  //     img.addEventListener("click", thumbnail)
  //   }
  // }
  // else {
  //   console.log("No imgs found")
  // }
  // open popup
    var popup = document.getElementById("openPopup")
    console.log(popup)
    if (popup !== null)
    {
        popup.addEventListener("click", popUp)
        console.log("pop up listener added")
    }
    else
    {
        console.log("Pop Up button not found")
    }

    // close popup
    var closeBtn = document.querySelector(".close")
    if (closeBtn !== null)
    {
        closeBtn.addEventListener("click", closeModal)
        console.log("Closing modal")
    }
    else
    {
        console.log("close button not found")
    }

    // close by click outside modal
    var modal = document.getElementById("modal")
    if (modal !== null)
    {
        modal.addEventListener("click", closeModalOutside)
    }
    else
    {
        console.log("modal not found")
    }
}

//thumbnail function that makes images with the class(img-thumbnail) open a larger pop-up image when clicked
// function thumbnail(e) {
  // Name of current image
//   const img_name = e.target.src
//   const popup = document.getElementById("popup")
//   if (popup === null) {
//     console.log(img_name)
//     const temp_name = img_name.split("/")[4]
//     console.log(temp_name)
//     const new_name = "images/" + temp_name.split("_")[0] + "_large.jpg";
//     console.log(new_name)


//     const newSpan = document.createElement("span");
//     newSpan.className = "thumbnail";
//     newSpan.setAttribute("id", "popup")

//     const thumbnail = document.createElement("img");
//     thumbnail.src = new_name;
//     thumbnail.className = "thumbnail show"

//     newSpan.appendChild(thumbnail)

//     e.target.insertAdjacentElement("afterend", newSpan)
//   }
//   else {
//     popup.remove();
//   }

// }

// not sure what this does.. highlights the nav link?
function activateMenu() {
  const navLinks = document.querySelectorAll('nav a');
  navLinks.forEach(link => {
    if (link.href === location.href) {
      link.classList.add('active');
    }
  })
}



//function to find all filter inputs on the page
function registerFilterListeners() {
    const searchInput = document.querySelector('input[type="search"]');
    const fearRadios = document.querySelectorAll('input[name="fear"]');
    const actorRadios = document.querySelectorAll('input[name="actor"]');
    const genreCheckboxes = document.querySelectorAll('input[type="checkbox"]');

    //runs filterrooms functions 
    if (searchInput) {
        searchInput.addEventListener('keyup', filterRooms);
    }
    
    fearRadios.forEach(radio => {
        radio.addEventListener('change', filterRooms);
    });
    
    actorRadios.forEach(radio => {
        radio.addEventListener('change', filterRooms);
    });
    
    genreCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', filterRooms);
    });
}


//function to find all filter inputs on the page
function registerFilterListeners() {
    const searchInput = document.querySelector('input[type="search"]');
    const fearRadios = document.querySelectorAll('input[name="fear"]');
    const actorRadios = document.querySelectorAll('input[name="actor"]');
    const genreCheckboxes = document.querySelectorAll('input[type="checkbox"]');

    //runs filterrooms functions 
    if (searchInput) {
        searchInput.addEventListener('keyup', filterRooms);
    }
    
    fearRadios.forEach(radio => {
        radio.addEventListener('change', filterRooms);
    });
    
    actorRadios.forEach(radio => {
        radio.addEventListener('change', filterRooms);
    });
    
    genreCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', filterRooms);
    });
}


// the main filter function.
function filterRooms() {

    //basically finds if the room matches all the filter values and only shows it if it does else it wont show
    //also updates the room number with a counter
    const searchText = document.querySelector('input[type="search"]').value.toLowerCase();
    const fearValue = document.querySelector('input[name="fear"]:checked').value;
    const actorValue = document.querySelector('input[name="actor"]:checked').value;
    //css selector ':checked' searches for the radio button pressed

    //checkbox: first generates an empty array and pushes values of checked checkbox into the array
    const checkedGenres = [];
    document.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
        checkedGenres.push(checkbox.value);
    });

    //get all room cards and reset counter
    const roomCards = document.querySelectorAll('.room-card');
    let visibleCount = 0;

    //main loop
    roomCards.forEach(card => {
        
        //get data from the cards -> data labels in php
        const cardTitle = (card.dataset.title || '').toLowerCase();
        const cardFear = card.dataset.fear;
        const cardActor = card.dataset.actor;
        const cardGenre = card.dataset.genre;

        //does card title include text from the search bar
        const titleMatch = cardTitle.includes(searchText);
        //radio and checkbox logic
        const fearMatch = (fearValue === 'all' || fearValue === cardFear);
        const actorMatch = (actorValue === 'all' || actorValue === cardActor);
        const genreMatch = (checkedGenres.length === 0 || checkedGenres.includes(cardGenre));

        //impt! only shows the room if ALL conditions are true! else nuh uh
        if (titleMatch && fearMatch && actorMatch && genreMatch) {
            card.style.display = 'block'; //show card
            visibleCount++;
        } else {
            card.style.display = 'none';  //hide card
        }
    });

    //update the "Showing {num} rooms" text
    const roomCountText = document.querySelector('.text-center.my-4 p');
    if (roomCountText) {
        roomCountText.textContent = `Showing ${visibleCount} room${visibleCount !== 1 ? 's' : ''}`;
    }
}

function popUp()
{
    console.log("Opening bookig");
    var popUpURL = "booking.php?token=";
    var modal = document.getElementById("modal");
    var iframe = document.getElementById("popupFrame");
    
    modal.style.display = "block";

    fetch("api/api_generate_token.php")
    .then(response => response.text())
    .then(token => {
        iframe.src = popUpURL + token;
    })

}

function closeModal()
{
    var modal = document.getElementById("modal");
    modal.style.display = "none";
}

function closeModalOutside(e)
{
    var modal = document.getElementById("modal");
    if (e.target == modal)
    {
        modal.style.display = "none";
    }
}