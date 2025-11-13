document.addEventListener("DOMContentLoaded", function () {
  registerEventListeners();
});

function registerEventListeners() {
  var imgs = document.getElementsByClassName("img-thumbnail")

  if (imgs !== null && imgs.length > 0) {
    for (var i = 0; i < imgs.length; i++) {
      var img = imgs[i]
      img.addEventListener("click", thumbnail)
    }
  }
  else {
    console.log("No imgs found")
  }
}

function thumbnail(e) {
  // Name of current image
  const img_name = e.target.src
  const popup = document.getElementById("popup")
  if (popup === null) {
    console.log(img_name)
    const temp_name = img_name.split("/")[4]
    console.log(temp_name)
    const new_name = "images/" + temp_name.split("_")[0] + "_large.jpg";
    console.log(new_name)


    const newSpan = document.createElement("span");
    newSpan.className = "thumbnail";
    newSpan.setAttribute("id", "popup")

    const thumbnail = document.createElement("img");
    thumbnail.src = new_name;
    thumbnail.className = "thumbnail show"

    newSpan.appendChild(thumbnail)

    e.target.insertAdjacentElement("afterend", newSpan)
  }
  else {
    popup.remove();
  }

}

function activateMenu() {
  const navLinks = document.querySelectorAll('nav a');
  navLinks.forEach(link => {
    if (link.href === location.href) {
      link.classList.add('active');
    }
  })
}

const fearRadios = document.querySelectorAll('input[name="fear"]');
const rooms = document.querySelectorAll('.room-card');
const roomCountText = document.querySelector('.text-center.my-4 p');

function updateRoomCount() {
  const visibleRooms = Array.from(rooms).filter(room => room.style.display !== 'none');
  const count = visibleRooms.length;
  
  if (roomCountText) {
    roomCountText.textContent = `Showing ${count} room${count !== 1 ? 's' : ''}`;
  }
}

fearRadios.forEach(radio => {
  radio.addEventListener('change', () => {
    const value = radio.value;
    rooms.forEach(room => {
      if (value === 'all' || room.dataset.fear === value) {
        room.style.display = 'block';
      } else {
        room.style.display = 'none';
      }
    });
    //update room count after filtering
    updateRoomCount();
  });
});
// Initial room count update
updateRoomCount();