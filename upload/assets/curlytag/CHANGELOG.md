# Changelog


## v0.1.1

[compare changes](https://github.com/curlytag/curlytag/compare/v0.1.0...v0.1.1)

### 🚀 Enhancements

- **playground:** Add interactive CurlyTag playground ([2ac79c3](https://github.com/curlytag/curlytag/commit/2ac79c3))
- Support render() in Node.js via fs.readFile ([9c21cf9](https://github.com/curlytag/curlytag/commit/9c21cf9))
- Add browser tests and expand runtime CI matrix ([#14](https://github.com/curlytag/curlytag/pull/14))

### 🩹 Fixes

- Use Array.isArray for length filter ([a7bc2ac](https://github.com/curlytag/curlytag/commit/a7bc2ac))
- Add unless to openclose map ([40698a1](https://github.com/curlytag/curlytag/commit/40698a1))
- Remove shadow variable in handleBreak ([f13246a](https://github.com/curlytag/curlytag/commit/f13246a))
- Use value field in filter block stack object ([57ff364](https://github.com/curlytag/curlytag/commit/57ff364))
- Correct raw string index checks for whitespace control ([7d242a0](https://github.com/curlytag/curlytag/commit/7d242a0))
- Move playground dependencies to devDependencies ([cee0140](https://github.com/curlytag/curlytag/commit/cee0140))
- Output not flushed when echo/endfilter is last token ([8416187](https://github.com/curlytag/curlytag/commit/8416187))
- Invalid if syntax renders body instead of empty string ([5776819](https://github.com/curlytag/curlytag/commit/5776819))
- **devcontainer:** Install vp, bun, deno as node user ([5ede20d](https://github.com/curlytag/curlytag/commit/5ede20d))
- Cycle tag broken due to undefined variables and wrong output mechanism ([98689f0](https://github.com/curlytag/curlytag/commit/98689f0))
- **filter:** Return array after unshift ([57df3ba](https://github.com/curlytag/curlytag/commit/57df3ba))
- **filter:** Add missing return in select ([053d20f](https://github.com/curlytag/curlytag/commit/053d20f))
- **filter:** Return array after shift ([25d0ad5](https://github.com/curlytag/curlytag/commit/25d0ad5))
- **filter:** Return array after pop ([#29](https://github.com/curlytag/curlytag/pull/29))
- **filter:** Return array after push ([#28](https://github.com/curlytag/curlytag/pull/28))
- **filter:** Fix accumulator shadowing in sum ([#27](https://github.com/curlytag/curlytag/pull/27))
- **filter:** Use end param instead of window.length in slice ([#26](https://github.com/curlytag/curlytag/pull/26))
- **filter:** Add missing return in reject ([#25](https://github.com/curlytag/curlytag/pull/25))
- False elseif branch renders its body instead of skipping ([#22](https://github.com/curlytag/curlytag/pull/22))
- **devcontainer:** Install Playwright browser as node user ([1b2d44c](https://github.com/curlytag/curlytag/commit/1b2d44c))
- Striptag filter strips script and style content due to wrong regex order ([#38](https://github.com/curlytag/curlytag/pull/38))
- For and unless else branch renders both blocks ([#37](https://github.com/curlytag/curlytag/pull/37))
- Case tag does not support dotted paths ([#36](https://github.com/curlytag/curlytag/pull/36))
- Groupby parameter shadowing ([#33](https://github.com/curlytag/curlytag/pull/33))
- Correct fetch() path construction for namespaces ([#32](https://github.com/curlytag/curlytag/pull/32))
- For loop crashes when iterating over null ([#34](https://github.com/curlytag/curlytag/pull/34))
- Break and continue outside loop corrupt if/else stack ([#35](https://github.com/curlytag/curlytag/pull/35))

### 💅 Refactors

- **tests:** Move array filter tests to individual files ([0e24e5c](https://github.com/curlytag/curlytag/commit/0e24e5c))
- **tests:** Split curlytag.test.js into individual test files ([9e21563](https://github.com/curlytag/curlytag/commit/9e21563))

### 📖 Documentation

- Add documentation to README ([#4](https://github.com/curlytag/curlytag/pull/4))
- Disable Jekyll Liquid processing for GitHub Pages ([bddbee8](https://github.com/curlytag/curlytag/commit/bddbee8))
- Use liquid fences in readme examples ([2e9c5db](https://github.com/curlytag/curlytag/commit/2e9c5db))
- Trim README, fix Jekyll build error ([cf1f9f3](https://github.com/curlytag/curlytag/commit/cf1f9f3))
- Add npm and CI badges ([c190813](https://github.com/curlytag/curlytag/commit/c190813))

### 🏡 Chore

- Sync package name in package-lock.json ([a243da4](https://github.com/curlytag/curlytag/commit/a243da4))
- Update vite-plus and vite to patch 3 high CVEs ([#16](https://github.com/curlytag/curlytag/pull/16))
- Pre-bake dev tools into devcontainer image ([#20](https://github.com/curlytag/curlytag/pull/20))
- Configure oxfmt and oxlint ([#18](https://github.com/curlytag/curlytag/pull/18))
- Upgrade vite-plus to v0.1.18, remove vite override ([0f64640](https://github.com/curlytag/curlytag/commit/0f64640))
- Update dependencies ([be292ad](https://github.com/curlytag/curlytag/commit/be292ad))
- Fix formatting ([c3e887d](https://github.com/curlytag/curlytag/commit/c3e887d))
- Add changelogen for release management ([15869db](https://github.com/curlytag/curlytag/commit/15869db))

### ✅ Tests

- Expand coverage and mark known failures ([#6](https://github.com/curlytag/curlytag/pull/6))

### 🤖 CI

- Cache Playwright, drop continue-on-error on lint, add concurrency ([#15](https://github.com/curlytag/curlytag/pull/15))

### ❤️ Contributors

- Anton Semenov ([@aurynx](https://github.com/aurynx))

