import { Document } from "flexsearch";
import * as preact from "preact";
import Search from "./Search";

/** @jsx preact.h */

const originalTitle = document.title;

function getURLP(name) {
    const elements = new RegExp(`[?|&]${name}=([^&;]+?)(&|#|;|$)`).exec(
        window.location.search,
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
            // We do this as jsonp instead of an XHR or fetch request
            // to be compatible with usage from filesystem
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

                // Only keep the pages related to the current language
                if (window.searchLanguage) {
                    const pagePrefix = `${window.searchLanguage}/`;
                    pages = pages.filter(
                        (item) => item.url.indexOf(pagePrefix) === 0,
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
        if (getURLP("q")) {
            this.settings.field.value = getURLP("q");

            this.loadData().then(() => {
                this.displaySearch();
            });
        }

        this.settings.field.addEventListener("keyup", (event) => {
            // Start loading index once the user types text in the field, not before
            this.loadData();

            if (parseInt(event.keyCode, 10) === 13) {
                this.loadData().then(() => {
                    this.displaySearch();
                });
            }
        });

        this.settings.form.addEventListener("submit", (event) => {
            event.preventDefault();
            this.loadData().then(() => {
                this.displaySearch();
            });
        });
    }

    keyUpHandler = (e) => {
        if (e.which === 27) {
            //escape
            this.handleClose();
        }
    };

    handleClose = () => {
        document.title = originalTitle;

        document.removeEventListener("keyup", this.keyUpHandler);

        document.body.classList.remove("with-search");
        preact.render(null, this.resultContainer);
        this.resultContainer = null;
    };

    displaySearch() {
        if (!this.resultContainer) {
            this.resultContainer = document.createElement("div");
            document.body.appendChild(this.resultContainer);
        }

        document.addEventListener("keyup", this.keyUpHandler);

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
            />,
            this.resultContainer,
        );

        document.body.classList.add("with-search");
        document.body.scrollTop = 0;
    }
}

// Main containers

function search(options) {
    const instance = new SearchEngine(options);
    instance.run();
}

// Declare globally
window.search = search;
