{% if error_warning) { ?>
<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> {{ error_warning }}</div>
<?php } ?>
{% if shipping_methods) { ?>
<p>{{ text_shipping_method }}</p>
% for shipping_method in shipping_methods %}
<p><strong>{{ shipping_method.title }}</strong></p>
{% if !$shipping_method['error']) { ?>
<?php foreach ($shipping_method['quote'] as $quote) { ?>
<div class="radio">
  <label>
    {% if quote['code'] == $code || !$code) { ?>
    <?php $code = $quote['code']; ?>
    <input type="radio" name="shipping_method" value="{{ quote.code }}" checked="checked" />
    {% else %}
    <input type="radio" name="shipping_method" value="{{ quote.code }}" />
    <?php } ?>
    {{ quote.title }} - {{ quote.text }}</label>
</div>
<?php } ?>
{% else %}
<div class="alert alert-danger">{{ shipping_method.error }}</div>
<?php } ?>
<?php } ?>
<?php } ?>
<p><strong>{{ text_comments }}</strong></p>
<p>
  <textarea name="comment" rows="8" class="form-control">{{ comment }}</textarea>
</p>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="{{ button_continue }}" id="button-shipping-method" data-loading-text="{{ text_loading }}" class="btn btn-primary" />
  </div>
</div>
