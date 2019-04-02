const video = document.getElementById('webcam');
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
const cam_btn = document.getElementById('cam_btn');
const img_btn = document.getElementById('img_btn');
const load_img = document.getElementById('load_img');
const photos = document.getElementById('right__photos');
const del = document.getElementsByClassName('del');

const cat = document.getElementById('cat');
const man = document.getElementById('man');
const sasha = document.getElementById('sasha');

let available = false;
let count = 0;

const sendPhoto = function(img64, filter) {
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText != null && xhr.responseText != "") {
      const newImg = document.createElement("IMG");
      newImg.className = "right__photos-min del";
      newImg.src = "photos/" + xhr.responseText;
      newImg.onclick = function(e) {
        const path = (e.srcElement && e.srcElement.src) || (e.target && e.target.src);
        const srcTab = path.split('/');
        const src = srcTab[srcTab.length - 1];

        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
          if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText == "OK") {
            photos.removeChild(e.srcElement || e.target);
          }
        };
        xhr.open('POST', './forms/remove.php', true);
        xhr.setRequestHeader('Content-Type', 'aplication/x-www-form-urlencoded');
        xhr.send('src=' + src);
      }
      photos.appendChild(newImg);
    }
  };
  xhr.open('POST', './forms/photos.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('img=' + '../img/' + filter + '&f=' + img64);
}

load_img.onchange = function(e) {
  const file = this.files[0];
  const img = new Image();
  const newImg = new Image();

  canvas.style.display = 'block';

  img.addEventListener('load', function(e) {
    ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, 640, 480);
    const img64 = canvas.toDataURL(img.type);
    window.URL.revokeObjectURL(file);
    newImg.src = document.querySelector('input[name="img"]:checked').value;
    const split = newImg.src.split('/');
    var file = split[split.length - 1];

    if (file === 'cat.png') {
      ctx.drawImage(newImg, 0, 0, 1024, 768, 0, 0, 640, 480);
    } else {
      ctx.drawImage(newImg, 0, 0, 1024, 768, 100, 200, 240, 180);
    }

    img_btn.onclick = function() {
      sendPhoto(img64, file);
    }
  }, false);

  img.src = window.URL.createObjectURL(this.files[0]);
  load_img.style.display = 'block';
}

const getVideo = function() {
  navigator.mediaDevices.getUserMedia({ video: true, audio: false })
    .then(localMediaStream => {

      video.srcObject = localMediaStream;
      // video.play();
      available = true;
      video.style.display = 'block';
      cam_btn.onclick = function() {
        const img = new Image();
        canvas.style.display = 'none';
        img_btn.style.display = 'none';

        img.addEventListener('load', function() {
          if (file === 'cat.png') {
            ctx.drawImage(img, 0, 0, 1024, 768, 0, 0, 640, 480);
          }
        }, false);


        img.src = document.querySelector('input[name="img"]:checked').value;
        const split = img.src.split('/');
        const file = split[split.length - 1];

        ctx.drawImage(video, 0, 0, 640, 480, 0, 0, 640, 480);
        const imgURL = canvas.toDataURL("image/png");

        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
          if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText != null && xhr.responseText != "") {
            let tmp = xhr.responseText.split('/');
            const newImg = document.createElement("img");
            const newSpan = document.createElement('span');

            if (count % 2) {
              newImg.className = "right__photos-min del odd";
              newSpan.className = 'tag odd';
            } else {
              newImg.className = "right__photos-min del even";
              newSpan.className = 'tag even';
            }
            count++;
            newSpan.innerText = tmp[1];
            newImg.src = "photos/" + tmp[0];
            newImg.onclick = function(e) {
              const path = e.srcElement.src;
              const srcTab = path.split('/');
              const src = srcTab[srcTab.length - 1];

              const xhr = new XMLHttpRequest();
              xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText == "OK") {
                  photos.removeChild(e.srcElement.nextElementSibling);
                  photos.removeChild(e.srcElement);
                }
              };
              xhr.open('POST', './forms/remove.php', true);
              xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
              xhr.send('src=' + src);
            }
            photos.appendChild(newImg);
            photos.appendChild(newSpan);
          }
        };
        xhr.open('POST', './forms/photos.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('img=' + '../img/' + file + '&f=' + imgURL);
      };
    })
    .catch(err => {
      console.error(`ERROR: `, err);
    });
}

const onCheckboxChecked = function(checkbox) {
  if (available) {
    cam_btn.style.display = 'block';
    if (checkbox.id === 'cat_icon') {
      cat.style.display = 'block';
      man.style.display = 'none';
      sasha.style.display = 'none';
    } else if (checkbox.id === 'sasha_icon') {
      sasha.style.display = 'block';
      cat.style.display = 'none';
      man.style.display = 'none';
    } else {
      sasha.style.display = 'none';
      cat.style.display = 'none';
      man.style.display = 'block';
    }
  }

  load_img.style.display = 'block';
  if (load_img.files.length) {
    const img = new Image();
    const newImg = new Image();

    img.addEventListener('load', function() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, 640, 480);
      
      const img64 = canvas.toDataURL(img.type);
      window.URL.revokeObjectURL(file);
      newImg.src = document.querySelector('input[name="img"]:checked').value;
      const split = newImg.src.split('/');
      const file = split[split.length - 1];

      if (file === 'cat.png') {
        ctx.drawImage(newImg, 0, 0, 1024, 768, 0, 0, 640, 480);
      } else {
        ctx.drawImage(newImg, 0, 0, 1024, 768, 100, 200, 240, 180);
      }

      img_btn.onclick = function() {
        sendPhoto(img64, file);
      }
    }, false);
    img.src = window.URL.createObjectURL(load_img.files[0]);
  }
}

getVideo();

for (let i = 0; i < del.length; i++) {
  del[i].onclick = function(e) {
    // console.log('new  ' + e);
    const path = (e.srcElement && e.srcElement.src) || (e.target && e.target.src);
    // console.log(path);
    const srcTab = path.split('/');
    // console.log(srcTab);
    const src = srcTab[srcTab.length - 1];
    // console.log(src);
    const xhr = new XMLHttpRequest();
    // console.log(xhr);
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText == 'OK') {
        photos.removeChild(e.srcElement.nextElementSibling);
        photos.removeChild(e.srcElement.nextElementSibling);
        photos.removeChild(e.srcElement.nextElementSibling);
        photos.removeChild(e.srcElement || e.target);
      }
    };
    xhr.open('POST', './forms/remove.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('src=' + src);
  }
}

function sendImg(img64, filter) {
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText != null && xhr.responseText != "") {
      let tmp = xhr.responseText.split('/');
      const newSpan = document.createElement('span');
      const img = document.createElement("img");

      if (count % 2) {
        img.className = "right__photos-min del odd";
        newSpan.className = 'tag odd';
      } else {
        img.className = "right__photos-min del even";
        newSpan.className = 'tag even';
      }
      count++;
      newSpan.innerText = tmp[1];
      img.src = "photos/" + tmp[0];
      img.onclick = function(e) {
        const path = (e.srcElement && e.srcElement.src) || (e.target && e.target.src);
        const tab = path.split('/');
        const src = tab[tab.length - 1];

        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
          if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText == "OK") {
            photos.removeChild(e.srcElement.nextElementSibling);
            photos.removeChild(e.srcElement || e.target);
          }
        };
        xhr.open("POST", "./forms/remove.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send(`src=${src}`);
      }
      photos.appendChild(img);
      photos.appendChild(newSpan);
    }
  };
  xhr.open("POST", "./forms/photos.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.send(`img=../img/${filter}&f=${img64}`);
}

load_img.onchange = function (e) {
  const file = this.files[0];
  const img = new Image();
  const newImg = new Image();

  canvas.style.display = "block";
  img.addEventListener("load", function(e) {
      ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, 640, 480);
      const img64 = canvas.toDataURL(img.type);
      window.URL.revokeObjectURL(file);

      newImg.src = document.querySelector('input[name="img"]:checked').value;
      const split = newImg.src.split("/");
      const name = split[split.length - 1];

      if (name === "cat.png") {
        ctx.drawImage(newImg, 0, 0, 1024, 768, 0, 0, 640, 480);
      } else if (name === "man.png") {
        ctx.drawImage(newImg, 0, 0, 1024, 768, 0, 0, 1080, 480);
      } else {
        ctx.drawImage(newImg, 0, 0, 1024, 768, 0, 0, 1080, 0);
      }

      img_btn.onclick = function () {
        sendImg(img64, name);
      }
  }, false);

  img.src = window.URL.createObjectURL(this.files[0]);
  img_btn.style.display = "block";
}
