import { ready } from "./utils";

const LOCAL_STORAGE_KEY = "daux_code_blocks_hidden";

function setCodeBlockStyle(codeBlocks, hidden) {
    for (let a = 0; a < codeBlocks.length; a++) {
        codeBlocks[a].classList.toggle("CodeToggler--hidden", hidden);
    }
    try {
        localStorage.setItem(LOCAL_STORAGE_KEY, hidden);
    } catch (e) {
        // local storage operations can fail with the file:// protocol
    }
}

function enableToggler(toggleCodeSection, codeBlocks) {
    const toggleCodeBlockBtnSet = toggleCodeSection.querySelector(
        ".CodeToggler__button--main",
    ); // available when floating is disabled

    toggleCodeBlockBtnSet.addEventListener(
        "change",
        (ev) => {
            setCodeBlockStyle(codeBlocks, !ev.target.checked);
        },
        false,
    );

    let hidden = false;
    try {
        hidden = localStorage.getItem(LOCAL_STORAGE_KEY);

        if (hidden === "false") {
            hidden = false;
        } else if (hidden === "true") {
            hidden = true;
        }

        if (hidden) {
            setCodeBlockStyle(codeBlocks, true);
            toggleCodeBlockBtnSet.checked = false;
        }
    } catch (e) {
        // local storage operations can fail with the file:// protocol
    }
}

ready(() => {
    const codeBlocks = document.querySelectorAll(".s-content pre");
    const toggleCodeSection = document.querySelector(".CodeToggler");

    if (!toggleCodeSection) {
        return;
    }

    if (codeBlocks.length) {
        enableToggler(toggleCodeSection, codeBlocks);
    } else {
        toggleCodeSection.classList.add("CodeToggler--hidden");
    }
});
