/* global fixture, test */
/* eslint-disable new-cap */

import { Selector } from "testcafe";

fixture`Search`.page`http://localhost:8080`;

test("Should display search results", async t => {
    await t.typeText("#search_input", "Daux").click(".Search__icon");

    const countSelector = Selector(".SearchResults__count");
    await t.expect(countSelector.innerText).contains("results");

    const titleSelector = Selector(".SearchResults__title");
    await t.expect(titleSelector.count).gt(2);
});
