const COLOR_MODE_KEY = "daux-color-mode";
const VALUE_DARK = "dark";
const VALUE_LIGHT = "light";
const CLASS_DARK = "dark";
const BUTTON = ".ColorMode__button";

function getInitialColorMode() {
    const persistedColorPreference =
        window.localStorage.getItem(COLOR_MODE_KEY);
    const hasPersistedPreference = typeof persistedColorPreference === "string";

    // If the user has explicitly chosen light or dark,
    // let's use it. Otherwise, this value will be null.
    if (hasPersistedPreference) {
        return persistedColorPreference;
    }

    // If they haven't been explicit, let's check the media
    // query
    const mql = window.matchMedia("(prefers-color-scheme: dark)");
    const hasMediaQueryPreference = typeof mql.matches === "boolean";
    if (hasMediaQueryPreference) {
        return mql.matches ? VALUE_DARK : VALUE_LIGHT;
    }

    // If they are using a browser/OS that doesn't support
    // color themes, let's default to 'light'.
    return VALUE_LIGHT;
}

const colorMode = getInitialColorMode();

// Set the current theme
document.body.classList.toggle(CLASS_DARK, colorMode === VALUE_DARK);

const toggleCodeBlockBtnSet = document.body.querySelector(BUTTON);

if (toggleCodeBlockBtnSet) {
    // Reflect the current theme on the checkbox
    toggleCodeBlockBtnSet.checked = colorMode === VALUE_DARK;

    toggleCodeBlockBtnSet.addEventListener(
        "change",
        (ev) => {
            const checked = ev.target.checked;

            document.body.classList.toggle(CLASS_DARK, checked);

            window.localStorage.setItem(
                COLOR_MODE_KEY,
                checked ? VALUE_DARK : VALUE_LIGHT,
            );
        },
        false,
    );
}
