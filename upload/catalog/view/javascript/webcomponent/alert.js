class XAlert extends HTMLElement {
    constructor() {
        super();

        this.shadow = this.attachShadow({
            mode: 'open'
        });
    }

    connectedCallback() {
        this.shadow.innerHTML = this.template;

        if ($(this).hasClass('alert-dismissible')) {
            window.setTimeout(function() {
                $(this).fadeTo(3000, 0, function() {
                    $(this).remove();
                });
            }, 3000);
        }
    }
}

class XAlertSuccess extends XAlert {
    template = '<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + this.innerHTML + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

customElements.define('x-alert-success', XAlertSuccess);

class XAlertDanger extends XAlert {
    template = '<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + this.innerHTML + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

customElements.define('x-alert-danger', XAlertDanger);

class XAlertWarning extends XAlert {
    template = '<div class="alert alert-warning alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + this.innerHTML + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

customElements.define('x-alert-warning', XAlertWarning);

class XAlertInfo extends XAlert {
    template = '<div class="alert alert-info alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + this.innerHTML + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

customElements.define('x-alert-info', XAlertInfo);