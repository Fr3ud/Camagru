const feed = document.getElementById('feed');
const more = document.getElementById('more');
const win = document.getElementById('window');
const winImg = document.getElementById('window_img');
const photos = document.getElementsByClassName('right__photos-min');
const close = document.getElementsByClassName('close')[0];
const comment = document.getElementById('comment');
const send = document.getElementById('send');
const likes = document.getElementsByClassName('like_btn');
const dislikes = document.getElementsByClassName('dislike_btn');

let select = null;
let last = null;

const showWindow = function(e) {
  win.style.display = 'block';
  winImg.src = (e.srcElement && e.srcElement.src) || (e.target && e.target.src);
  select = (e.srcElement && e.srcElement.src) || (e.target && e.target.src);
}

const showWin = function(src) {
  win.style.display = 'block';
  winImg.src = `photos/${src}`;
  select = `photos/${src}`;
}

const saveMyHTML = function(html) {
  return html
       .replace(/&/g, '&amp;')
       .replace(/</g, '&lt;')
       .replace(/>/g, '&gt;')
       .replace(/"/g, '&quot;')
       .replace(/'/g, '&#039;');
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

  if (val == '' || val == null) return;

  const path = select.split('/');
  const imgPath = path[path.length - 1];
  const xhr = new XMLHttpRequest();

  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText != null && xhr.responseText != '') {
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
  xhr.send(`img=${imgPath}&comment=${val}`);
}

let l = [];
let d = [];

const add_like = function(src) {
  let span = document.querySelectorAll(`[data-src="${src}"]`)[0];
  // console.log(`like SPAN 1: ${span}`);
  span.innerHTML = eval(span.innerHTML * 1 + 1);
  l[src] = true;

  if (d == [] || d[src] == undefined || d[src] == null) return;

  span = document.querySelectorAll(`[data-src="${src}"]`)[1];
  // console.log(`like SPAN 2: ${span}`);
  span.innerHTML = eval(span.innerHTML * 1 - 1);
  d[src] = null;
}

const add_dislike = function(src) {
  let span = document.querySelectorAll(`[data-src="${src}"]`)[1];
  // console.log(`dislike SPAN 1: ${span}`);
  
  span.innerHTML = eval(span.innerHTML * 1 + 1);
  d[src] = true;
  
  if (l == [] || l[src] == undefined || l[src] == null) return;
  
  span = document.querySelectorAll(`[data-src="${src}"]`)[0];
  // console.log(`dislike SPAN 2: ${span}`);
  span.innerHTML = eval(span.innerHTML * 1 - 1);
  l[src] = null;
}

for (let i = 0; i < likes.length; i++) {
  likes[i].onclick = function(e) {
    const src = (e.srcElement && e.srcElement.getAttribute('data-image') || e.target.getAttribute('data-image'));
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      // console.log(xhr.responseText);
      if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText != null && xhr.responseText == 'add') {
        add_like(src);
      } else if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText != null && xhr.responseText == 'new') {
        d[src] = true;
        add_like(src);
      }
    };
    xhr.open('POST', './forms/like.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`img=${src}&type=L`);
  }
}

for (let i = 0; i < dislikes.length; i++) {
  dislikes[i].onclick = function(e) {
    const src = (e.srcElement && e.srcElement.getAttribute('data-image') || e.target.getAttribute('data-image'));
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText != null && xhr.responseText == 'add') {
        add_dislike(src);
      } else if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText != null && xhr.responseText == 'new') {
        l[src] = true;
        add_dislike(src);
      }
    };
    xhr.open('POST', './forms/like.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`img=${src}&type=D`);
  }
}


function like(el) {
  const src = el.getAttribute('data-image');
  const xhr = new XMLHttpRequest();
  
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText != null && xhr.responseText == 'add') {
      add_like(src);
    } else if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText != null && xhr.responseText == 'new') {
      d[src] = true;
      add_like(src);
    }
  };
  xhr.open('POST', './forms/like.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send(`img=${src}&type=L`);
}

function dislike(el) {
  const src = el.getAttribute('data-image');
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText != null && xhr.responseText == 'add') {
      add_dislike(src);
    } else if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText != null && xhr.responseText == 'new') {
      l[src] = true;
      add_dislike(src);
    }
  };
  xhr.open('POST', './forms/like.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send(`img=${src}&type=D`);
}

const loadMore = function(id, iPP) {
  if (last != null) {
    id = last;
  }

  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0) && xhr.responseText != null && xhr.responseText != '') {
      if (xhr.responseText === 'error') return;
        
      
      // console.log(xhr.responseText);
      const res = JSON.parse(xhr.responseText);
      // console.log(res);
      last = res[Object.keys(res).length - 2]['id'];
      for (let i = 0; res[i]; i++) {
        const div = document.createElement('div');
        let html = '';

        for (let j = 0; res[i]['comments'] != null && res[i]['comments'][j] != null; j++) {
          html += `<span class="comment">${saveMyHTML(res[i]['comments'][j]['username'])}: ${saveMyHTML(res[i]['comments'][j]['comment'])}</span>`;
        }

        div.innerHTML =
        `<img onclick="showWin('${res[i]['img']}');" class="right__photos-min del" src="photos/${res[i]['img']}">
        <div id="like_btn">
          <img onclick="like(this);" class="like_btn" src="img/like.png" data-image="${res[i]['img']}">
          <span class="like_count" data-src="${res[i]['img']}">${res[i]['likes']}</span>
          <img onclick="dislike(this);" class="dislike_btn" src="img/dislike.png" data-image="${res[i]['img']}">
          <span class="dislike_count" data-src="${res[i]['img']}">${res[i]['dislikes']}</span>
          ${html}
        </div>`;
        div.className = 'img';
        div.setAttribute('data-img', res[i]['img']);
        feed.appendChild(div);
      }
      if (typeof(res['more']) === 'undefined') {
        more.style.display = "none";
      }
    }
  };
  xhr.open('POST', './forms/more.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.send(`id=${id}&num=${iPP}`);
}