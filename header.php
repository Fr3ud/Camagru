<header id="header">
  <h1 class="logo"><a href="/camagru/index.php">Camagru</a></h1>
  <nav>
    <?php if (isset($_SESSION['id'])) { ?>
      <div class="button" onclick="location.href='gallery.php'">
        <span>Gallery</span>
      </div>
      <div class="button" onclick="location.href='feed.php'">
        <span>Views</span>
      </div>
      <div class="button" onclick="location.href='forms/logout.php'">
        <span>Logout</span>
      </div>
    <?php } else { ?>
      <div class="button" onclick="location.href='index.php'">
        <span>Login</span>
      </div>
    <?php } ?>
    </nav>
</header>