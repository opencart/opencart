import { WebComponent } from './../webcomponent.js';

class XPagination extends WebComponent {
    href = '';
    target = '';
    limit = 10;
    total = 0;
    num_links = 8;
    num_pages = 0;
    first = [];
    last = [];
    next = [];
    prev = [];
    links = [];

    get page() {
        return Number(this.getAttribute('page'));
    }

    set page(value) {
        this.setAttribute('page', value);
    }

    event = {
        connected: async () => {
            this.href = this.getAttribute('href');
            this.target = this.getAttribute('target');
            this.page = this.getAttribute('page');
            this.limit = this.getAttribute('limit');
            this.total = this.getAttribute('total');
            this.num_pages = Math.ceil(this.total / this.limit);

            if (this.page > 1) {
                this.first = {
                    page: 1,
                    href: this.href.replace('{page}', 1)
                };

                if ((this.page - 1) === 1) {
                    this.prev = {
                        page: 1,
                        href: this.href.replace('{page}', 1)
                    };
                } else {
                    this.prev = {
                        page: (this.page - 1),
                        href: this.href.replace('{page}', (this.page - 1))
                    };
                }
            }

            let start = ((this.page - 1) * this.limit);
            let end = (start > (this.num_pages - this.limit)) ? this.num_pages : (start + this.limit);

            if (end > this.num_links) {
                end = start + this.num_links;
            }

            for (let i = start; i < end; i++) {
                this.links[i] = {
                    page: (i + 1),
                    href: this.href.replace('{page}', (i + 1))
                };
            }

            if (this.num_pages > this.page) {
                this.next = {
                    page: this.page + 1,
                    href: this.href.replace('{page}', this.page + 1)
                };

                this.last = {
                    page: this.num_pages,
                    href: this.href.replace('{page}', this.num_pages)
                };
            }

            this.event.render();
        },
        render: () => {
            if (this.num_pages > 1) {
                let html = '<ul class="pagination">';

                if (this.first.length) {
                    html += '<li class="page-item"><a href="' + this.first.href +'" data-value="1" class="page-link">|&lt;</a></li>';
                }

                if (this.prev.length) {
                    html += '<li class="page-item"><a href="' + this.prev.href +'" data-value="' + this.prev.page +'" class="page-link">&lt;</a></li>';
                }

                for (let i in this.links) {
                    if (this.links[i].page == this.page) {
                        html += '<li class="page-item active"><span class="page-link">' + this.links[i].page + '</span></li>';
                    } else {
                        html += '<li class="page-item"><a href="' + this.links[i].href + '" data-value="' + this.links[i].page + '" class="page-link">' + this.links[i].page + '</a></li>';
                    }
                }

                if (this.next.length) {
                    html += '<li class="page-item"><a href="' + this.next.href + '" data-value="' + this.next.page + '" class="page-link">&gt;</a></li>';
                }

                if (this.last.length) {
                    html += '<li class="page-item"><a href="' + this.last.href + '" data-value="' + this.last.page + '" class="page-link">&gt;|</a></li>';
                }

                html += '</ul>';

                this.innerHTML = html;

                let event = this.querySelector('a');

                event.addEventListener('click', this.event.onclick);
            }
        },
        fetch: async (url) => {
            let response = await fetch(url);

            console.log(response);

            if (response.status == 200) {
                return response.text();
            }
        },
        onclick: async (e) => {
            e.preventDefault();

            this.event.fetch(e.target.getAttribute('href')).then(this.event.onload);

            console.log(e.target.getAttribute('href'));
        },
        onload: (html) => {
            let element = document.querySelector(this.target);

            element.innerHTML = html;

            console.log(html);
            console.log(element);

        }
    };
}

customElements.define('x-pagination', XPagination);