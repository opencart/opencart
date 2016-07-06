{% if reviews %}
{% for review in reviews %}
<table class="table table-striped table-bordered">
  <tr>
    <td style="width: 50%;"><strong>{{ review.author }}</strong></td>
    <td class="text-right">{{ review.date_added }}</td>
  </tr>
  <tr>
    <td colspan="2"><p>{{ review.text }}</p>
      <?php for ($i = 1; $i <= 5; $i++) { ?>
      {% if review['rating'] < $i %}
      <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
      {% else %}
      <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
      <?php } ?>
      <?php } ?></td>
  </tr>
</table>
<?php } ?>
<div class="text-right">{{ pagination }}</div>
{% else %}
<p>{{ text_no_reviews }}</p>
<?php } ?>
