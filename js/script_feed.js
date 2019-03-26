const win = document.getElementById('window');
const winImg = document.getElementById('window_img');
const photos = document.getElementsByClassName('right__photos-min');
const close = document.getElementsByClassName('close')[0];
const comment = document.getElementById('comment');
const send = document.getElementById('send');

let select = null;

const showWindow = function(e) {
  win.style.display = 'block';
  winImg.src = (e.srcElement && e.srcElement.src) || (e.target && e.target.src);
  select = (e.srcElement && e.srcElement.src) || (e.target && e.target.src);
}

const saveMyHTML = function(html) {
  return html
       .replace(/&/g, "&amp;")
       .replace(/</g, "&lt;")
       .replace(/>/g, "&gt;")
       .replace(/"/g, "&quot;")
       .replace(/'/g, "&#039;");
}

for (let i = 0; i < photos.length; i++) {
  photos[i].onclick = showWindow;
}

photos.onclick = function() {
  win.style.display = 'block';
}

close.onclick = function() {
  win.style.display = 'none';
}

window.onclick = function(e) {
  if (e.target == win) {
    win.style.display = 'none';
  }
}

send.onclick = function(e) {
  const val = comment.value;

  if (val == "" || val == null) return;

  const path = select.split('/');
  const imgPath = path[path.length - 1];
  const xhr = new XMLHttpRequest();

  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText != null && xhr.responseText != "") {
      const div = document.querySelectorAll(`[data-img="${imgPath}"]`)[0];
      const span = document.createElement('span');

      comment.value = '';
      win.style.display = 'none';
      span.innerHTML = `${xhr.responseText}: ${saveMyHTML(val)}`;
      span.setAttribute('class', 'comment');
      div.appendChild(span);
    }
  };
  xhr.open('POST', './forms/comments.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send('img=' + '../img/' + file + '&f=' + imgURL);
  xhr.send(`img=${imgPath}&comment=${val}`);
}
