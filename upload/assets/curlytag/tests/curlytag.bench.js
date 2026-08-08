import { bench, describe } from 'vite-plus/test';
import { template } from '#curlytag';

describe('CurlyTag performance', () => {
    const simpleTemplate = 'Hello {{ name }}!';
    const simpleData = { name: 'World' };

    bench('simple variable interpolation', () => {
        template.parse(simpleTemplate, simpleData);
    });

    const filterTemplate = '{{ name | upper | truncate: 5, "." }}';

    bench('chained filters', () => {
        template.parse(filterTemplate, { name: 'alice wonderland' });
    });

    const loopTemplate = '{% for item in items %}{{ item }}{% endfor %}';
    const loopData = { items: Array.from({ length: 100 }, (_, i) => `item${i}`) };

    bench('for loop (100 items)', () => {
        template.parse(loopTemplate, loopData);
    });

    const conditionalTemplate = '{% if a %}A{% elseif b %}B{% elseif c %}C{% else %}D{% endif %}';

    bench('if / elseif / else chain', () => {
        template.parse(conditionalTemplate, { a: false, b: false, c: true });
    });

    const complexTemplate = `
        {% for user in users %}
            {% if user.active %}
                {{ user.name | upper }} ({{ user.age }})
            {% else %}
                [inactive]
            {% endif %}
        {% endfor %}
    `;
    const complexData = {
        users: Array.from({ length: 50 }, (_, i) => ({
            name: `user${i}`,
            age: 20 + i,
            active: i % 2 === 0,
        })),
    };

    bench('complex template (for + if + filters, 50 items)', () => {
        template.parse(complexTemplate, complexData);
    });

    bench('tokenize only', () => {
        template.tokenize(complexTemplate);
    });
});
