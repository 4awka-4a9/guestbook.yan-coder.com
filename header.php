<!doctype html>
<html lang="en" data-bs-theme="auto">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?php echo $description;?>" />
    <meta
      name="author"
      content="yan-coder"
    />
    <meta name="generator" content="Astro v5.13.2" />
    <title><?php echo $title;?></title>

    <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">

    <link
      rel="canonical"
      href="https://getbootstrap.com/docs/5.3/examples/pricing/"
    />
    <script src="js/color-modes.js"></script>
    
    <!-- Popperjs -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <!-- Tempus Dominus JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/js/tempus-dominus.min.js" crossorigin="anonymous"></script>

    <!-- Tempus Dominus Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/css/tempus-dominus.min.css" crossorigin="anonymous">

    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="theme-color" content="#712cf9" />
    <link href="css/main.css" rel="stylesheet" />
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }
      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      .b-example-divider {
        width: 100%;
        height: 3rem;
        background-color: #0000001a;
        border: solid rgba(0, 0, 0, 0.15);
        border-width: 1px 0;
        box-shadow:
          inset 0 0.5em 1.5em #0000001a,
          inset 0 0.125em 0.5em #00000026;
      }
      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }
      .bi {
        vertical-align: -0.125em;
        fill: currentColor;
      }
      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }
      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }
      .btn-bd-primary {
        --bd-violet-bg: #712cf9;
        --bd-violet-rgb: 112.520718, 44.062154, 249.437846;
        --bs-btn-font-weight: 600;
        --bs-btn-color: var(--bs-white);
        --bs-btn-bg: var(--bd-violet-bg);
        --bs-btn-border-color: var(--bd-violet-bg);
        --bs-btn-hover-color: var(--bs-white);
        --bs-btn-hover-bg: #6528e0;
        --bs-btn-hover-border-color: #6528e0;
        --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
        --bs-btn-active-color: var(--bs-btn-hover-color);
        --bs-btn-active-bg: #5a23c8;
        --bs-btn-active-border-color: #5a23c8;
      }
      .bd-mode-toggle {
        z-index: 1500;
      }
      .bd-mode-toggle .bi {
        width: 1em;
        height: 1em;
      }
      .bd-mode-toggle .dropdown-menu .active .bi {
        display: block !important;
      }
      #comments-header{ text-align: center;}
      #comments-form{border: 1px dotted black; width: 50%; padding-left: 20px;}
      #comments-form textarea{width: 70%; min-height: 100px;}
      #comments-panel{border: 1px dashed black; width: 50%; padding-left: 20px; margin-top: 20px;}
      .comment-date{font-style: italic;}
      
      .card {
        margin-top: 10px;
        margin-bottom: 10px;
      }

      .textarea {
        resize: none;
      }

      .commentsTitle {
        margin-top: 10px;
        margin-bottom: 10px;
      }

      .avatar {
        object-fit: cover;
        width: 30px;
        height: 30px;
        border-radius: 100px;
      }

      .m-t-b {
        margin-top: 5px;
        margin-bottom: 5px;
      }

    </style>

  </head>
  <body>

    <div class="container py-3">
      <header>
        <div
          class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom"
        >
          <a
            href="index.php"
            class="d-flex align-items-center link-body-emphasis text-decoration-none"
          >
            <span class="fs-4">Guest Book</span>
          </a>
          <nav class="d-inline-flex mt-2 mt-md-0 ms-md-auto">
            <a
              class="btn  btn-outline-secondary me-3 py-2 link-body-emphasis text-decoration-none"
              href="index.php"
              >Home</a
            >
            <a
              class="btn btn-outline-secondary me-3 py-2 link-body-emphasis text-decoration-none"
              href="profile.php"
              >Edit profile</a
            >
            <a
              class="btn  btn-outline-secondary me-3 py-2 link-body-emphasis text-decoration-none"
              href="logout.php"
              >Log out</a
            >

          </nav>
        </div>
      </header>