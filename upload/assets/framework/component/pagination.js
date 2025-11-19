import { WebComponent } from './../library/webcomponent.js';

class XPagination extends WebComponent {
    href = '';
    target = '';
    limit = 10;
    total = 0;
    num_links = 8;
    num_pages = 0;
    first = '';
    last = '';
    next = '';
    prev = '';
    links = [];

    get page() {
        return parseInt(this.getAttribute('page'));
    }

    set page(value) {
        this.setAttribute('page', value);
    }

    event = {
        connected: async () => {
            this.href = this.getAttribute('href');
            this.target = this.getAttribute('target');
            this.limit = this.getAttribute('limit');
            this.total = this.getAttribute('total');
            this.num_pages = Math.ceil(this.total / this.limit);

            if (this.page > 1) {
                this.first = this.href.replace('{page}', 1);

                if ((this.page - 1) === 1) {
                    this.prev = this.href.replace('{page}', 1);
                } else {
                    this.prev = this.href.replace('{page}', (this.page - 1));
                }
            }

            let start = 0;
            let end = 0;

            if (this.num_pages <= this.num_links) {
                start = 1;
                end = this.num_pages;
            } else {
                start = this.page - Math.floor(this.num_links / 2);
                end = this.page + Math.floor(this.num_links / 2);
            }

            if (start < 1) {
                start = 1;
                end += Math.abs(start) + 1;
            }

            if (end > this.num_pages) {
                start -= (end - this.num_pages);
                end = this.num_pages;
            }

            for (let i = start; i <= end; i++) {
                this.links[i] = {
                    page: i,
                    href: this.href.replace('{page}', i)
                };
            }

            if (this.num_pages > this.page) {
                this.next = this.href.replace('{page}', this.page + 1);
                this.last = this.href.replace('{page}', this.num_pages);
            }

            this.event.render();
        },
        render: () => {
            if (this.num_pages > 1) {
                let html = '<ul class="pagination">';

                if (this.first) {
                    html += '<li class="page-item"><a href="' + this.first +'" class="page-link">|&lt;</a></li>';
                }

                if (this.prev) {
                    html += '<li class="page-item"><a href="' + this.prev + '" class="page-link">&lt;</a></li>';
                }

                for (let i in this.links) {
                    if (this.links[i].page == this.page) {
                        html += '<li class="page-item active"><span class="page-link">' + this.links[i].page + '</span></li>';
                    } else {
                        html += '<li class="page-item"><a href="' + this.links[i].href + '" class="page-link">' + this.links[i].page + '</a></li>';
                    }
                }

                if (this.next) {
                    html += '<li class="page-item"><a href="' + this.next + '" class="page-link">&gt;</a></li>';
                }

                if (this.last) {
                    html += '<li class="page-item"><a href="' + this.last + '" class="page-link">&gt;|</a></li>';
                }

                html += '</ul>';

                this.innerHTML = html;

                this.querySelectorAll('a').forEach((link) => link.addEventListener('click', this.event.onclick));
            }
        },
        onclick: async (e) => {
            e.preventDefault();

            this.event.fetch(e.target.getAttribute('href')).then(this.event.onload);
        },
        fetch: async (url) => {
            let response = await fetch(url);

            if (response.status == 200) {
                return response.text();
            }
        },
        onload: (html) => {
            let element = document.querySelector(this.target);

            element.innerHTML = html;
        }
    };
}

customElements.define('x-pagination', XPagination);