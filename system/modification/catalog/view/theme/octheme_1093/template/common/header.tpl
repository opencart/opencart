<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
<link href="catalog/view/theme/octheme_1093/stylesheet/stylesheet.css" rel="stylesheet">
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>

            <style type="text/css">
           body{
           <?php if ($backgroundimage) { ?>
           background-image: url(<?php echo $backgroundimage; ?>) ;
           background-repeat:<?php echo $repeatbackground; ?>;
          <?php } else { ?>
          <?php } ?>
           background-color: <?php echo $backgroundcolor; ?> ;
           color: <?php echo $text_color; ?>;
          }
          h1, h2, h3, h4, h5, h6 {
	      color: <?php echo $title_color; ?>;
          }
          a {
          color: <?php echo $link_color; ?>;
          }
          a:hover {
	      color: <?php echo $link_color_hover; ?>;
          }
          #top {
	     background-color: <?php echo $top_bar_background; ?>;
         }
         header{
         background-color: <?php echo $top_bar_background; ?>;
         }
          #top .btn-link, #top-links li, #top-links a {
	     color: <?php echo $top_bar_link_color; ?>;
         }
         #top .btn-link:hover, #top-links a:hover {
        	color: <?php echo $top_bar_link_color_hover; ?>;
         }
         #top .fa {
        	color: <?php echo $top_bar_link_icon; ?>;
         }

        #search .input-lg {
         background:<?php echo $searchbackground; ?>;
         color:<?php echo $searchcolor; ?>;
        }
         #search .btn-lg {
         color:<?php echo $button_searchcolor; ?>;
         background: <?php echo $button_searchbackground; ?>;
        }
        #cart > .btn {
    	color: <?php echo $cartlink; ?>;
        background:<?php echo $cartbackground; ?>;
          }
      #cart.open > .btn {
	   background-color: <?php echo $cartbackgroundopen; ?>;
     	color: <?php echo $cartlinkopen; ?>;
       }
       #cart > .btn:hover {
       background: <?php echo $cartbackgroundopen; ?>;
     	color: <?php echo $cartlinkopen; ?>;
      }
      #menu {
      background: <?php echo $menubackground; ?>;
      }
     #menu .nav > li > a {
	color: <?php echo $menulink; ?>;
     }
     #menu .nav > li > a:hover, #menu .nav > li.open > a {
	background: <?php echo $menulinkhoverbg; ?>;
    color: <?php echo $menulinkhover; ?>;
    }
    #menu .dropdown-menu {
	 background: <?php echo $menubackgrounddropdown; ?>;
}
#menu .dropdown-inner a {
	color: <?php echo $menulinkdropdown; ?>;
}
#menu .dropdown-inner li a:hover {
	color: <?php echo $menulinkdropdownhover; ?>;
    background: <?php echo $menulinkdropdownhoverbg; ?>;
}
#menu .see-all:hover, #menu .see-all:focus {
	color: <?php echo $menulinkdropdownhover; ?>;
    background: <?php echo $menulinkdropdownhoverbg; ?>;
}
#menu .btn-navbar:hover, #menu .btn-navbar:focus, #menu .btn-navbar:active, #menu .btn-navbar.disabled, #menu .btn-navbar[disabled] {
	color: <?php echo $menulinkdropdownhover; ?>;
    background: <?php echo $menulinkdropdownhoverbg; ?>;
}
footer {
    background: <?php echo $footerbg; ?>;
}
footer a {
	color: <?php echo $footerlink; ?>;
}
footer a:hover {
	color: <?php echo $footerlinkhover; ?>;
}
footer h5 {
	color: <?php echo $footertitle; ?>;
}
.product-thumb {
	background: <?php echo $productsbg; ?>;
}
.product-thumb:hover {
	background: <?php echo $productsbghover; ?>;
}
.product-thumb  h4 a{
	color: <?php echo $productstitle; ?>;
}
.product-thumb h4 a:hover {
	color: <?php echo $productstitlehover; ?>;
}
.product-thumb .button-group, .product-thumb .button-group button {
	background: <?php echo $buttoncartbg; ?>;
    color: <?php echo $buttoncarttext; ?>;
}
.product-thumb .button-group button:hover {
	background: <?php echo $buttoncartbghover; ?>;
    color: <?php echo $buttoncarttexthover; ?>;
}

.product-thumb .button-group button + button {
    color: <?php echo $buttoncartlike; ?>;
}
.product-thumb .button-group button + button:hover {
    color: <?php echo $buttoncartlikehover; ?>;
}
.product-thumb .btn-group .btn-default:hover {
    color: <?php echo $buttoncartlikehover; ?>;
}
.product-thumb .btn-group .btn-default {
    color: <?php echo $buttoncartlike; ?>;
}
.btn-primary {
   background: <?php echo $buttondefault; ?>;
}
.btn-primary:hover {
   background: <?php echo $buttondefaulthover; ?>;
}
.mfp-figure:after {
    background:<?php echo $popup_image; ?>;
    }
            </style>
            
<?php echo $google_analytics; ?>

					<link rel="stylesheet" type="text/css" media="screen,projection" href="catalog/view/javascript/backtotop/stylesheet/ui.totop.css" />
				
</head>
<body class="<?php echo $class; ?>">
<nav id="top">
  <div class="container">
    <?php echo $currency; ?>
    <?php echo $language; ?>
    <div id="top-links" class="nav pull-right">
      <ul class="list-inline">
        <li><a href="<?php echo $contact; ?>"><i class="fa fa-phone"></i></a> <span class="hidden-xs hidden-sm hidden-md"><?php echo $telephone; ?></span></li>
        <li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span> <span class="caret"></span></a>
          <ul class="dropdown-menu dropdown-menu-right">
            <?php if ($logged) { ?>
            <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
            <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
            <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
            <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
            <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
            <?php } else { ?>
            <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
            <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
            <?php } ?>
          </ul>
        </li>
        <li><a href="<?php echo $wishlist; ?>" id="wishlist-total" title="<?php echo $text_wishlist; ?>"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_wishlist; ?></span></a></li>
        <li><a href="<?php echo $shopping_cart; ?>" title="<?php echo $text_shopping_cart; ?>"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_shopping_cart; ?></span></a></li>
        <li><a href="<?php echo $checkout; ?>" title="<?php echo $text_checkout; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_checkout; ?></span></a></li>
      </ul>
    </div>
  </div>
</nav>
<header>
  <div class="container">
    <div class="row">
      <div class="col-sm-4">
        <div id="logo">
          <?php if ($logo) { ?>
          <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
          <?php } else { ?>
          <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
          <?php } ?>
        </div>
      </div>
      <div class="col-sm-5"><?php echo $search; ?>
      </div>
      <div class="col-sm-3"><?php echo $cart; ?></div>
    </div>
  </div>
</header>
<?php if ($categories) { ?>
<div class="container">
  <nav id="menu" class="navbar">
    <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>
      <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <ul class="nav navbar-nav">
        <?php foreach ($categories as $category) { ?>
        <?php if ($category['children']) { ?>
        <li class="dropdown"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['name']; ?></a>
          <div class="dropdown-menu">
            <div class="dropdown-inner">
              <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
              <ul class="list-unstyled">
                <?php foreach ($children as $child) { ?>
                <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
                <?php } ?>
              </ul>
              <?php } ?>
            </div>
            <a href="<?php echo $category['href']; ?>" class="see-all"><?php echo $text_all; ?> <?php echo $category['name']; ?></a> </div>
        </li>
        <?php } else { ?>
        <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
        <?php } ?>
        <?php } ?>
      </ul>
    </div>
  </nav>
</div>
<?php } ?>
