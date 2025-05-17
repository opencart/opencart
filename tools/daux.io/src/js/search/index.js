import { Document } from "flexsearch";
import * as preact from "preact";
import Search from "./Search";

/** @jsx preact.h */

const originalTitle = document.title;

function getURLP(name) {
    const elements = new RegExp(`[?|&]${name}=([^&;]+?)(&|#|;|$)`).exec(
        window.location.search
    );

    return (
        decodeURIComponent((elements?.[1] || "").replace(/\+/g, "%20")) || null
    );
}

class SearchEngine {
    constructor(options) {
        this.settings = {
            field: document.getElementById("search_input"),
            form: document.getElementById("search_form"),
            show: 10,
            showURL: true,
            showTitleCount: true,
            minimumLength: 3,
            descriptiveWords: 25,
            highlightTerms: true,
            highlightEveryTerm: false,
            contentLocation: "daux_search_index.js",
            ...options,
        };

        this.searchIndex = {
            pages: [],
        };
    }

    loadData() {
        if (!this.loadingPromise) {
            const po = document.createElement("script");
            po.type = "text/javascript";
            po.async = true;
            po.src = this.settings.base_url + this.settings.contentLocation;
            const s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(po, s);

            this.loadingPromise = new Promise((resolve) => {
                window.load_search_index = (data) => resolve(data);
            }).then((json) => {
                this.searchIndex = new Document({
                    doc: {
                        id: "url",
                        tag: "tags",
                        field: ["title", "text"],
                        store: ["title", "text"],
                    },
                });

                let pages = json.pages;

                if (window.searchLanguage) {
                    const pagePrefix = `${window.searchLanguage}/`;
                    pages = pages.filter(
                        (item) => item.url.indexOf(pagePrefix) === 0
                    );
                }

                for (const page of pages) {
                    this.searchIndex.add(page);
                }
            });
        }

        return this.loadingPromise;
    }

    run() {
        const query = getURLP("q");
        if (query) {
            this.settings.field.value = query;

            this.loadData().then(() => {
                this.displaySearch();
            });
        }

        window.addEventListener("popstate", () => {
            const query = getURLP("q");
            if (query) {
                this.settings.field.value = query;

                this.loadData().then(() => {
                    this.displaySearch();
                });
            } else {
                this.handleClose();
            }
        });

        this.settings.field.addEventListener("keyup", (event) => {
            this.loadData();

            if (parseInt(event.keyCode, 10) === 13) {
                const query = this.settings.field.value.trim();
                if (query.length >= this.settings.minimumLength) {
                    history.pushState(
                        null,
                        "",
                        `?q=${encodeURIComponent(query)}`
                    );
                    this.loadData().then(() => {
                        this.displaySearch();
                    });
                }
            }
        });

        this.settings.form.addEventListener("submit", (event) => {
            event.preventDefault();

            const query = this.settings.field.value.trim();
            if (query.length >= this.settings.minimumLength) {
                history.pushState(null, "", `?q=${encodeURIComponent(query)}`);
                this.loadData().then(() => {
                    this.displaySearch();
                });
            }
        });
    }

    keyUpHandler = (e) => {
        if (e.which === 27) {
            this.handleClose();
        }
    };

    handleClose = () => {
        document.title = originalTitle;

        document.removeEventListener("keyup", this.keyUpHandler);

        document.body.classList.remove("with-search");
        preact.render(null, this.resultContainer);
        this.resultContainer = null;

        this.settings.field.value = "";
        history.pushState(null, "", window.location.pathname);
    };

    displaySearch() {
        if (!this.resultContainer) {
            this.resultContainer = document.createElement("div");
            document.body.appendChild(this.resultContainer);
        }

        document.addEventListener("keyup", this.keyUpHandler);

        const searchTerm = this.settings.field.value;

        preact.render(
            <Search
                onSearch={(term) =>
                    this.searchIndex.search(term, { enrich: true })
                }
                onClose={this.handleClose}
                onTitleChange={(title) => {
                    document.title = `${title} ${originalTitle}`;
                }}
                settings={this.settings}
                searchTerm={searchTerm}
            />,
            this.resultContainer
        );

        document.body.classList.add("with-search");
        document.body.scrollTop = 0;
    }
}

function search(options) {
    const instance = new SearchEngine(options);
    instance.run();
}

window.search = search;
