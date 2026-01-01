class XAddressInfo extends WebComponent {
    data = [];

    async connected() {
        this.load.language('account/address');

        this.innerHTML = this.load.template('account/address', this.language.all());
    }
}

customElements.define('x-address', XAddress);