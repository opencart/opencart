<form action="{{ action }}" method="get">
  <input type="hidden" name="mid" value="{{ mid }}" />
  <input type="hidden" name="amt" value="{{ amt }}" />
  <input type="hidden" name="amt_editable" value="N" />
  <input type="hidden" name="currency" value="{{ currency }}" />
  <input type="hidden" name="ref" value="{{ ref }}" />
  <input type="hidden" name="pmt_sender_email" value="{{ pmt_sender_email }}" />
  <input type="hidden" name="pmt_contact_firstname" value="{{ pmt_contact_firstname }}" />
  <input type="hidden" name="pmt_contact_surname" value="{{ pmt_contact_surname }}" />
  <input type="hidden" name="pmt_contact_phone" value="{{ pmt_contact_phone }}" />
  <input type="hidden" name="pmt_country" value="{{ pmt_country }}" />
  <input type="hidden" name="regindi_address1" value="{{ regindi_address1 }}" />
  <input type="hidden" name="regindi_address2" value="{{ regindi_address2 }}" />
  <input type="hidden" name="regindi_sub" value="{{ regindi_sub }}" />
  <input type="hidden" name="regindi_state" value="{{ regindi_state }}" />
  <input type="hidden" name="regindi_pcode" value="{{ regindi_pcode }}" />
  <input type="hidden" name="return" value="{{ return }}" />
  <input type="hidden" name="popup" value="false" />
  <div class="buttons">
    <div class="pull-right"><input type="submit" value="{{ button_confirm }}" class="btn btn-primary" /></div>
  </div>
</form>
