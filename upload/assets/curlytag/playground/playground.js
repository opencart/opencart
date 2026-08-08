import { template } from '#curlytag';
import { EditorView, basicSetup } from 'codemirror';
import { ViewPlugin, Decoration } from '@codemirror/view';
import { EditorState, Compartment, RangeSetBuilder } from '@codemirror/state';
import { foldService } from '@codemirror/language';
import { html } from '@codemirror/lang-html';
import { json } from '@codemirror/lang-json';
import appsIcon from 'remixicon/icons/System/apps-2-line.svg?raw';
import fileCopyIcon from 'remixicon/icons/Document/file-copy-line.svg?raw';
import resetLeftIcon from 'remixicon/icons/System/reset-left-line.svg?raw';

const cmTheme = EditorView.theme({
    '&': {
        fontSize: 'inherit',
        height: '100%',
        background: 'transparent',
    },
    '.cm-scroller': {
        fontFamily: 'var(--font-mono)',
        lineHeight: '1.6',
        overflow: 'auto',
    },
    '.cm-content': {
        padding: '12px 0',
        caretColor: 'var(--ct-text)',
    },
    '.cm-line': {
        padding: '0 14px',
    },
    '.cm-gutters': {
        background: 'transparent',
        border: 'none',
        color: 'var(--ct-text-dim)',
    },
    '.cm-lineNumbers': {
        display: 'none',
    },
    '.cm-foldGutter': {
        minWidth: '1.5rem',
    },
    '.cm-foldGutter .cm-gutterElement': {
        padding: '0 6px 0 4px',
        cursor: 'pointer',
    },
    '.cm-foldPlaceholder': {
        background: 'color-mix(in srgb, var(--ct-paper-wash) 70%, transparent)',
        border: '1px solid var(--ct-inner-border)',
        color: 'var(--ct-text-dim)',
        borderRadius: '999px',
        margin: '0 4px',
        padding: '0 6px',
    },
    '&.cm-focused': {
        outline: 'none',
    },
    '&.cm-focused .cm-cursor': {
        borderLeftColor: 'var(--ct-text)',
    },
    '.cm-selectionBackground': {
        background: 'color-mix(in srgb, var(--ct-accent) 22%, transparent) !important',
    },
    '.cm-activeLine': {
        background: 'transparent',
    },
    '.cm-activeLineGutter': {
        background: 'transparent',
    },
});

const wsSpaceDeco = Decoration.mark({ class: 'cm-ws-space' });
const wsTabDeco = Decoration.mark({ class: 'cm-ws-tab' });

const buildWhitespaceDeco = (view) => {
    const builder = new RangeSetBuilder();

    for (const { from, to } of view.visibleRanges) {
        const text = view.state.doc.sliceString(from, to);

        for (let i = 0; i < text.length; i++) {
            const ch = text[i];

            if (ch === ' ') builder.add(from + i, from + i + 1, wsSpaceDeco);
            else if (ch === '\t') builder.add(from + i, from + i + 1, wsTabDeco);
        }
    }

    return builder.finish();
};

const whitespacePlugin = ViewPlugin.fromClass(
    class {
        constructor(view) {
            this.decorations = buildWhitespaceDeco(view);
        }
        update(u) {
            if (u.docChanged || u.viewportChanged) this.decorations = buildWhitespaceDeco(u.view);
        }
    },
    { decorations: (v) => v.decorations },
);

const sourceEl = document.getElementById('output-source-code');
const popover = document.getElementById('examples-panel');
const examplesTrigger = document.querySelector('.examples__trigger');
const outputState = {
    kind: 'empty',
    content: '',
};
const editorControls = {
    template: {
        copyButton: document.querySelector('[data-editor-action="copy-template"]'),
        resetButton: document.querySelector('[data-editor-action="reset-template"]'),
    },
    data: {
        copyButton: document.querySelector('[data-editor-action="copy-data"]'),
        resetButton: document.querySelector('[data-editor-action="reset-data"]'),
    },
};

const withSvgClass = (svg) =>
    svg.replace('<svg', '<svg class="button-icon__svg" aria-hidden="true"');

const setButtonIcon = (slot, svg) => {
    slot.innerHTML = withSvgClass(svg);
};

const getButtonLabel = (button) => button.querySelector('[data-button-label]') ?? button;

const applyButtonIcons = () => {
    setButtonIcon(examplesTrigger.querySelector('.examples__trigger-icon'), appsIcon);

    for (const [action, button] of [
        ['copy', editorControls.template.copyButton],
        ['reset', editorControls.template.resetButton],
        ['copy', editorControls.data.copyButton],
        ['reset', editorControls.data.resetButton],
    ]) {
        const slot = button.querySelector('.editor-section__action-icon');

        setButtonIcon(slot, action === 'copy' ? fileCopyIcon : resetLeftIcon);
    }
};

const setOutputState = (kind, content = '') => {
    outputState.kind = kind;
    outputState.content = content;
};

const exampleTemplateModules = import.meta.glob('./examples/*/template.html', {
    query: '?raw',
    import: 'default',
});

const exampleDataModules = import.meta.glob('./examples/*/data.json', {
    import: 'default',
});

const templateFoldBoundaries = {
    if: new Set(['elseif', 'else', 'endif']),
    elseif: new Set(['elseif', 'else', 'endif']),
    else: new Set(['endif', 'endunless', 'endfor', 'endcase']),
    unless: new Set(['else', 'endunless']),
    case: new Set(['when', 'else', 'endcase']),
    when: new Set(['when', 'else', 'endcase']),
    for: new Set(['else', 'endfor']),
    capture: new Set(['endcapture']),
    filter: new Set(['endfilter']),
    raw: new Set(['endraw']),
    comment: new Set(['endcomment']),
};

const templateNestedClosers = new Map([
    ['if', 'endif'],
    ['unless', 'endunless'],
    ['case', 'endcase'],
    ['for', 'endfor'],
    ['capture', 'endcapture'],
    ['filter', 'endfilter'],
    ['raw', 'endraw'],
    ['comment', 'endcomment'],
]);

const templateFoldOpeners = new Set(templateNestedClosers.keys());

const getTemplateCommands = (text) =>
    Array.from(text.matchAll(/\{%-?\s*([A-Za-z_][\w-]*)\b/g), ([, command]) =>
        command.toLowerCase(),
    );

const getTemplateFoldRange = (state, lineStart) => {
    const startLine = state.doc.lineAt(lineStart);
    const startCommand = getTemplateCommands(startLine.text).find(
        (command) => command in templateFoldBoundaries,
    );

    if (!startCommand) return null;

    const boundaryCommands = templateFoldBoundaries[startCommand];
    const nestedBlocks = [];

    for (let lineNumber = startLine.number + 1; lineNumber <= state.doc.lines; lineNumber++) {
        const line = state.doc.line(lineNumber);

        for (const command of getTemplateCommands(line.text)) {
            const nestedTop = nestedBlocks[nestedBlocks.length - 1];

            if (nestedTop && command === templateNestedClosers.get(nestedTop)) {
                nestedBlocks.pop();
                continue;
            }

            if (!nestedTop && boundaryCommands.has(command)) {
                const from = startLine.to;
                const to = line.from - 1;

                return to > from ? { from, to } : null;
            }

            if (templateFoldOpeners.has(command)) {
                nestedBlocks.push(command);
            }
        }
    }

    return null;
};

const templateLanguage = () => [
    html(),
    foldService.of((state, lineStart) => getTemplateFoldRange(state, lineStart)),
];

const createEditor = (parent, langExt, onChange) => {
    const langCompartment = new Compartment();

    return new EditorView({
        parent,
        state: EditorState.create({
            doc: '',
            extensions: [
                basicSetup,
                langCompartment.of(langExt()),
                cmTheme,
                whitespacePlugin,
                EditorView.updateListener.of((update) => {
                    if (update.docChanged) onChange();
                }),
            ],
        }),
    });
};

const originalDocs = { template: '', data: '' };

let renderTimer;

const scheduleRender = () => {
    clearTimeout(renderTimer);
    renderTimer = setTimeout(render, 80);
};

const renderNow = () => {
    clearTimeout(renderTimer);
    return render();
};

const tplView = createEditor(document.getElementById('template-editor'), templateLanguage, () => {
    syncResetButton('template');
    scheduleRender();
});

const dataView = createEditor(document.getElementById('data-editor'), json, () => {
    syncResetButton('data');
    scheduleRender();
});

const getEditorView = (kind) => (kind === 'template' ? tplView : dataView);

const getEditorDoc = (kind) => getEditorView(kind).state.doc.toString();

const replaceEditorDoc = (kind, value) => {
    const view = getEditorView(kind);

    view.dispatch({
        changes: { from: 0, to: view.state.doc.length, insert: value },
    });
};

const syncResetButton = (kind) => {
    editorControls[kind].resetButton.hidden = getEditorDoc(kind) === originalDocs[kind];
};

const syncEditorActions = () => {
    syncResetButton('template');
    syncResetButton('data');
};

const flashButton = (button, label) => {
    const labelEl = getButtonLabel(button);
    const previousLabel = labelEl.textContent;

    labelEl.textContent = label;
    window.setTimeout(() => {
        labelEl.textContent = previousLabel;
    }, 1200);
};

const copyEditorDoc = async (kind) => {
    try {
        await navigator.clipboard.writeText(getEditorDoc(kind));
        flashButton(editorControls[kind].copyButton, 'Copied');
    } catch {
        flashButton(editorControls[kind].copyButton, 'Failed');
    }
};

const resetEditorDoc = (kind) => {
    replaceEditorDoc(kind, originalDocs[kind]);
    getEditorView(kind).focus();
    renderNow();
};

const highlighterPromise = import('shiki').then(({ createHighlighter }) =>
    createHighlighter({
        themes: ['github-light', 'github-dark'],
        langs: ['html'],
    }),
);

const getShikiTheme = () => {
    return window.matchMedia('(prefers-color-scheme: dark)').matches
        ? 'github-dark'
        : 'github-light';
};

const markWhitespace = (el) => {
    const walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT);
    const textNodes = [];
    let node;

    while ((node = walker.nextNode())) textNodes.push(node);

    for (const tn of textNodes) {
        if (!/ |\t/.test(tn.nodeValue)) continue;

        const frag = document.createDocumentFragment();

        for (const ch of tn.nodeValue) {
            if (ch === ' ' || ch === '\t') {
                const s = document.createElement('span');

                s.className = ch === ' ' ? 'shiki-ws-space' : 'shiki-ws-tab';
                s.textContent = ch;
                frag.appendChild(s);
            } else {
                frag.appendChild(document.createTextNode(ch));
            }
        }

        tn.replaceWith(frag);
    }
};

const highlightOutput = async (code) => {
    const highlighter = await highlighterPromise;

    sourceEl.innerHTML = highlighter.codeToHtml(code || ' ', {
        lang: 'html',
        theme: getShikiTheme(),
    });

    markWhitespace(sourceEl);
};

const presentOutput = async () => {
    if (outputState.kind === 'data-error') {
        sourceEl.innerHTML =
            '<div class="editor-section__error">⚠ Invalid JSON in Data panel</div>';
        return;
    }

    await highlightOutput(outputState.content);
};

const render = async () => {
    const tplCode = tplView.state.doc.toString();
    const dataCode = dataView.state.doc.toString();
    let data;

    try {
        data = JSON.parse(dataCode);
    } catch {
        setOutputState('data-error');
        await presentOutput();
        return;
    }

    try {
        const result = template.parse(tplCode, data);

        setOutputState('html', result);
        await presentOutput();
    } catch (e) {
        const message = String(e.message);

        setOutputState('template-error', message);
        await presentOutput();
    }
};

const loadExampleFiles = async (key) => {
    const templateLoader = exampleTemplateModules[`./examples/${key}/template.html`];
    const dataLoader = exampleDataModules[`./examples/${key}/data.json`];

    if (!templateLoader || !dataLoader) return null;

    const [templateSource, dataSource] = await Promise.all([templateLoader(), dataLoader()]);

    return {
        template: templateSource,
        data: dataSource,
    };
};

let activeBtn = null;
let exampleLoadToken = 0;

const loadExample = async (key) => {
    const loadToken = ++exampleLoadToken;

    try {
        const example = await loadExampleFiles(key);

        if (!example || loadToken !== exampleLoadToken) return;

        const dataSource = JSON.stringify(example.data, null, 2);

        originalDocs.template = example.template;
        originalDocs.data = dataSource;

        replaceEditorDoc('template', example.template);
        replaceEditorDoc('data', dataSource);
        syncEditorActions();

        activeBtn?.classList.remove('examples__item--active');
        activeBtn = popover.querySelector(`[data-example="${key}"]`);
        activeBtn?.classList.add('examples__item--active');

        await renderNow();
    } catch (error) {
        console.error(`Failed to load example "${key}"`, error);

        if (loadToken !== exampleLoadToken) return;

        setOutputState('template-error', `Failed to load example "${key}".`);
        await presentOutput();
    }
};

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
    presentOutput();
});

popover.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-example]');

    if (!btn) return;

    void loadExample(btn.dataset.example);
    popover.hidePopover();
});

document.addEventListener('click', (e) => {
    const actionButton = e.target.closest('[data-editor-action]');

    if (!actionButton) return;

    const [action, kind] = actionButton.dataset.editorAction.split('-');

    if (action === 'copy') {
        copyEditorDoc(kind);
        return;
    }

    if (action === 'reset') resetEditorDoc(kind);
});

applyButtonIcons();
void loadExample('category');
