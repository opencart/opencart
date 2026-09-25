import { WebComponent } from '../index.js';

customElements.define('x-pagination', class extends WebComponent {
    static observed = [
        'href',
        'target',
        'limit',
        'total',
        'page'
    ];

    num_links = 8;

    get href() {
        return this.getAttribute('href');
    }

    set href(value) {
        this.setAttribute('href', value);
    }

    get target() {
        return this.getAttribute('target');
    }

    set target(value) {
        this.setAttribute('target', value);
    }

    get limit() {
        return parseInt(this.getAttribute('limit'));
    }

    set limit(value) {
        this.setAttribute('limit', value);
    }

    get total() {
        return parseInt(this.getAttribute('total'));
    }

    set total(value) {
        this.setAttribute('total', value);
    }

    get page() {
        return parseInt(this.getAttribute('page'));
    }

    set page(value) {
        this.setAttribute('page', value);
    }

    render() {
        let num_pages = Math.ceil(this.total / this.limit);

        let first = '';
        let prev = '';

        if (this.page > 1) {
            first = this.href.replace('{page}', 1);

            if ((this.page - 1) === 1) {
                prev = this.href.replace('{page}', 1);
            } else {
                prev = this.href.replace('{page}', (this.page - 1));
            }
        }

        let start = 0;
        let end = 0;

        if (num_pages <= this.num_links) {
            start = 1;
            end = num_pages;
        } else {
            start = this.page - Math.floor(this.num_links / 2);
            end = this.page + Math.floor(this.num_links / 2);
        }

        if (start < 1) {
            start = 1;
            end += Math.abs(start) + 1;
        }

        if (end > num_pages) {
            start -= (end - num_pages);
            end = num_pages;
        }

        let next = '';
        let last = '';

        if (num_pages > this.page) {
            next = this.href.replace('{page}', this.page + 1);
            last = this.href.replace('{page}', num_pages);
        }

        let html = '';

        if (num_pages > 1) {
            html += '<ul class="pagination">';

            if (first) {
                html += '<li class="page-item"><a href="' + first +'" data-on="click:onClick" class="page-link">|&lt;</a></li>';
            }

            if (prev) {
                html += '<li class="page-item"><a href="' + prev + '" data-on="click:onClick" class="page-link">&lt;</a></li>';
            }

            for (let i = start; i <= end; i++) {
                if (i == this.page) {
                    html += '<li class="page-item active"><span class="page-link">' + i + '</span></li>';
                } else {
                    html += '<li class="page-item"><a href="' + this.href.replace('{page}', i) + '" data-on="click:onClick" class="page-link">' + i + '</a></li>';
                }
            }

            if (next) {
                html += '<li class="page-item"><a href="' + next + '" data-on="click:onClick" class="page-link">&gt;</a></li>';
            }

            if (last) {
                html += '<li class="page-item"><a href="' + last + '" data-on="click:onClick" class="page-link">&gt;|</a></li>';
            }

            html += '</ul>';
        }

        return html;
    }

    async onClick(e) {
        e.preventDefault();

        let target = document.getElementById(this.target);

        target.src = this.href;
    }
});