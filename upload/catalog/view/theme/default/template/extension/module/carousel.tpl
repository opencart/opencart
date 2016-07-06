<div id="carousel{{ module }}" class="owl-carousel">
 {% for banner in banners %}
  <div class="item text-center">
    {% if banner.link %}
    <a href="{{ banner.link }}"><img src="{{ banner.image }}" alt="{{ banner.title }}" class="img-responsive" /></a>
    {% else %}
    <img src="{{ banner.image }}" alt="{{ banner.title }}" class="img-responsive" />
    {% endif %}
  </div>
  {% endfor %}
</div>
<script type="text/javascript"><!--
$('#carousel{{ module }}').owlCarousel({
	items: 6,
	autoPlay: 3000,
	navigation: true,
	navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
	pagination: true
});
--></script>