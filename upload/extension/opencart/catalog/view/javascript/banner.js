$(document).ready(function() {
    new bootstrap.Carousel(document.querySelector('#carousel-banner-'), {
        ride: 'carousel',
        interval: interval,
        wrap: true
    });
});