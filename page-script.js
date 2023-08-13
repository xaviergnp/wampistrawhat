// Log In
function loginTab() {
  window.scroll(0, 0);
  var login = document.getElementById("logintab-box");
  var signup = document.getElementById("signuptab-box");
  var knylogo = document.getElementById("logologo");
  document
    .getElementById("id-body-login-page")
    .setAttribute(
      "style",
      "background-image: url(assets/img/light-wall.jpg) !important;"
    );
  login.setAttribute("style", "display:block");
  signup.setAttribute("style", "display:none");
  knylogo.setAttribute("style", "margin-top: 80px");
}
function signupTab() {
  window.scroll(0, 0);

  var login = document.getElementById("logintab-box");
  var signup = document.getElementById("signuptab-box");
  var knylogo = document.getElementById("logologo");
  document
    .getElementById("id-body-login-page")
    .setAttribute(
      "style",
      "background-image: url(assets/img/dark-wallpaper.jpg) !important;"
    );
  login.setAttribute("style", "display:none");
  signup.setAttribute("style", "display:block");
  knylogo.setAttribute("style", "margin-top: 20px");
}

// Menu Dropdown
function menuToggle() {
  var menuTog = document.getElementById("id-dropdown-menu-container");
  var accTog = document.getElementById("id-dropdown-account-container");
  if (menuTog.className.indexOf("menu-hidden-show") == -1) {
    if (accTog.className.indexOf("dropdown-account-container-show") != -1) {
      accTog.className = accTog.className.replace(
        " dropdown-account-container-show",
        ""
      );
    }
    menuTog.className += " menu-hidden-show";
  } else {
    menuTog.className = menuTog.className.replace(" menu-hidden-show", "");
  }
}

// Acc Dropdown
function accToggle() {
  var menuTog = document.getElementById("id-dropdown-menu-container");
  var accTog = document.getElementById("id-dropdown-account-container");
  if (accTog.className.indexOf("dropdown-account-container-show") == -1) {
    if (menuTog.className.indexOf("menu-hidden-show") != -1) {
      menuTog.className = menuTog.className.replace(" menu-hidden-show", "");
    }
    accTog.className += " dropdown-account-container-show";
  } else {
    accTog.className = accTog.className.replace(
      " dropdown-account-container-show",
      ""
    );
  }
}

//Hide Toggle Dropdowns
function hideDropdown() {
  var menuTog = document.getElementById("id-dropdown-menu-container");
  var accTog = document.getElementById("id-dropdown-account-container");

  if (menuTog.className.indexOf("menu-hidden-show") != -1) {
    menuTog.className = menuTog.className.replace(" menu-hidden-show", "");
  }

  if (accTog.className.indexOf("dropdown-account-container-show") != -1) {
    accTog.className = accTog.className.replace(
      " dropdown-account-container-show",
      ""
    );
  }
}

$(document).on("click", function (event) {
  var $trigger1 = $(".user-login-button");
  var $trigger2 = $("#id-menu-hidden");
  if (
    $trigger1 !== event.target &&
    !$trigger1.has(event.target).length &&
    $trigger2 !== event.target &&
    !$trigger2.has(event.target).length
  ) {
    hideDropdown();
  }
});

// $(document).on("click", function (event) {
//   var $trigger1 = $(".user-login-button");
//   var $trigger2 = $("#id-menu-hidden");
//   if ($trigger2 !== event.target && !$trigger2.has(event.target).length) {
//     // accToggle();
//     menuToggle();
//   }
// });

//Show/Hide Episode Video Playback
function episodeDisplayShow() {
  var v = document.getElementById("kny-vid-display-id");
  v.setAttribute("class", "kny-vid-display-show");
}

function episodeDisplayHide() {
  var v = document.getElementById("kny-vid-display-id");
  v.setAttribute("class", "kny-vid-display-hide");
  // document.getElementsByClassName("kny-play-vid")[0].load();
  // document.getElementById("kny-vid-jump-id").pause();
  document.getElementById("kny-vid-jump-id").load();
  // document.querySelector("#kny-vid-jump-id").load();
}

//Trailer Show/Hide/Play
window.onload = function () {
  var popup = document.getElementById("popup");
  var overlay = document.getElementById("backgroundOverlay");
  document.onclick = function (e) {
    if (e.target.id == "closeVid") {
      exitvideo();
      popup.style.display = "none";
      overlay.style.display = "none";
    }
    if (e.target.id == "backgroundOverlay") {
      exitvideo();
      popup.style.display = "none";
      overlay.style.display = "none";
    }
  };
};

function openvidbox() {
  var popup2 = document.getElementById("popup");
  var overlay2 = document.getElementById("backgroundOverlay");

  popup2.style.display = "block";
  overlay2.style.display = "block";
  playvideo();
}

function playvideo() {
  var vid = document.getElementById("kny-trailer-vid");
  // vid.pause();
  // var prevSource = vid.src;
  // vid.src = "";
  vid.load();
  vid.play();
  // vid.currentTime = 0;
  // vid.play();
}
function exitvideo() {
  var vid = document.getElementById("kny-trailer-vid");
  vid.pause();
  // var prevSource = vid.src;
  // vid.src = "";
  vid.load();
  // vid.src = prevSource;

  // vid.pause();
  // vid.currentTime = 0;
}
function jumpVid() {
  document.getElementById("kny-vid-jump-id-p").scrollIntoView(); //jump to vid
  // window.scroll(0, window.scrollY - 70); //57 //70
  // .scrollIntoView({ behavior: "smooth", block: "start", inline: "nearest",});
  //   const jmp = document.documentElement;
  //   jmp.scrollTop = 540; //jump to vid
}

//Link Button to play eps
// let episodeListButton = document.querySelectorAll(
//   ".ep-button-container .ep-button"
// );
// episodeListButton.forEach((episodeButton) => {
//   episodeButton.onclick = () => {
//     jumpVid();
//     let eplink = episodeButton.querySelector(".ep-button-link").src;
//     let epnumber = episodeButton.querySelector("i").innerHTML;
//     let eptitle = episodeButton.querySelector("p").innerHTML;
//     let eptitletext = episodeButton.querySelector("i:nth-of-type(2)").innerHTML;
//     let epthumbnail = episodeButton.querySelector(".ep-button-link").poster;

//     document.querySelector(".kny-vid-background .kny-play-vid").src = eplink;
//     document.querySelector(".kny-vid-background .kny-play-vid").load(); //.load() .play()
//     document.querySelector(
//       ".episode-text-container .episode-text-epNumber"
//     ).innerHTML = epnumber;
//     document.querySelector(
//       ".episode-text-container .episode-text-epTitle"
//     ).innerHTML = eptitle;
//     document.querySelector(
//       ".episode-text-container .episode-text-epTitletext"
//     ).innerHTML = eptitletext;
//     document.querySelector(".kny-vid-background .kny-play-vid").poster =
//       epthumbnail;
//   };
// });

//Link Table to play eps
// let episodeList = document.querySelectorAll(".table-episode tr");
// episodeList.forEach((episode) => {
//   episode.onclick = () => {
//     // var v = document.getElementById("kny-vid-display-id");
//     // v.setAttribute("class", "kny-vid-display-show");
//     episodeDisplay();
//     jumpVid();
//     let eplink = episode.querySelector("video").src;
//     let epnumber = episode.querySelector("td").innerHTML;
//     let eptitle = episode.querySelector("p").innerHTML;
//     let eptitletext = episode.querySelector("i").innerHTML;
//     let epthumbnail = episode.querySelector("video").poster;
//     document.querySelector(".kny-vid-background .kny-play-vid").src = eplink;
//     document.querySelector(".kny-vid-background .kny-play-vid").load(); //.load() .play()
//     document.querySelector(
//       ".episode-text-container .episode-text-epNumber"
//     ).innerHTML = epnumber;
//     document.querySelector(
//       ".episode-text-container .episode-text-epTitle"
//     ).innerHTML = eptitle;
//     document.querySelector(
//       ".episode-text-container .episode-text-epTitletext"
//     ).innerHTML = eptitletext;
//     document.querySelector(".kny-vid-background .kny-play-vid").poster =
//       epthumbnail;
//   };
// });

//Character Description Hide
function charinfoHide() {
  var v = document
    .getElementById("char-box-info-id")
    .classList.remove("char-box-info-show");
}

function jumpCharinfo() {
  document.getElementById("char-box-info-id").scrollIntoView(); //jump to char info
  //window.scroll(0, window.scrollY - 70); //57 //70
}

// Character Description Show
let charfig = document
  .getElementById("char-fig-container-id")
  .querySelectorAll(".char-box-fig .char-box-fig-img");

charfig.forEach((charfigimg) => {
  charfigimg.onclick = () => {
    let charboxinfo = document
      .getElementById("char-box-info-id")
      .querySelector("h3").innerHTML;

    let charImg = charfigimg.querySelector(".char-box-fig-hiddeninfo img").src;
    let charName = charfigimg.querySelector(
      ".char-box-fig-hiddeninfo h3"
    ).innerHTML;
    let charText = charfigimg.querySelector(
      ".char-box-fig-hiddeninfo p"
    ).innerHTML;
    document
      .getElementById("char-box-info-id")
      .querySelector(".char-display-full-pic").src = charImg;
    document.getElementById("char-box-info-id").querySelector("h3").innerHTML =
      charName;
    document.getElementById("char-box-info-id").querySelector("p").innerHTML =
      charText;

    var v = document
      .getElementById("char-box-info-id")
      .classList.add("char-box-info-show");
    jumpCharinfo();
  };
});
