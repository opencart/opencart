import { WebComponent } from '../component.js';

class ButtonText extends WebComponent {
    static observed = ['state'];
    text = '';
    width = 0;

    render() {
        let icon = '';
        let type = this.getAttribute('type');
        let state = this.getAttribute('state');

        return '<button type="button" class="btn btn-' + type + '" data-bind="button"><i class="fa ' + icon + '"></i> ' + this.innerHTML + '</button>';
    }
}

customElements.define('button-text', ButtonText);

class ButtonSubmit extends WebComponent {
    static observed = ['state'];
    text = '';
    width = 0;

    get state() {
        return this.getAttribute('state');
    }

    set state(value) {
        if (value == 'loading') {

            this.$button.disabled = true;

            this.text = this.$button.innerHTML;

            console.log(this.$button.offsetWidth);

           // this.button.width(this.button.offsetWidth);

            this.$button.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-light"></i>';

        }

        if (value == 'reset') {
            this.$button.disabled = false;

            this.$button.innerHTML = this.text;
            //this.button.4

        }

        this.setAttribute('state', value);
    }

    render() {
        let icon = '';
        let type = this.getAttribute('type');
        let state = this.getAttribute('state');

        return '<button type="submit" class="btn btn-' + type + '" data-bind="button"><i class="fa ' + icon + '"></i> ' + this.innerHTML + '</button>';
    }

    click(e) {
        /*
        let element = this;

        if (state == 'loading') {
            this.html = $(element).html();

            $(element).width($(element).width()).html('<i class="fa-solid fa-circle-notch fa-spin text-light"></i>');
        }

        if (state == 'reset') {
            $(element).width('').html(this.html);
        }

        // If button
        if ($(element).is('button')) {
            if (state == 'loading') {
                this.state = $(element).prop('disabled');

                $(element).prop('disabled', true);
            }

            if (state == 'reset') {
                $(element).prop('disabled', this.state);
            }
        }

        // If link
        if ($(element).is('a')) {
            if (state == 'loading') {
                this.state = $(element).hasClass('disabled');

                $(element).addClass('disabled');
            }

            if (state == 'reset') {
                if (this.state) {
                    $(element).addClass('disabled');
                } else {
                    $(element).removeClass('disabled');
                }
            }
        }
        */
    }
}

class ButtonLink extends WebComponent {
    static observed = ['state'];
    text = '';
    width = 0;

    render() {
        let icon = '';
        let type = this.getAttribute('type');
        let state = this.getAttribute('state');



        return '<a href="" class="btn btn-' + type + '" data-bind="button"><i class="fa ' + icon + '"></i> ' + this.innerHTML + '</a>';
    }
}

customElements.define('button-link', ButtonLink);