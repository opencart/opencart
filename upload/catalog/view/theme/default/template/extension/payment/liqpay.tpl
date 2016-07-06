<form action="{{ action }}" method="post">
  <input type="hidden" name="operation_xml" value="{{ xml }}">
  <input type="hidden" name="signature" value="{{ signature }}">
  <div class="buttons">
    <div class="pull-right">
      <input type="submit" value="{{ button_confirm }}" class="btn btn-primary" />
    </div>
  </div>
</form>
