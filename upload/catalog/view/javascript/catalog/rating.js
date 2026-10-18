import { WebComponent } from '../index.js';

customElements.define('review-rating', class extends WebComponent {
    async render() {
        let data = new Map();
        {% for i in (1..5) %}
{% if rating < i %}
<span class="fa-stack"><i class="fa-regular fa-star fa-stack-1x"></i></span>
{% else %}
<span class="fa-stack"><i class="fa-solid fa-star fa-stack-1x"></i><i class="fa-regular fa-star fa-stack-1x"></i></span>
{% endif %}
{% endfor %}
return `<div class="rating">{% for i in 1..5 %}{% if rating < i %} <span class="fa-stack"><i class="fa-regular fa-star fa-stack-1x"></i></span> {% else %} <span class="fa-stack"><i class="fa-solid fa-star fa-stack-1x"></i><i class="fa-regular fa-star fa-stack-1x"></i></span> {% endif %}{% endfor %}</div>`;
    }
});




