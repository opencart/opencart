import { WebComponent } from '../../engine.js';

customElements.define('button-submit', class extends WebComponent {
    observed = [
        'loading',
        'disabled'
    ];
    states = {
        loading: false,
        disabled: false
    };
    static formAssociated = true;
    html = '';
    width;

    render() {
        let html = '<button type="submit" @ref="button" class="btn btn-primary"';

        if (this.hasAttribute('disabled')) {
            html += ' disabled';
        }

        if (this.hasAttribute('loading')) {
            html += ' style="width: ' + this.width + 'px;"';
        }

        html += '>';

        if (this.hasAttribute('loading')) {
            html += '<i class="fa-solid fa-circle-notch fa-spin text-light"></i>';
        } else {
            html += this.html;
        }

        html += '</button>';

        return html;
    }

    handleConnect() {
        this.html = this.innerHTML;
        this.width = this.offsetWidth;
    }

    handleClick(e) {
        //this.handleState(['loading'])
    }

    HandleDisabled() {
        this.update();
    }

    handleLoading() {
        this.update();
    }
});