export const factory = {
    config: async (registry) => {
        return new Config();
    },
    storage: (registry) => {
        return new Storage();
    },
    language: (registry) => {
        return new Language();
    },
    template: (registry) => {
        return new Template();
    },
    url: () => {
        return new Url();
    },
    session: async () => {
        return new Session();
    },
    local: () => {
        return new Local();
    },
    db: () => {
        return new Db();
    },
    cart: async (registry) => {
        return new Cart(registry);
    },
    tax: async (registry) => {
        let tax_classes = await registry.get('storage').fetch('localisation/tax_class');

        return new Tax(tax_classes);
    },
    currency: async (registry) => {
        let currencies = await registry.get('storage').fetch('localisation/currency');

        return new Currency(currencies);
    }
};