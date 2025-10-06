import * as preact from "preact";
import { textLinkNext, textLinkPrevious } from "./translation";
/** @jsx preact.h */

export default function Pagination({ counter, start, settings, onPageSelect }) {
    const pages = Math.ceil(counter / settings.show);
    const page = start / settings.show;

    let displayedPages;
    if (page <= 2) {
        // Display max three pages
        displayedPages = Math.min(pages, 3);
    } else {
        // Display two more pages, but don't overflow
        displayedPages = Math.min(pages, page + 2);
    }

    const items = [];

    for (let f = 0; f < displayedPages; f++) {
        if (f === page) {
            items.push(<li className="current">{f + 1}</li>);
        } else {
            items.push(
                <li>
                    <button
                        type="button"
                        className="SearchResults__footer__link"
                        onClick={() => onPageSelect(f * settings.show)}
                    >
                        {f + 1}
                    </button>
                </li>,
            );
        }
    }

    return (
        <div className="SearchResults__footer">
            <ul className="SearchResults__footer__links Pager">
                {start > 0 && (
                    <li className="Pager--prev">
                        <button
                            type="button"
                            className="SearchResults__footer__link"
                            onClick={() => onPageSelect(start - settings.show)}
                        >
                            {textLinkPrevious}
                        </button>
                    </li>
                )}
                {items}
                {page + 1 !== pages && (
                    <li className="Pager--next">
                        <button
                            type="button"
                            className="SearchResults__footer__link"
                            onClick={() => onPageSelect(start + settings.show)}
                        >
                            {textLinkNext}
                        </button>
                    </li>
                )}
            </ul>
        </div>
    );
}
