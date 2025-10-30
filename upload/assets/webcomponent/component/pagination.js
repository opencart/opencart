import { WebComponent } from './../webcomponent.js';

class XPagination extends WebComponent {
    href = '';
    target = '';
    page = 1;
    limit = 10;
    total = 0;
    num_links = 8;
    num_pages = 0;
    first = '';
    last = '';
    next = '';
    prev = '';
    links = [];

    event = {
        connected: async () => {
            this.href = this.getAttribute('href');
            this.target = this.getAttribute('target');
            this.page = this.getAttribute('page');
            this.limit = this.getAttribute('limit');
            this.total = this.getAttribute('total');
            this.num_pages = Math.ceil(this.total / this.limit);

            if (this.page > 1) {
                this.first = this.href.replace('/page/', 1);

                if ((this.page - 1) === 1) {
                    this.prev = this.href.replace('/=/', 1);
                } else {
                    this.prev = this.href.replace('/=/', this.page - 1);
                }
            }

            let start = ((this.page - 1) * this.limit);
            let end = (start > (this.num_pages - this.limit)) ? this.num_pages : (start + this.limit);

            for (let i = start; i < end; i++) {
                this.links[i] = {
                    page: (i + 1),
                    href: this.href
                };
            }

            if (this.num_pages > this.page) {
                this.next = this.href.replace('/=/', this.page + 1);
                this.last = this.href.replace('/=/', this.num_pages);
            } else {
                this.next = '';
                this.last = '';
            }

            if (this.num_pages > 1) {
                this.event.render();
            }
        },
        render: () => {
            let html = '<ul class="pagination">';

            if (this.first) {
                html += '<li class="page-item"><a href="' + this.first +'" class="page-link">|&lt;</a></li>';
            }

            if (this.prev) {
                html += '<li class="page-item"><a href="' + this.prev +'" class="page-link">&lt;</a></li>';
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
        }
    };
}

customElements.define('x-pagination', XPagination);