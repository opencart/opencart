// Currency
local.get('currency');

const currency = async () => {
    let form = document.getElementById('form-currency');

    console.log(form);

    let elements = form.querySelectorAll('a');

    elements.forEach((element) => {

    });

    currency.addEventListener('click', async (e) => {
        let element = this;

        let code = $(element).attr('href');

        registry.local.set('currency', code);
    });

    const currencies = await registry.storage.fetch('localisation/currency');

};