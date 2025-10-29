$('#range a').on('click', function(e) {
    e.preventDefault();

    $(this).parent().find('a').removeClass('active');

    $(this).addClass('active');

    $.ajax({
        type: 'get',
        url: 'index.php?route=extension/opencart/dashboard/chart.chart&user_token={{ user_token }}&range=' + $(this).attr('href'),
        dataType: 'json',
        success: function(json) {
            if (typeof json['order'] == 'undefined') {
                return false;
            }

            var option = {
                shadowSize: 0,
                colors: ['#9FD5F1', '#1065D2'],
                bars: {
                    show: true,
                    fill: true,
                    lineWidth: 1
                },
                grid: {
                    backgroundColor: '#FFFFFF',
                    hoverable: true
                },
                points: {
                    show: false
                },
                xaxis: {
                    show: true,
                    ticks: json['xaxis']
                }
            }

            $.plot('#chart-sale', [json['order'], json['customer']], option);

            $('#chart-sale').bind('plothover', function(event, pos, item) {
                $('.tooltip').remove();

                if (item) {
                    var element = document.querySelector("#chart-sale");
                    var rect = element.getBoundingClientRect();
                    var elementTop = rect.top + window.scrollY;
                    var elementLeft = rect.left + window.scrollX;

                    $('<div id="tooltip" class="tooltip top show"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('#chart-sale');

                    $('#tooltip').css({
                        position: 'absolute',
                        top: (elementTop - item.pageY) + 50,
                        left: (item.pageX - elementLeft),
                        pointer: 'cursor'
                    }).fadeIn('slow');

                    $('#chart-sale').css('cursor', 'pointer');
                } else {
                    $('#chart-sale').css('cursor', 'auto');
                }
            });
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#range a.active').trigger('click');