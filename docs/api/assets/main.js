// open details
document.querySelectorAll('.expandable').forEach(el => {
	el.addEventListener('click', e => {
		e.currentTarget.classList.toggle('collapsed')
	})
})


// table sort
document.querySelectorAll('.sortable').forEach(el => {
	const table = el.closest('table')
	const items = Array.from(table.querySelectorAll('[data-order]'))
	let orderBy = 'order'

	items.forEach((item, i) => {
		item.dataset.orderNatural = i.toString(10).padStart(3, '0')
	})

	el.addEventListener('click', () => {
		items.sort((a, b) => a.dataset[orderBy].localeCompare(b.dataset[orderBy]))
		table.append(...items)
		orderBy = orderBy === 'order' ? 'orderNatural' : 'order'
	})
})


// search
document.querySelectorAll('.search').forEach(el => {
	function tokenize(s, offset = 0) {
		return Array.from(s.matchAll(/[A-Z]{2,}|[a-zA-Z][a-z]*|\S/g)).map(it => ([it.index + offset, it[0].toLowerCase()]))
	}

	function prefix(a, aa, b, bb) {
		let len = 0
		while (aa < a.length && bb < b.length && a[aa++] === b[bb++]) len++
		return len
	}

	function matchTokens(elementTokens, queryTokens, i = 0, ii = 0, j = 0, jj = 0) {
		if (i === queryTokens.length) {
			return []

		} else if (j === elementTokens.length) {
			return null
		}

		const [elementOffset, elementToken] = elementTokens[j]
		const [, queryToken] = queryTokens[i]
		const prefixLength = prefix(queryToken, ii, elementToken, jj)

		const subMatches = ii + prefixLength === queryToken.length
			? matchTokens(elementTokens, queryTokens, i + 1, 0, j, jj + prefixLength)
			: jj + prefixLength === elementToken.length
			? matchTokens(elementTokens, queryTokens, i, ii + prefixLength, j + 1, 0)
			: null

		return subMatches
			? [[elementOffset + jj, prefixLength], ...subMatches]
			: matchTokens(elementTokens, queryTokens, i, ii, j + 1, 0)
	}

	function* getResults(query, dataset) {
		const queryTokens = tokenize(query)

		for (const [name, path, tokens] of dataset) {
			const matches = matchTokens(tokens, queryTokens)
			yield* matches !== null ? [[name, path, matches]] : []
		}
	}

	function* iteratorSlice(it, length) {
		while (length--) {
			const item = it.next()

			if (item.done) {
				return
			}

			yield item.value
		}
	}

	function* iteratorMap(it, fn) {
		for (const item of it) {
			yield fn(item)
		}
	}

	function renderResult([name, path, matches]) {
		const li = document.createElement('li')
		const anchor = li.appendChild(document.createElement('a'))
		anchor.href = path

		let i = 0
		for (const [matchOffset, matchLength] of matches) {
			anchor.append(name.slice(i, matchOffset))
			anchor.appendChild(document.createElement('b')).innerText = name.slice(matchOffset, matchOffset + matchLength)
			i = matchOffset + matchLength
		}

		if (i < name.length) {
			anchor.append(name.slice(i))
		}

		return li
	}

	const searchInput = el.querySelector('.search-input')
	const resultsDiv = el.querySelector('.search-results')

	let dataset = null
	let datasetPromise = null
	let resultIterator = [][Symbol.iterator]()
	let resultNext = resultIterator.next()
	let resultItems = []
	let headIndex = 0
	let activeIndex = 0

	const VISIBLE_COUNT = 20

	searchInput.addEventListener('input', async () => {
		dataset ??= await (datasetPromise ??= new Promise(resolve => {
			const script = document.createElement('script')
			script.src = el.dataset.elements
			document.head.appendChild(script)
			window.ApiGen ??= {}
			window.ApiGen.resolveElements = (elements) => {
				const unified = [
					...(elements.namespace ?? []).map(([name, path]) => [name, path, tokenize(name)]),
					...(elements.function ?? []).map(([name, path]) => [name, path, tokenize(name)]),
					...(elements.classLike ?? []).flatMap(([classLikeName, path, members]) => [
						[classLikeName, path, tokenize(classLikeName)],
						...(members.constant ?? []).map(([constantName, anchor]) => [`${classLikeName}::${constantName}`, `${path}#${anchor}`, tokenize(`${constantName}`, classLikeName.length + 2)]),
						...(members.property ?? []).map(([propertyName, anchor]) => [`${classLikeName}::\$${propertyName}`, `${path}#${anchor}`, tokenize(`\$${propertyName}`, classLikeName.length + 2)]),
						...(members.method ?? []).map(([methodName, anchor]) => [`${classLikeName}::${methodName}()`, `${path}#${anchor}`, tokenize(`${methodName}()`, classLikeName.length + 2)]),
					]),
				]

				resolve(unified.sort((a, b) => a[0].localeCompare(b[0])))
			}
		}))

		resultIterator = iteratorMap(getResults(searchInput.value, dataset), renderResult)
		resultItems = Array.from(iteratorSlice(resultIterator, VISIBLE_COUNT))
		resultNext = resultIterator.next()
		resultsDiv.replaceChildren(...resultItems)
		headIndex = 0
		activeIndex = 0
		resultItems[activeIndex]?.classList.add('active')
	})

	searchInput.addEventListener('keydown', e => {
		if (e.key === 'Escape') {
			searchInput.blur()

		} else if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
			e.preventDefault()
			let nextIndex = activeIndex + { ArrowUp: -1, ArrowDown: +1 }[e.key]

			if (nextIndex < 0) {
				while (!resultNext.done) {
					resultItems.push(resultNext.value)
					resultNext = resultIterator.next()
				}

			} else if (nextIndex === resultItems.length) {
				if (!resultNext.done) {
					resultItems.push(resultNext.value)
					resultNext = resultIterator.next()
				}
			}

			nextIndex = (nextIndex + resultItems.length) % resultItems.length
			headIndex = Math.max(headIndex, 0, nextIndex - VISIBLE_COUNT + 1)
			headIndex = Math.min(headIndex, nextIndex)

			resultsDiv.replaceChildren(...resultItems.slice(headIndex, headIndex + VISIBLE_COUNT))
			resultItems[activeIndex]?.classList.remove('active')
			resultItems[nextIndex]?.classList.add('active')
			activeIndex = nextIndex

		} else if (e.key === 'Enter') {
			e.preventDefault()
			const active = resultsDiv.querySelector('.active') ?? resultsDiv.firstElementChild
			active?.querySelector('a').click()
		}
	})
})


// line selection
let ranges = []
let last = null
const match = window.location.hash.slice(1).match(/^\d+(?:-\d+)?(?:,\d+(?:-\d+)?)*$/)

const handleLinesSelectionChange = () => {
	history.replaceState({}, '', '#' + ranges.map(([a, b]) => a === b ? a : `${a}-${b}`).join(','))
	document.querySelectorAll('.source-line.selected').forEach(el => el.classList.remove('selected'))

	for (let [a, b] of ranges) {
		for (let i = a; i <= b; i++) {
			document.getElementById(`${i}`).classList.add('selected')
		}
	}
}

if (match) {
	ranges = match[0].split(',').map(range => range.split('-').map(n => Number.parseInt(n)))
	ranges = ranges.map(([a, b]) => b === undefined ? [a, a] : [a, b])
	ranges = ranges.filter(([a, b]) => a <= b)
	handleLinesSelectionChange()

	const first = Math.max(1, Math.min(...ranges.flat()) - 3)
	requestAnimationFrame(() => document.getElementById(`${first}`).scrollIntoView())
}

document.querySelectorAll('.source-lineNum').forEach(a => {
	a.addEventListener('click', e => {
		e.preventDefault()
		const line = e.currentTarget.closest('tr')
		const n = Number.parseInt(line.id)
		const selected = line.classList.contains('selected') && e.ctrlKey
		const extending = e.shiftKey && ranges.length > 0

		if (!e.ctrlKey) {
			ranges = extending ? [ranges[ranges.length - 1]] : []
		}

		if (extending) {
			ranges[ranges.length - 1] = [Math.min(n, last), Math.max(n, last)]

		} else if (selected) {
			ranges = ranges.flatMap(([a, b]) => (a <= n && n <= b) ? [[a, n - 1], [n + 1, b]] : [[a, b]])
			ranges = ranges.filter(([a, b]) => a <= b)

		} else {
			ranges[ranges.length] = [n, n]
			last = n
		}

		handleLinesSelectionChange()
	})
})
