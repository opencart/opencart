import * as preact from "preact";
/** @jsx preact.h */

// TODO :: restore highlight
/*function highlightText(search, text) {
    if (settings.highlightTerms) {
        var pattern = new RegExp(
            `(${search})`,
            settings.highlightEveryTerm ? "gi" : "i"
        );
        text = text.replace(
            pattern,
            '<span class="SearchResults__highlight">$1</span>'
        );
    }

    return text;
}*/

export default function Result({ settings, item }) {
    let text;
    if (item.text) {
        text = item.text
            .split(" ")
            .slice(0, settings.descriptiveWords)
            .join(" ");
        if (
            item.text.length < text.length &&
            text.charAt(text.length - 1) !== "."
        ) {
            text += " ...";
        }
    }

    return (
        <div className="SearchResult">
            <div className="SearchResults__title">
                <a href={settings.base_url + item.url}>{item.title}</a>
            </div>
            {settings.showURL && (
                <div className="SearchResults__url">
                    <a href={settings.base_url + item.url}>
                        {item.url.toLowerCase().replace(/https?:\/\//g, "")}
                    </a>
                </div>
            )}
            {text && <div className="SearchResults__text">{text}</div>}
        </div>
    );
}
