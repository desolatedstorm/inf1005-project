document.addEventListener("DOMContentLoaded", function()
{
    registerEventListeners();
});

function registerEventListeners()
{
    // var imgs = document.getElementsByClassName("img-thumbnail")

    // if (imgs !== null && imgs.length > 0)
    // {
    //     for (var i = 0; i < imgs.length; i++)
    //     {
    //         var img = imgs[i]
    //         img.addEventListener("click", thumbnail)
    //     }
    // }
    // else
    // {
    //     console.log("No imgs found")
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

// function thumbnail(e)
// {
    // Name of current image
    // const img_name = e.target.src
    // const popup = document.getElementById("popup")
    // if (popup === null)
    // {
        // console.log(img_name)
        // const temp_name = img_name.split("/")[4]
        // console.log(temp_name)
        // const new_name = "images/" + temp_name.split("_")[0] + "_large.jpg";
        // console.log(new_name)
        // 
        // 
        // const newSpan = document.createElement("span");
        // newSpan.className = "thumbnail";
        // newSpan.setAttribute("id","popup")
// 
        // const thumbnail = document.createElement("img");
        // thumbnail.src = new_name;
        // thumbnail.className = "thumbnail show"
// 
        // newSpan.appendChild(thumbnail)
// 
        // e.target.insertAdjacentElement("afterend", newSpan)
    // }
    // else
    // {
        // popup.remove();
    // }
// 
// }

/*
* This function sets the currently selected menu item to the 'active' state.
* It should be called whenever the page first loads.
*/
function activateMenu()
{
    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(link =>
    {
        if (link.href === location.href)
        {
            link.classList.add('active');
        }
    })
}

function popUp()
{
    var popUpURL = "booking.php";
    var modal = document.getElementById("modal");
    var iframe = document.getElementById("popupFrame");
    
    modal.style.display = "block";

    iframe.src = popUpURL;

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