<div id="footer">
  <?php if ($informations) { ?>
  <div class="column">
    <h3><?php echo $text_information; ?></h3>
    <ul>
      <?php foreach ($informations as $information) { ?>
      <li><a class="colorbox-info" rel="colorbox-info" href="<?php echo str_replace('http:', '', $information['href']); ?>"><?php echo $information['title']; ?></a></li>
      <?php } ?>
    </ul>
  </div>
  <?php } ?>
  <div class="column">
    <h3><?php echo $text_service; ?></h3>
    <ul>
      <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
      <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
      <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
    </ul>
  </div>
  <div class="column">
    <h3><?php echo $text_extra; ?></h3>
    <ul>
      <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
      <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
      <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
      <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
    </ul>
  </div>
  <div class="column">
    <h3><?php echo $text_account; ?></h3>
    <ul>
      <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
      <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
      <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
      <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
    </ul>
  </div>
</div>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
<div id="powered"><?php echo $powered; ?></div>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
</div>
<script type="text/javascript">
// The id of the element we will search in
var CONTAINER_ID = 'content';
// Higlight start tag
var HIGHLIGHT_START = '<span style="background-color: #ff0;">';
// Highlight end tag
var HIGHLIGHT_END = '</span>';

// DON'T CHANGE THIS !
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('$(a).x(p(){5(!a.n(l)){b s}4 k=z A(a.D);4 7,d,6;5(k.q(\'?\')==-1){b s}7=k.c(\'?\');7=7[1].c(\'&\');o(4 i B 7){d=7[i].c(\'=\');5(d[0]==\'y\'){6=C(d[1]);6=6.c(\' \');o(4 j=0;j<6.8;j++){5(6[j].8>=1){r(6[j])}}b h}}b h});p r(e){4 2=a.n(l).v;4 g=\'\';4 i=-1;4 w=e.m();4 9=2.m();H(2.8>0){i=9.q(w,i+1);5(i<0){g+=2;2=\'\'}E{5(2.f(\'>\',i)>=2.f(\'<\',i)){5(9.f(\'/u>\',i)>=9.f(\'<u\',i)){g+=2.G(0,i)+F+2.t(i,e.8)+I;2=2.t(i+e.8);9=2.m();i=-1}}}}a.n(l).v=g;b h}',45,45,'||bodyText||var|if|keywords|parts|length|lcBodyText|document|return|split|keyValue|searchTerm|lastIndexOf|newText|true|||href|CONTAINER_ID|toLowerCase|getElementById|for|function|indexOf|doHighlight|false|substr|script|innerHTML|lcSearchTerm|ready|filter_name|new|String|in|decodeURI|location|else|HIGHLIGHT_START|substring|while|HIGHLIGHT_END'.split('|'),0,{}));
</script>
<script type="text/javascript"><!--
$('.colorbox-info').colorbox({ width: "50%", speed:250});
//--></script>
</body></html>