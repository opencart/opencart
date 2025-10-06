import * as preact from "preact";

import Pagination from "./Pagination";
import Result from "./Result";
import {
    textSearchNoResults,
    textSearchOneCharacterOrMore,
    textSearchOneResult,
    textSearchResults,
    textSearchShouldBeXOrMore,
    textSearchTooShort,
} from "./translation";

/** @jsx preact.h */

export default class Search extends preact.Component {
    constructor(props) {
        super(props);

        this.state = {
            search: this.props.settings.field.value || "",
            start: 0,
        };
    }

    // "click", ".SearchResults__close"
    handleClose = () => {
        this.props.onClose();
    };

    scrollTop = () => {
        if (this.resultRef) {
            this.resultRef.scrollTop = 0;
        }
    };

    handlePaginate = (start) => {
        this.setState({ start }, this.scrollTop);
    };

    handleChange = (event) => {
        this.setState({ search: event.target.value, start: 0 }, this.scrollTop);

        this.props.settings.field.value = event.target.value;
    };

    getResults() {
        const { settings } = this.props;
        const { start } = this.state;

        const warnings = [];
        let counter = 0;
        let results = [];

        if (this.state.search.length < settings.minimumLength) {
            warnings.push(textSearchTooShort);
            warnings.push(
                settings.minimumLength === 1
                    ? textSearchOneCharacterOrMore
                    : textSearchShouldBeXOrMore.replace(
                          "!min",
                          settings.minimumLength,
                      ),
            );

            return { warnings, counter, results, start };
        }

        const found = Object.values(
            this.props
                .onSearch(this.state.search)
                .reduce((acc, fieldResult) => {
                    // FlexSearch returns results per field
                    // We de-duplicate them here and have a single array of results
                    for (const result of fieldResult.result) {
                        if (!acc.hasOwnProperty(result.id)) {
                            acc[result.id] = {
                                url: result.id,
                                title: result.doc.title,
                                text: result.doc.text,
                            };
                        }
                    }

                    return acc;
                }, {}),
        );

        counter = found.length;

        if (counter === 0) {
            warnings.push(textSearchNoResults);
            return { warnings, counter, results, start };
        }

        if (settings.showTitleCount) {
            this.props.onTitleChange(`(${counter})`);
        }

        results = found.filter(
            (item, itemNumber) =>
                itemNumber >= start && itemNumber < settings.show + start,
        );

        return { warnings, counter, results, start };
    }

    render() {
        const { settings } = this.props;
        const { warnings, counter, results, start } = this.getResults();

        return (
            <div>
                <div className="SearchResultsBackdrop" />
                <div
                    className="SearchResults"
                    ref={(el) => {
                        this.resultRef = el;
                    }}
                >
                    <input
                        className="Search__field"
                        placeholder="Search..."
                        autoComplete="on"
                        autoSave="text_search"
                        type="search"
                        value={this.state.search}
                        onInput={this.handleChange}
                    />
                    <button
                        type="button"
                        className="SearchResults__close"
                        onClick={this.handleClose}
                    >
                        &times;
                    </button>
                    <div className="SearchResults__count">
                        {counter === 1
                            ? textSearchOneResult
                            : textSearchResults.replace("!count", counter)}
                    </div>
                    {warnings.map((warning) => (
                        <div key={warning} className="SearchResults__warning">
                            {warning}
                        </div>
                    ))}
                    {results.map((result) => (
                        <Result
                            key={result.title}
                            item={result}
                            settings={settings}
                        />
                    ))}
                    {counter > settings.show && (
                        <Pagination
                            counter={counter}
                            start={start}
                            settings={settings}
                            onPageSelect={this.handlePaginate}
                        />
                    )}
                </div>
            </div>
        );
    }
}
